<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<?php include "templates/include/header.php"?>

		<!--div class="buttons" id="editBottons">
			<input type="submit" form="edit" name="saveChange" value="Сохранить" />
			<input type="submit" form="edit" formnovalidate name="cancel" value="Отмена" />
		</div-->


	<form action="./?action=<?php echo $results['formAction']?>" name="edit" id="edit" method="POST">
		<input type="hidden" name="categoryId" value="<?php echo $results['category']->id ?>"/>

	<?php if(isset($results['errorMessage'])) { ?>
		<div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>

		<ul>
			<li>
				<label for="category_publish">Публикация</label>
				<script type="text/javascript">
				$(function(){
					if('<?php echo $results['category']->name?>' && '<?php echo $results['category']->category_publish?>' != '1'){
						$('#category_publish').removeAttr('checked');
					}
				});
				</script>
				<input type="checkbox" checked name="category_publish" id="category_publish" value="1"/>
			</li>
			<li>
				<label for="name">Название</label>
				<input type="text" name="name" id="name" required autofocus maxlength="255" placeholder="Название категории" value="<?php echo htmlspecialchars($results['category']->name);?>" />
			</li>
			<li>
				<label for="description">Описание</label>
				<textarea name="description" id="description" maxlength="1000" placeholder="Описание" style="height: 5em;"><?php echo htmlspecialchars($results['category']->description);?></textarea>
			</li>
			<li>
				<label for="category_parent_id">Родительская категория</label>
				<select name="category_parent_id"  id="category_parent_id">
				<option value="0">--категория--</option>
				<?php
					function selectCat($list, $par=0, $mark="--", $id, $pid){
						foreach($list as $cat){
							if($cat->category_parent_id == $par && $cat->id != $id){ ?>
								<option value="<?php echo $cat->id?>"<?php echo ($cat->id == $pid) ? " selected" : "";?>><?php echo $mark . $cat->name?></option>
				<?php
								selectCat($list, $cat->id, $mark . "--", $id, $pid);
							}
						}
					}

					selectCat($results['categories'], $par=0, $mark="--", $results['category']->id, $results['category']->category_parent_id);
				?>
				</select>
			</li>
		</ul>


	</form>



	<?php if($results['category']->id) { ?>
		<!--a href="./?action=deleteCategory&amp;categoryId=<?php echo $results['category']->id?>" 
			onclick="return confirm('Удалить категорию?')">Удалить категорию</a-->
	<?php } ?>

<?php include "templates/include/footer.php"?>