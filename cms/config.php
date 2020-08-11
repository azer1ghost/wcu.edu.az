<?php

define('API_TITLE', 'WCU.EDU.DEV CMS');

// Always provide a TRAILING SLASH (/) AFTER A PATH
define('URL', '/cms/');
define('SITE_URL', 'http://wcu.dev.com/');
define('LIBS', 'libs/');
define('BASE_DIR', '/home2/wuitcenter/WCU.EDU.AZ/');
define('UPLOAD_DIR', BASE_DIR.'upload/');
define('UPLOAD_DIR_LINK', '/upload/');


define('PHP_DATE_FORMAT', 'Y-m-d H:i');
define('DB_DATE_FORMAT', '%Y-%m-%d %h:%i');
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'wcu');
define('DB_USER', 'root');
define('DB_PASS', '4145124azer');

/* IMAGE SIZES */
define('photo_pages_width', '800');
define('photo_pages_height', true);
define('thumb_pages_width', '400');
define('thumb_pages_height', true);

define('photo_certificate_width', '800');
define('photo_certificate_height', true);
define('thumb_certificate_width', '268');
define('thumb_certificate_height', true);

define('photo_expeditions_width', '800');
define('photo_expeditions_height', true);
define('thumb_expeditions_width', '260');
define('thumb_expeditions_height', '196');

define('photo_post_width', '800');
define('photo_post_height', true);
define('thumb_post_width', '260');
define('thumb_post_height', '174');

define('photo_graduate_width', '800');
define('photo_graduate_height', true);
define('thumb_graduate_width', '260');
define('thumb_graduate_height', '174');

define('photo_undergraduate_width', '800');
define('photo_undergraduate_height', true);
define('thumb_undergraduate_width', '164');
define('thumb_undergraduate_height', '124');

define('photo_gallery_width', '800');
define('photo_gallery_height', true);
define('thumb_gallery_width', '260');
define('thumb_gallery_height', '174');

define('photo_feedback_width', '64');
define('photo_feedback_height', '64');

define('photo_mainfeatures_width', '48');
define('photo_mainfeatures_height', '48');

define('photo_mainbanner_width', '290');
define('photo_mainbanner_height', '200');

define('photo_chair_width', '260');
define('photo_chair_height', '346');

define('photo_video_width', '260');
define('photo_video_height', '173');

define('PHOTO_LINKS_WIDTH', '120');
define('PHOTO_LINKS_HEIGHT', '60');
define('PHOTO_ACTIVITY_WIDTH', '350');
define('PHOTO_ACTIVITY_HEIGHT', '263');
define('photo_journal_width', '640');
define('photo_journal_height', '480');
define('photo_galleryphoto_width', '1024');
define('photo_galleryphoto_height', '768');
define('thumb_galleryphoto_width', '300');
define('thumb_galleryphoto_height', '225');

// The sitewide hashkey, do not change this because its used for passwords!
// This is for other hash keys... Not sure yet
define('HASH_GENERAL_KEY', 'MFCMSv1.0');

// This is for database passwords only
define('HASH_PASSWORD_KEY', 'catsFLYhigh2000milesM&Fasdqwe');

// ReCaptcha v2
define('RECAPTCHA_SECRET', '6Lf-jiYUAAAAAHLVviEpTw_dtK3ko0F7LefdgM2C');
define('RECAPTCHA_SITEKEY', '6Lf-jiYUAAAAAB_Mp6GM9mThlhySTWkrmHs0FG-H');
