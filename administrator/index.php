<?php
define("CONSTANT", true);
session_start();

require("../config.php");

$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if($action != "login" && $action != "logout" && !$username){
	User::login();
	exit;
}

switch($action){
	case 'login':
		User::login();
		break;
	case 'logout':
		User::logout();
		break;
	case 'newArticle':
		Article::newArticle();
		break;
	case 'newCategory':
		Category::newCategory();
		break;
	case 'editCategory':
		Category::editCategory();
		break;
	case 'editArticle':
		Article::editArticle();
		break;
	case 'deleteArticle':
		Article::deleteArticle();
		break;
	case 'deleteCategory':
		Category::deleteCategory();
		break;
	case 'listCategories':
		Category::listCategories();
		break;
	case 'listArticles':
		Article::listArticles();
		break;
	default:
		require(TEMPLATE_PATH . "/panel.php");
}