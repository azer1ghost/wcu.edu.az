<?php

class Graduate_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'graduate';
    }

    /* LIST */
    public function listItems() {
        $selectData = [
                'deleted'=>0,
                'lang'=>MFAdmin::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`text`, P.`post_time`, P.`update_date`, P.`status`
                    FROM `cms_graduate` P
                    LEFT JOIN `cms_graduate_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE `deleted`=:deleted
                    ORDER BY `post_time` desc";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
    public function deleteItem($items) {
        $ids = implode(',', $items);
        $updateData = array(
                'deleted' => '1',
                'update_date' => date('Y-m-d H:i:s')
        );
        $result = $this->db->update('`cms_graduate`', $updateData, '`id` IN (' . $ids . ')');
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
        $result = $this->db->update('`cms_graduate`', $updateData, '`id` IN (' . $ids . ')');
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
            header('Location: ' . URL . $this->_menu.'/add');
            exit;
        }
        $error = [];

        foreach (MFAdmin::$_langs as $key=>$value){
            /*
             * CHECK title
             */
            $data['data_title_'.$key] = isset($_POST['data_title_'.$key]) ? Func::check($_POST['data_title_'.$key]) : '';

            /*
             * CHECK slug
             */
            $data['data_slug_'.$key] = isset($_POST['data_slug_'.$key]) ? Func::check($_POST['data_slug_'.$key]) : '';
            if($data['data_slug_'.$key]){

                $selectData = [
                        'slug'=>"%{$data['data_slug_'.$key]}%",
                        'deleted'=>0,
                        'lang'=>$key
                ];

                $query = "SELECT count(P.`id`) as `count`
                            FROM `cms_graduate` P
                            LEFT JOIN `cms_graduate_text` T ON T.p_id = P.id and T.lang =:lang
                            WHERE P.`deleted`=:deleted and T.slug LIKE :slug";
                $result = $this->db->select($query, $selectData);
                if($result[0]['count']>0){
                    echo $data['data_slug_'.$key] = $data['data_slug_'.$key].'-'.$result[0]['count'];
                }
            }

            /*
             * CHECK text
             */
            $data['data_text_'.$key] = isset($_POST['data_text_'.$key]) ? Func::checkText($_POST['data_text_'.$key]) : '';
        }
        $data_text = array_filter($data);
        if(empty($data_text)){
            $error[] = Lang::get('Please input titles');
        }

        /*
         * CHECK status
         */
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;
        if(!$data['status']){
            $error[] = Lang::get('Please input status');
        }

        /*
         * CHECK secret_id
         */
        $data['sid'] = isset($_POST['sid']) ? Func::check($_POST['sid']): 0;
        if(!$data['sid']){
            $error[] = Lang::get('Error secret_id');
        }

        /*
         * CHECK post time
         */
        $data['post_time'] = (isset($_POST['post_time']) && !empty($_POST['post_time'])) ? Func::check($_POST['post_time']) : date('Y-m-d H:i:00');


        if (empty($error)){
            // insert
            $insertData = [
                    'status'=>$data['status'],
                    'post_time'=>$data['post_time'],

                    'creator_id'=>Session::get('userid'),
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->insert('cms_graduate', $insertData);

            if ($result) {
                $last_id = $result;

                $updateData = [
                        'p_id'=>$last_id
                ];
                $this->db->update('`cms_graduate_photo`', $updateData, "`secret_id`='{$data['sid']}'");

                foreach (MFAdmin::$_langs as $key=>$value){
                    // insert
                    $insertData = [
                            'p_id'=>$last_id,
                            'title'=>$data['data_title_'.$key],
                            'slug'=>$data['data_slug_'.$key],
                            'text'=>$data['data_text_'.$key],
                            'lang'=>$key
                    ];
                    $this->db->insert('cms_graduate_text', $insertData);
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

        /*
         * SELECT `data`
         */
        $query = "SELECT S.`id`, S.`status`, S.`post_time`, T.`title`, T.`slug`, T.`text`, T.`lang`, 
	                  (SELECT `secret_id` FROM `cms_graduate_photo` WHERE p_id = S.id LIMIT 1) as  `secret_id` 
                    FROM `cms_graduate` S 
                    LEFT JOIN `cms_graduate_text` T ON T.p_id = S.id   
                    WHERE S.`id`=:id";
        $resultText = $this->db->select($query, $selectData);

        /*
         * SELECT photo
         */
        $query = "SELECT `id`, `thumb`, `secret_id`
                    FROM `cms_graduate_photo` 
                    WHERE `p_id` =:id 
                    ORDER BY `ordering`";
        $resultPhoto = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($resultText as $value){
            $mas['id'] = $value['id'];
            $mas['secret_id'] = $value['secret_id'];
            $mas['status'] = $value['status'];
            $mas['post_time'] = $value['post_time'];

            $mas['title'][$value['lang']] = $value['title'];
            $mas['slug'][$value['lang']] = $value['slug'];
            $mas['text'][$value['lang']] = $value['text'];
        }

        $mas['photo'] = $resultPhoto;

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
             * CHECK slug
             */
            $data['data_slug_'.$key] = isset($_POST['data_slug_'.$key]) ? Func::check($_POST['data_slug_'.$key]) : '';

            /*
             * CHECK text
             */
            $data['data_text_'.$key] = isset($_POST['data_text_'.$key]) ? Func::checkText($_POST['data_text_'.$key]) : '';
        }
        $data_text = array_filter($data);
        if(empty($data_text)){
            $error[] = Lang::get('Please input titles');
        }

        /*
         * CHECK status
         */
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;
        if(!$data['status']){
            $error[] = Lang::get('Please input status');
        }

        /*
         * CHECK secret_id
         */
        $data['sid'] = isset($_POST['sid']) ? Func::check($_POST['sid']): 0;
        if(!$data['sid']){
            $error[] = Lang::get('Error secret_id');
        }

        /*
         * CHECK post time
         */
        $data['post_time'] = (isset($_POST['post_time']) && !empty($_POST['post_time'])) ? Func::check($_POST['post_time']) : date('Y-m-d H:i:00');


        if(empty($error)) {

            $updateData = [
                    'status'=>$data['status'],
                    'post_time'=>$data['post_time'],

                    'creator_id'=>Session::get('userid'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->update('`cms_graduate`', $updateData, "`id`={$id}");

            if (isset($result['mysql_error'])) {
                return ['model_error' => $result['mysql_error']];
            } else {

                $updateData = [
                        'p_id'=>$id
                ];
                $this->db->update('`cms_graduate_photo`', $updateData, "`secret_id`='{$data['sid']}'");

                $deleted = $this->db->delete('cms_graduate_text', "`p_id`='{$id}'");
                if($deleted) {
                    foreach (MFAdmin::$_langs as $key=>$value){
                        // insert
                        $insertData = [
                                'p_id'=>$id,
                                'title'=>$data['data_title_'.$key],
                                'slug'=>$data['data_slug_'.$key],
                                'text'=>$data['data_text_'.$key],
                                'lang'=>$key
                        ];
                        $this->db->insert('cms_graduate_text', $insertData);
                    }
                    return ['model_success' => Lang::get('Changed data')];
                }

            }

        } else {
            return ['model_error' => $error];
        }
    }

    /* HELPER */
    public function maxOrderPhoto($sid) {
        $selectData = [
                'secret_id'=>$sid
        ];

        $query = "SELECT MAX(`ordering`) as `ordering` 
                    FROM `cms_graduate_photo` 
                     WHERE `secret_id`=:secret_id 
                     ORDER BY `ordering` DESC 
                     LIMIT 1";
        $result = $this->db->select($query, $selectData);
        return $result[0]['ordering'];
    }
    public function multiupload(){

        header('Content-Type: application/json');
        if(!empty($_FILES['file']) && isset($_POST['sid']) && $_POST['sid']!='undefined'){
            $sid = Func::check($_POST['sid']);

            /***********************************
             * DELETE IMAGE NO USE
             ***********************************/
            $this->deletePhoto();

            /***********************************
             * GET MAX ORDER THIS sid
             ***********************************/
            $maxOrder = $this->maxOrderPhoto($sid) + 1;

            /***********************************
             * Upload image
             ***********************************/
            $files = array();
            foreach ($_FILES['file'] as $k => $l) {
                foreach ($l as $i => $v) {
                    if (!array_key_exists($i, $files))
                        $files[$i] = array();
                    $files[$i][$k] = $v;
                }
            }

            $allowed = array('jpg', 'png', 'jpeg', 'gif');
            $allowedContentType = array('image/jpeg', 'image/gif', 'image/png');

            $succeeded = array();
            $failed = array();

            require 'helper/class.upload.php';
            foreach ($files as $file) {
                if($file['error'] === 0){
                    // check file
                    $temp = $file['tmp_name'];
                    $imageinfo = getimagesize($temp); // mime-type
                    $ext = explode('.', $file['name']);
                    $ext = strtolower(end($ext));

                    if (in_array($ext, $allowed)) {
                        if ($file['size'] <= 4194304) {
                            if (in_array($file['type'], $allowedContentType)) {
                                if (in_array($imageinfo['mime'], $allowedContentType)) {

                                    $fileName = md5_file($temp). time();
                                    $photo_upload_name = $fileName . '-photo';
                                    $photo_db_name = $fileName . '-photo.'.$ext;
                                    $thumb_upload_name = $fileName . '-thumb';
                                    $thumb_db_name = $fileName. '-thumb.'.$ext;

                                    // upload image
                                    $handle = new upload($file);
                                    if ($handle->uploaded) {
                                        // photo
                                        $handle->file_new_name_body = $photo_upload_name;
                                        $handle->file_new_name_body   = $photo_upload_name;
                                        $handle->file_overwrite       = true;
                                        $handle->allowed              = array('image/*');
                                        $handle->image_resize         = true;
                                        //$handle->image_watermark    = _imgdir_.'logo1.png';
                                        //$handle->image_watermark_x  = 20;
                                        //$handle->image_watermark_y  = 20;
                                        $handle->image_x              = photo_graduate_width;
                                        $handle->image_ratio_y        = photo_graduate_height;
                                        $handle->dir_chmod            = 0777;
                                        $handle->process(UPLOAD_DIR . 'Image/'.$this->_menu);

                                        // thummb
                                        $handle->file_new_name_body = $thumb_upload_name;
                                        $handle->file_overwrite     = true;
                                        $handle->allowed            = array('image/*');
                                        $handle->image_resize       = true;
                                        $handle->image_ratio_crop  = true;
                                        $handle->image_x            = thumb_graduate_width;
                                        $handle->image_y            = thumb_graduate_height;
                                        $handle->dir_chmod          = 0777;
                                        $handle->process(UPLOAD_DIR . 'Image/'.$this->_menu);
                                        if ($handle->processed) {
                                            $handle->clean();
                                        }
                                    }


                                    // insert photo DB
                                    $insertData = [
                                            'secret_id'=>$sid,
                                            'ordering'=>$maxOrder,
                                            'thumb'=>$thumb_db_name,
                                            'photo'=>$photo_db_name,
                                            'date'=>date('Y-m-d H:i:s')
                                    ];
                                    $result = $this->db->insert('`cms_graduate_photo`', $insertData);
                                    if($result){
                                        $succeeded[] = array(
                                                'id'=>$result,
                                                'name'=>$file['name'],
                                                'file'=>$thumb_db_name
                                        );
                                    }
                                }
                            }
                        } else {
                            $failed[] = array(
                                    'name'=>$file['name'].' '.Lang::get('image size is big')
                            );
                        }
                    } else {
                        $failed[] = array(
                                'name'=>$file['name'].' '.Lang::get('image type is no image')
                        );
                    }
                    $maxOrder++; // ordering
                }
            }

            if(!empty($_POST['ajax'])){
                echo json_encode(array(
                        'succeeded'=>$succeeded,
                        'failed'=>$failed
                ));
            }

        }
    }
    public function deletePhoto($gid=NULL, $sid=NULL){
        if(isset($gid) && isset($sid)){
            $gid = (int) $gid;
            $sid = Func::check($sid);
            $selectData = [
                    'id'=>$gid,
                    'secret_id'=>$sid
            ];
            $query = "SELECT `thumb`, `photo` FROM `cms_graduate_photo` WHERE `id`=:id and `secret_id`=:secret_id LIMIT 1";
            $deleteDbWhere = "`id`={$gid} and `secret_id`='{$sid}'";
        } else {
            $selectData = [
                    'p_id'=>0
            ];
            $query = "SELECT `thumb`, `photo` FROM `cms_graduate_photo` WHERE `p_id`=:p_id and date(`date`) < CURDATE()";
            $deleteDbWhere = "`p_id`=0 and date(`date`) < CURDATE()";
        }
        $result = $this->db->select($query, $selectData);
        //print_r($result);
        foreach ($result as $key=>$value){
            @unlink(UPLOAD_DIR . 'Image/' . $this->_menu . '/' . $value['thumb']);
            @unlink(UPLOAD_DIR . 'Image/' . $this->_menu . '/' . $value['photo']);
        }

        // delete row from table
        $this->db->delete('`cms_graduate_photo`', $deleteDbWhere);

    }
    public function orderphoto(){
        $updateRecordsArray = $_POST['recordsArray'];
        if(is_array($updateRecordsArray) && !empty($updateRecordsArray)){
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {
                $id = (int) $recordIDValue;
                $updateData = [
                        'ordering'=> $listingCounter
                ];
                $this->db->update('`cms_graduate_photo`', $updateData, "`id`={$id}");
                $listingCounter = $listingCounter + 1;
            }
        } else {
            echo Lang::get('Please order image correctly');
        }
    }
}