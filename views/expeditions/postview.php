
<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">

        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>

        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->post['title'])?></h1>

        <div class="content">
            <div class="content__left">
                <div>
                    <p class="third-col">
                        <?=$this->post['title']?>
                    </p>
                    <?php
                    if(!empty($this->post['thumb'])){
                        ?><img src="<?=UPLOAD_DIR_LINK?>Image/<?=$this->menu?>/<?=$this->post['thumb']?>" alt="<?=$this->post['title']?>" class="third-col"><?php
                    }
                    ?>

                    <?=$this->post['text']?>

                    <?php
                    if(!empty($this->listPhoto)){
                        foreach ($this->listPhoto as $key=>$value){
                            ?>
                            <a id="single_image" href="<?=UPLOAD_DIR_LINK?>Image/<?=$this->menu?>/<?=$value['photo']?>" data-fancybox="gallery">
                                <img src="<?=UPLOAD_DIR_LINK?>Image/<?=$this->menu?>/<?=$value['thumb']?>" alt="" width="164">
                            </a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>