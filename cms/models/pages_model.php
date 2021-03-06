<?php

class Pages_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'pages';
    }

    /* LIST */
    public function listItems($select = false) {
        $menuData = array(
                'items' => array(),
                'parents' => array()
        );
        $where = "";
        $selectData = [
                'deleted'=>0,
                'lang'=>MFAdmin::$_lang
        ];
        if($select){
            $selectData['status'] = 2;
            $where .= " and `status`=:status";

        }
        $query = "SELECT P.`id`, P.`parent`, P.`static_page`, T.`title`, T.`text`, P.`ordering`, P.`status`
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE `deleted`=:deleted $where
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        foreach ($result as $value) {
            $menuData['items'][$value['id']] = $value;
            $menuData['parents'][$value['parent']][] = $value['id'];
        }

        return $menuData;
    }
    public function deleteItem($items) {
        $ids = implode(',', $items);
        $updateData = array(
                'deleted' => '1',
                'update_date' => date('Y-m-d H:i:s')
        );
        $result = $this->db->update('`cms_pages`', $updateData, '`id` IN (' . $ids . ')');
        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Selected row deleted') . ' (' . $result . ')'];
        }
    }
    public function updateStatus($items, $status) {
        $updateData = array(
                'status' => $status,
                'update_date' => date('Y-m-d H:i:s')
        );
        $ids = implode(',', $items);
        $result = $this->db->update('`cms_pages`', $updateData, '`id` IN (' . $ids . ')');
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
            $result = $this->db->update('`cms_pages`', $updateData, '`id` IN (' . $key . ')');
        }

        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Data changed')];
        }
    }

    /* ADD */
    public function maxOrder() {
        $selectData = [];

        $query = "SELECT MAX(`ordering`) as `ordering` 
                    FROM `cms_pages`";
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

        /*
         * CHECK parent
         */
        $data['data_parent'] = isset($_POST['data_parent']) ? (int) $_POST['data_parent']: 0;

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
             * CHECK link
             */
            $data['data_link_'.$key] = isset($_POST['data_link_'.$key]) ? Func::check($_POST['data_link_'.$key]) : '';

            /*
             * CHECK subtitle
             */
            $data['data_subtitle_'.$key] = isset($_POST['data_subtitle_'.$key]) ? Func::check($_POST['data_subtitle_'.$key]) : '';

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
         * CHECK status && ordering
         */
        $data['data_static_page'] = isset($_POST['data_static_page']) ? (int) $_POST['data_static_page']: 0;
        $data['data_post_in_page'] = isset($_POST['data_post_in_page']) ? (int) $_POST['data_post_in_page']: 0;
        $data['data_target'] = isset($_POST['data_target']) ? (int) $_POST['data_target']: 0;
        $data['data_ordering'] = isset($_POST['data_ordering']) ? (int) $_POST['data_ordering']: 0;
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;

        /*
         * CHECK secret_id
         */
        $data['sid'] = isset($_POST['sid']) ? Func::check($_POST['sid']): 0;
        if(!$data['sid']){
            $error[] = Lang::get('Error secret_id');
        }

        if (empty($error)){
            // insert
            $insertData = [
                    'parent'=>$data['data_parent'],
                    'ordering'=>$data['data_ordering'],
                    'static_page'=>$data['data_static_page'], // 1-static, 0-dynamic
                    'post_in_page'=>$data['data_post_in_page'],
                    'target'=>$data['data_target'], // 1-_blank, 0
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->insert('cms_pages', $insertData);
            if ($result) {
                $last_id = $result;

                $updateData = [
                        'p_id'=>$last_id
                ];
                $this->db->update('`cms_pages_photo`', $updateData, "`secret_id`='{$data['sid']}'");

                foreach (MFAdmin::$_langs as $key=>$value){
                    // insert
                    $insertData = [
                            'p_id'=>$last_id,
                            'title'=>$data['data_title_'.$key],
                            'subtitle'=>$data['data_subtitle_'.$key],
                            'slug'=>$data['data_slug_'.$key],
                            'link'=>$data['data_link_'.$key],
                            'text'=>$data['data_text_'.$key],
                            'lang'=>$key
                    ];
                    $this->db->insert('cms_pages_text', $insertData);
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

        $query = "SELECT P.`id`, P.`parent`, P.`static_page`, P.`post_in_page`, P.`target`, 
                      T.`title`,  T.`subtitle`, T.`slug`, T.`link`, T.`text`, T.`lang`, P.`ordering`, P.`status`, 
	                  (SELECT `secret_id` FROM `cms_pages_photo` WHERE p_id = P.id LIMIT 1) as  `secret_id` 
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id  
                    WHERE P.`id`=:id
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);


        /*
         * SELECT photo
         */
        $query = "SELECT `id`, `thumb`, `secret_id`
                    FROM `cms_pages_photo` 
                    WHERE `p_id` =:id 
                    ORDER BY `ordering`";
        $resultPhoto = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($result as $value){
            $mas['id'] = $value['id'];
            $mas['parent'] = $value['parent'];
            $mas['ordering'] = $value['ordering'];
            $mas['static_page'] = $value['static_page'];
            $mas['post_in_page'] = $value['post_in_page'];
            $mas['target'] = $value['target'];
            $mas['status'] = $value['status'];

            $mas['title'][$value['lang']] = $value['title'];
            $mas['subtitle'][$value['lang']] = $value['subtitle'];
            $mas['slug'][$value['lang']] = $value['slug'];
            $mas['link'][$value['lang']] = $value['link'];
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
        /*
         * CHECK parent
         */
        $data['data_parent'] = isset($_POST['data_parent']) ? (int) $_POST['data_parent']: 0;

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
             * CHECK link
             */
            $data['data_link_'.$key] = isset($_POST['data_link_'.$key]) ? Func::check($_POST['data_link_'.$key]) : '';

            /*
             * CHECK link
             */
            $data['data_subtitle_'.$key] = isset($_POST['data_subtitle_'.$key]) ? Func::check($_POST['data_subtitle_'.$key]) : '';

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
         * CHECK status && ordering
         */
        $data['data_static_page'] = isset($_POST['data_static_page']) ? (int) $_POST['data_static_page']: 0;
        $data['data_post_in_page'] = isset($_POST['data_post_in_page']) ? (int) $_POST['data_post_in_page']: 0;
        $data['data_target'] = isset($_POST['data_target']) ? (int) $_POST['data_target']: 0;
        $data['data_ordering'] = isset($_POST['data_ordering']) ? (int) $_POST['data_ordering']: 0;
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;

        /*
         * CHECK secret_id
         */
        $data['sid'] = isset($_POST['sid']) ? Func::check($_POST['sid']): 0;
        if(!$data['sid']){
            $error[] = Lang::get('Error secret_id');
        }

        if(empty($error)) {

            $updateData = [
                    'parent'=>$data['data_parent'],
                    'ordering'=>$data['data_ordering'],
                    'static_page'=>$data['data_static_page'], // 1-static, 0-dynamic
                    'post_in_page'=>$data['data_post_in_page'],
                    'target'=>$data['data_target'], // 1-_blank, 0
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->update('`cms_pages`', $updateData, "`id`={$id}");

            if (isset($result['mysql_error'])) {
                return ['model_error' => $result['mysql_error']];
            } else {

                $updateData = [
                        'p_id'=>$id
                ];
                $this->db->update('`cms_pages_photo`', $updateData, "`secret_id`='{$data['sid']}'");

                $deleted = $this->db->delete('cms_pages_text',"`p_id`={$id}");
                if($deleted) {
                    foreach (MFAdmin::$_langs as $key=>$value){
                        // insert
                        $insertData = [
                                'p_id'=>$id,
                                'title'=>$data['data_title_'.$key],
                                'subtitle'=>$data['data_subtitle_'.$key],
                                'slug'=>$data['data_slug_'.$key],
                                'link'=>$data['data_link_'.$key],
                                'text'=>$data['data_text_'.$key],
                                'lang'=>$key
                        ];
                        $this->db->insert('cms_pages_text', $insertData);
                    }
                    return ['model_success' => Lang::get('Data changed')];
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
                    FROM `cms_pages_photo` 
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
                                        $handle->image_x              = photo_pages_width;
                                        $handle->image_ratio_y        = photo_pages_height;
                                        $handle->dir_chmod            = 0777;
                                        $handle->process(UPLOAD_DIR . 'Image/'.$this->_menu);

                                        // thummb
                                        $handle->file_new_name_body = $thumb_upload_name;
                                        $handle->file_overwrite     = true;
                                        $handle->allowed            = array('image/*');
                                        $handle->image_resize       = true;
                                        $handle->image_x            = thumb_pages_width;
                                        $handle->image_ratio_y      = thumb_pages_height;
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
                                    $result = $this->db->insert('`cms_pages_photo`', $insertData);
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
            $query = "SELECT `thumb`, `photo` FROM `cms_pages_photo` WHERE `id`=:id and `secret_id`=:secret_id LIMIT 1";
            $deleteDbWhere = "`id`={$gid} and `secret_id`='{$sid}'";
        } else {
            $selectData = [
                    'p_id'=>0
            ];
            $query = "SELECT `thumb`, `photo` FROM `cms_pages_photo` WHERE `p_id`=:p_id and date(`date`) < CURDATE()";
            $deleteDbWhere = "`p_id`=0 and date(`date`) < CURDATE()";
        }
        $result = $this->db->select($query, $selectData);
        //print_r($result);
        foreach ($result as $key=>$value){
            @unlink(UPLOAD_DIR . 'Image/' . $this->_menu . '/' . $value['thumb']);
            @unlink(UPLOAD_DIR . 'Image/' . $this->_menu . '/' . $value['photo']);
        }

        // delete row from table
        $this->db->delete('`cms_pages_photo`', $deleteDbWhere);

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
                $this->db->update('`cms_pages_photo`', $updateData, "`id`={$id}");
                $listingCounter = $listingCounter + 1;
            }
        } else {
            echo Lang::get('Please order image correctly');
        }
    }
}