<?php

class Expeditions_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * listPost
     */
    public function listPost() {
        $selectData = [
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`exp_date`, 
                        (SELECT `thumb` FROM `cms_expeditions_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb` 
                    FROM `cms_expeditions` P 
                    LEFT JOIN `cms_expeditions_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`status`=:status 
                    ORDER BY P.`ordering` ASC";
        $result = $this->db->select($query, $selectData);

        return $result;
    }

    /**
     * View One Post
     */
    public function viewPost($slug) {
        if ($slug) {
            $selectData = [
                    'id' => $slug,
                    'deleted' => 0,
                    'status' => 2,
                    'lang' => MF::$_lang
            ];
            $query = "SELECT P.`id`, T.`title`, T.`text`,
                          (SELECT `thumb` FROM `cms_expeditions_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb`
                        FROM `cms_expeditions` P 
                        LEFT JOIN `cms_expeditions_text` T ON T.p_id = P.id and T.`lang`=:lang 
                        WHERE P.`id`=:id and P.`deleted`=:deleted and P.`status`=:status 
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

    /**
     * List Photo
     */
    public function listPhoto($id) {
        if ($id>0) {
            $selectData = [
                    'p_id' => $id,
                    'lang' => MF::$_lang
            ];
            $query = "SELECT PO.`photo`, PO.`thumb`, T.`title`  
                        FROM `cms_expeditions_photo` PO 
                        LEFT JOIN `cms_expeditions_text` T ON T.`p_id` = PO.`p_id` and T.`lang`=:lang 
                        WHERE PO.`p_id`=:p_id 
                        ORDER by PO.`ordering`";
            $result = $this->db->select($query, $selectData);
            if (empty($result)) {
                header('Location: ' . URL . MF::$_lang);
                exit;
            } else {
                return $result;
            }
        } else {
            $result = ['data' => Lang::get('Content not found')];
        }

        return $result;
    }

}