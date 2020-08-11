<?php

class Model {
    function __construct() {
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }


    /**
     *  View Page
     */
    public function getPage($slug) {
        $slug = (isset($slug) && !empty($slug)) ? Func::check($slug) : '';

        if(!$slug){
            header('Location: ' . URL . MF::$_lang . '/error');
            exit;
        }

        $selectData = [
            'slug' => $slug,
            'deleted' => 0,
            'status' => 2,
            'lang' => MF::$_lang
        ];
        $query = "SELECT P.`id`, P.`parent`, T.`title` 
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.`lang`=:lang 
                    WHERE T.`slug`=:slug and P.`deleted`=:deleted and P.`status`=:status 
                    LIMIT 1";
        $result = $this->db->select($query, $selectData);
        if (empty($result)) {
            return 0;
        } else {
            return $result[0];
        }
    }

    /**
     * Get Parent
     */
    public function getParent($childId){
        $ids = array();
        while ($childId > 0) {
            $ids[] = $childId;

            $selectData = [
                'id'=>$childId,
                'deleted'=>0,
                'status'=>'2'
            ];
            $query = "SELECT P.`parent` 
                        FROM `cms_pages` P
                        WHERE P.`id`=:id and P.`deleted`=:deleted and P.`status`=:status";
            $result = $this->db->select($query, $selectData);
            $childId = $result[0]['parent'];
        }
        $result = array_reverse($ids);

        return $result;
    }

    /**
     * Create breadcrumb
     */
    public function create_breadcrumb($massiv=''){
        $html = "";
        if(!empty($massiv)){
            $end = end($massiv);

            foreach ($massiv as $value){
                $selectData = [
                    'id'=>$value,
                    'deleted'=>0,
                    'status'=>'2',
                    'post_in_page'=>'0',
                    'lang'=>MF::$_lang
                ];
                $query = "SELECT P.`id`, P.`static_page`, T.`title`, T.`slug`, T.`link`, T.`lang`,
                      (SELECT `thumb` FROM `cms_pages_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb`
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.p_id = P.id and T.lang =:lang 
                    WHERE P.`deleted`=:deleted and P.`post_in_page`=:post_in_page and P.`id`=:id and P.`status`=:status
                    ORDER BY P.`ordering`";
                $result = $this->db->select($query, $selectData);

                if(!empty($result[0])){
                    $value = $result[0];

                    $link = Func::create_link($value);
                    $html .= ($value['id'] == $end)?'<span>'.Func::lang_uni_upperI(MF::$_lang, $value['title']).'</span>':'<a href="'.$link.'">'.Func::lang_uni_upperI(MF::$_lang, $value['title']).'</a>';
                }
            }

        }
        return $html;
    }

}