<?php

class Sports_Model extends Model {

    public function __construct() {
        parent::__construct();
    }


    /**
     * listPost
     */
    public function listItems($slug) {
        $selectData = [
                'slug'=>$slug,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`text` 
                    FROM `cms_pages` P 
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`status`=:status and P.parent = (
                      SELECT `p_id`
                        FROM `cms_pages_text`
                        WHERE `slug` =:slug and `lang` =:lang
                        LIMIT 1
                    )
                    ORDER BY P.`ordering` ASC";
        $resultText = $this->db->select($query, $selectData);

        /*
         * SELECT photo
         */
        $mas = [];
        foreach ($resultText as $v){
            $selectData = [
                    'id' => $v['id']
            ];
            $query = "SELECT `id`, `thumb`, `photo`
                        FROM `cms_pages_photo` 
                        WHERE `p_id` =:id 
                        ORDER BY `ordering`";
            $resultPhoto = $this->db->select($query, $selectData);
            $mas[$v['id']]['text'] = $v;
            $mas[$v['id']]['photo'] = $resultPhoto;
        }
        return $mas;
    }
}