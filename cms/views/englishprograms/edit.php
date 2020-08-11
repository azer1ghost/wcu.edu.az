<form action="<?= URL . $this->menu ?>/update/<?= $this->item['id'] ?>" method="post">
    <input type="hidden" name="mf_token" value="<?= Func::token() ?>">
    <input type="hidden" id="sid" name="sid" value="<?= (isset($this->item['secret_id']))?$this->item['secret_id']:Hash::create('md5', Func::rund_number(), HASH_PASSWORD_KEY)?>">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header"><?= $this->title ?>
                <div class="pull-right">
                    <button type="submit" class="btn btn-danger btn-sm"><?= Lang::get('Save') ?></button>
                    <button type="reset" class="btn btn-primary btn-sm"><?= Lang::get('Reset') ?></button>
                </div>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            // show errors
            $error = Session::get('note_error') ? Session::get('note_error') : NULL;
            if($error){
                Func::notification($error);
                Session::delete('note_error');
            }

            // show success
            $success = Session::get('note_success') ? Session::get('note_success') : NULL;
            if($success){
                Func::notification($success, 'success');
                Session::delete('note_success');
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $this->title ?> / <?= $this->titleSub ?>
                </div><!-- /.panel-heading -->

                <div class="panel-body confirm-panel user-edit">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?= Lang::get('Photo') ?> </label>
                                <input type="file" id="file" multiple name="image[]" data-menu="<?=$this->menu?>">
                                <div class="bar">
                                    <span class="bar-fill" id="pb">
                                        <span class="bar-fill-text" id="pt"></span>
                                    </span>
                                </div>

                                <div class="uploads" id="uploads">
                                    <div id="succeeded" data-menu="<?=$this->menu?>">
                                        <?php
                                        if(isset($this->item['photo']) && !empty($this->item['photo'])){
                                            foreach($this->item['photo'] as $key=>$value){
                                                ?>
                                                <div class="img" id="recordsArray_<?= $value['id'] ?>">
                                                    <img height="100" src="<?=UPLOAD_DIR_LINK?>Image/<?=$this->menu?>/<?=$value['thumb']?>">
                                                    <a href="/cms/<?=$this->menu?>/deletephoto/<?=$value['id']?>/<?=$value['secret_id']?>" class="delete"></a>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div id="failed"></div>
                                </div>

                                <p>
                                    <span>Şəkillərin maksimum çəkisi – 4MB olmalıdır.</span><br>
                                    <span>Şəkillərin formatı jpg, png və ya gif olmalıdır.</span><br>
                                    <span>Şəkillərin ölçüləri <?= photo_undergraduate_width ?>x600 piksel olmalıdır.</span><br>
                                </p>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?= Lang::get('Page') ?> *</label>
                                <select class="form-control" name="data_parent">
                                    <?php
                                    function buildMenu($parentId, $menuData, $parent=false) {
                                        $html = '';
                                        if (isset($menuData['parents'][$parentId])) {
                                            foreach ($menuData['parents'][$parentId] as $itemId) {
                                                $selected = ($parent == $menuData['items'][$itemId]['id'])?' selected':'';
                                                $html .= '<option value="' . $menuData['items'][$itemId]['id'] . '" '.$selected.'>' .$menuData['items'][$itemId]['title'] . '</option>';
                                                $html .= buildMenu($itemId, $menuData, $parent);
                                            }
                                        }
                                        return $html;
                                    }
                                    ?>
                                    <option value="0">&nbsp;</option>
                                    <?= buildMenu(0, $this->pagesList, $this->item['parent']) ?>
                                    <?php unset($this->pagesList); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?= Lang::get('Ordering') ?> *</label>
                                <input class="form-control" name="data_ordering" value="<?=(isset($this->item['ordering']))?$this->item['ordering']:''?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?= Lang::get('Code') ?></label>
                                <input class="form-control" name="data_code" value="<?=(isset($this->item['code']))?$this->item['code']:''?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?= Lang::get('Price') ?></label>
                                <input class="form-control" name="data_price" value="<?=(isset($this->item['price']))?$this->item['price']:''?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?= Lang::get('Price for foreign') ?></label>
                                <input class="form-control" name="data_price_foreign" value="<?=(isset($this->item['price_foreign']))?$this->item['price_foreign']:''?>">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <!-- Tab -->
                            <div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <?php
                                    $x=1;
                                    foreach (MFAdmin::$_langs as $key=>$value){
                                        ?>
                                        <li role="presentation"<?=($x==1)?' class="active"':''?>><a href="#tab_<?=$key?>" aria-controls="tab_<?=$key?>"><?=$value?></a></li>
                                        <?php
                                        $x++;
                                    }
                                    ?>
                                </ul>
                                <div class="tab-content">
                                    <?php
                                    $x=1;
                                    foreach (MFAdmin::$_langs as $key=>$value){
                                        ?>
                                        <div role="tabpanel" class="tab-pane<?=($x==1)?' active':''?>" id="tab_<?=$key?>" data-lang="<?=$key?>">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?= Lang::get('Title') ?> *</label>
                                                        <input class="form-control" name="data_title_<?=$key?>" value="<?=(isset($this->item['title'][$key]))?$this->item['title'][$key]:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?= Lang::get('Slug') ?> *</label>
                                                        <input class="form-control" name="data_slug_<?=$key?>" value="<?=(isset($this->item['slug'][$key]))?$this->item['slug'][$key]:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?= Lang::get('Type') ?></label>
                                                        <input class="form-control" name="data_type_<?=$key?>" value="<?=(isset($this->item['type'][$key]))?$this->item['type'][$key]:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= Lang::get('Text') ?> *</label>
                                                        <textarea class="form-control myBasicEditor" name="data_text_<?=$key?>"><?=(isset($this->item['text'][$key]))?$this->item['text'][$key]:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $x++;
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- Tab -->
                        </div>

                        <div class="col-md-12">
                            <div class="form-group radio">
                                <label><?= Lang::get('Status') ?></label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="2" <?= ($this->item['status'] == 2) ? ' checked' : '' ?>><?= Lang::get('Active') ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="1" <?= ($this->item['status'] == 1) ? ' checked' : '' ?>><?= Lang::get('Passive') ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="checkbox" name="data_post_in_page" value="1" <?= ($this->item['post_in_page'] == 1) ? ' checked' : '' ?>> <?= Lang::get('Post in page') ?>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.row -->
</form>