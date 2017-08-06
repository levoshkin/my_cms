<?php
/**
* version 1.0
*
*/
define("CONSTANT", true);

require('config.php');

$action = isset($_GET['action']) ? $_GET['action'] : "";

switch($action){
	case "archive":
		Article::archive();
		break;
	case "viewArticle":
		Article::viewArticle();
		break;
	default:
		Article::homepage();
}