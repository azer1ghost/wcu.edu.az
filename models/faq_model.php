<?php

class Faq_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * LIST items
     */
    public function listItems($category = '') {

        $selectData = [
            'parent'=>101,
            'status'=>2,
            'deleted'=>0,
            'lang'=>MF::$_lang
        ];
        $query = "SELECT T.`slug` 
                    FROM `cms_pages` P 
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang  
                    WHERE P.`deleted`=:deleted and P.`status`=:status and P.`parent`=:parent 
                    ORDER BY P.`ordering` 
                    LIMIT 1";
        $result = $this->db->select($query, $selectData);

        /**
         * Set default category
         */
        $category = ($category)?$category:$result[0]['slug'];


        /**
         * Select list
         */
        $selectData = [
            'category'=>$category,
            'status'=>2,
            'deleted'=>0,
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`text` 
                    FROM `cms_faq` P
                    LEFT JOIN `cms_faq_text` T ON T.p_id = P.id and T.lang =:lang 
                    LEFT JOIN `cms_pages_text` PA ON PA.p_id = P.`category` and PA.lang =:lang 
                    
                    WHERE P.`deleted`=:deleted and P.`status`=:status and PA.`slug`=:category 
                    ORDER BY P.`ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }

    /**
     * LIST items
     */
    public function listCategory() {
        $selectData = [
            'parent'=>101,
            'status'=>2,
            'deleted'=>0,
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`static_page`, T.`title`, T.`slug`, T.`link`, T.`lang` 
                    FROM `cms_pages` P 
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang  
                    WHERE P.`deleted`=:deleted and P.`status`=:status and P.`parent`=:parent 
                    ORDER BY P.`ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
}