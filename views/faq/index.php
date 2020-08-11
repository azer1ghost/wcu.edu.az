<div class="page">
    <?php include (__DIR__.'/../faq/inc.right.nav.php'); ?>

    <div class="page-container">

        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div>

            <?php
            foreach($this->listItems as $value) {
                ?>
                <div class="wu-accordion  wu-faq">
                    <div class="wu-accordion__head" data-open="false"><?=$value['title']?></div>
                    <div class="wu-accordion__body">
                        <?=$value['text']?>
                    </div>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>