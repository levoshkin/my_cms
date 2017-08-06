<?php

if (!defined("CONSTANT"))  die ('Доступ запрещен!');

class Article{
	
	/**
	*@var int id article;
	*/
	public $id = null;
	
	/**
	*@var int Date of first publication of the article;
	*/
	public $publicationDate = null;
	
	/**
	*@var string Full title of the article;
	*/
	public $title = null;
	
	/**
	*@var string Short description of the article;
	*/
	public $summary = null;
	
	/**
	*@var string HTML content of the article;
	*/
	public $content = null;
	
	
	public $category_id = array();

	/**
	*@param assoc Property Value;
	*/

	public function __construct($data=array()){

		if(isset($data['id']))
			$this->id = (int)$data['id'];
		
		if(isset($data['publicationDate']))
			$this->publicationDate = (int)$data['publicationDate'];
		
		if(isset($data['title']))
			$this->title = $data['title']; //preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title']);
		
		if(isset($data['summary']))
			$this->summary = $data['summary']; //preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['summary']);
		
		if(isset($data['content']))
			$this->content = $data['content'];

		if(isset($data['category_id']))
			$this->category_id = $data['category_id'];

	}
	
	
	/**
	* Устанавливаем свойств с помощью значений формы редактирования записи в заданном массиве
	*
	* @param assoc Значения записи формы
	*/
	
	public function storeFormValues ($params){
		
		$this->__construct($params);
		if(isset($params['publicationDate'])){
			$publicationDate = explode("-", $params['publicationDate']);
			
			if(count($publicationDate) == 3){
				list($y, $m, $d) = $publicationDate;
				$this->publicationDate = mktime(0, 0, 0, $m, $d, $y);
			}
		}
	}
	

	/**
	* Возвращаем объект статьи соответствующий заданному ID статьи
	*
	* @param int ID статьи
	* @return Article|false Объект статьи или false, если запись не найдена или возникли проблемы
	*/

	public static function getById( $id ) {

		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

		$category_id = array();
		$sql = "SELECT * FROM articles_to_categories WHERE article_id = :id";
		$sth = $db->prepare($sql);
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();	

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$category_id[] = $row['category_id'];
		}

		$sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE id = :id";
		$sth = $db->prepare($sql);
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$row = $sth->fetch();
		$db = null;
		$row['category_id'] = $category_id;

		if($row) return new Article($row);
	}
	
	
	/**
	* Возвращает все (или диапазон) объектов статей в базе данных
	*
	* @param int Optional Количество строк (по умолчанию все)
	* @param string Optional Столбец по которому производится сортировка  статей (по умолчанию "publicationDate DESC")
	* @return Array|false Двух элементный массив: results => массив, список объектов статей; totalRows => общее количество статей
	*/

	public static function getList($numRows=1000000, $category=0, $order="publicationDate DESC"){
		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

		if($category != 0){

			$where = ($category != 0) ? " WHERE category_id=10" . $category : "";

			$sql = "SELECT article_id, category_id FROM articles_to_categories WHERE category_id=" . $category;
			$st = $db->query($sql);
			$list = array();
			while($row = $st->fetch()){
				
				$sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) AS publicationDate 
				FROM articles WHERE id = ".(int)$row['article_id'] . " ORDER BY " . $order . " LIMIT " . $numRows;
				$sth = $db->query($sql);

				while($rows = $sth->fetch()){
					$article = new Article($rows);
					$list[] = $article;
				}
			}
		}else{

			$sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) AS publicationDate 
			FROM articles ORDER BY " . $order . " LIMIT " . $numRows;
			$sth = $db->query($sql);

			while($rows = $sth->fetch(PDO::FETCH_ASSOC)){

				$category_id = array();
				$sql = "SELECT a.*, c.name, c.id FROM articles_to_categories as a 
						LEFT JOIN categories as c ON c.id=a.category_id 
						WHERE a.article_id=".$rows['id'];
				$st = $db->query($sql);
				
				while($row = $st->fetch(PDO::FETCH_ASSOC)){
					$category_id[$row['name']] = $row['id'];
				}
				
				$rows['category_id'] = $category_id;
				$article = new Article($rows);
				$list[] = $article;
			}			
		}

		//Total rows
		$sql = "SELECT FOUND_ROWS()  AS totalRows";
		$totalRows = $db->query($sql)->fetch();
		$db = null;
		 return (array("results" => $list, "categories" => $categories, "totalRows" => count($list)));
	}
	
	
	/**
	* Вставляем текущий объект статьи в базу данных, устанавливаем его свойства.
	*/
	
	public function insert(){
		
		if(!is_null($this->id))
			trigger_error("Article::insert() : Пытается вставить объект Article, 
				который уже имеет ID ($this->id)", E_USER_ERROR);
		
		//Insert Article
		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
		$sql = "INSERT INTO articles (publicationDate, title, summary, content) 
					VALUES (FROM_UNIXTIME(:publicationDate), :title, :summary, :content)";
		$sth = $db->prepare($sql);
		$sth->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
		$sth->bindValue(":title", $this->title, PDO::PARAM_STR);
		$sth->bindValue(":summary", $this->summary, PDO::PARAM_STR);
		$sth->bindValue(":content", $this->content, PDO::PARAM_STR);
		$sth->execute();
		$this->id = $db->lastInsertId();

		foreach($this->category_id as $category_id){
			$sql = "INSERT INTO articles_to_categories (article_id, category_id) VALUES ($this->id, $category_id)";
			$db->query($sql);
		}
		$db = null;
	}


	/**
	* Обновляем текущий объект статьи в базе данных
	*/
	
	public function update(){
		//Есть ли у объекта статьи ID
		if(is_null($this->id))
			trigger_error("Article::update() : Пытается обновить объект Article, 
							у которого в наборе нет свойства ID", E_USER_ERROR);
		
		//Update article
		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
		$sql = "UPDATE articles SET publicationDate = FROM_UNIXTIME(:publicationDate), 
					title = :title, summary = :summary, content = :content WHERE id = :id";
		$sth = $db->prepare($sql);
		$sth->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
		$sth->bindValue(":title", $this->title, PDO::PARAM_STR);
		$sth->bindValue(":summary", $this->summary, PDO::PARAM_STR);
		$sth->bindValue(":content", $this->content, PDO::PARAM_STR);
		$sth->bindValue(":id", $this->id, PDO::PARAM_INT);
		$sth->execute();

		$sql = "DELETE FROM articles_to_categories WHERE article_id=". $this->id;
		$db->query($sql);

		foreach($this->category_id as $category_id){
			$sql = "INSERT INTO articles_to_categories (article_id, category_id) VALUES ($this->id, $category_id)";
			$db->query($sql);
		}
		$db = null;
	}

	
	/**
	* Удаляем текущий объект статьи в базе данных
	*/
	
	public function delete(){
		
		if(is_null($this->id))
			trigger_error("Article::delete() : Пытается удалить объект Article, у которого в наборе нет свойства ID", E_USER_ERROR);
		
		//Delete article
		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

		$sql = "DELETE FROM articles_to_categories WHERE article_id = :id";
		$sth = $db->prepare($sql);
		$sth->bindValue(":id", $this->id, PDO::PARAM_INT);
		$sth->execute();

		$sql = "DELETE FROM articles WHERE id = :id LIMIT 1";
		$sth = $db->prepare($sql);
		$sth->bindValue(":id", $this->id, PDO::PARAM_INT);
		$sth->execute();
		$db = null;
	}

	public static function viewArticle(){
		if(!isset($_GET['articleId']) || !$_GET['articleId']){
			homepage();
			return;
		}
		
		$results = array();
		$results['article'] = Article::getById((int)$_GET['articleId']);
		$results['pageTitle'] = $results['article']->title . " | " . SITE_NAME;
		require(TEMPLATE_PATH . '/viewArticle.php');
	}

	public static function archive(){
		if(isset($_GET['category']))
			$category = $_GET['category'];

		$numRows = 1000000;

		$results = array();

		$data = Article::getList($numRows, $category);
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$data = Category::getListCategories();
		$results['categories'] = $data['results'];

		foreach($results['categories'] as $cat){
			if($cat->id == $category)
				$results['pageHeading'] = $cat->name;
		}
		
		$results['pageTitle'] = ($results['pageHeading']) ? $results['pageHeading'] . " | " . SITE_NAME : " Все статьи | " . SITE_NAME;
		require(TEMPLATE_PATH . '/archive.php');
	}


	public static function homepage(){
		if(isset($_GET['category']))
			$category = $_GET['category'];
		$results = array();
		$data = Article::getList(HOMEPAGE_NUM_ARTICLES, $category);
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = SITE_NAME;
			$data = Category::getListCategories();
			$results['categories'] = $data['results'];
		require(TEMPLATE_PATH . '/homepage.php');
	}



	public static function newArticle(){
		
		$results = array();
		$results['pageTitle'] = "Новая статья";
		$results['formAction'] = "newArticle";
		
		if(isset($_POST['saveChangeAndClose'])){
			$article = new Article;
			$article->storeFormValues($_POST);
			$article->insert();
			header("Location: ./?action=listArticles&status=changesSaved");

		}elseif($_POST['cancel']){

			header("Location: ./?action=listArticles");

		}elseif(isset($_POST['saveChange'])){

			if(!$article = Article::getById((int)$_POST['articleId'])){

				header("Location: ./?action=listArticles&error=articleNotFound");
				return;
			}

			$article = new Article;
			$article->storeFormValues($_POST);
			$article->insert();
			header("Location: ./?action=editArticle&articleId=".$article->id."&status=changesSaved");

		}else{

			$results['article'] = new Article;
			$data = Category::getListCategories();
			$results['categories'] = $data['results'];

				if(isset($_GET['status'])){

					if($_GET['status'] == "changesSaved")
						$results['statusMessage'] = "Изменения сохранены.";
				}

			require(TEMPLATE_PATH . "/editArticle.php");

		}
	}


	public static function editArticle(){

		$results = array();
		$results['formAction'] = "editArticle";

		if(isset($_POST['saveChangeAndClose'])){

			if(!$article = Article::getById((int)$_POST['articleId'])){

				header("Location: ./?action=listArticles&error=articleNotFound");
				return;
			}

			$article->storeFormValues($_POST);
			$article->update();
			header("Location: ./?action=listArticles&status=changesSaved");

		}elseif($_POST['cancel']){

			header("Location: ./?action=listArticles");

		}elseif(isset($_POST['saveChange'])){

			if(!$article = Article::getById((int)$_POST['articleId'])){

				header("Location: ./?action=listArticles&error=articleNotFound");
				return;
			}

			$article->storeFormValues($_POST);
			$article->update();
			header("Location: ./?action=editArticle&articleId=".(int)$_POST['articleId']."&status=changesSaved");

		}else{

			$results['article'] = Article::getById((int)$_GET['articleId']);
			$data = Category::getListCategories();
			$results['categories'] = $data['results'];
			$results['pageTitle'] = "Редактировать статью | <span style='font-size:16px'>" . $results['article']->title . "</span>";

				if(isset($_GET['status'])){

					if($_GET['status'] == "changesSaved")
						$results['statusMessage'] = "Изменения сохранены.";
				}

			require(TEMPLATE_PATH . "/editArticle.php");

		}

	}


	public static function deleteArticle(){

		if(!$article = Article::getById((int)$_GET['articleId'])){

			header("Location: ./?action=listArticles&error=articleNotFound");
			return;
		}

		$article->delete();
		header("Location: ./?action=listArticles&status=articleDeleted");
	}


	public static function listArticles(){
		$results = array();
		$data = Article::getList();
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['category_id'] = $data['categories'];
		$results['pageTitle'] = "Все статьи";

		if(isset($_GET['error'])){
			if($_GET['error'] == "articleNotFound")
				$results['errorMessage'] = "Error: Article not found.";
		}

		if(isset($_GET['status'])){

			if($_GET['status'] == "changesSaved")
				$results['statusMessage'] = "Изменения сохранены.";

			if($_GET['status'] == "articleDeleted")
				$results['statusMessage'] = "Статья удалена.";
		}

		require(TEMPLATE_PATH . "/listArticles.php");
		
	}
}
?>