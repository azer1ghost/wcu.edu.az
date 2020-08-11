<?php

class News_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * LIST items
     */
    public function listItems($category='', $page_position='', $item_per_page='') {
        $selectData = [
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];

        $where = "";
        if($category != 'all'){
            $selectData1 = [
                    'slug'=>$category,
                    'deleted'=>0,
                    'status'=>2,
                    'lang'=>MF::$_lang
            ];

            $query = "SELECT P.`id` 
                            FROM `cms_pages` P
                            LEFT JOIN `cms_pages_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang 
                            WHERE P.`deleted` =:deleted and P.`status` =:status and T.`slug`=:slug
                            LIMIT 1";
            $result = $this->db->select($query, $selectData1);

            $selectData['category'] = $result[0]['id'];
            $where .= " and P.category=:category ";
        }

        $query = "SELECT P.`id`, T.`title`, T.`slug`,
                                  DAY(P.`post_time`) as `day`,
                                  MONTH(P.`post_time`) as `month`,
                                  YEAR(P.`post_time`) as `year`, 
                    (SELECT `thumb` FROM `cms_post_photo` WHERE p_id = P.id ORDER BY `ordering` LIMIT 1) as  `thumb`
                    FROM `cms_post` P
                    LEFT JOIN `cms_post_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang 
                    WHERE P.`deleted` =:deleted and P.`status` =:status $where
                    ORDER BY P.`post_time` desc 
                    LIMIT $page_position, $item_per_page";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
    /**
     * LIST items count
     */
    public function listCount($category='') {
        $category = isset($category) ? Func::check($category) : '';
        $selectData = [
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];

        $where = "";
        if($category != 'all'){
            $selectData1 = [
                    'slug'=>$category,
                    'deleted'=>0,
                    'status'=>2,
                    'lang'=>MF::$_lang
            ];

            $query = "SELECT P.`id` 
                            FROM `cms_pages` P
                            LEFT JOIN `cms_pages_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang 
                            WHERE P.`deleted` =:deleted and P.`status` =:status and T.`slug`=:slug
                            LIMIT 1";
            $result = $this->db->select($query, $selectData1);

            $selectData['category'] = $result[0]['id'];
            $where .= " and P.category=:category ";
        }

        $query = "SELECT count(P.`id`) as `count`
                    FROM `cms_post` P
                    LEFT JOIN `cms_post_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang 
                    WHERE P.`deleted` =:deleted and P.`status` =:status $where
                    ORDER BY P.`post_time` desc";
        $result = $this->db->select($query, $selectData);

        return $result[0]['count'];
    }

    /**
     *  View Page
     */
    public function viewPost($id) {
        if ($id) {
            $selectData = [
                    'id' => $id,
                    'deleted' => 0,
                    'status' => 2,
                    'lang' => MF::$_lang
            ];
            $query = "SELECT P.`id`, T.`title`, T.`text`,
                                  DAY(P.`post_time`) as `day`,
                                  MONTH(P.`post_time`) as `month`,
                                  YEAR(P.`post_time`) as `year`
                        FROM `cms_post` P
                        LEFT JOIN `cms_post_text` T ON T.p_id = P.id and T.`lang`=:lang 
                        WHERE P.`id`=:id and P.`deleted`=:deleted and P.`status`=:status 
                        LIMIT 1";
            $result = $this->db->select($query, $selectData);
            if (empty($result[0])) {
                header('Location: ' . URL . MF::$_lang.'/news');
                exit;
            } else {

                /*
                 * SELECT photo
                 */
                $mas = $result[0];
                $selectData = [
                        'id' => $mas['id']
                ];
                $query = "SELECT `id`, `thumb`, `photo`
                            FROM `cms_post_photo` 
                            WHERE `p_id` =:id 
                            ORDER BY `ordering`";
                $mas['photo'] = $this->db->select($query, $selectData);
                return $mas;
            }
        } else {
            $result = ['title' => Lang::get('Title not found'), 'text' => Lang::get('Content not found')];
        }
    }

    /**
     * LIST category
     */
    public function listCategory($slug='news') {
        $selectData = [
                'slug'=>$slug,
                'deleted'=>0,
                'status'=>2,
                'lang'=>MF::$_lang
        ];
        $query = "SELECT P.`id`, T.`title`, T.`slug` 
                    FROM `cms_pages` P
                    LEFT JOIN `cms_pages_text` T ON T.`p_id` = P.`id` AND T.`lang` =:lang
                    
                    WHERE P.`parent` = (
                        SELECT `p_id`
                        FROM `cms_pages_text`
                        WHERE `slug` =:slug and `lang` =:lang
                        LIMIT 1
                    ) and P.`deleted` =:deleted and P.`status` =:status
                    
                    ORDER BY `ordering`";
        $result = $this->db->select($query, $selectData);
        return $result;
    }
}