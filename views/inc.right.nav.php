<div class="page-sidebar">
    <div class="sidebar">
        <h3><?=Func::lang_uni(MF::$_lang, $this->menuList[$this->parents[1]]['title'])?></h3>
        <?php
        function toUL($arr, $parents=array(), $id=NULL, $show=1) {
            //echo $id.'<br>';
            //if($id >= 107 && $id<=112){
            //    $id = 55;
            //}
            $show = ($show)?' style="display:block"':' style="display:none"';
            $html = '<ul'.$show.'>';
            foreach ($arr as $v) {
                $link = Func::create_link($v);
                $active = ($v['id'] == $id)?' class="active"':'';
                $target = (isset($v['target']) && ($v['target'] == 1))?' target="_blank"':'';

                $html .= '<li><a href="' . $link . '" '.$target.$active.'>' . $v['title'] . '</a>';
                if (array_key_exists('children', $v)) {
                    $show = (isset($parents[2]) && $v['id'] == $parents[2])?1:0;
                    $html .= toUL($v['children'], $parents, $id, $show);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        }

        // build the list and output it
        echo toUL($this->menuList[$this->parents[1]]['children'], $this->parents, $this->page['id'], 1);
        // print "<pre>";
        // print_r($this->parents);
        // print "</pre>";
        ?>
    </div>
</div>