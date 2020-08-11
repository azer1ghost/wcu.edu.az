
<div class="news-widget">

    <div class="news-tabs">
        <ul>
            <?php
            $x=1;
            foreach ($this->newstype as $key=>$value){
                ?><li><a<?=($x==1)?' class="active"':''?> href="javascript:void(0)" style="border: none"><?=$value?></a></li><?php
                $x++;
            }
            ?>
        </ul>

        <?php
        $x=1;
        foreach ($this->newstype as $key=>$value){

            ?>
            <div class="tabs-one-tab<?=($x==1)?' active':''?>" id="main-tab-<?=$x?>">
                <div class="news-widget-minicont">
                    <?php
                    foreach ($this->listPost[$key] as $k=>$v){
                        ?>
                        <div class="mini-news">
                            <div class="news-date">
                                <p><?=$v['day']?></p>
                                <p><?=$this->month[$v['month']]?></p>
                            </div>
                            <div class="news-content">
                                <?php if($key==2){ ?><p><?=$v['hour']?></p><?php } ?>
                                <p><a href="<?=URL.MF::$_lang?>/news/view/<?=$v['id']?>"><?=$v['title']?></a></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
            $x++;
        }
        ?>

        <!--a href="<?=URL.MF::$_lang?>/news" class="news-all-link active"><?=Lang::get('All events')?></a-->
        <a href="<?=URL.MF::$_lang?>/news" class="news-all-link active"><?=Lang::get('All news')?></a>

    </div>


</div>
<div class="main-content-block">

    <div class="main-content-block-container">
        <div class="main-content-inner">
            <h3><?=Lang::get('KEY FEATURES')?></h3>
            <h4><?=Lang::get('key_features_more')?></h4>
            <?php
            $icon = ['globe-icon','awards-icon','career-icon','labs-icon','money-icon','books-icon'];
            foreach ($this->listFeatures as $key=>$value){
                ?>
                <div class="main-key-feature">
                    <div class="<?=$icon[$key]?> key-feature-icon"></div>
                    <div class="key-feature-text"><?=$value['title']?></div>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="main-content-video">
            <div class="video-block" style="background: url('<?=UPLOAD_DIR_LINK?>Image/video-cover-2.png'); background-size: cover" data-videolink="<?= (isset($this->def[7])) ? $this->def[7] : '' ?>">
                <a href="javascript:void(0)" class="play-video-link"></a>
            </div>
        </div>
    </div>

    <div class="player-widget">
        <h2><?=Lang::get('Western Caspian University Official Hymn')?></h2>
        <div class="player-control">
            <a href="javascript:" id="play-bt"></a>
            <div class="player-volume">
                <div class="player-volume__vols"></div>
                <div></div>
            </div>
            <div class="player-time">
                0:00
            </div>
            <div class="player-progress">
                <div></div>
            </div>
            <audio controls="controls" id="audio-player">
                <source src="<?=UPLOAD_DIR_LINK?>Media/track.ogg" type="audio/ogg" />
                <source src="<?=UPLOAD_DIR_LINK?>Media/track-1.mp3" type="audio/mpeg" />
                <?=Lang::get('Your browser does not support the audio element.')?>
            </audio>
        </div>
    </div>

</div>