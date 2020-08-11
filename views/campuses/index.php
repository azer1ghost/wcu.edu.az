<div class="page">
    <?php include (__DIR__.'/../inc.right.nav.php'); ?>

    <div class="page-container">

        <div class="breadcrumb">
            <?=$this->breadcrumb?>
        </div>
        <h1 class="pagetitle"><?= Func::lang_uni(MF::$_lang, $this->page['title'])?></h1>

        <div class="campuses">
            <?php
            foreach ($this->listItems as $key=>$value){
                if(isset($value['map'])){
                    $map = explode(', ', $value['map']);
                } else {
                    $map = array('40.3643163', '49.8297746');
                }
                ?>

                <div class="campuses__one">
                    <div class="campuses__one-info">
                        <p class="campus-title"><?=$value['title']?></p>
                        <p><b><?=Lang::get('Ünvan')?>:</b><?=$value['address']?></p>
                        <p><b><?=Lang::get('İndeks')?>:</b><?=$value['index']?></p>
                        <p><b><?=Lang::get('Telefon')?>:</b><?=$value['phone']?></p>
                        <?php 
                        if(!empty($value['fax'])) {
                            ?><p><b><?=Lang::get('Faks')?>:</b><?=$value['fax']?></p><?php
                        }
                        if(!empty($value['email'])) {
                            ?><p><b><?=Lang::get('E-ünvan')?>:</b><?=$value['email']?></p><?php
                        }
                        ?>

                    </div>
                    <div class="campuses__one-map">
                        <!--Link on google marker-->
                        <a target="_blank" href="https://www.google.com/maps/?q=<?=$map[0]?>,<?=$map[1]?>">
                            <span class="mobile-only">view on map</span>
                            <div class="map-container" data-lat="<?=$map[0]?>" data-lng="<?=$map[1]?>"></div>
                        </a>
                    </div>
                </div>
                <?php
            }
            ?>

        </div>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa5hpRhxqaY2x6RjlZGmlOBYfmLfQmzMM&callback=initMap"></script>
    </div>
</div>