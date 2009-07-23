<?php

error_reporting(E_ALL);
ini_set('display_errors', 2);

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(__FILE__));
}

require_once APP_PATH . '/classes/SEOKit.php';

spl_autoload_register(array('SEOKit', 'autoload'));
set_error_handler(array('SEOKit', 'errorHandler'));

date_default_timezone_set(Options::get('timezone'));

CronTab::run(true);

Controller::dispatchRequest();
