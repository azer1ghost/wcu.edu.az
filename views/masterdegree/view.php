<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>

        <?php
        if(!empty($this->post['photo'][0])){
            ?><img class="page-img third-fixed" src="<?=UPLOAD_DIR_LINK?>Image/masterdegree/<?=$this->post['photo'][0]['photo']?>" alt=""><?php
        }
        ?>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->post['title'])?></h1>

        <div class="content">
            <div class="content__left">
                <div>
                    <?=Func::replace_custom_to_html($this->post['text'])?>

                    <?php
                    if(!empty($this->innerPost)){
                        foreach ($this->innerPost as $value){
                            ?>

                            <div class="wu-accordion">
                                <div class="wu-accordion__head" data-open="false"><?=$value['title']?></div>
                                <div class="wu-accordion__body"><?=$value['text']?></div>

                            </div>
                            <?php
                        }
                    }
                    ?>

                    <?php
                    if(!empty($this->post['photo'])){
                        $x=1;
                        ?><div class="post-inner-gallery"><?php
                        foreach ($this->post['photo'] as $value){
                            if($x>1){
                                ?>
                                <a href="<?=UPLOAD_DIR_LINK?>Image/masterdegree/<?=$value['photo']?>" data-fancybox="gallery">
                                    <img src="<?=UPLOAD_DIR_LINK?>Image/masterdegree/<?=$value['thumb']?>" width="164" alt="gallery">
                                </a>
                                <?php
                            }
                            $x++;
                        }
                        ?></div><?php
                    }
                    ?>
                </div>
            </div>
            <div class="content__right">
                <div>

                </div>
            </div>
        </div>
    </div>
</div>