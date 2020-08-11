<?php
/**
 * Created by MF.
 * User: MF
 * Date: 6/12/2017
 * Time: 10:17 AM
 */

class Func extends Database {

    /**
     * Check
     */
    public static function check($str) {
        $str = trim($str);
        $str = htmlspecialchars(strip_tags($str), ENT_QUOTES);
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


    /**
     * Token
     */
    public static function rund_number(){
        return rand(1000000, 9999999);
    }
    public static function token(){
        Session::init();
        Session::set('mf_token', md5(uniqid(mt_rand(), true)));
        return Session::get('mf_token');
    }
    public static function token_check($token){
        return (Session::get('mf_token') == $token)?1:0;
    }

    /**
     * HELPER
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

    public static function find_word($str, $text){
        return str_ireplace($str, '<span class="find">'.$str.'</span>', $text);
    }

    public static function lang_uni($lang, $title){
        $mas = ['i', 'I'];
        return ($lang=='az')?str_replace($mas,'İ', $title):$title;
    }

    public static function lang_uni_upperI($lang, $title){
       // $title = ucfirst(mb_strtolower($title, 'UTF-8'));
        $mas = ['I'];
        return ($lang=='az')?str_replace($mas,'İ', $title):$title;
    }

    public static function create_link($value){
        if($value['link']){
            $link = $value['link'];
        } else {
            if ($value['slug'] == '#') {
                $link = 'javascript:';
            } elseif($value['slug']==''){
                $link = URL.MF::$_lang;
            } else{
                $link = ($value['static_page'] == 0) ?
                    URL . MF::$_lang . '/pages/view/' . $value['slug'] :
                    URL . MF::$_lang . '/' . $value['slug'];
            }
        }
        return $link;
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

    public static function replace_custom_to_html($html){
        $html = str_ireplace('[[video', '<div class="main-content-video"><div class="video-block"', $html);
        $html = str_ireplace(']]', '><a href="javascript:void(0)" class="play-video-link"></a></div></div>' , $html);
        return $html;

    }

    public static function paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url, $page_var = false){
        $page_var = isset($page_var)?$page_var:NULL;
        $pagination = '';
        if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
            $pagination .= '<div class="pg-container"><ul class="pagination">';

            $right_links    = $current_page + 3;
            $previous       = $current_page - 3; //previous link
            $next           = $current_page + 1; //next link
            $first_link     = true; //boolean var to decide our first link

            if($current_page > 1){
                $previous_link = $current_page-1;
                //$pagination .= '<li class="first"><a class="prev" href="'.$page_url.'/1'.$page_var.'"><span aria-hidden="true">&laquo;</span></a></li>'; //first link
                $pagination .= '<li><a class="prev" href="'.$page_url.'/'.$previous_link.$page_var.'"> </a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li><a href="'.$page_url.'/'.$i.$page_var.'">'.$i.'</a></li>';
                    }
                }
                $first_link = false; //set first link to false
            }

            if($first_link){ //if current active page is first link
                $pagination .= '<li class="first active"><a>'.$current_page.'</a></li>';
            }elseif($current_page == $total_pages){ //if it's the last active link
                $pagination .= '<li class="last active"><a>'.$current_page.'</a></li>';
            }else{ //regular current link
                $pagination .= '<li><a class="active">'.$current_page.'</a></li>';
            }

            for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
                if($i<=$total_pages){
                    $pagination .= '<li><a href="'.$page_url.'/'.$i.$page_var.'">'.$i.'</a></li>';
                }
            }
            if($current_page < $total_pages){
                $next_link = $current_page + 1;
                $pagination .= '<li><a class="next" href="'.$page_url.'/'.$next_link.$page_var.'"></a></li>'; //next link
                //$pagination .= '<li class="last"><a class="next" href="'.$page_url.'/'.$total_pages.$page_var.'"><span aria-hidden="true">&raquo;</span></a></li>'; //last link
            }

            $pagination .= '</ul></div>';
        }
        return $pagination; //return pagination links
    }
}