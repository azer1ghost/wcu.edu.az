<?php

// Always provide a TRAILING SLASH (/) AFTER A PATH
define('URL', '/');
define('SITE_URL', 'http://wcu.dev.com/');
define('LIBS', 'libs/');
define('UPLOAD_DIR_LINK', '/upload/');

// LIMITS
define('LIMIT_NEWS', '10');


define('PHP_DATE_FORMAT', 'Y-m-d H:i');
define('DB_DATE_FORMAT', '%Y-%m-%d %h:%i');
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'wcu');
define('DB_USER', 'root');
define('DB_PASS', '4145124azer');

// The sitewide hashkey, do not change this because its used for passwords!
// This is for other hash keys... Not sure yet
define('HASH_GENERAL_KEY', 'MFCMSv1.0');

// This is for database passwords only
define('HASH_PASSWORD_KEY', 'catsFLYhigh2000milesM&Fasdqwe');

// ReCaptcha v2
define('RECAPTCHA_SECRET', '6Lf-jiYUAAAAAHLVviEpTw_dtK3ko0F7LefdgM2C');
define('RECAPTCHA_SITEKEY', '6Lf-jiYUAAAAAB_Mp6GM9mThlhySTWkrmHs0FG-H');
