<?php

if (!defined("CONSTANT"))  die ('Доступ запрещен!');

class User{

	public $username = null;

	public $name = null;

	public $lastname = null;

	public $email = null;
	
	public $password = null;
	
	/**
	*	Уровень доступа
	*/
	public $access = null;


	public function __construct($data=array()){

		if(isset($data['username']))
			$this->username = (int)$data['username'];

		if(isset($data['name']))
			$this->name = (int)$data['name'];

		if(isset($data['lastname']))
			$this->lastname = (int)$data['lastname'];

		if(isset($data['email']))
			$this->email = (int)$data['email'];

		if(isset($data['password']))
			$this->password = (int)$data['password'];

		if(isset($data['access']))
			$this->access = (int)$data['access'];
	}


	public static function login(){
		
		$results = array();
		$results['pageTitle'] = "Авторизация | " . SITE_NAME;
		
		if(isset($_POST['login'])){
			
			if($_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD){
				$_SESSION['username'] = ADMIN_USERNAME;
				header("Location: ./");
				
			}else{
				
				$results['errorMessage'] = "Некорректное имя пользователя или пароль. Пожалуйста попробуйте еще раз.";
				require(TEMPLATE_PATH . '/loginForm.php');
				
			}
			
		}else{
			
			require(TEMPLATE_PATH . '/loginForm.php');
			
		}
	}

	public static function logout(){
		unset($_SESSION['username']);
		header("Location: ./");
	}
}