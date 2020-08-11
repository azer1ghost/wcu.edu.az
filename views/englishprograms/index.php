<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">
        
        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div class="content">
            <div>
                <table class="table-undergraduate">
                    <thead>
                        <tr>
                            <th width=30px>#</th>
                            <th><?=Lang::get('İxtisasın şifri')?></th>
                            <th><?=Lang::get('İxtisasın adı')?></th>
                            <th><?=Lang::get('Tədris növü')?></th>
                            <th><?=Lang::get('İllik təhsil haqqı')?></th>
                            <th><?=Lang::get('Xarici tələbələr üçün təhsil haqqı')?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->listItems as $key=>$value) {
                        ?>
                        <tr>
                            <td class="table-separator" colspan="3"><h3><?=$value['text']['title']?></h3></td>
                        </tr>
                        <?php
                        $x=1;
                        foreach ($value['sub'] as $k=>$v) {
                            ?>
                            <tr>
                                <td><?=$x?>.</td>
                                <td><?=$v['code']?></td>
                                <td><a href="<?=URL.MF::$_lang?>/englishprograms/view/<?=$v['slug']?>"><?=$v['title']?></a></td>
                                <td><?=$v['type']?></td>
                                <td><?=$v['price']?></td>
                                <td><?=$v['price_foreign']?></td>
                            </tr>
                            <?php
                            $x++;
                        }
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>