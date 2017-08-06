<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<!DOCTYPE html>
<html lang="russian">
<head>
	<title><?php echo htmlspecialchars($results['pageTitle']);?></title>
	<link rel="stylesheet" type="text/css" href="templates/style.css">
	<script type="text/javascript" src="../lib/jquery/jquery.js"></script>
  <script src="tinymce/js/tinymce/tinymce.js"></script>
  <script>tinymce.init({
  						selector:"textarea#content, #summary",
  						theme:"modern", 
  						language:"ru",
  						toolbar:"| undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages |",
       					plugins : "jbimages, code, pagebreak,link,table,save,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,template,image imagetools",
						relative_urls: false,
						//toolbar:"jbimages",
  						file_browser_callback_types: 'file image media',
						file_browser_callback: function(field_name, url, type, win) {win.document.getElementById(field_name).value = 'my browser value';}

 					});
  </script>
	<script type="text/javascript">
		$(document).ready(function(){

			function sticky(){
				var y = $(window).scrollTop();
				if(y > (60)){
					$('#editBottons').css({
						'position': 'fixed',
						'top': '60px',
						'margin': '0 0 0 0',
						'left': ($(window).width() - $('#editBottons').width())/2
					});

					$('#navbuttons').css({
						'top': '10px',
						'margin': '0 0 0 0',
					});

				}else{
					$('#editBottons').removeAttr('style');
					$('#navbuttons').removeAttr('style');
				}
			}

			$(window).scroll(sticky);
			$(window).resize(sticky);
		});
	</script>

</head>
<body>
	<?php if((isset($_GET['action']) && isset($_SESSION['username'])) || isset($_SESSION['username'])){ ?>
	<div class="navbuttons" id="navbuttons">
		<input type="submit" onclick="location='./'" value="Панель"/>
		<input type="submit" onclick="location='./?action=listArticles'" value="Статьи"/>
		<input type="submit" onclick="location='./?action=listCategories'" value="Категории"/>
		<?php if ($_GET['action'] == 'newCategory' || $_GET['action'] == 'editCategory' || $_GET['action'] == 'newArticle' || $_GET['action'] == 'editArticle') { ?>
			<?php if($results['category']->id) { ?>
				<input type="submit" onclick="location='?action=deleteCategory&amp;categoryId=<?php echo $results['category']->id?>'" value="Удалить"/>
			<?php } ?>
			<?php if($results['article']->id) { ?>
				<input type="submit" onclick="location='?action=deleteArticle&amp;articleId=<?php echo $results['article']->id?>';return confirm('Удалить статью?')" value="Удалить"/>
			<?php } ?>
			<input style="width: 12em;" type="submit" form="edit" name="saveChangeAndClose" value="Сохранить и закрыть" />
			<input type="submit" form="edit" name="saveChange" value="Сохранить"/>
			<input type="submit" form="edit" formnovalidate name="cancel" value="Отмена" />
		<?php } ?>
		<?php if ($_GET['action'] == 'listCategories') { ?>
			<input type="submit" name="newCategory" value="Создать" onclick="location='./?action=newCategory'"/>
		<?php } ?>
		<?php if ($_GET['action'] == 'listArticles') { ?>
			<input type="submit" name="newArticle" value="Создать" onclick="location='./?action=newArticle'"/>
		<?php } ?>
	</div>
	<?php } ?>
	<div id="bg">
	<div id="container">
		<a href=".."  target="_blank"><img id="logo" src="templates/images/logo.png" alt="<?php echo SITE_NAME;?>"></a>
		
	<div class="adminHeader">
		<h2><?php echo SITE_NAME?> | Админпанель</h2>
		<p>Вы вошли как <b><?php echo htmlspecialchars($_SESSION['username'])?></b>. <a href="./?action=logout">Выйти</a></p>
	</div>

	<h1><?php echo $results['pageTitle']?></h1>

	<?php if(isset($results['errorMessage'])) { ?>
		<div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>

	<?php if(isset($results['statusMessage'])) { ?>
		<div class="statusMessage"><?php echo $results['statusMessage'] ?></div>

	<?php } ?>

