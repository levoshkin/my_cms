<?php

if (!defined("CONSTANT"))  die ('Доступ запрещен!');

ini_set( "display_errors", true );
date_default_timezone_set( "Europe/Kiev" );
setlocale(LC_ALL, "");
define('DB_DSN', 'mysql:host=localhost;dbname=cms');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('CLASS_PATH', 'classes/');
define('TEMPLATE_PATH', 'templates');
define('HOMEPAGE_NUM_ARTICLES', 5);
define('ADMIN_USERNAME', 'admin');
define('VERSION', '0.1.0');
define('ADMIN_PASSWORD', 'admin');
define('SITE_NAME', 'SD-MASTER');


spl_autoload_register(function ($class_name) {
    include CLASS_PATH . $class_name . '.php';
});


function handleException($e){
	echo "Извините, возникла проблема. Попробуйте позже.";
	error_log($e->getMessage());
}

set_exception_handler('handleException');
?>
