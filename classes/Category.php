<?php
if (!defined("CONSTANT"))  die ('Доступ запрещен!');

class Category{
	
	public $id = null;
	
	public $name = null;
	
	public $description = null;
	
	public $category_parent_id = 0;
	
	public $category_publish = 0;
	
	public $ordering = null;



	public function __construct($data=array()){

		if(isset($data['id']))
			$this->id = (int)$data['id'];
		
		if(isset($data['name']))
			$this->name = $data['name']; //preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['name']);
		
		if(isset($data['description']))
			$this->description = $data['description']; //preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['description']);
		
		if(isset($data['category_parent_id']))
			$this->category_parent_id = (int)$data['category_parent_id'];
		
		if(isset($data['category_publish']))
			$this->category_publish = (int)$data['category_publish'];
		else
			$this->category_publish = 0;
		
		if(isset($data['ordering']))
			$this->ordering = (int)$data['ordering'];
		
	}


	public function storeFormValues ($params){
		
		$this->__construct($params);

	}


	public static function getById($id){

		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
		$sql = "SELECT * FROM categories WHERE id = :id";
		$st = $db->prepare($sql);
		$st->bindValue(":id", $id, PDO::PARAM_INT);
		$st->execute();
		$row = $st->fetch();
		$db = null;

		if($row) return new Category($row);

	}


	public static function getListCategories($numRows=1000000, $order="name ASC"){

		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
		$sql = "SELECT * FROM categories ORDER BY " . $order . " LIMIT :numRows";
		$st = $db->prepare($sql);
		$st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
		$st->execute();
		$list = array();

		while($row = $st->fetch()){
			$category = new Category($row);
			$list[] = $category;
		}

		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $db->query($sql)->fetch();
		$db = null;

		return (array('results' => $list, 'totalRows' => $totalRows[0]));
	}


	public function insert(){

		if(!is_null($this->id))
			trigger_error("Category::insert : пытается вставить категорию, 
							которая уже имеет ID ($this->id)", E_USER_ERROR);

		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
		$sql = "INSERT INTO categories ( name, description, category_parent_id, category_publish)
					 VALUES ( :name, :description, :category_parent_id, :category_publish)";
		$st = $db->prepare($sql);
		$st->bindValue(":name", $this->name, PDO::PARAM_STR);
		$st->bindValue(":description", $this->description, PDO::PARAM_STR);
		$st->bindValue(":category_parent_id", $this->category_parent_id, PDO::PARAM_INT);
		$st->bindValue(":category_publish", $this->category_publish, PDO::PARAM_INT);
		$st->execute();
		$this->id = $db->lastInsertId();
		$db = null;
	}


	public function update(){

		if(is_null($this->id))
			trigger_error("Category::updateCategory : пытается обновить объект, 
							у которого нет свойства ID", E_USER_ERROR);

		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
		$sql = "UPDATE categories 
					SET name = :name, description = :description, 
						category_parent_id = :category_parent_id, category_publish = :category_publish
					WHERE id = :id";
		$st = $db->prepare($sql);
		$st->bindValue(":id", $this->id, PDO::PARAM_INT);
		$st->bindValue(":name", $this->name, PDO::PARAM_STR);
		$st->bindValue(":description", $this->description, PDO::PARAM_STR);
		$st->bindValue(":category_parent_id", $this->category_parent_id, PDO::PARAM_INT);
		$st->bindValue(":category_publish", $this->category_publish, PDO::PARAM_INT);
		$st->execute();
		$db = null;
	}
	

	public function delete(){

		if(is_null($this->id))
			trigger_error("Category::deleteCategory : пытается удалить объект, 
							у которого нет свойства ID", E_USER_ERROR);

		$db = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
		$sql = "DELETE FROM categories WHERE id = :id LIMIT 1";
		$st = $db->prepare($sql);
		$st->bindValue(":id", $this->id, PDO::PARAM_INT);
		$st->execute();
		$db = null;
	}


	public static function newCategory(){

		$results = array();
		$results['pageTitle'] = "Новая категория";
		$results['formAction'] = "newCategory";

		if(isset($_POST['saveChangeAndClose'])){

			$category = new Category;
			$category->storeFormValues($_POST);
			$category->insert();
			header("Location: ./?action=listCategories&status=changesSaved");

		}elseif($_POST['cancel']){

			header("Location: ./?action=listCategories");

		}elseif(isset($_POST['saveChange'])){

			$category = new Category;
			$category->storeFormValues($_POST);
			$category->insert();
			header("Location: ./?action=editCategory&categoryId=".$category->id."&status=changesSaved");

		}else{

			$data = Category::getListCategories();
			$results['category'] = new Category;
			$results['categories'] = $data['results'];

				if(isset($_GET['status'])){

					if($_GET['status'] == "changesSaved")
						$results['statusMessage'] = "Изменения сохранены.";
				}
			
			require(TEMPLATE_PATH . "/editCategory.php");

		}
	}

	public static function editCategory(){

		$results = array();
		$results['pageTitle'] = "Редактирование категории";
		$results['formAction'] = "editCategory";

		if(isset($_POST['saveChangeAndClose'])){

			if(!$category = Category::getById((int)$_POST['categoryId'])){

				header("Location: ./?action=listCategories&error=categoryNotFound");
				return;
			}

			$category->storeFormValues($_POST);
			$category->update();
			header("Location: ./?action=listCategories&status=changesSaved");

		}elseif($_POST['cancel']){

			header("Location: ./?action=listCategories");

		}elseif(isset($_POST['saveChange'])){

			if(!$category = Category::getById((int)$_POST['categoryId'])){

				header("Location: ./?action=listCategories&error=categoryNotFound");
				return;
			}

			$category->storeFormValues($_POST);
			$category->update();
			header("Location: ./?action=editCategory&categoryId=".$category->id."&status=changesSaved");

		}else{
			$data = Category::getListCategories();
			$results['category'] = Category::getById((int)$_GET['categoryId']);
			$results['categories'] = $data['results'];

				if(isset($_GET['status'])){

					if($_GET['status'] == "changesSaved")
						$results['statusMessage'] = "Изменения сохранены.";
				}

			require(TEMPLATE_PATH . "/editCategory.php");

		}
	}


	public static function deleteCategory(){

		if(!$category = Category::getById((int)$_GET['categoryId'])){

			header("Location: ./?action=listCategories&error=categoryNotFound");
			return;
		}

		$category->delete();
		header("Location: ./?action=listCategories&status=categoryDeleted");
	}


	public static function listCategories(){
		$results = array();
		$data = Category::getListCategories();
		$results['categories'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = "Категории";

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

		require(TEMPLATE_PATH . "/listCategories.php");
	}	
}