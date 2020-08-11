
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
                    <a href="<?=URL.MF::$_lang?>/expeditions/view/<?=$value['id']?>">
                        <div class="photo">
                            <img src="<?=UPLOAD_DIR_LINK?>Image/<?=$this->menu?>/<?=$value['thumb']?>" alt="<?=$value['title']?>"/>
                        </div>
                        <p class="expedition-date"><?=$value['exp_date']?></p>
                        <p><?=$value['title']?></p>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>