<?php

class Feedback_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'feedback';
    }

    /* LIST */
    public function listItems() {
        $selectData = [
                'deleted'=>0,
                'lang'=>MFAdmin::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, P.`ordering`, P.`update_date`, P.`status`
                    FROM `cms_feedback` P
                    LEFT JOIN `cms_feedback_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE `deleted`=:deleted
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
    public function deleteItem($items) {
        $ids = implode(',', $items);
        $updateData = array(
                'deleted' => '1',
                'update_date' => date('Y-m-d H:i:s')
        );
        $result = $this->db->update('`cms_feedback`', $updateData, '`id` IN (' . $ids . ')');
        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Seçilmiş sətrlər silinmişdir') . ' (' . $result . ')'];
        }
    }
    public function updateStatus($items, $status) {
        $updateData = array(
                'status' => $status,
                'update_date' => date('Y-m-d H:i:s')
        );
        $ids = implode(',', $items);
        $result = $this->db->update('`cms_feedback`', $updateData, '`id` IN (' . $ids . ')');
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
            $result = $this->db->update('`cms_feedback`', $updateData, '`id` IN (' . $key . ')');
        }

        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Changed data')];
        }
    }

    /* ADD */
    public function maxOrder() {
        $selectData = [];

        $query = "SELECT MAX(`ordering`) as `ordering` 
                    FROM `cms_feedback`";
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
            header('Location: ' . URL . $this->_menu.'/add');
            exit;
        }
        $error = [];

        //if(empty($_FILES['data_photo']['name'])){
        //    $error[] = Lang::get('Please select photo');
        //}

        foreach (MFAdmin::$_langs as $key=>$value){
            /*
             * CHECK name
             */
            $data['data_title_'.$key] = isset($_POST['data_title_'.$key]) ? Func::check($_POST['data_title_'.$key]) : '';

            /*
             * CHECK position
             */
            $data['data_position_'.$key] = isset($_POST['data_position_'.$key]) ? Func::check($_POST['data_position_'.$key]) : '';

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
        $data['data_ordering'] = isset($_POST['data_ordering']) ? (int) $_POST['data_ordering']: 0;
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;

        if (empty($error)){
            // insert
            $insertData = [
                    'ordering'=>$data['data_ordering'],
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->insert('cms_feedback', $insertData);

            if ($result) {
                $last_id = $result;

                $uploadPhoto = Func::uploadPhoto($_FILES['data_photo'], $this->_menu, $last_id, Func::rund_number(), photo_feedback_width, photo_feedback_height);
                if(isset($uploadPhoto['error']) && !empty($uploadPhoto['error'])){
                    return ['data'=>$data, 'error'=>$uploadPhoto['error']];
                } else {
                    $updateData = array(
                            'photo' => $uploadPhoto
                    );
                    $this->db->update('`cms_feedback`', $updateData, "`id`={$last_id}");
                }

                foreach (MFAdmin::$_langs as $key=>$value){
                    // insert
                    $insertData = [
                            'p_id'=>$last_id,
                            'title'=>$data['data_title_'.$key],
                            'position'=>$data['data_position_'.$key],
                            'text'=>$data['data_text_'.$key],
                            'lang'=>$key
                    ];
                    $this->db->insert('cms_feedback_text', $insertData);
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

        $query = "SELECT S.`id`, S.`photo`, T.`title`, T.`position`, T.`text`, T.`lang`, S.`ordering`, S.`status` 
                    FROM `cms_feedback` S 
                    LEFT JOIN `cms_feedback_text` T ON T.p_id = S.id   
                    WHERE S.`id`=:id 
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($result as $value){
            $mas['id'] = $value['id'];
            $mas['photo'] = $value['photo'];
            $mas['ordering'] = $value['ordering'];
            $mas['status'] = $value['status'];
            $mas['title'][$value['lang']] = $value['title'];
            $mas['position'][$value['lang']] = $value['position'];
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
             * CHECK position
             */
            $data['data_position_'.$key] = isset($_POST['data_position_'.$key]) ? Func::check($_POST['data_position_'.$key]) : '';

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
        $data['data_ordering'] = isset($_POST['data_ordering']) ? (int) $_POST['data_ordering']: 0;
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;


        if(empty($error)) {

            $updateData = [
                    'ordering'=>$data['data_ordering'],
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->update('`cms_feedback`', $updateData, "`id`={$id}");

            if (isset($result['mysql_error'])) {
                return ['model_error' => $result['mysql_error']];
            } else {

                $uploadPhoto = Func::uploadPhoto($_FILES['data_photo'], $this->_menu, $id, Func::rund_number(), photo_feedback_width, photo_feedback_height);
                if(isset($uploadPhoto['error']) && !empty($uploadPhoto['error'])){
                    return ['data'=>$data, 'error'=>$uploadPhoto['error']];
                } else {
                    $updateData = array(
                            'photo' => $uploadPhoto
                    );
                    $this->db->update('`cms_feedback`', $updateData, "`id`={$id}");
                }


                $deleted = $this->db->delete('cms_feedback_text','`p_id` IN (' . $id . ')');
                if($deleted) {
                    foreach (MFAdmin::$_langs as $key=>$value){
                        // insert
                        $insertData = [
                                'p_id'=>$id,
                                'title'=>$data['data_title_'.$key],
                                'position'=>$data['data_position_'.$key],
                                'text'=>$data['data_text_'.$key],
                                'lang'=>$key
                        ];
                        $this->db->insert('cms_feedback_text', $insertData);
                    }
                    return ['model_success' => Lang::get('Data changed')];
                }

            }

        } else {
            return ['model_error' => $error];
        }
    }
    public function deletePhoto($id) {
        $selectData = [
                'id' => $id
        ];
        $query = "SELECT S.`photo` 
                    FROM `cms_feedback` S 
                    WHERE S.`id`=:id";
        $result = $this->db->select($query, $selectData);

        if(!empty($result)){
            $updateData = array(
                    'photo' => ''
            );
            $this->db->update('`cms_feedback`', $updateData, "`id` = {$id}");

            foreach ($result[0] as $value){
                @unlink(UPLOAD_DIR.'Image/'.$this->_menu.'/'.$value);
            }
            return 1;
        } else {
            return 0;
        }


    }
}