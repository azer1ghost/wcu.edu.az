
<div class="search-page__head">
    <div class="breadcrumb">
        <a href="<?=URL.MF::$_lang?>"><?=Lang::get('Main')?></a>
        <span><?=Lang::get('Search results')?></span>
    </div>
    <h1 class="pagetitle"><?=Lang::get('Search results')?></h1>
</div>

<div class="page search-page">
    <div class="page-sidebar">
        <form class="search-form" method="get" action="<?=URL.MF::$_lang?>/search" data-lang="<?=MF::$_lang?>">
            <div class="search-wrap">
                <input class="search__area" type="text" name="q" placeholder="<?=Lang::get('Search...')?>">
                <input class="search__btn" type="submit" value="">
            </div>
        </form>
        <p><b>“<?=$this->q?>”</b> sözü üzrə<br/>
            veb-sayt üzrə <b><?=$this->get_total_rows?> nəticə</b> tapıldı.</p>
    </div>

    <div class="page-container">

        <div class="content">
            <div class="content__left">
                <div>

                    <ul class="search-list">
                        <?php
                        if($this->get_total_rows>0){
                            foreach ($this->listItems as $value){
                                switch ($value['types']){
                                    case 'pages':
                                        $link = Func::create_link($value);
                                        break;
                                    case 'post':
                                        $link = URL.MF::$_lang.'/news/view/'.$value['id'];
                                        break;
                                    case 'expeditions':
                                        $link = URL.MF::$_lang.'/expeditions/view/'.$value['id'];
                                        break;
                                    case 'undergraduate':
                                        $link = URL.MF::$_lang.'/undergraduate/view/'.$value['id'];
                                        break;
                                    default:
                                        $link = URL.MF::$_lang.'#';

                                }
                                ?>

                                <li class="search-item">
                                    <a class="search-item__link" href="<?=$link?>"><?=Func::find_word($this->q, $value['title'])?></a>
                                    <p class="search-item__text"><?=Func::sub_string(Func::find_word($this->q, strip_tags($value['text'])), 180)?></p>
                                </li>
                                <?php
                            }
                        } else {
                            ?>

                            <div class="no-results">
                                <h3><?=Lang::get('Axtarış heç bir nəticə vermədi.')?></h3>
                                <p><?=Lang::get('Xahiş edirik, digər açar sözlərdən istifadə edin.')?></p>
                            </div>
                            <?php
                        }
                        ?>

                    </ul>

                </div>
            </div>
        </div>


        <?php
        /*
         * Pagination
         */
        echo isset($this->pagination)?$this->pagination:'';
        ?>

    </div>
</div>