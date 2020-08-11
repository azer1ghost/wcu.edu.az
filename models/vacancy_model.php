<?php

class Vacancy_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * LIST items
     */
    public function listItems() {
        $selectData = [
                'status'=>2,
                'deleted'=>0,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`text` 
                    FROM `cms_vacancy` P
                    LEFT JOIN `cms_vacancy_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`status`=:status
                    ORDER BY P.`ordering`";
        $result = $this->db->select($query, $selectData);

        return $result;
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
            $query = "SELECT P.`id`, P.`parent`, T.`title`, T.`text` 
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
}