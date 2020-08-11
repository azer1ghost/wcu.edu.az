<?php

class Login_Model extends Model {
    public function __construct() {
        parent::__construct();
    }

    public function run() {
        /*
         * Check Token
         */
        $_POST['token'] = isset($_POST['token']) ? Func::check($_POST['token']) : '';
        if (!Func::token_check($_POST['token'])) {
            Session::set('note_error', Lang::get('Token Error!'));
            header('location: ' . URL . 'login');
            exit;
        }

        $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        $data['login'] = isset($_POST['login']) ? Func::check($_POST['login']) : '';
        $data['password'] = isset($_POST['password']) ? $_POST['password'] : '';

        if (!$data['login'] or !$data['password']) {
            $error[] = Lang::get('Please enter username and password');
        }

        /*
         * @CHECK  - if anyibrut count > 5 run recaptcha
         */
        $count = $this->anyibrutCount();
        if($count>3 && empty($error)){
            if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                $secret = RECAPTCHA_SECRET;
                $google_link = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response'];
                $verifyResponse = file_get_contents($google_link);
                $responseData = json_decode($verifyResponse);
                if (!$responseData->success) {
                    $error[] = Lang::get('Please enter the correct reCaptcha!');
                }
            } else {
                $error[] = Lang::get('Please enter the correct reCaptcha!');
            }
        }

        if (empty($error)) {
            //echo Hash::create('sha256', $_POST['password'], HASH_PASSWORD_KEY);
            //exit;
            $query = "SELECT `id`, `name`, `role`
                            FROM `cms_admins` 
                            WHERE `login` = :login AND `password` = :password AND `status` = '2' and `deleted`=0";
            $sth = $this->db->prepare($query);
            $sth->execute(array(
                    ':login' => $data['login'],
                    ':password' => Hash::create('sha256', $data['password'], HASH_PASSWORD_KEY)
            ));
			
			
			
			
            $m = $sth->fetch();
            $count = $sth->rowCount();
			
			
			//print_r($count); die();
			
			
			
            if ($count > 0) {
                // login
                //Session::init();
                Session::set('role', $m['role']);
                Session::set('loggedIn', true);
                Session::set('userid', $m['id']);
                Session::set('userName', $m['name']);
                Session::set('admin_token', Func::token());

                header('Location: ' . URL);
                exit;
            } else {
                /*
                 * IF not enter true for show reCaptcha
                 */
                $input = json_encode($data);
                $insertData = [
                        'input'=> $input,
                        'ip'=>$ip,
                        'time'=>time()
                ];
                $this->db->insert('`cms_admin_antibrut`', $insertData);

                Session::set('note_error', 'Not right username or password. Please try again!');
                header('Location: ' . URL . 'login');
                exit;
            }

        } else {
            Session::set('note_error', $error[0]);
            header('location: ' . URL . 'login');
            exit;
        }

    }

    public function anyibrutCount() {
        $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        $selectData = [
                'ip' => $ip,
                'time'=>strtotime('-30 minute')
        ];
        $query = "SELECT count(`id`) as `count` 
                    FROM `cms_admin_antibrut` 
                    WHERE `ip` =:ip and `time` >= :time";
        return $this->db->selectCount($query, $selectData);
    }
}