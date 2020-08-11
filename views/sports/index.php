<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">
        
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div class="content">
            <div class="content__left">
                <div>

                    <?php
                    foreach ($this->listItems as $value){
                        ?>
                        <h3><?=$value['text']['title']?></h3>
                        <?=$value['text']['text']?>

                        <?php
                        if(!empty($value['photo'])){
                            foreach ($value['photo'] as $v) {
                                ?>
                                <a href="<?= UPLOAD_DIR_LINK ?>Image/pages/<?= $v['photo'] ?>" data-fancybox="gallery-<?= $value['text']['id'] ?>">
                                    <img width="164" src="<?= UPLOAD_DIR_LINK ?>Image/pages/<?= $v['thumb'] ?>" alt="<?= $value['text']['title'] ?>">
                                </a>
                                <?php
                            }
                        }
                        ?>
                        <?php
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