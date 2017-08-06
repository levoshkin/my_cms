<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<!DOCTYPE html>
<html lang="russian">
<head>
	<title><?php echo htmlspecialchars($results['pageTitle']);?></title>
	<link rel="stylesheet" type="text/css" href="templates/style.css">
	<script type="text/javascript" src="../lib/jquery/jquery.js"></script>
  	<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<body>
	<div id="container" style="min-height: 90vh;">
		<a href="."><img id="logo" src="templates/images/logo.png" alt="<?php echo SITE_NAME;?>"></a>

		<div id="lift_position" style="float:left;margin-right:20px;font-size:14px">
		<h3 style="color:#fff">Категории</h3>
		<ul style="color:#fff">
			<?php
				function selectCat($list, $par=0, $mark=""){
					foreach($list as $cat){
						if($cat->category_parent_id == $par && $cat->id != $id && $cat->category_publish != 0){ ?>
							<li onclick=""><a style="text-decoration:none;color:#fff" href="./?action=archive&amp;category=<?php echo $cat->id?>"><?php echo $mark . $cat->name?></li>
			<?php
							selectCat($list, $cat->id, $mark . "--");
						}
					}
				}

				selectCat($results['categories']);
			?>	
			<script type="text/javascript">



			</script>
			<li><a href="./?action=archive" style="text-decoration:none;color:#fff">Все статьи</a></li>				
		</ul>

		</div>
		<div>

