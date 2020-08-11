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
                    <img class="third-col third-fixed" src="<?=URL?>public/images/vacancy.png" alt="vacancy">
                    <?=Func::replace_custom_to_html($this->page['text'])?>

                    <!--h2><?=Lang::get('Kafedralar üzrə vakansiyalar')?></h2-->
                    <?php
                    foreach ($this->listItems as $key=>$value){
                        ?>
                        <div class="wu-accordion">
                            <div class="wu-accordion__head" data-open="false"><?=$value['title']?></div>
                            <div class="wu-accordion__body"><?=$value['text']?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="content__right">
                <div></div>
            </div>
        </div>
    </div>
</div>