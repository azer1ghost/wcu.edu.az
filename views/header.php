<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet">
    <title><?= (isset($this->title)) ? $this->title . ' | ' : ''; ?> <?= ($this->def[1]) ? $this->def[1] : '' ?></title>
    <meta name="description" content="<?= (isset($this->def[2])) ? $this->def[2] : '' ?>">
    <meta name="keywords" content="<?= (isset($this->def[3])) ? $this->def[3] : '' ?>">
    <meta name="author" content="MF">
    <link rel="icon" type="image/png" href="/public/images/favicon.png" />

    <link rel="stylesheet" type="text/css" href="scripts/slider/slider.css" media="all">
    <link rel="stylesheet" type="text/css" href="<?= URL ?>scripts/css/my_style.css">
    <link rel="stylesheet" href="<?= URL ?>scripts/lightbox/simple-lightbox.min.css">

    <?php
    if (isset($this->css)) {
        foreach ($this->css as $css) {
    ?>
            <link href="<?= URL ?>public/<?= $css ?>" rel="stylesheet"><?php
                                                                    }
                                                                }
                                                                        ?>
    <link href="<?= URL ?>public/css/lib.css" rel="stylesheet">
    <link href="<?= URL ?>public/css/app.css" rel="stylesheet">
</head>

<body <?= (isset($this->menu) && ($this->menu == 'index')) ? 'class="main-page"' : '' ?>>
    <nav>
        <div class="lang-container">
            <ul>
                <?php
                $page_link = filter_var(substr($_SERVER["REQUEST_URI"], 3), FILTER_SANITIZE_URL);
                foreach (MF::$_langs as $key => $value) {
                    $select = ($key == MF::$_lang) ? ' class="active"' : '';
                ?><li><a href="<?= URL . $key . $page_link ?>" <?= $select ?>><?= $value ?></a></li><?php
                                                                                                }
                                                                                                    ?>
            </ul>
        </div>

        <div class="nav-container" style="float:inherit; border: none;">
            <ul>
                <li><?= Lang::get('The foundation of a successful') ?></li>
            </ul>
        </div>

        <div class="nav-search-container">
            <img class="nav-link-img search-img" src="<?= URL ?>public/images/search-icon.svg" alt="search">
        </div>

        <div class="nav-container">
            <ul>
                <?php
                foreach ($this->topMenuThird as $key => $value) {
                    $link = Func::create_link($value);
                ?>
                    <li><a href="<?= $link ?>" <?= ($value['target'] == 1) ? ' target="_blank"' : '' ?>><?= Func::lang_uni(MF::$_lang, $value['title']) ?></a></li>
                <?php
                }
                ?>
            </ul>
        </div>

        <div class="nav-container">
            <ul>
                <?php
                foreach ($this->topMenuFirst as $key => $value) {
                    $link = Func::create_link($value);
                ?>

                    <li><a href="<?= $link ?>" <?= ($value['target'] == 1) ? ' target="_blank"' : '' ?>><?= Func::lang_uni(MF::$_lang, $value['title']) ?></a></li>
                <?php
                }
                ?>
                <?php
                $uri_lang = substr($_SERVER['REQUEST_URI'], 0, 3);
                ?>
                <?php if ($uri_lang == '/en') : ?>
                    <li><a href="<?= URL ?>en/pages/view/alumni">ALUMNI</a></li>
                <?php else : ?>
                    <li><a href="<?= URL ?>az/pages/view/alumni">MƏZUNLAR</a></li>
                <?php endif; ?>

            </ul>
        </div>
    </nav>
    <form class="search search-form" method="get" action="<?= URL . MF::$_lang ?>/search" data-lang="<?= MF::$_lang ?>">
        <div class="search-wrap">
            <input class="search__area" type="text" name="q" placeholder="<?= Lang::get('Search...') ?>">
            <input class="search__close" type="button">
        </div>
    </form>

    <div class="main-header<?= (isset($this->menu) && ($this->menu == 'index')) ? '' : ' custom-header' ?>">

        <!--<div class="elan_baner">-->
        <!--    <div class="banner_box">-->
        <!--        <a href="https://koronavirusinfo.az/az/page/koronavirus-covid-19/koronavirusun-yayilmasi" target="_blank">-->
        <!--        	<img src="<?= URL ?>upload/banner/covid.jpg">-->
        <!--        </a>-->
        <!--    </div>-->
        <!--</div>-->

        <div class="main-header-menu">
            <a href="<?= SITE_URL . MF::$_lang ?>"><img src="<?= URL ?>public/images/wu-logo.png" /></a>

            <img class="mobile-logo" src="<?= URL ?>public/images/wu-minilogo.png" alt="logo">
            <a href="javascript:history.back()" class="prev-page"></a>
            <a href="javascript:" class="nav-burger"></a>

            <ul>
                <?php
                foreach ($this->topMenuSecond as $key => $value) {
                    $link = Func::create_link($value['text']);
                ?>

                    <?php if ($value['text']['slug'] != 'alumni') : ?>

                        <li id="link_<?= $value['text']['id']; ?>">
                            <a href="javascript:void(0)" <?= ($value['text']['target'] == 1) ? ' target="_blank"' : '' ?>><?= Func::lang_uni(MF::$_lang, $value['text']['title']) ?></a>
                            <?php
                            if (!empty($value['sub'])) { ?>

                                <div class="submenu-container" style="background-image: url('<?= ($value['text']['thumb']) ? UPLOAD_DIR_LINK . 'Image/pages/' . $value['text']['thumb'] : URL . 'public/images/faculty-3.png' ?>');">
                                    <div class="submenu__text">
                                        <h2><?= $value['text']['title'] ?></h2>
                                        <p><?= $value['text']['subtitle'] ?></p>
                                    </div>
                                    <div class="submenu__list">
                                        <ul>
                                            <?php
                                            foreach ($value['sub'] as $v) {
                                                $link = Func::create_link($v); ?>
                                                <li><a href="<?= $link ?>" <?= ($v['target'] == 1) ? ' target="_blank"' : '' ?>><?= $v['title'] ?></a></li>
                                            <?php } ?>
                                        </ul>

                                    </div>
                                </div>
                            <?php } ?>

                        </li>
                    <?php endif; ?>
                <?php } ?>

            </ul>
        </div>



        <?php
        if (isset($this->menu) && ($this->menu == 'index')) {
        ?>

            <h1 style="text-align: center;"><?= Lang::get('The foundation of a successful career') ?></h1>

            <?php
            $x = 1;
            foreach ($this->listMainBanner as $key => $value) {
            ?>
                <div class="main-undergraduate  main-header-tabs<?= ($x == 1) ? ' active' : '' ?>">


                    <div class="s_box">
                        <div class="slider">
                            <div id="slider" class="slide-list">
                                <div class="slide-wrap">

                                    <?php
                                    foreach ($value['text'] as $k => $v) {
                                        $link = ($v['link']) ? $v['link'] : 'javascript:'; ?>
                                
                                        <div class="slide-item">
                                            <div class="main-undergrad-one">
                                                <a href="<?= $link ?>">
                                                    <div class="main-undergraduate-one__image">
                                                        <img class="slider-image" src="<?= UPLOAD_DIR_LINK ?>Image/mainbanner/<?= $v['photo'] ?>" alt="<?= $v['title'] ?>">
                                                    </div>
                                                    <p><span style="text-align: center;"><?= $v['title'] ?></span></p>
                                                </a>
                                            </div>
                                        </div>
                                        
                                    <?php } ?>

                                </div>

                            </div>

                        </div>
                        <div class="arrows">
                            <div class="navy prev-slide"></div>
                            <div class="navy next-slide"></div>
                        </div>
                    </div>




                    <p>
                        <!--a class="main-undergraduate-link" href="#">About undergraduate</a-->
                    </p>
                </div>
            <?php $x++;
            }  ?>



            <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
            <script src="scripts/slider/slider.js"></script>


            <div class="right-side-links">
                <ul>
                    <?php foreach ($this->listMainBanner as $key => $value) { ?>
                        <li><a href="<?= $value['category']['link'] ?>"><span><?= $value['category']['title'] ?></span></a></li>
                    <?php } ?>
                </ul>
            </div>

            <div class="left-side-links">
                <ul>
                    <?php $x = 1;
                    foreach ($this->listMainBannerleft as $key => $value) { ?>
                        <li><a href="<?= $value['category']['link'] ?>" class="active"><span><?= $value['category']['title'] ?></span></a></li>
                    <?php $x++;
                    } ?>
                </ul>
            </div>


    </div>
<?php
        }
?>
</div>