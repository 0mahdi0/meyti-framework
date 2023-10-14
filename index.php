<?php

use App\Helpers\Config;

require_once './vendor/autoload.php';

Config::getFileContents('variable');
Config::getFileContents('functions');

require_once './urls.php';
