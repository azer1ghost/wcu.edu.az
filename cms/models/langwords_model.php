<?php

class Langwords_Model extends Model {
    public $_menu;

    public function __construct() {
        parent::__construct();

        $this->_menu = 'langwords';
    }

    /* LIST */
    public function listItems() {
        $selectData = [
                'deleted'=>0
        ];
        $query = "SELECT *
                    FROM `cms_langwords` P
                    WHERE P.`deleted`=:deleted 
                    ORDER BY `id` desc";
        $result = $this->db->select($query, $selectData);

        return $result;
    }
    public function updateStatus($items, $status) {
        $updateData = array(
                'status' => $status,
                'update_date' => date('Y-m-d H:i:s')
        );
        $ids = implode(',', $items);
        $result = $this->db->update('`cms_langwords`', $updateData, '`id` IN (' . $ids . ')');
        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {
            return ['model_success' => Lang::get('Selected posts changed status')];
        }
    }
    public function saveItem($items) {
        /**
         * Update db
         */
        foreach ($items as $key => $id) {
            $updateData = array(
                    'id' => $id,
                    'update_date' => date('Y-m-d H:i:s')
            );

            foreach(MFAdmin::$_langs as $key=>$value){
                $updateData[$key] = htmlspecialchars($_POST[$key][$id]);
            }
            $result = $this->db->update('`cms_langwords`', $updateData,"`id`={$id}");
        }

        if (isset($result['mysql_error'])) {
            return ['model_error' => $result['mysql_error']];
        } else {

            /**
             * Update file
             */
            foreach(MFAdmin::$_langs as $key=>$value){
                // insert db to lang file
                $text = '<?php $keys = [';

                $selectData = [
                    'status'=>2,
                    'deleted'=>'0'
                ];
                $query = "SELECT `id`,  `key`,  `".$key."`
                                FROM `cms_langwords` P
                                WHERE P.`deleted`=:deleted and P.`status`=:status 
                                ORDER BY `id` desc";
                $result = $this->db->select($query, $selectData);
                foreach ($result as $m){
                    if($m[$key]!=''){
                        $text .= "'".$m["key"]."'=>'".$m[$key]."', \n";
                    }
                }

                $text .= ']; ?>';

                // file location
                $filename = BASE_DIR.'/langs/'.$key.".php";
                $file = fopen($filename, "w");
                if (!file_exists($filename)) {
                    @mkdir($filename, 0777);
                }


                if (file_exists($filename)) {
                    fwrite($file, $text);
                    fclose($file);
                }
            }
            return ['model_success' => Lang::get('Changed data')];
        }
    }

    /* ADD */
    public function addWords(){
        $folders = ['views', 'controllers', 'models'];
        foreach ($folders as $value){
            $path = realpath(BASE_DIR.$value.'/');
            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);

            foreach($objects as $name => $object){
                if(!is_dir($name)){
                    $content = file($name);

                    foreach($content as $key=>$value){

                        $say = substr_count($value, 'Lang::get');
//                        echo $value.'/'.$say.'<hr>';
//                        echo "say = $say <br>";

                        for($z=1; $z<=$say; $z++){
                            if($z==1){
                                $first  = strpos($value, "Lang::get('");
                                $second = strpos($value, "')", $first );
                            } else {
                                $first  = strpos($value, "Lang::get('", ($first + strlen("Lang::get('")) );
                                $second = strpos($value, "')", $first );
                            }

//                            echo "first = $first";
//                            echo '<br>';
//                            echo "second = $second";
//                            echo '<br />';

                            if ($first !== false) {
                                $first1 = $first+11;
                                $second1 = $second - $first1;

                                $word = trim(substr($value, $first1, $second1));

                                if($word!=''){

                                    $selectData = [
                                        'deleted'=>0,
                                        'key'=>$word
                                    ];
                                    $query = "SELECT count(`id`) as `count`
                                                FROM `cms_langwords` P
                                                WHERE P.`deleted`=:deleted and `key`=:key";
                                    $result = $this->db->select($query, $selectData);
                                    $count = $result[0]['count'];
                                    if($count == 0){
                                        // insert

                                        $name = str_ireplace('\\', '/', $name);
                                        $insertData = [
                                            'key'=>$word,
                                            'az'=>$word,
                                            'status'=>'2',
                                            'file'=>str_ireplace(BASE_DIR, '/', $name),

                                            'creator_id'=>Session::get('userid'),
                                            'create_date' => date('Y-m-d H:i:s'),
                                            'update_date' => date('Y-m-d H:i:s')
                                        ];
                                        $this->db->insert('cms_langwords', $insertData);
                                        //echo '<hr>';
                                    }


                                }

                            }
                        }

                    }
                }
            }

        }
    }
}