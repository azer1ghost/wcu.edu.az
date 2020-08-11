<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->pageInner['title'])?></h1>

        <div class="content">
            <div class="content__left">
                <div>
                    <?php
                    if($this->pageInner['photo']){
                        ?><img class="third-col third-fixed" src="<?=UPLOAD_DIR_LINK?>Image/chair/<?=$this->pageInner['photo']?>" alt="<?=$this->pageInner['title']?>" /><?php
                    }
                    ?>
                    <?=Func::replace_custom_to_html($this->pageInner['text'])?>
                </div>
            </div>
            <div class="content__right">
                <div>

                </div>
            </div>
        </div>
    </div>
</div>