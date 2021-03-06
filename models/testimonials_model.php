<?php

class Testimonials_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * LIST items
     */
    public function listItems() {
        $selectData = [
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`photo`, T.`title`, T.`position`, T.`text`
                    FROM `cms_feedback` P
                    LEFT JOIN `cms_feedback_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE `deleted`=:deleted and `status`=:status
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
}