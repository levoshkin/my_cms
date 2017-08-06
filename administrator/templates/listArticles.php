<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<?php include "templates/include/header.php" ?>

		<table id="listArticles">
		<tr>
			<th>Дата публикации</th>
			<th>Категория</th>
			<th>Статья</th>
		</tr>

		<?php foreach($results['articles'] as $article) { ?>
		<tr style="cursor:pointer" onclick="location='./?action=editArticle&amp;articleId=<?php echo $article->id?>'">
			<td><?php echo iconv("cp1251", "UTF-8", strftime('%d %B %Y', $article->publicationDate))?></td>
			<td><?php foreach($article->category_id as $n => $i){echo $n."<br>";}?></td>
			<td><?php echo $article->title?></td>
		</tr>
		<?php } ?>
	</table>

	<p style="color:#ccc">
		<?php echo ($results['pageHeading']) ? "В категории \"".$results['pageHeading']."\" " : "Всего "?> <?php echo $results['totalRows'];?> стат<?php
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

<?php include "templates/include/footer.php" ?>