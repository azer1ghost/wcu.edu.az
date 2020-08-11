<?php

class Admin_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'admin';
    }

    /* LIST */
    public function listItems() {
        $selectData = [
                'deleted'=>0
        ];
        $query = "SELECT `id`, `name`, `status` 
                    FROM `cms_admins`
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
        $result = $this->db->update('`cms_admins`', $updateData, '`id` IN (' . $ids . ')');
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
        $result = $this->db->update('`cms_admins`', $updateData, '`id` IN (' . $ids . ')');
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

        /*
         * CHECK Name
         */
        $data['name'] = isset($_POST['name']) ? Func::check($_POST['name']) : '';
        if(empty($data['name'])){
            $error[] = Lang::get('Please input name');
        }

        /*
         * CHECK Login
         */
        $data['login'] = isset($_POST['login']) ? Func::check($_POST['login']) : '';
        if(empty($data['login'])){
            $error[] = Lang::get('Please input login name');
        }

        /*
         * CHECK password
         */
        $data['password'] = isset($_POST['password']) ? Func::check($_POST['password']) : '';
        if(empty($data['password'])){
            $error[] = Lang::get('Please input password');
        }

        /*
         * CHECK Roles
         */
        if (!empty($_POST['role'])) {
            foreach ($_POST['role'] as $key => $value) {
                if (trim($value)) {
                    $data['role'][] = Func::check($value);
                }
            }
        } else {
            $error[] = Lang::get('Please input roles');
        }

        /*
         * CHECK status && ordering
         */
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status'] : '';

        if (empty($error)){
            // insert
            $insertData = [
                    'name' => $data['name'],
                    'login' => $data['login'],
                    'password' => Hash::create('sha256', $data['password'], HASH_PASSWORD_KEY),
                    'role' => json_encode($data['role']),
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'create_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s')
            ];
            $result = $this->db->insert('cms_admins', $insertData);

            if ($result) {
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

        $query = "SELECT * 
                    FROM `cms_admins`
                    WHERE `id`=:id 
                    LIMIT 1";
        $result = $this->db->select($query, $selectData);
        return $result[0];
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

        /*
         * CHECK Name
         */
        $data['name'] = isset($_POST['name']) ? Func::check($_POST['name']) : '';
        if(empty($data['name'])){
            $error[] = Lang::get('Please input name');
        }

        /*
         * CHECK Login
         */
        $data['login'] = isset($_POST['login']) ? Func::check($_POST['login']) : '';
        if(empty($data['login'])){
            $error[] = Lang::get('Please input login name');
        }

        /*
         * CHECK password
         */
        $data['password'] = isset($_POST['password']) ? Func::check($_POST['password']) : '';

        /*
         * CHECK Roles
         */
        if (!empty($_POST['role'])) {
            foreach ($_POST['role'] as $key => $value) {
                if (trim($value)) {
                    $data['role'][] = Func::check($value);
                }
            }
        } else {
            $error[] = Lang::get('Please input roles');
        }

        /*
         * CHECK status && ordering
         */
        $data['status'] = isset($_POST['status']) ? (int) $_POST['status'] : '';

        if(empty($error)) {

            $updateData = [
                    'name' => $data['name'],
                    'login' => $data['login'],
                    'role' => json_encode($data['role']),
                    'status'=>$data['status'],

                    'creator_id'=>Session::get('userid'),
                    'update_date' => date('Y-m-d H:i:s')
            ];

            if (isset($data['password']) && !empty($data['password'])) {
                $updateData['password'] = Hash::create('sha256', $data['password'], HASH_PASSWORD_KEY);
            }

            $result = $this->db->update('`cms_admins`', $updateData, "`id`={$id}");

            if (isset($result['mysql_error'])) {
                return ['model_error' => $result['mysql_error']];
            } else {
                return ['model_success' => Lang::get('Data changed')];
            }

        } else {
            return ['model_error' => $error];
        }
    }
}