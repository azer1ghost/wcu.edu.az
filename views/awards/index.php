<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">
        
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div>
            <?php
            foreach ($this->listItems as $value){
                ?>
                <div class="wu-accordion  wu-years">
                    <div class="wu-accordion__head" data-open="false">
                        <?=$value['text']['year']?>
                    </div>
                    <div class="wu-accordion__body">
                        <div class="wu-accordion__body-awards">
                            <div><?=$value['text']['text']?></div>
                            <div>
                                <?php
                                if(!empty($value['photo'])){
                                    ?>
                                    <div class="gallery-item" style="float: right">
                                        <a href="javascript:void(0)"><img src="<?=UPLOAD_DIR_LINK?>Image/certificate/<?=$value['text']['thumb']?>"></a>
                                        <div>
                                            <?php
                                            foreach ($value['photo'] as $v){
                                                ?><a href="<?=UPLOAD_DIR_LINK?>Image/certificate/<?=$v['photo']?>" data-fancybox="gallery-<?=$value['text']['id']?>"><img src="<?=UPLOAD_DIR_LINK?>Image/certificate/<?=$v['thumb']?>" alt="<?=$value['text']['title']?>"></a><?php
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
                </div>
                <?php
            }
            ?>
        </div>

    </div>
</div>