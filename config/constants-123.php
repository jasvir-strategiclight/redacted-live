<?php
//if (strpos($_SERVER['SCRIPT_URI'], 'www') !== false) {
//    if (strpos($_SERVER['SCRIPT_URI'], 'https') !== false) {
//        header("LOCATION:".str_replace("www.","", $_SERVER['SCRIPT_URI']));
//    } else {
//        header("LOCATION:".str_replace("www.","", str_replace("http","https",$_SERVER['SCRIPT_URI'])));
//    }
//}
define('DEBUG', false);
define('EMAIL_TEST_MODE', false);  // true = test // false = live

define('SITE_TITLE', 'Morning Invest');
define('SITE_URL', 'https://referral.morninginvest.com/');
define('AFFILIATE_URL', 'https://referral.morninginvest.com/affiliate-r/');
define('DASHBOARD_URL', 'https://referral.morninginvest.com/affiliate/');
//define('ADMIN_EMAIL', 'satinder@strategiclight.com');
define('ADMIN_EMAIL', 'param@exploringmind.com');
//define('ADMIN_EMAIL', 'param_b_singh@msn.com');
//define('ADMIN_EMAIL', 'param@exploringmind.com');
//define('ADMIN_EMAIL', 'shailender@strategiclight.com');

define('LONG_DATE', 'l, F j, Y \a\t g:i a');
define('DATE_PICKER', 'm-d-Y');
define('SQL_DATETIME', 'Y-m-d H:i:s');
define('SHORT_DATE', 'M j, y  g:i a');
define('DATE_ONLY', 'j-m-Y');
define('NICE_DATE', 'l, F j, Y');
define('PAGE_LIMIT', 20);
define('FROM_EMAIL', 'clayton@morninginvest.com');
define('SUPPORT_EMAIL', 'clayton@morninginvest.com');
define('SMALL_THUMB_WIDTH', 200);
define('MEDIUM_THUMB_WIDTH', 500);
define('LARGE_THUMB_WIDTH', 900);
define('COPY_RIGHTS', '2020 &copy; Morning Invest All Rights Reserved.'); 

/*
 * Database Details
 */

//define('DB_HOST', 'localhost');
//define('DB_USER', 'mminvest_referral');
//define('DB_PASS', 'ROmV&Zv*kj1!');
//define('DB_NAME', 'mminvest_referral');

define('DB_HOST', 'localhost');
define('DB_USER', 'morinves_referrallive');
define('DB_PASS', 'jVq{qe$6p0klhLa9rP)gy,oh');
define('DB_NAME', 'morinves_referrallive');

/*
 * SMTP Details
 */

//define('SMTP_HOST', 'mail.pelvichep.com');
//define('SMTP_PORT', 587);
//define('SMTP_USER', 'info@pelvichep.com');
//define('SMTP_PASS', 'Info123$$');

// define('SMTP_HOST', 'mail.morninginvest.com');
// define('SMTP_PORT', 587);
// define('SMTP_USER', 'team@morninginvest.com');
// define('SMTP_PASS', '#?~*q{PMM73L');

//define('SMTP_HOST', 'smtp.sendgrid.net');
//define('SMTP_PORT', 587);
//define('SMTP_USER', 'apikey');
//define('SMTP_PASS', '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');


//define('SMTP_HOST', 'mail.morninginvest.com');
//define('SMTP_PORT', 587);
//define('SMTP_USER', 'team@morninginvest.com');
//define('SMTP_PASS', '#?~*q{PMM73L');


define('SMTP_HOST', 'mail.morninginvest.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'clayton@morninginvest.com');
define('SMTP_PASS', '0B47#p4h54qSy-rE[CTPK^Bx');

/*
 * Status Constants
 */

define('SUCCESS_CODE', 200);
define('INVALID_ACCESS_TOKEN', 459);
define('TOKEN_EXPIRED', 457);
define('CODE_BAD_REQUEST', 400);
define('SYSTEM_ERROR', 500);
define('MISMATCH', 408);
define('USER_ALREADY_EXIST', 520);
define('RECORD_NOT_FOUND', 404);
define('PAGE_NOT_FOUND', 404);
define('EMAIL_DOESNOT_REGISTERED', 413);
