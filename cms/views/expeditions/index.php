<form action="<?= URL . $this->menu ?>/index" method="post">
    <input type="hidden" name="mf_token" value="<?= Func::token() ?>">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header"><?= $this->title ?>
                <div class="pull-right">
                    <a href="<?= URL . $this->menu ?>/add" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span> <?= Lang::get('Add') ?></a>
                    <button type="submit" name="action" value="save" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-floppy-disk"></span> <span class="hidden-xs"><?= Lang::get('Save') ?></span></button>
                    <button type="submit" name="action" value="delete" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-remove"></span> <span class="hidden-xs"><?= Lang::get('Delete') ?></span></button>
                    <button type="submit" name="action" value="activate" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-eye-open"></span> <span class="hidden-xs"><?= Lang::get('Activate') ?></span></button>
                    <button type="submit" name="action" value="deactivate" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-eye-close"></span> <span class="hidden-xs"><?= Lang::get('Deactivate') ?></span></button>
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
                    <table width="100%" class="table table-hover" id="dataTables" data-sorting="disable">
                        <thead>
                        <tr>
                            <th width="40"><?= Lang::get('ID') ?></th>
                            <th width="40"><input type="checkbox" class="checkAll"></th>
                            <th width="40"><?= Lang::get('Edit') ?></th>
                            <th width="40"><?= Lang::get('Ordering') ?></th>
                            <th><?= Lang::get('Title') ?></th>
                            <th width="140"><?= Lang::get('Date') ?></th>
                            <th width="80"><?= Lang::get('Status') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($this->listItems as $key => $value) {
                            ?>
                            <tr class="gradeX">
                                <td class="text-center"><?= $value['id'] ?></td>
                                <td class="text-center"><input id="<?= $value['id'] ?>" type="checkbox" name="ch[]" value="<?= $value['id'] ?>"></td>
                                <td class="text-center"><a href="<?= URL . $this->menu ?>/edit/<?= $value['id'] ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                <td class="text-center" data-order="<?= $value['ordering'] ?>"><input type="text" name="or[<?= $value['id'] ?>]" value="<?= $value['ordering'] ?>" class="ordering" /></td>
                                <td><label for="<?= $value['id'] ?>"><?= $value['title'] ?></label></td>
                                <td class="text-center"><?= $value['update_date'] ?></td>
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