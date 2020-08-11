<?php

class Header_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     *  Top menu First
     */
    public function topMenuFirst() {
        $selectData = [
            'deleted'=>0,
            'status'=>'2',
            'parent'=>'99',
            'post_in_page'=>'0',
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`parent`, P.`static_page`, P.`target`, T.`title`, T.`subtitle`, T.`slug`, T.`link`, T.`lang`,
                      (SELECT `thumb` FROM `cms_pages_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb`
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`post_in_page`=:post_in_page and P.`parent`=:parent and P.`status`=:status
                    ORDER BY P.`ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }

    /**
     *  Top menu First
     */
    public function topMenuThird() {
        $selectData = [
            'deleted'=>0,
            'status'=>'2',
            'parent'=>'104',
            'post_in_page'=>'0',
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`parent`, P.`static_page`, P.`target`, T.`title`, T.`subtitle`, T.`slug`, T.`link`, T.`lang`,
                      (SELECT `thumb` FROM `cms_pages_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb`
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`post_in_page`=:post_in_page and P.`parent`=:parent and P.`status`=:status
                    ORDER BY P.`ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }

    /**
     *  Top menu Second
     */
    public function topMenuSecond() {
        $selectData = [
            'deleted'=>0,
            'status'=>'2',
            'parent'=>'1',
            'post_in_page'=>'0',
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`parent`, P.`static_page`, P.`target`, T.`title`, T.`subtitle`, T.`slug`, T.`link`, T.`lang`,
                      (SELECT `thumb` FROM `cms_pages_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb`
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`post_in_page`=:post_in_page and P.`parent`=:parent and P.`status`=:status
                    ORDER BY P.`ordering`";
        $result = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($result as $k=>$v){
            $selectData1 = [
                'parent'=>$v['id'],
                'deleted'=>0,
                'status'=>2,
                'post_in_page'=>'0',
                'lang'=>MF::$_lang
            ];
            $query1 = "SELECT P.`id`, T.`title`, P.`static_page`, P.`target`, T.`slug`, T.`link`, T.`lang`
                            FROM `cms_pages` P
                            LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                            WHERE `deleted`=:deleted and P.`post_in_page`=:post_in_page and `status`=:status and `parent`=:parent
                            ORDER BY `ordering`";
            $result1 = $this->db->select($query1, $selectData1);
            $mas[$v['id']]['text'] = $v;
            $mas[$v['id']]['sub'] = $result1;
        }
//        print "<pre>";
//        print_r($mas);
//        print "</pre>";

        return $mas;
    }

    /**
     *  Menu
     */
    public function menuList() {
        $selectData = [
            'deleted'=>0,
            'status'=>'2',
            'post_in_page'=>'0',
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`parent`, P.`static_page`, P.`target`, T.`title`, T.`slug`, T.`link`, T.`lang`  
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`post_in_page`=:post_in_page and P.`status`=:status
                    ORDER BY P.`ordering`";
        $result = $this->db->select($query, $selectData);

        $refs = array();
        $list = array();
        foreach ($result as $value) {
            $thisref = &$refs[$value['id']];
            $thisref['id'] = $value['id'];
            $thisref['parent'] = $value['parent'];
            $thisref['title'] = $value['title'];
            $thisref['slug'] = $value['slug'];
            $thisref['link'] = $value['link'];
            $thisref['static_page'] = $value['static_page'];
            $thisref['target'] = $value['target'];
            $thisref['lang'] = $value['lang'];

            if ($value['parent'] == 1) {
                $list[$value['id']] = &$thisref;
            } else {
                $refs[$value['parent']]['children'][$value['id']] = &$thisref;
            }
        }

        return $list;
    }

    /**
     *  Settings
     */
    public function settingsList() {
        $selectData = [
                'deleted'=>0,
                'status'=>'2',
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`text`  
                    FROM `cms_settings` P
                    INNER JOIN `cms_settings_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`status`=:status
                    ORDER BY `id`";
        $result = $this->db->select($query, $selectData);
        $mas = [];
        foreach ($result as $value){
            $mas[$value['id']] = $value['text'];
        }
        return $mas;

    }
}