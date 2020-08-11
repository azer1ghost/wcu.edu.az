
<div class="footer">
    <div class="footer-copyrights">
        <img src="<?=URL?>public/images/wu-transp-logo.png"/>
        <p><?=Lang::get('All rights reserved. Western University Azerbaijan')?></p>
        
    </div>

    <?php
    $x=1;
    foreach($this->topMenuSecond as $key=>$value){
        if($value['text']['slug']=='#'){
            $link = 'javascript:';
        } else {
            $link = ($value['text']['static_page'] == 0) ?
                URL . $value['text']['lang'] . '/pages/view/' . $value['text']['slug'] :
                URL . $value['text']['lang'] . '/' . $value['text']['slug'];
        }
        if($x == 5){
            break;
        }
        ?>

        <div class="footer-list">
            <h3><?=Func::lang_uni(MF::$_lang, $value['text']['title'])?></h3>
            <?php
            if(!empty($value['sub'])){
                ?>

                <ul>
                    <?php
                    foreach ($value['sub'] as $v) {
                        if($v['slug']=='#'){
                            $link = 'javascript:';
                        } else {
                            $link = ($v['static_page'] == 0) ?
                                URL . $v['lang'] . '/pages/view/' . $v['slug'] :
                                URL . $v['lang'] . '/' . $v['slug'];
                        }
                        ?>

                        <li><a href="<?=$link?>"><?=$v['title']?></a></li><?php
                    }
                    ?>

                </ul>
                <?php
            }
            ?>

        </div>
        <?php
        $x++;
    }
    ?>

    <div class="footer-socials">
        <?php
        if($this->def[4]){
            ?><a href="<?=$this->def[4]?>" class="facebook-link" target="_blank"></a><?php
        }
        if($this->def[5]){
            ?><a href="<?=$this->def[5]?>" class="twitter-link" target="_blank"></a><?php
        }
        if($this->def[6]){
            ?><a href="<?=$this->def[6]?>" class="youtube-link" target="_blank"></a><?php
        }
        if($this->def[8]){
            ?><a href="<?=$this->def[8]?>" class="instagram-link" target="_blank"></a><?php
        }
        ?>
        
        <div><a href="http://innovator.wcu.edu.az"><img src="<?= URL ?>public/images/inno.jpg"></a></div>
    </div>
    
    
    
    
</div>


<?php
if (isset($this->js)) {
    foreach ($this->js as $js) {
        ?>
        <script src="<?= URL ?>public/<?= $js ?>"></script><?php
    }
}
?>
<script type="text/javascript" src="<?= URL ?>public/js/lib.js"></script>
<script type="text/javascript" src="<?= URL ?>public/js/app.js"></script>

<script>
$(function(){
    $('.search-form').submit(function(){
        var q = $(this).find('input').val(),
            lang = $(this).attr('data-lang');


        location.replace('/' + lang + '/search/index/' + q);
        return false;
    });
})
    function backAway(){
    //if it was the first page
    if(history.length === 1){
        window.location = "http://www.mysite.com/"
    } else {
        history.back();
    }
}
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112469949-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-112469949-1');
</script>

</body>
</html>