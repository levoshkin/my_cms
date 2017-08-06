<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<?php include "templates/include/header.php";?>

	<h1><?php echo ($results['pageHeading'] != "") ? $results['pageHeading'] : "Все статьи"?></h1>
	<p style="color:#ccc">
		<?php echo ($results['pageHeading']) ? "В категории \"".$results['pageHeading']."\" " : "Всего "?> <?php echo ($results['totalRows']!=0)?$results['totalRows']:"нет";?> стат<?php
		if($results['totalRows'] % 100 < 10 || $results['totalRows'] % 100 >= 20){
			switch($results['totalRows'] % 10){
				case 1:
					echo "ья";
					break;
				case 2:
				case 3:
				case 4:
					echo "ьи";
					break;
				default:
					echo "ей";
			}
		}else{
			echo "ей";
		}
		?>.
	</p>
	<ul id="headline" class="archive"  style="color:#fff; list-style:none">
		<?php foreach($results['articles'] as $article){ ?>
			<li>
			<h2>
				<span class="pubDate"><?php echo iconv("cp1251", "UTF-8", strftime('%d %B %Y', $article->publicationDate));?></span>
				<a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><?php echo htmlspecialchars($article->title)?></a>
			</h2>
			<p  class="summary" style="color:#ccc"><?php echo $article->summary?></p>
			</li>
		<?php } ?>
	</ul>
	<p><a href="./">На главную</a></p>
<?php include "templates/include/footer.php";?>