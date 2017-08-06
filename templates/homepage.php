<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<?php include "templates/include/header.php"?>

	<ul id="headlines">

	<?php foreach($results['articles'] as $article){ ?>

		<li>
			<h2>
				<span class="pubDate"><?php echo iconv("cp1251", "UTF-8", strftime('%d %B %Y', $article->publicationDate));?></span>
				<a href="./?action=viewArticle&amp;articleId=<?php echo $article->id?>"><?php echo htmlspecialchars($article->title)?></a>
			</h2>
			<p class="summary" style="color:#ccc"><?php echo $article->summary?></p>
		</li>
	<?php } ?>
	</ul>
		<a href="./?action=archive">Все статьи</a>

<?php include "templates/include/footer.php";?>
