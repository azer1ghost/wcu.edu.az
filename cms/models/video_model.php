<?php

class Video_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'video';
    }

    /* LIST */
    public function listItems() {
        $selectData = [
                'deleted'=>0,
                'lang'=>MFAdmin::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, P.`ordering`, P.`update_date`, P.`status`
                    FROM `cms_video` P
                    LEFT JOIN `cms_video_text` T ON T.p_id = P.id and T.lang =:lang 
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
        $result = $this->db->update('`cms_video`', $updateData, '`id` IN (' . $ids . ')');
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
        $result = $this->db->update('`cms_video`', $updateData, '`id` IN (' . $ids . ')');
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
            $result = $this->db->update('`cms_video`', $updateData, '`id` IN (' . $key . ')');
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
                    FROM `cms_video`";
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

        if(empty($_FILES['data_photo']['name'])){
            $error[] = Lang::get('Please select photo');
        }

        foreach (MFAdmin::$_langs as $key=>$value){
            /*
             * CHECK name
             */
            $data['data_title_'.$key] = isset($_POST['data_title_'.$key]) ? Func::check($_POST['data_title_'.$key]) : '';

            /*
             * CHECK Link
             */
            $data['data_link_'.$key] = isset($_POST['data_link_'.$key]) ? Func::checkUrl($_POST['data_link_'.$key]) : '';
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
            $result = $this->db->insert('cms_video', $insertData);

            if ($result) {
                $last_id = $result;

                foreach (MFAdmin::$_langs as $key=>$value){
                    // insert
                    $insertData = [
                            'p_id'=>$last_id,
                            'title'=>$data['data_title_'.$key],
                            'link'=>$data['data_link_'.$key],
                            'lang'=>$key
                    ];
                    $this->db->insert('cms_video_text', $insertData);
                }

                /* upload image */
                $file = Func::ext($_FILES['data_photo']['name']);

                if ($file['type'] == 'Image') {
                    $photo_upload_name = $this->_menu.'-' . $last_id .'-'.Func::rund_number();
                    $photo_db_name = $photo_upload_name.$file['ext'];

                    require 'helper/class.upload.php';
                    $handle = new upload($_FILES['data_photo']);
                    if ($handle->uploaded) {
                        $handle->file_new_name_body   = $photo_upload_name;
                        $handle->file_overwrite       = true;
                        $handle->allowed              = array('image/*');
                        $handle->image_resize         = true;
                        $handle->image_ratio_crop     = true;
                        $handle->image_x              = photo_video_width;
                        $handle->image_y              = photo_video_height;
                        $handle->dir_chmod            = 0777;
                        $handle->process(UPLOAD_DIR.'Image/'.$this->_menu.'/');
                        if ($handle->processed) {
                            $handle->clean();

                            $updateData = array(
                                    'photo' => $photo_db_name
                            );
                            $this->db->update('`cms_video`', $updateData, '`id` IN (' . $last_id . ')');
                        } else {
                            return ['data'=>$data, 'error'=>$handle->error];
                        }
                    }
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

        $query = "SELECT S.`id`, S.`photo`, T.`title`, T.`link`, T.`lang`, S.`ordering`, S.`status` 
                    FROM `cms_video` S 
                    LEFT JOIN `cms_video_text` T ON T.p_id = S.id   
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
            $mas['link'][$value['lang']] = $value['link'];
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
             * CHECK Link
             */
            $data['data_link_'.$key] = isset($_POST['data_link_'.$key]) ? Func::checkUrl($_POST['data_link_'.$key]) : '';
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
            $result = $this->db->update('`cms_video`', $updateData, "`id`={$id}");

            if (isset($result['mysql_error'])) {
                return ['model_error' => $result['mysql_error']];
            } else {


                if(!empty($_FILES['data_photo']['name'])){
                    /* upload image */
                    $last_id = $id;
                    $file = Func::ext($_FILES['data_photo']['name']);

                    if ($file['type'] == 'Image') {
                        $photo_upload_name = 'video-' . $last_id .'-'.Func::rund_number();
                        $photo_db_name = $photo_upload_name.$file['ext'];

                        require 'helper/class.upload.php';
                        $handle = new upload($_FILES['data_photo']);
                        if ($handle->uploaded) {
                            $handle->file_new_name_body   = $photo_upload_name;
                            $handle->file_overwrite       = true;
                            $handle->allowed              = array('image/*');
                            $handle->image_resize         = true;
                            $handle->image_ratio_crop     = true;
                            $handle->image_x              = photo_video_width;
                            $handle->image_y              = photo_video_height;
                            $handle->dir_chmod            = 0777;
                            $handle->process(UPLOAD_DIR.'Image/'.$this->_menu.'/');
                            if ($handle->processed) {
                                $handle->clean();

                                $updateData = array(
                                        'photo' => $photo_db_name
                                );
                                $this->db->update('`cms_video`', $updateData, '`id` IN (' . $last_id . ')');
                            } else {
                                return ['data'=>$data, 'error'=>$handle->error];
                            }
                        }
                    }
                }

                $deleted = $this->db->delete('cms_video_text','`p_id` IN (' . $id . ')');
                if($deleted) {
                    foreach (MFAdmin::$_langs as $key=>$value){
                        // insert
                        $insertData = [
                                'p_id'=>$id,
                                'title'=>$data['data_title_'.$key],
                                'link'=>$data['data_link_'.$key],
                                'lang'=>$key
                        ];
                        $this->db->insert('cms_video_text', $insertData);
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
                    FROM `cms_video` S 
                    WHERE S.`id`=:id";
        $result = $this->db->select($query, $selectData);

        if(!empty($result)){
            $updateData = array(
                    'photo' => ''
            );
            $this->db->update('`cms_video`', $updateData, "`id` = {$id}");

            foreach ($result[0] as $value){
                @unlink(UPLOAD_DIR.'Image/'.$this->_menu.'/'.$value);
            }
            return 1;
        } else {
            return 0;
        }


    }
}