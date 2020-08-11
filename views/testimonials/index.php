<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">
        
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div class="testimonials">
            <?php
            foreach ($this->listItems as $key=>$value){
                ?>

                <div class="testimonial">
                    <?php
                    if($value['photo']){
                        ?><div class="photo" style="background-image: url('<?=UPLOAD_DIR_LINK?>Image/feedback/<?=$value['photo']?>')"></div><?php
                    }
                    ?>
                    <div class="testimonial__head">
                        <div>
                            <p><?=$value['title']?></p>
                            <p><?=$value['position']?></p>
                        </div>
                    </div>
                    <div class="testimonial__content"><?=htmlspecialchars_decode($value['text'])?></div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>