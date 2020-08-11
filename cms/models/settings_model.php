<?php

class Settings_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'settings';
    }

    /* LIST */
    public function listItems() {
        $selectData = [
                'deleted'=>0,
                'lang'=>MFAdmin::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`text`, P.`update_date`, P.`status`
                    FROM `cms_settings` P
                    LEFT JOIN `cms_settings_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE `deleted`=:deleted
                    ORDER BY `id`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
    public function deleteItem($items) {
        $ids = implode(',', $items);
        $updateData = array(
                'deleted' => '1',
                'update_date' => date('Y-m-d H:i:s')
        );
        $result = $this->db->update('`cms_settings`', $updateData, '`id` IN (' . $ids . ')');
        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Selected rows deleted') . ' (' . $result . ')'];
        }
    }
    public function updateStatus($items, $status) {
        $updateData = array(
                'status' => $status,
                'update_date' => date('Y-m-d H:i:s')
        );
        $ids = implode(',', $items);
        $result = $this->db->update('`cms_settings`', $updateData, '`id` IN (' . $ids . ')');
        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Selected posts changed status')];
        }
    }

    /* ADD */
    public function createItem() {
        if(!$_POST){
            header('Location: ' . URL . $this->_menu.'/add');
            exit;
        }

        $_POST['mf_token'] = isset($_POST['mf_token']) ? Func::check($_POST['mf_token']) : '';
        if (!Func::token_check($_POST['mf_token'])) {
            Session::set('note_error', Lang::get('Token Error!'));
            header('location: ' . URL . $this->_menu.'/add');
            exit;
        }
        $error = [];

        foreach (MFAdmin::$_langs as $key=>$value){
            /*
             * CHECK title
             */
            $data['data_title_'.$key] = isset($_POST['data_title_'.$key]) ? Func::check($_POST['data_title_'.$key]) : '';
            $data['data_text_'.$key] = isset($_POST['data_text_'.$key]) ? Func::check($_POST['data_text_'.$key]) : '';
        }
        $data_text = array_filter($data);
        if(empty($data_text)){
            $error[] = Lang::get('Please input titles');
        }

        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;

        if (empty($error)){
            // insert
            $insertData = [
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->insert('cms_settings', $insertData);


            if ($result) {
                $last_id = $result;

                foreach (MFAdmin::$_langs as $key=>$value){
                    // insert
                    $insertData = [
                            'p_id'=>$last_id,
                            'title'=>$data['data_title_'.$key],
                            'text'=>$data['data_text_'.$key],
                            'lang'=>$key
                    ];
                    $this->db->insert('cms_settings_text', $insertData);
                }

                return 1;
            } else {
                return $result[2];
            }
        } else {
            return ['data'=>$data, 'error'=>$error];
        }
    }

    /* EDIT */
    public function singleItem($id) {
        $selectData = [
                'id' => $id
        ];

        $query = "SELECT S.`id`, T.`title`, T.`text`, T.`lang`, S.`status` 
                    FROM `cms_settings` S 
                    LEFT JOIN `cms_settings_text` T ON T.p_id = S.id   
                    WHERE S.`id`=:id";
        $result = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($result as $value){
            $mas['id'] = $value['id'];
            $mas['status'] = $value['status'];
            $mas['title'][$value['lang']] = $value['title'];
            $mas['text'][$value['lang']] = $value['text'];
        }

        return $mas;
    }
    public function updateItem($id) {
        if(!$_POST){
            header('location: ' . URL . $this->_menu.'/add');
            exit;
        }

        $_POST['mf_token'] = isset($_POST['mf_token']) ? Func::check($_POST['mf_token']) : '';
        if (!Func::token_check($_POST['mf_token'])) {
            Session::set('note_error', Lang::get('Token Error!'));
            header('location: ' . URL . $this->_menu.'/edit/'.$id);
            exit;
        }

        $error = [];

        foreach (MFAdmin::$_langs as $key=>$value){
            /*
             * CHECK title
             */
            $data['data_title_'.$key] = isset($_POST['data_title_'.$key]) ? Func::check($_POST['data_title_'.$key]) : '';

            /*
             * CHECK text
             */
            $data['data_text_'.$key] = isset($_POST['data_text_'.$key]) ? Func::check($_POST['data_text_'.$key]) : '';
        }
        $data_text = array_filter($data);
        if(empty($data_text)){
            $error[] = Lang::get('Please input titles');
        }

        /*
         * CHECK status && ordering
         */
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;


        if(empty($error)) {

            $updateData = [
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->update('`cms_settings`', $updateData, "`id`={$id}");

            if (isset($result['mysql_error'])) {
                return ['model_error' => $result['mysql_error']];
            } else {
                $deleted = $this->db->delete('cms_settings_text','`p_id` IN (' . $id . ')');
                if($deleted) {
                    foreach (MFAdmin::$_langs as $key=>$value){
                        // insert
                        $insertData = [
                                'p_id'=>$id,
                                'title'=>$data['data_title_'.$key],
                                'text'=>$data['data_text_'.$key],
                                'lang'=>$key
                        ];
                        $this->db->insert('cms_settings_text', $insertData);
                    }
                    return ['model_success' => Lang::get('Data changed')];
                }
            }

        } else {
            return ['model_error' => $error];
        }
    }
}