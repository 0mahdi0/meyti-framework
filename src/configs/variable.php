<?php

use App\Helpers\Config;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, *');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

define("DbConfig", Config::get('database', 'pdo'));
define("SITE_URL", " ");
define("FRONT_URL", "");
define("API_URL", "");
define("ENCRYPT_METHOD", "des-ede-ofb");
define("SECRET_KEY", "MEYTIROLE");
define("SECRET_IV", "MEYTIROLE");
define("API_KEY", "");
define("API_IV", "");

// google auth
define("GOOGLE_CLIETN_ID", "MEYTIROLE");
define("GOOGLE_CLIETN_SECRET", "MEYTIROLE");

//mail
define("MAIL_HOST", "");
define("MAIL_PORT", 465);
define("MAIL_USER", "");
define("MAIL_USERNAME", "");
define("MAIL_PASS", "");

define("TELEGRAM_BOT_TOKEN", "");
define("TELEGRAM_CHANEL", "");

define("SMS_TOKEN_KEY", "");
define("SMS_MAIN_PHONE", "");
define("SIMOEL_API_KEY", "");

define("UPLOAD_PATH", ABSPATH . "/upload/");
define("UPLOADLIST_PATH", ABSPATH . "/upload/list/");
define("UPLOAD_URL", "");
