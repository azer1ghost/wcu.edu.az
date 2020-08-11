<form action="<?= URL . $this->menu ?>/index" method="post">
    <input type="hidden" name="mf_token" value="<?= Func::token() ?>">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header"><?= $this->title ?>
                <div class="pull-right">
                    <a href="<?= URL . $this->menu ?>/add" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span> <?= Lang::get('Add new words') ?></a>
                    <button type="submit" name="action" value="save" class="btn btn-info btn-sm"><?= Lang::get('Save') ?></button>
                    <button type="submit" name="action" value="activate" class="btn btn-primary btn-sm"><?= Lang::get('Activate') ?></button>
                    <button type="submit" name="action" value="deactivate" class="btn btn-primary btn-sm"><?= Lang::get('Deactivate') ?></button>
                </div>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            // show errors
            $error = Session::get('note_error') ? Session::get('note_error') : NULL;
            if ($error) {
                Func::notification($error);
                Session::delete('note_error');
            }

            // show success
            $success = Session::get('note_success') ? Session::get('note_success') : NULL;
            if ($success) {
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
                    <table width="100%" class="table table-hover" id="dataTablesNoSorting">
                        <thead>
                        <tr>
                            <th width="40"><?= Lang::get('ID') ?></th>
                            <th><?= Lang::get('Title') ?></th>
                            <?php
                            foreach(MFAdmin::$_langs as $key=>$value){
                                ?><th width="20%"><?=$value?></th><?php
                            }
                            ?>
                            <th width="80"><?= Lang::get('Status') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($this->listItems as $key => $value) {
                            ?>
                            <tr class="gradeX">
                                <td class="text-center"><?= $value['id'] ?></td>
                                <td><label for="key-<?= $value['id'] ?>"><?= $value['key'] ?></label></td>
                                <?php
                                foreach(MFAdmin::$_langs as $k=>$v){
                                    ?>
                                    <td data-search="<?=htmlspecialchars($value[$k])?>">
                                        <input
                                                type="text"
                                                name="<?=$k?>[<?=$value['id']?>]"
                                                value="<?=$value[$k]?>"
                                                size="6"
                                                autocomplete="off"
                                                class="form-control">
                                    </td>
                                    <?php
                                }
                                ?>
                                <input type="hidden" name="id[]" value="<?=$value['id']?>">
                                <td class="text-center"><?= ($value['status'] == 2) ? Lang::get('Active') : Lang::get('Deactive') ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.row -->
</form>