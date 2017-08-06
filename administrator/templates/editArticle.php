<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<?php include "templates/include/header.php"?>

	<form action="./?action=<?php echo $results['formAction']?>" name="edit" id="edit" method="POST">
		<input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>"/>

	<?php if(isset($results['errorMessage'])) { ?>
		<div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>

		<ul>
			<li>
				<label for="title">Название</label>
				<input type="text" name="title" id="title" required autofocus maxlength="255" placeholder="Название статьи" value="<?php echo htmlspecialchars($results['article']->title);?>" />
			</li>
			<li>
				<label for="summary">Краткое описание</label><br /><br />
				<textarea name="summary" id="summary" maxlength="1000" placeholder="Краткое описание" style="height: 15em;"><?php echo $results['article']->summary?></textarea>
			</li>
			<li>
				<label for="content">Содержание</label><br /><br />
				<textarea name="content" id="content" maxlength="100000" placeholder="Содержание" style="height: 30em;"><?php echo $results['article']->content?></textarea>
			</li>
			<li>
				<label for="category_id">Выбор категории</label>
				<select name="category_id[]" id="category_id" size="10" required multiple style="width: 300px">
				<option>-категория-</option>
				<?php
					function selectCat($list, $par=0, $mark="|--", $cid){
						foreach($list as $cat){
							if($cat->category_parent_id == $par){ ?>
								<option value="<?php echo $cat->id?>"<?php foreach($cid as $i){ echo ($cat->id == $i) ? " selected" : "";}?>><?php echo $mark . $cat->name?></option>
				<?php
								selectCat($list, $cat->id, $mark . "|--", $cid);
							}
						}
					}

					selectCat($results['categories'], $par=0, $mark="|--", $results['article']->category_id);
				?>					
				</select>
			</li>
			<li>
				<label for="publicationDate">Дата публикации</label>
				<input type="date" name="publicationDate" id="publicationDate" style="width: 300px" required maxlength="10" placeholder="Дата публикации" value="<?php echo $results['article']->publicationDate ? date('Y-m-d', $results['article']->publicationDate) : ''?>" />
			</li>
		</ul>

	</form>

	<?php if($results['article']->id) { ?>
		<!--a href="./?action=deleteArticle&amp;articleId=<?php echo $results['article']->id?>" 
			onclick="return confirm('Удалить статью?')">Удалить статью</a-->
	<?php } ?>

<?php include "templates/include/footer.php"?>