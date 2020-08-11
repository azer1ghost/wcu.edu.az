<?php

class Search_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * LIST items
     */
    public function listItems($q='', $page_position='', $item_per_page='') {
        $selectData = [
                'q'=>"%{$q}%",
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];

        $query = "SELECT *
                    FROM (
                          
                      SELECT P.`id`, T.`title`, T.`text`, 'pages' as `types`, P.`static_page`, T.`slug`, T.`link` 
                          FROM `cms_pages` P
                          INNER JOIN `cms_pages_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q) 
                          
                          UNION 
                          
                      SELECT P.`id`, T.`title`, T.`text`, 'post' as `types`, P.`type`, T.`slug`, 'empty' 
                          FROM `cms_post` P
                          INNER JOIN `cms_post_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q) 
                          
                          UNION 
                          
                      SELECT P.`id`, T.`title`, T.`text`, 'expeditions' as `types`, 'empty', 'empty', 'empty'  
                          FROM `cms_expeditions` P
                          INNER JOIN `cms_expeditions_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q)
                          
                          UNION 
                          
                      SELECT P.`id`, T.`title`, T.`text`, 'undergraduate' as `types`, P.`category`, T.`slug`, 'empty' 
                          FROM `cms_undergraduate` P
                          INNER JOIN `cms_undergraduate_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q) 
                         
                         
                    ) as T 
                    LIMIT $page_position, $item_per_page";
        $result = $this->db->select($query, $selectData);
        //print "<pre>";
        //print_r($result);
        //print "</pre>";

        return $result;
    }

    /**
     * LIST items count
     */
    public function listCount($q='') {
        $selectData = [
                'q'=>"%{$q}%",
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT count(*) as `count`
                    FROM (
                      SELECT P.`id` 
                          FROM `cms_post` P
                          INNER JOIN `cms_post_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q) 
                          
                          UNION 
                          
                      SELECT P.`id` 
                          FROM `cms_pages` P
                          INNER JOIN `cms_pages_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q) 
                          
                          UNION 
                          
                      SELECT P.`id` 
                          FROM `cms_chair` P
                          INNER JOIN `cms_chair_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang  
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q) 
                          
                          UNION 
                          
                      SELECT P.`id` 
                          FROM `cms_faq` P
                          INNER JOIN `cms_faq_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang  
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q) 
                          
                          UNION 
                          
                      SELECT P.`id` 
                          FROM `cms_expeditions` P
                          INNER JOIN `cms_expeditions_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q)
                          
                          UNION 
                          
                      SELECT P.`id` 
                          FROM `cms_undergraduate` P
                          INNER JOIN `cms_undergraduate_text` T ON T.`p_id` = P.`id` and T.`lang` =:lang 
                          WHERE P.`deleted`=:deleted and P.`status`=:status and (T.`title` like :q or T.`text` like :q) 
                         
                         
                    ) as T";
        $result = $this->db->select($query, $selectData);

        return $result[0]['count'];
    }

}