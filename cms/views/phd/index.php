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
                    <table width="100%" class="table table-hover" id="dataTablesNoSorting">
                        <thead>
                        <tr>
                            <th width="40"><?= Lang::get('ID') ?></th>
                            <th width="40"><input type="checkbox" class="checkAll"></th>
                            <th width="40"><?= Lang::get('Edit') ?></th>
                            <th width="50"><?= Lang::get('Ordering') ?></th>
                            <th><?= Lang::get('Title') ?></th>
                            <th width="200"><?= Lang::get('Slug') ?></th>
                            <th width="80"><?= Lang::get('Status') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        function buildList($parentId, $menuData, $level = 0) {
                            $html = "";
                            if (isset($menuData['parents'][$parentId])) {
                                $level = $level +1;
                                $margin = ($level-1)*40;
                                $margin = $margin.'px';
                                foreach ($menuData['parents'][$parentId] as $itemId) {

                                    $html .= '<tr>
                                                <td class="text-center">' . $menuData['items'][$itemId]['id'] . '.</td>
                                                <td class="text-center">';
                                                    if ($menuData['items'][$itemId]['id']) {
                                                        $html .='<input type="checkbox" value="' . $menuData['items'][$itemId]['id'] . '" name="ch[]" id="checkBox' . $menuData['items'][$itemId]['id'] . '"/>';
                                                    }
                                                    $html .= '</td>
                                                    <td class="text-center"><a href="'.URL .'phd/edit/' . $menuData['items'][$itemId]['id'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                                    <td class="text-center" data-order="' . $menuData['items'][$itemId]['ordering'] . '"><input type="text" name="or[' . $menuData['items'][$itemId]['id'] . ']" class="ordering" value="' . $menuData['items'][$itemId]['ordering'] . '" /></td>
                                                    <td class="text-left"><label style="margin-left:'.$margin.';display:inline-block" for="checkBox' . $menuData['items'][$itemId]['id'] . '">' . $menuData['items'][$itemId]['title'].'</label></td>
                                                    <td>' . $menuData['items'][$itemId]['slug'] . '</td>
                                                    <td class="text-center">' .(($menuData['items'][$itemId]['status'] == 2) ? Lang::get('Active') : Lang::get('Deactive')). '</td>
                                                </tr>';
                                    $html .= buildList($itemId, $menuData, $level);
                                }

                            }
                            return $html;
                        }
                        echo buildList(0, $this->listItems);
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