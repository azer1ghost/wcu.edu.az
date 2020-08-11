<?php

class Awards_Model extends Model {

    public function __construct() {
        parent::__construct();
    }


    /**
     * listPost
     */
    public function listItems() {
        $selectData = [
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`year`, T.`title`, T.`text`,  
                        (SELECT `thumb` FROM `cms_certificate_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb` 
                    FROM `cms_certificate` P 
                    LEFT JOIN `cms_certificate_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`status`=:status 
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
                        FROM `cms_certificate_photo` 
                        WHERE `p_id` =:id 
                        ORDER BY `ordering`";
            $resultPhoto = $this->db->select($query, $selectData);
            $mas[$v['id']]['text'] = $v;
            $mas[$v['id']]['photo'] = $resultPhoto;
        }
        return $mas;

    }
}