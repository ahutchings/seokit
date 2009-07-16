<?php

// @todo move initialization code to root index.php
error_reporting(E_ALL);
ini_set('display_errors', 2);

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(__FILE__));
}

require_once APP_PATH . '/classes/SEOKit.php';

spl_autoload_register(array('SEOKit', 'autoload'));

$db = DB::connect();

// api key available from http://developer.yahoo.com/wsregapp/index.php
// more details here http://developer.yahoo.com/search/

$yahoo_api_key  = 'uloL8vHV34Gb64k1sNiHx6l8ZPrpEfWm1WNCcez4LscfDvLRHdVr3_tDpwFjTYmkTAO8iNA9NJwZiuI-';
$scriptlocation = "http://seokit.localhost";
