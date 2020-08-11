<?php
/**
 * Created by MF.
 * User: MF
 * Date: 6/12/2017
 * Time: 10:17 AM
 */

class Func extends Database {

    /*
     * Check
     */
    public static function check($str) {
        $str = trim($str);
        $str = htmlspecialchars(strip_tags($str), ENT_QUOTES);
        return $str;
    }
    public static function checkText($str) {
        $str = trim($str);
        //$str = htmlspecialchars($str, ENT_QUOTES);
        return $str;
    }
    public static function checkFloat($str) {
        $str = trim($str);
        $str = filter_var($str, FILTER_VALIDATE_FLOAT);
        return $str;
    }
    public static function checkMail($str) {
        $str = trim($str);
        $str = filter_var($str, FILTER_VALIDATE_EMAIL);
        return $str;
    }
    public static function checkIP($str) {
        $str = trim($str);
        $str = filter_var($str, FILTER_VALIDATE_IP);
        return $str;
    }
    public static function checkUrl($str) {
        $str = trim($str);
        $str = filter_var($str, FILTER_VALIDATE_URL);
        return $str;
    }
    public static function arrayFlatten($array=[]) {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::arrayFlatten($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /*
     *
     */
    public static function sub_string($str, $length=false){
        $len = strlen($str);
        $length = ($length < 1) ? 80 : $length;
        $str = wordwrap($str, $length, "<br>\n");
        $str = explode("<br>\n", $str);

        if($len>$length) {
            $str[0] .=" ...";
        }
        return $str[0];
    }

    public static function ext($file) {
        $ext = explode('.', $file);
        $ext = strtolower('.' . $ext[count($ext) - 1]);
        $result = array();
        if ($ext == '.gif' || $ext == '.jpg' || $ext == '.jpeg' || $ext == '.png' || $ext == '.bmp') {
            $result['ext'] = $ext;
            $result['type'] = 'Image';
        } elseif ($ext == '.swf') {
            $result['ext'] = $ext;
            $result['type'] = 'Flash';
        } elseif ($ext == '.flv' || $ext == '.mp4' || $ext == '.avi' || $ext == '.mp3') {
            $result['ext'] = $ext;
            $result['type'] = 'Media';
        } elseif ($ext == '.ppt' || $ext == '.pptx' || $ext == '.rar' || $ext == '.zip' || $ext == '.doc' || $ext == '.docx' || $ext == '.xls' || $ext == '.xlsx' || $ext == '.txt' || $ext == '.pdf') {
            $result['ext'] = $ext;
            $result['type'] = 'File';
        }
        return $result;
    }

    public static function uploadPhoto($files='', $menu='menu', $id=1, $rund_number='', $width=false, $height=false){
        if(!empty($files['name'])){
            $file = Func::ext($files['name']);

            if ($file['type'] == 'Image') {
                $photo_upload_name = $menu.'-' . $id .'-'.$rund_number;
                $photo_db_name = $photo_upload_name.$file['ext'];

                require 'helper/class.upload.php';
                $handle = new upload($files);
                if ($handle->uploaded) {
                    $handle->file_new_name_body   = $photo_upload_name;
                    $handle->file_overwrite       = true;
                    $handle->allowed              = array('image/*');
                    $handle->image_resize         = true;
                    if($width){
                        $handle->image_x          = $width;
                    }
                    if($height){
                        $handle->image_ratio_y    = $height;
                    }
                    $handle->dir_chmod            = 0777;
                    $handle->process(UPLOAD_DIR.'Image/'.$menu.'/');
                    if ($handle->processed) {
                        $handle->clean();

                        $msg = $photo_db_name;
                    } else {
                        $msg = ['error'=>$handle->error];
                    }
                }
            } else {
                $msg = 'No image type';
            }
        } else {
            $msg = 'No image';
        }

        return $msg;
    }

    /*
     * Token
     */
    public static function rund_number(){
        return rand(1000000, 9999999);
    }
    public static function token(){
        Session::set('mf_token', md5(uniqid(mt_rand(), true)));
        return Session::get('mf_token');
    }
    public static function token_check($token){
        return (Session::get('mf_token') == $token)?1:0;
    }

    /*
     * HELPER
     */
    public static function notification($note, $success = false) {
        if (isset($note) && !empty($note)) {
            if (is_array($note)) {
                ?>
                <div class="alert <?=$success?'alert-success':'alert-danger'?>">
                    <ol>
                        <?php
                        foreach ($note as $value) {
                            ?>
                            <li><?= $value ?></li>
                            <?php
                        }
                        ?>
                    </ol>
                </div>
                <?php
            } else {
                ?>
                <div class="alert <?=$success?'alert-success':'alert-danger'?>"><?= $note ?></div>
                <?php
            }
        }
    }
}