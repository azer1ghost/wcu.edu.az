<?php

class Mainbanner_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'mainbanner';
    }

    /* LIST */
    public function listItems() {
        $selectData = [
                'deleted'=>0,
                'lang'=>MFAdmin::$_lang
        ];
        $query = "SELECT P.`id`, P.`ordering`, PA.`title` as `category`, T.`title`, P.`update_date`, P.`status`
                    FROM `cms_mainbanner` P
                    LEFT JOIN `cms_mainbanner_text` T ON T.p_id = P.id and T.lang =:lang 
                    LEFT JOIN `cms_pages_text` PA ON PA.`p_id` = P.`category` and PA.`lang` =:lang 
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
        $result = $this->db->update('`cms_mainbanner`', $updateData, '`id` IN (' . $ids . ')');
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
        $result = $this->db->update('`cms_mainbanner`', $updateData, '`id` IN (' . $ids . ')');
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
            $result = $this->db->update('`cms_mainbanner`', $updateData, '`id` IN (' . $key . ')');
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
                    FROM `cms_mainbanner`";
        $result = $this->db->select($query, $selectData);
        return $result[0]['ordering'];
    }
    public function listCategory($parent) {
        $selectData = [
                'parent' => $parent,
                'status'=>2,
                'deleted'=>0,
                'lang'=>MFAdmin::$_lang
        ];

        $query = "SELECT P.`id`, T.`title` 
                    FROM `cms_pages` P 
                    LEFT JOIN `cms_pages_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                    WHERE P.`parent`=:parent and P.`status`=:status and P.`deleted`=:deleted 
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);
        return $result;
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

        foreach (MFAdmin::$_langs as $key=>$value){
            /*
             * CHECK title
             */
            $data['data_title_'.$key] = isset($_POST['data_title_'.$key]) ? Func::check($_POST['data_title_'.$key]) : '';
            $data['data_subtitle_'.$key] = isset($_POST['data_subtitle_'.$key]) ? Func::check($_POST['data_subtitle_'.$key]) : '';

            /*
             * CHECK link
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
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;

        $data['data_ordering'] = isset($_POST['data_ordering']) ? (int) $_POST['data_ordering']: 0;
        if(!$data['data_ordering']){
            $error[] = Lang::get('Please input order');
        }

        $data['data_category'] = isset($_POST['data_category']) ? (int) $_POST['data_category']: 0;
        if(!$data['data_category']){
            $error[] = Lang::get('Please input category');
        }


        if (empty($error)){
            // insert
            $insertData = [
                    'ordering'=>$data['data_ordering'],
                    'category'=>$data['data_category'],
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->insert('cms_mainbanner', $insertData);
            if ($result) {
                $last_id = $result;

                if(!empty($_FILES['data_photo']['name'])){
                    $uploadPhoto = Func::uploadPhoto($_FILES['data_photo'], $this->_menu, $last_id, Func::rund_number(), photo_mainbanner_width, photo_mainbanner_height);
                    if(isset($uploadPhoto['error']) && !empty($uploadPhoto['error'])){
                        return ['data'=>$data, 'error'=>$uploadPhoto['error']];
                    } else {
                        $updateData = array(
                                'photo' => $uploadPhoto
                        );
                        $this->db->update('`cms_mainbanner`', $updateData, "`id`={$last_id}");
                    }
                }

                foreach (MFAdmin::$_langs as $key=>$value){
                    // insert
                    $insertData = [
                            'p_id'=>$last_id,
                            'title'=>$data['data_title_'.$key],
                            'subtitle'=>$data['data_subtitle_'.$key],
                            'link'=>$data['data_link_'.$key],
                            'lang'=>$key
                    ];
                    $this->db->insert('cms_mainbanner_text', $insertData);
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

        $query = "SELECT P.`id`, P.`photo`, P.`category`, T.`title`, T.`subtitle`, T.`link`, T.`lang`, P.`ordering`, P.`status` 
                    FROM `cms_mainbanner` P
                    LEFT JOIN `cms_mainbanner_text` T ON T.p_id = P.id  
                    WHERE P.`id`=:id
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($result as $value){
            $mas['id'] = $value['id'];
            $mas['photo'] = $value['photo'];
            $mas['ordering'] = $value['ordering'];
            $mas['category'] = $value['category'];
            $mas['status'] = $value['status'];

            $mas['title'][$value['lang']] = $value['title'];
            $mas['subtitle'][$value['lang']] = $value['subtitle'];
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
            $data['data_subtitle_'.$key] = isset($_POST['data_subtitle_'.$key]) ? Func::check($_POST['data_subtitle_'.$key]) : '';

            /*
             * CHECK link
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
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status']: 0;

        $data['data_ordering'] = isset($_POST['data_ordering']) ? (int) $_POST['data_ordering']: 0;
        if(!$data['data_ordering']){
            $error[] = Lang::get('Please input order');
        }

        $data['data_category'] = isset($_POST['data_category']) ? (int) $_POST['data_category']: 0;
        if(!$data['data_category']){
            $error[] = Lang::get('Please input category');
        }

        if(empty($error)) {

            $updateData = [
                    'ordering'=>$data['data_ordering'],
                    'category'=>$data['data_category'],
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->update('`cms_mainbanner`', $updateData, "`id`={$id}");

            if (isset($result['mysql_error'])) {
                return ['model_error' => $result['mysql_error']];
            } else {

                if(!empty($_FILES['data_photo']['name'])){
                    $uploadPhoto = Func::uploadPhoto($_FILES['data_photo'], $this->_menu, $id, Func::rund_number(), photo_mainbanner_width, photo_mainbanner_height);
                    if(isset($uploadPhoto['error']) && !empty($uploadPhoto['error'])){
                        return ['data'=>$data, 'error'=>$uploadPhoto['error']];
                    } else {
                        $updateData = array(
                                'photo' => $uploadPhoto
                        );
                        $this->db->update('`cms_mainbanner`', $updateData, "`id`={$id}");
                    }
                }

                $deleted = $this->db->delete('cms_mainbanner_text',"`p_id`={$id}");
                if($deleted) {
                    foreach (MFAdmin::$_langs as $key=>$value){
                        // insert
                        $insertData = [
                                'p_id'=>$id,
                                'title'=>$data['data_title_'.$key],
                                'subtitle'=>$data['data_subtitle_'.$key],
                                'link'=>$data['data_link_'.$key],
                                'lang'=>$key
                        ];
                        $this->db->insert('cms_mainbanner_text', $insertData);
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
                    FROM `cms_mainbanner` S 
                    WHERE S.`id`=:id";
        $result = $this->db->select($query, $selectData);

        if(!empty($result)){
            $updateData = array(
                    'photo' => ''
            );
            $this->db->update('`cms_mainbanner`', $updateData, "`id` = {$id}");

            foreach ($result[0] as $value){
                @unlink(UPLOAD_DIR.'Image/'.$this->_menu.'/'.$value);
            }
            return 1;
        } else {
            return 0;
        }


    }
}