<form action="<?= URL . $this->menu?>/update/<?= $this->item['id'] ?>" method="post">
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
    <!-- /.row -->
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
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?= Lang::get('Name') ?></label>
                                <input type="text" class="form-control" name="name" value="<?=(isset($this->item['name']))?$this->item['name']:''?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?= Lang::get('Login') ?></label>
                                <input type="text" class="form-control" name="login" value="<?=(isset($this->item['login']))?$this->item['login']:''?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?= Lang::get('Password') ?></label>
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group admin-role">
                                <label><?= Lang::get('Role') ?></label><br>
                                <?php
                                $role = json_decode($this->item['role']);
                                foreach (MFAdmin::$_menu_list as $key => $value) {
                                    if ($key != 'hidden') {
                                        foreach ($value as $k => $v) {
                                            $checked = in_array($v, $role) ? ' checked' : '';
                                            ?>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="role[]" value="<?= $v ?>"<?= $checked ?>>
                                                <span><?= Lang::get($v) ?></span>
                                            </label>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
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