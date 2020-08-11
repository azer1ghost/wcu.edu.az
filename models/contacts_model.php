<?php

class Contacts_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * LIST items
     */
    public function listItems() {
        $selectData = [
                'category'=>2,
                'status'=>2,
                'deleted'=>0,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`address`, P.`index`, P.`phone`, P.`fax`, P.`email`, P.`map`
                    FROM `cms_camp` P
                    LEFT JOIN `cms_camp_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`status`=:status and P.`category`=:category
                    ORDER BY P.`ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
}