<?php

class Phd_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * LIST items
     */
    public function listItems() {
        $selectData = [
                'parent'=>0,
                'category'=>4,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title` 
                    FROM `cms_undergraduate` P
                    LEFT JOIN `cms_undergraduate_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE `deleted`=:deleted and `category`=:category and `status`=:status and `parent`=:parent
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        $mas = [];
        foreach ($result as $k=>$v){
            $selectData1 = [
                    'parent'=>$v['id'],
                    'category'=>4,
                    'deleted'=>0,
                    'status'=>2,
                    'lang'=>MF::$_lang
            ];
            $query1 = "SELECT P.`id`, P.`price`, P.`price_foreign`, P.`code`, T.`title`, T.`type`, T.`slug`
                            FROM `cms_undergraduate` P
                            LEFT JOIN `cms_undergraduate_text` T ON T.p_id = P.id and T.lang =:lang 
                            WHERE `deleted`=:deleted and `category`=:category and `status`=:status and `parent`=:parent
                            ORDER BY `ordering`";
            $result1 = $this->db->select($query1, $selectData1);
            $mas[$v['id']]['text'] = $v;
            $mas[$v['id']]['sub'] = $result1;

        }

        return $mas;
    }

    /**
     * innerPost
     */
    public function viewPost($slug='') {
        $selectData = [
                'slug'=>$slug,
                'category'=>4,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`text`
                    FROM `cms_undergraduate` P
                    LEFT JOIN `cms_undergraduate_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang
                    
                    WHERE T.`slug`=:slug and P.`deleted` =:deleted and `category`=:category and P.`status` =:status
                    
                    LIMIT 1";
        $result = $this->db->select($query, $selectData);
        if(!empty($result)){

            /*
             * SELECT photo
             */
            $mas = $result[0];
            $selectData = [
                'id' => $mas['id']
            ];
            $query = "SELECT `id`, `thumb`, `photo`
                            FROM `cms_undergraduate_photo` 
                            WHERE `p_id` =:id 
                            ORDER BY `ordering`";
            $mas['photo'] = $this->db->select($query, $selectData);
            return $mas;
        } else {
            return 0;
        }

    }

    /**
     * innerPost
     */
    public function innerPost($parentId='') {
        $selectData = [
                'parent'=>$parentId,
                'category'=>4,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`text`, T.`slug` 
                        FROM `cms_undergraduate` P
                        LEFT JOIN `cms_undergraduate_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang
                        WHERE P.`parent`=:parent and P.`deleted` =:deleted and `category`=:category and P.`status` =:status
                        ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
}