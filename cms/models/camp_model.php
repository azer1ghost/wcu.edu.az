<?php

class Camp_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'camp';
    }

    /* LIST */
    public function listItems() {
        $selectData = [
                'deleted'=>0,
                'lang'=>MFAdmin::$_lang
        ];
        $query = "SELECT P.`id`, P.`ordering`, P.`category`, T.`title`, P.`update_date`, P.`status`
                    FROM `cms_camp` P
                    LEFT JOIN `cms_camp_text` T ON T.p_id = P.id and T.lang =:lang 
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
        $result = $this->db->update('`cms_camp`', $updateData, '`id` IN (' . $ids . ')');
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
        $result = $this->db->update('`cms_camp`', $updateData, '`id` IN (' . $ids . ')');
        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Selected posts changed status')];
        }
    }
    public function orderingItem($items) {
        foreach ($items as $key => $value) {
            $updateData = array(
                    'ordering' => $value,
                    'update_date' => date('Y-m-d H:i:s')
            );
            $result = $this->db->update('`cms_camp`', $updateData, '`id` IN (' . $key . ')');
        }

        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Data changed!')];
        }
    }

    /* ADD */
    public function maxOrder() {
        $selectData = [];

        $query = "SELECT MAX(`ordering`) as `ordering` 
                    FROM `cms_camp`";
        $result = $this->db->select($query, $selectData);
        return $result[0]['ordering'];
    }
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
            $data['data_address_'.$key] = isset($_POST['data_address_'.$key]) ? Func::check($_POST['data_address_'.$key]) : '';
        }
        $data_text = array_filter($data);
        if(empty($data_text)){
            $error[] = Lang::get('Please input titles');
        }

        $data['data_index'] = isset($_POST['data_index']) ? Func::check($_POST['data_index']): '';
        $data['data_phone'] = isset($_POST['data_phone']) ? Func::check($_POST['data_phone']): '';
        $data['data_fax']   = isset($_POST['data_fax']) ? Func::check($_POST['data_fax']): '';
        $data['data_email'] = isset($_POST['data_email']) ? Func::check($_POST['data_email']): '';
        $data['data_map']   = isset($_POST['data_map']) ? Func::check($_POST['data_map']): '';
        if(!$data['data_map']){
            $error[] = Lang::get('Please input map coordinate');
        }

        /*
         * CHECK order
         */
        $data['data_ordering'] = isset($_POST['data_ordering']) ? (int) $_POST['data_ordering']: 0;
        if(!$data['data_ordering']){
            $error[] = Lang::get('Please input order');
        }

        /*
         * CHECK category
         */
        $data['data_category'] = isset($_POST['data_category']) ? (int) $_POST['data_category']: 0;

        /*
         * CHECK status
         */
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;
        if(!$data['status']){
            $error[] = Lang::get('Please input status');
        }

        if (empty($error)){
            // insert
            $insertData = [
                    'index'=>$data['data_index'],
                    'phone'=>$data['data_phone'],
                    'fax'=>$data['data_fax'],
                    'email'=>$data['data_email'],
                    'map'=>$data['data_map'],
                    'category'=>$data['data_category'],
                    'ordering'=>$data['data_ordering'],
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->insert('cms_camp', $insertData);


            if ($result) {
                $last_id = $result;

                foreach (MFAdmin::$_langs as $key=>$value){
                    // insert
                    $insertData = [
                            'p_id'=>$last_id,
                            'title'=>$data['data_title_'.$key],
                            'address'=>$data['data_address_'.$key],
                            'lang'=>$key
                    ];
                    $this->db->insert('cms_camp_text', $insertData);
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

        $query = "SELECT S.`id`, S.`ordering`, S.`category`, S.`index`, S.`phone`, S.`fax`, S.`email`, S.`map`, T.`title`, T.`address`, T.`lang`, S.`status` 
                    FROM `cms_camp` S 
                    LEFT JOIN `cms_camp_text` T ON T.p_id = S.id   
                    WHERE S.`id`=:id";
        $result = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($result as $value){
            $mas['id'] = $value['id'];
            $mas['ordering'] = $value['ordering'];
            $mas['category'] = $value['category'];
            $mas['index'] = $value['index'];
            $mas['phone'] = $value['phone'];
            $mas['fax'] = $value['fax'];
            $mas['email'] = $value['email'];
            $mas['map'] = $value['map'];
            $mas['status'] = $value['status'];
            $mas['title'][$value['lang']] = $value['title'];
            $mas['address'][$value['lang']] = $value['address'];
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
            $data['data_address_'.$key] = isset($_POST['data_address_'.$key]) ? Func::check($_POST['data_address_'.$key]) : '';
        }
        $data_text = array_filter($data);
        if(empty($data_text)){
            $error[] = Lang::get('Please input titles');
        }

        $data['data_index'] = isset($_POST['data_index']) ? Func::check($_POST['data_index']): '';
        $data['data_phone'] = isset($_POST['data_phone']) ? Func::check($_POST['data_phone']): '';
        $data['data_fax']   = isset($_POST['data_fax']) ? Func::check($_POST['data_fax']): '';
        $data['data_email'] = isset($_POST['data_email']) ? Func::check($_POST['data_email']): '';
        $data['data_map']   = isset($_POST['data_map']) ? Func::check($_POST['data_map']): '';
        if(!$data['data_map']){
            $error[] = Lang::get('Please input map coordinate');
        }

        /*
         * CHECK order
         */
        $data['data_ordering'] = isset($_POST['data_ordering']) ? (int) $_POST['data_ordering']: 0;
        if(!$data['data_ordering']){
            $error[] = Lang::get('Please input order');
        }

        /*
         * CHECK category
         */
        $data['data_category'] = isset($_POST['data_category']) ? (int) $_POST['data_category']: 0;

        /*
         * CHECK status
         */
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;
        if(!$data['status']){
            $error[] = Lang::get('Please input status');
        }


        if(empty($error)) {

            $updateData = [
                    'index'=>$data['data_index'],
                    'phone'=>$data['data_phone'],
                    'fax'=>$data['data_fax'],
                    'email'=>$data['data_email'],
                    'map'=>$data['data_map'],
                    'category'=>$data['data_category'],
                    'ordering'=>$data['data_ordering'],
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->update('`cms_camp`', $updateData, "`id`={$id}");

            if (isset($result['mysql_error'])) {
                return ['model_error' => $result['mysql_error']];
            } else {
                $deleted = $this->db->delete('cms_camp_text','`p_id` IN (' . $id . ')');
                if($deleted) {
                    foreach (MFAdmin::$_langs as $key=>$value){
                        // insert
                        $insertData = [
                                'p_id'=>$id,
                                'title'=>$data['data_title_'.$key],
                                'address'=>$data['data_address_'.$key],
                                'lang'=>$key
                        ];
                        $this->db->insert('cms_camp_text', $insertData);
                    }
                    return ['model_success' => Lang::get('Data changed')];
                }
            }

        } else {
            return ['model_error' => $error];
        }
    }
}