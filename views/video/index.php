<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">

        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div class="video-list">
            <?php
            foreach ($this->listItems as $key=>$value){
                ?>

                <div class="video-gallery-item">
                    <a href="javascript:void(0)" data-videolink="<?=$value['link']?>">
                        <div>
                            <img src="<?=UPLOAD_DIR_LINK?>Image/video/<?=$value['photo']?>" alt="<?=$value['title']?>">
                        </div>
                        <p><?=$value['title']?></p>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>