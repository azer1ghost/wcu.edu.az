<div class="page">
    <div class="page-container page-news">
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>
		<ul class="news-tabs"></ul>
        <?php
        $x=1;
        foreach ($this->listItems as $value){
            ?>
            <div class="news-item">
                <a href="<?=URL.MF::$_lang?>/graduate/view/<?=$value['slug']?>">
                    <div class="photo">
                        <img src="<?=UPLOAD_DIR_LINK?>Image/graduate/<?=$value['thumb']?>" alt="<?=$value['title']?>">
                    </div>
                    <div class="name">
                        <div>
                            <!--span><?=$value['day']?> <?=$this->month[$value['month']]?></span-->
                            <p><?=$value['title']?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }

        /*
         * Pagination
         */
        echo isset($this->pagination)?$this->pagination:'';
        ?>
    </div>
</div>