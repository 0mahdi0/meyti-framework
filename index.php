<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

use App\Helpers\Config;

require_once './vendor/autoload.php';
define("ABSPATH", __DIR__);
define("ISTEST", true);
Config::getFileContents('variable');
Config::getFileContents('functions');

require_once './urls.php';
