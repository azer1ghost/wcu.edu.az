<?php

class Index_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /* Banner */
    public function listMainBanner() {
        $selectData = [
            'parent'=>146,
            'deleted'=>0,
            'status'=>2,
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title` , T.`link`
                    FROM `cms_pages` P 
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`parent`=:parent and P.`deleted`=:deleted and P.`status`=:status 
                    ORDER BY P.`ordering` ASC";
        $resultText = $this->db->select($query, $selectData);

        /*
         * SELECT photo
         */
        $mas = [];
        foreach ($resultText as $v){
            $selectData1 = [
                'category' => $v['id'],
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
            ];
            $query = "SELECT P.`id`, P.`photo`, T.`title`, T.`subtitle`, T.`link` 
                        FROM `cms_mainbanner` P 
                        LEFT JOIN `cms_mainbanner_text` T ON T.p_id = P.id and T.lang =:lang 
                        WHERE P.`category`=:category and P.`deleted`=:deleted and P.`status`=:status 
                        ORDER BY P.`ordering` ASC
                        LIMIT 0,3";
            $result = $this->db->select($query, $selectData1);

            $mas[$v['id']]['category'] = $v;
            $mas[$v['id']]['text'] = $result;
        }
        //print "<pre>";
        //print_r($mas);
        //print "</pre>";

        return $mas;
    }


    public function listMainBannerleft() {
        $selectData = [
            'parent'=>489,  // id
            'deleted'=>0,
            'status'=>2,
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title` , T.`link` , T.`link` 
                    FROM `cms_pages` P 
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`parent`=:parent and P.`deleted`=:deleted and P.`status`=:status 
                    ORDER BY P.`ordering` ASC";
        $resultText = $this->db->select($query, $selectData);

        /*
         * SELECT photo
         */
        $mas = [];
        foreach ($resultText as $v){
            $selectData1 = [
                'category' => $v['id'],
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
            ];
            $query = "SELECT P.`id`, P.`photo`, T.`title`, T.`subtitle`, T.`link` 
                        FROM `cms_mainbanner` P 
                        LEFT JOIN `cms_mainbanner_text` T ON T.p_id = P.id and T.lang =:lang 
                        WHERE P.`category`=:category and P.`deleted`=:deleted and P.`status`=:status 
                        ORDER BY P.`ordering` ASC
                        LIMIT 0,3";
            $result = $this->db->select($query, $selectData1);

            $mas[$v['id']]['category'] = $v;
            $mas[$v['id']]['text'] = $result;
        }
        //print "<pre>";
        //print_r($mas);
        //print "</pre>";

        return $mas;
    }


    /* News events */
    public function listPost() {
        for ($x=1;$x<3;$x++){
            $selectData = [
                'type'=>$x,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
            ];
            $query = "SELECT P.`id`, 
                          DAY(P.`post_time`) as `day`, 
                          MONTH(P.`post_time`) as `month`, 
                          DATE_FORMAT(P.`post_time`, '%H:%i %p') as `hour`, 
                          T.`title`, T.`slug`
                        FROM `cms_post` P 
                        LEFT JOIN `cms_post_text` T ON T.p_id = P.id and T.lang =:lang 
                        WHERE P.`type`=:type and P.`deleted`=:deleted and P.`status`=:status 
                        ORDER BY P.`post_time` DESC 
                        LIMIT 3";
            $result[$x] = $this->db->select($query, $selectData);
        }

        return $result;
    }

    /* FEATURES */
    public function listFeatures() {
        $selectData = [
            'deleted'=>0,
            'status'=>2,
            'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`  
                    FROM `cms_mainfeatures` P 
                    LEFT JOIN `cms_mainfeatures_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`status`=:status 
                    ORDER BY P.`ordering` ASC";
        $result = $this->db->select($query, $selectData);

        return $result;
    }

}