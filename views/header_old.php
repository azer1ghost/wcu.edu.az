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

                        <li>
                            <a href="javascript:void(0)" <?= ($value['text']['target'] == 1) ? ' target="_blank"' : '' ?>><?= Func::lang_uni(MF::$_lang, $value['text']['title']) ?></a>
                            <?php
                            if (!empty($value['sub'])) {
                            ?>

                                <div class="submenu-container" style="background-image: url('<?= ($value['text']['thumb']) ? UPLOAD_DIR_LINK . 'Image/pages/' . $value['text']['thumb'] : URL . 'public/images/faculty-3.png' ?>');">
                                    <div class="submenu__text">
                                        <h2><?= $value['text']['title'] ?></h2>
                                        <p><?= $value['text']['subtitle'] ?></p>
                                    </div>
                                    <div class="submenu__list">
                                        <ul>
                                            <?php
                                            foreach ($value['sub'] as $v) {
                                                $link = Func::create_link($v);
                                            ?>

                                                <li><a href="<?= $link ?>" <?= ($v['target'] == 1) ? ' target="_blank"' : '' ?>><?= $v['title'] ?></a></li><?php
                                                                                                                                                        }
                                                                                                                                                            ?>

                                        </ul>

                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                        </li>
                    <?php endif; ?>
                <?php
                }
                ?>

            </ul>
        </div>









        <?php
        if (isset($this->menu) && ($this->menu == 'index')) {
        ?>
            <h1><?= Lang::get('The foundation of a successful career') ?></h1>

            <?php
            $x = 1;
            foreach ($this->listMainBanner as $key => $value) {
            ?>
                <div class="main-undergraduate  main-header-tabs<?= ($x == 1) ? ' active' : '' ?>">

                    <?php if ($x == 1) : ?>
                        <div class="s_box">
                            <div class="slider">
                                <div class="slide-list">
                                    <div class="slide-wrap">
                                    <?php endif; ?>


                                    <?php
                                    foreach ($value['text'] as $k => $v) {
                                        $link = ($v['link']) ? $v['link'] : 'javascript:';
                                    ?>

                                        <?php if ($x == 1) : ?>
                                            <div class="slide-item">
                                            <?php endif; ?>
                                            <div class="main-undergrad-one">
                                                <a href="<?= $link ?>">
                                                    <div class="main-undergraduate-one__image">
                                                        <img src="<?= UPLOAD_DIR_LINK ?>Image/mainbanner/<?= $v['photo'] ?>" alt="<?= $v['title'] ?>">
                                                    </div>
                                                    <p><span><?= $v['title'] ?></span></p>
                                                    <?php
                                                    if ($v['subtitle'] == '2019 / 2020 tədris ili üçün') {
                                                        $v['subtitle'] = '2020 / 2021 tədris ili üçün';
                                                    }
                                                    if ($v['subtitle'] == '2019/ 2020 tədris ili üçün') {
                                                        $v['subtitle'] = '2020 / 2021 tədris ili üçün';
                                                    }
                                                    if ($v['subtitle'] == '2018 / 2019 tədris ili üçün') {
                                                        $v['subtitle'] = '2020 / 2021 tədris ili üçün';
                                                    }
                                                    if ($v['subtitle'] == 'For the 2019/2020 academic years') {
                                                        $v['subtitle'] = 'For the 2020/2021 academic years';
                                                    }
                                                    if ($v['subtitle'] == 'Academic year of 2018/2019') {
                                                        $v['subtitle'] = 'Academic year of 2020/2021';
                                                    }
                                                    ?>
                                                    <p><?= $v['subtitle'] ?></p>
                                                </a>
                                            </div>

                                            <?php if ($x == 1) : ?>
                                            </div>
                                        <?php endif; ?>


                                    <?php
                                    }
                                    ?>

                                    <?php // butun bele yazilarin icerisinde olan if($x == 1) elave script uchundur, statik qoshulub 
                                    ?>
                                    <?php if ($x == 1) : ?>
                                        <?php
                                        if ($_SERVER['REQUEST_URI'] == '/en') {
                                            $sl_title1 = 'Our Universitiy\'s profile at "THE" World University rankings';
                                            $sl_title2 = 'Our Universitiy\'s profile at "QS" World University rankings';
                                            $sl_title3 = 'WCU Elsevier Scopus profile';
                                        } else {
                                            $sl_title1 = '"THE" DÜNYA REYTİNQ TƏŞKİLATI SAYTINDA UNİVERSİTETİMİZİN PROFİLİ';
                                            $sl_title2 = '"QS" DÜNYA REYTİNQ TƏŞKİLATI SAYTINDA UNİVERSİTETİMİZİN PROFİLİ';
                                            $sl_title3 = 'WCU ELSEVİER SCOPUS HESABI';
                                        }
                                        ?>

                                        <div class="slide-item">
                                            <div class="main-undergrad-one">
                                                <a href="https://www.timeshighereducation.com/world-university-rankings/western-caspian-university?fbclid=IwAR0nugr6voQ4OG3E3bFl2OzcOZQ48TS7BT1d1ZwygzRThzclgWzhbaFdviY" target="_blank">
                                                    <div class="main-undergraduate-one__image">
                                                        <img src="/scripts/time.png" alt="<?= $sl_title1 ?>">
                                                    </div>
                                                    <p><span><?= $sl_title1 ?></span></p>
                                                    <p></p>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="slide-item">
                                            <div class="main-undergrad-one">
                                                <a href="https://www.topuniversities.com/universities/western-caspian-university" target="_blank">
                                                    <div class="main-undergraduate-one__image">
                                                        <img src="/scripts/top_univers.jpg" alt="<?= $sl_title2 ?>">
                                                    </div>
                                                    <p><span><?= $sl_title2 ?></span></p>
                                                    <p></p>
                                                </a>
                                            </div>
                                        </div>


                                        <div class="slide-item">
                                            <div class="main-undergrad-one">
                                                <a href="https://www.scopus.com" target="_blank">
                                                    <div class="main-undergraduate-one__image">
                                                        <img src="/scripts/sc.jpg" alt="<?= $sl_title3 ?>">
                                                    </div>
                                                    <p><span><?= $sl_title3 ?></span></p>
                                                    <p></p>
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                            <div class="navy prev-slide"></div>
                            <div class="navy next-slide"></div>
                        </div>


                    <?php endif; ?>

                    <p>
                        <!--a class="main-undergraduate-link" href="#">About undergraduate</a-->
                    </p>
                </div>
            <?php
                $x++;
            }
            ?>



            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
            <script src="scripts/slider/slider.js"></script>


            <?php
                $url = $_SERVER['REQUEST_URI'];
                $lang = current(array_filter(explode("/", parse_url($url, PHP_URL_PATH))));
            ?>
            <!-- MOBILE SLIDER END -->
            <?php if ($lang == "az" or $lang == "") { ?>
                <div class="right-side-links">
                    <ul>
                        <li>
                            <a href="https://www.wcu.edu.az/az/undergraduate" >
                                <span>BAKALAVR PROQRAMLARI</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.wcu.edu.az/az/masterdegree">
                                <span>MAGİSTR PROQRAMLARI</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.wcu.edu.az/az/pages/view/doktorantura-proqrami-uzre-ixtisaslar">
                                <span>DOKTORANTURA PROQRAMLARI</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="left-side-links">
                    <ul>
                        <li>
                            <a href="#" class="active">
                                <span>PROSPECTIVE UNDERGRADUATE</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"  class="active">
                                <span>TƏLƏBƏLƏR</span>
                            </a>
                        </li>
                    </ul>
                </div>

            <?php }
            if ($lang == "en") { ?>
                <div class="right-side-links">
                    <ul>
                        <li>
                            <a href="https://www.wcu.edu.az/en/undergraduate" >
                                <span>BACHELOR DEGREE PROGRAM</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.wcu.edu.az/en/masterdegree">
                                <span>MASTER’S DEGREE PROGRAM</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.wcu.edu.az/en/pages/view/doktorantura-proqrami-uzre-ixtisaslar">
                                <span>PhD Degree</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="left-side-links">
                    <ul>
                        <li>
                            <a href="#" class="active">
                                <span>PROSPECTIVE UNDERGRADUATE</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"  class="active">
                                <span>CURRENT STUDENT</span>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php } ?>

    </div>
<?php
        }
?>
</div>