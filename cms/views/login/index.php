<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= (isset($this->description)) ? $this->description : 'RESURS API'; ?>">
    <meta name="author" content="MF">
    <title><?= (isset($this->title)) ? $this->title : 'RESURS API'; ?></title>
    <link href="//fonts.googleapis.com/css?family=Roboto:400,400i,700,700i&amp;subset=cyrillic,latin-ext" rel="stylesheet">
    <link href="<?= URL ?>public/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= URL ?>public/css/default.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="<?= URL ?>public/js/html5shiv.js"></script>
    <script src="<?= URL ?>public/js/respond.min.js"></script>
    <![endif]-->

</head>

<body class="login-body">
<div class="container-fluid login">
    <div class="login-panel">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Lang::get('Content Management System') ?></h3>
            <p><?= Lang::get('Log in to your account') ?></p>
        </div>
        <div class="login-form">
            <?php
            // show errors
            $error = Session::get('note_error') ? Session::get('note_error') : NULL;
            if($error){
                Func::notification($error);
                Session::delete('note_error');
            }
            ?>
            <form role="form" action="<?= URL ?>login/run" method="post">
                <input type="hidden" name="token" value="<?= Func::token() ?>">
                <div class="form-group">
                    <input class="form-control" placeholder="<?= Lang::get('Username') ?>" name="login" type="text" autofocus>
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="<?= Lang::get('Password') ?>" name="password" type="password">
                </div>
                <?php
                if($this->recaptca){
                    ?>

                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITEKEY ?>"></div>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    </div>
                    <?php
                }
                ?>

                <button class="btn btn-lg btn-success btn-block"><?= Lang::get('ENTER') ?></button>
            </form>

        </div>
        <footer>© Copyright <?= date('Y') ?></footer>
    </div>
</div>
</body>
</html>
