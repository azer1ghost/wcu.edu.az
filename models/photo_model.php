<?php

class Photo_Model extends Model {

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
        $query = "SELECT P.`id`, T.`title`,  
                        (SELECT `thumb` FROM `cms_gallery_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb` 
                    FROM `cms_gallery` P 
                    LEFT JOIN `cms_gallery_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`status`=:status 
                    ORDER BY P.`id` DESC";
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
                        FROM `cms_gallery_photo` 
                        WHERE `p_id` =:id 
                        ORDER BY `ordering`";
            $resultPhoto = $this->db->select($query, $selectData);
            $mas[$v['id']]['text'] = $v;
            $mas[$v['id']]['photo'] = $resultPhoto;
        }
        return $mas;
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