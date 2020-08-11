<?php

class Pages_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *  View Page
     */
    public function viewPage($slug) {
        if ($slug) {
            $selectData = [
                'slug' => $slug,
                'deleted' => 0,
                'status' => 2,
                'lang' => MF::$_lang
            ];
            $query = "SELECT P.`id`, P.`parent`, T.`title`, T.`text`,
                          (SELECT `photo` FROM `cms_pages_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `photo`
                        FROM `cms_pages` P
                        LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.`lang`=:lang 
                        WHERE T.`slug`=:slug and P.`deleted`=:deleted and P.`status`=:status 
                        LIMIT 1";
            $result = $this->db->select($query, $selectData);
            if (empty($result)) {
                header('Location: ' . URL . MF::$_lang);
                exit;
            } else {
                $result = $result[0];
            }
        } else {
            $result = ['title' => Lang::get('Title not found'), 'text' => Lang::get('Content not found')];
        }

        return $result;
    }

    /**
     * innerPost
     */
    public function innerPost($parentId='') {
        $selectData = [
                'parent'=>$parentId,
                'post_in_page'=>'1',
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`text`, T.`slug` 
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang
                    WHERE P.`parent`=:parent and P.`post_in_page` =:post_in_page and P.`deleted` =:deleted and P.`status` =:status
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
}