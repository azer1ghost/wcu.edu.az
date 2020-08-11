<div class="page">
    <div class="page-container">
        <div class="breadcrumb">
            <a href="<?=URL.MF::$_lang?>"><?=Lang::get('Main')?></a>
            <a href="<?=URL.MF::$_lang?>/news"><?=Lang::get('News')?></a>
            <span><?=$this->viewPost['title']?></span>
        </div>

        <?php
        if(!empty($this->viewPost['photo'][0])){
            ?><img class="page-img third-fixed" src="<?=UPLOAD_DIR_LINK?>Image/graduate/<?=$this->viewPost['photo'][0]['photo']?>" alt=""><?php
        }
        ?>

        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->viewPost['title'])?></h1>

        <div class="content">
            <div class="content__left">
                <div>

                    <?=$this->viewPost['text']?>

                    <?php
                    if(!empty($this->viewPost['photo'])){
                        $x=1;
                        foreach ($this->viewPost['photo'] as $value){
                            if($x>1){
                                ?><a href="<?=UPLOAD_DIR_LINK?>Image/graduate/<?=$value['photo']?>" data-fancybox="gallery"><img src="<?=UPLOAD_DIR_LINK?>Image/graduate/<?=$value['thumb']?>" width="164" alt="gallery"></a> <?php
                            }
                            $x++;
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="content__right">
                <div></div>
            </div>
        </div>
    </div>
</div>