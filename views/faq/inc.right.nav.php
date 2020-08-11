<div class="page-sidebar">
    <div class="sidebar">
        <h3><?=Lang::get('F.A.Q.')?></h3>
        <ul>
        <?php
        foreach ($this->listCategory as $v){
            ?><li><a href="<?=URL.MF::$_lang?>/faq/index/<?= $v['slug'] ?>"><?= $v['title'] ?></a></li><?php
        }
        ?>
        </ul>
    </div>
</div>