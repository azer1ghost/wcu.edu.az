<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>

        <?php
        if(!empty($this->page['photo'])){
            ?><img class="page-img third-fixed" src="<?=UPLOAD_DIR_LINK?>Image/pages/<?=$this->page['photo']?>" alt=""><?php
        }
        ?>

        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div class="content">
            <div class="content__left">
                <div>
                    <?php
                    if(!empty($this->listChair)){
                        foreach ($this->listChair as $value){
                            ?>
                            <h2><?=$value['text']['title']?></h2>
                            <?php
                            if(!empty($value['sub'])){
                                ?>
                                <ul>
                                    <?php
                                    foreach ($value['sub'] as $v=>$k){
                                        ?><li><a href="<?=URL.MF::$_lang?>/faculties/view/<?=$this->slug?>/<?=$k['slug']?>"><?=$k['title']?></a></li><?php
                                    }
                                    ?>
                                </ul>
                                <?php
                            }
                        }
                    }
                    ?>

                    <?=Func::replace_custom_to_html($this->page['text'])?>

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
                </div>
            </div>
            <div class="content__right">
                <div>

                </div>
            </div>
        </div>
    </div>
</div>