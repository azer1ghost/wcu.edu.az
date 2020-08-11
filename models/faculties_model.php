<?php

class Faculties_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * LIST items
     */
    public function listItems($slug) {
        $selectData = [
                'slug'=>$slug,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`slug`, 
                    (SELECT `thumb` FROM `cms_pages_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb`
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang
                    
                    WHERE P.`parent` = (
                        SELECT `p_id`
                        FROM `cms_pages_text`
                        WHERE `slug` =:slug and `lang` =:lang
                        LIMIT 1
                    ) and P.`deleted` =:deleted and P.`status` =:status
                    
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

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

    /**
     * listChair
     */
    public function listChair($categoryId='') {
        $selectData = [
                'category'=>$categoryId,
                'parent'=>0,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`slug` 
                    FROM `cms_chair` P
                    LEFT JOIN `cms_chair_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang 
                    WHERE P.`category`=:category and P.`parent`=:parent and P.`deleted` =:deleted and P.`status` =:status
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($result as $k=>$v){
            $selectData1 = [
                    'parent'=>$v['id'],
                    'deleted'=>0,
                    'status'=>2,
                    'lang'=>MF::$_lang
            ];
            $query1 = "SELECT P.`id`, T.`title`, T.`slug`
                            FROM `cms_chair` P
                            LEFT JOIN `cms_chair_text` T ON T.p_id = P.id and T.lang =:lang 
                            WHERE `deleted`=:deleted and `status`=:status and `parent`=:parent
                            ORDER BY `ordering`";
            $result1 = $this->db->select($query1, $selectData1);
            $mas[$v['id']]['text'] = $v;
            $mas[$v['id']]['sub'] = $result1;
        }

        return $mas;
    }

    /**
     * innerChair
     */
    public function innerChair($slug='') {
        $selectData = [
                'slug'=>$slug,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`photo`, T.`title`, T.`text`
                    FROM `cms_chair` P
                    LEFT JOIN `cms_chair_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang
                    WHERE T.`slug`=:slug and P.`deleted` =:deleted and P.`status` =:status
                    LIMIT 1";
        $result = $this->db->select($query, $selectData);

        return $result[0];
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
                return $result[0];
            }
        } else {
            $result = ['title' => Lang::get('Title not found'), 'text' => Lang::get('Content not found')];
        }

        return $result;
    }
}