<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<?php include "include/header.php";?>
	<h1 style="width:75%"><?php echo htmlspecialchars($results['article']->title);?></h1>
	<!--div style="width:75%; font-style:italic; color:#ccc"><?php echo htmlspecialchars($results['article']->summary);?></div-->
	<div style="width:75%; color:#ccc"><?php echo $results['article']->content;?></div>
	<p class="pubDate">Опубликовано <?php echo iconv("cp1251", "UTF-8", strftime('%d %B %Y', $results['article']->publicationDate));?></p>

	<p><a href="./">На главную</a></p>
<?php include "templates/include/footer.php";?>