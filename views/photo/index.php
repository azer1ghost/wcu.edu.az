
<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">

        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div class="gallery-list">
            <?php
            foreach ($this->listPost as $key=>$value){
                ?>

                <div class="gallery-item">
                    <a href="javascript:void(0)">
                        <div><img src="<?=UPLOAD_DIR_LINK?>Image/gallery/<?=$value['text']['thumb']?>" alt="<?=$value['text']['title']?>"></div>
                        <p><?=$value['text']['title']?></p>
                    </a>
                    <div>
                        <?php
                        if(!empty($value['photo'])) {
                            foreach ($value['photo'] as $photo) {
                                ?>
                                <a id="single_image" href="<?=UPLOAD_DIR_LINK?>Image/gallery/<?=$photo['photo']?>" data-fancybox="<?=$value['text']['id']?>">
                                    <img src="<?=UPLOAD_DIR_LINK?>Image/gallery/<?=$photo['thumb']?>" alt="">
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>

        </div>

    </div>
</div>