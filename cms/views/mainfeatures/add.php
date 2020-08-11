<form action="<?= URL . $this->menu ?>/create" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mf_token" value="<?= Func::token() ?>">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= Lang::get('Photo') ?></label>
                                <input class="form-control" name="data_photo" type="file" size="32">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= Lang::get('Ordering') ?> *</label>
                                <input class="form-control" name="data_ordering" value="<?=(isset($this->postData['data_ordering']))?$this->postData['data_ordering']:$this->maxOrder?>">
                            </div>
                        </div>

                        <div class="col-md-12">

                            <!-- Tab -->
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
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?= Lang::get('Title') ?> *</label>
                                                    <textarea class="form-control myBasicEditor" name="data_title_<?=$key?>"><?=(isset($this->postData['data_title_'.$key]))?$this->postData['data_title_'.$key]:''?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $x++;
                                }
                                ?>
                            </div>
                            <!-- Tab end -->

                        </div>
                        <div class="col-md-12">
                            <div class="form-group radio">
                                <label><?= Lang::get('Status') ?>*</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="2" checked><?= Lang::get('Active') ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="status" value="1"><?= Lang::get('Passive') ?>
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