<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <?php
        foreach ($this->listItems as $key=>$value){
            ?>
            <div class="faculty-item">
                <a href="<?=URL.MF::$_lang?>/faculties/view/<?= $value['slug'] ?>">
                    <div class="photo">
                        <img src="<?= UPLOAD_DIR_LINK ?>Image/pages/<?= $value['thumb'] ?>" alt="<?= $value['title'] ?>">
                    </div>
                    <div class="name">
                        <span><?= $value['title'] ?></span>
                    </div>
                </a>
            </div>
        <?php
        }
        ?>
    </div>
</div>