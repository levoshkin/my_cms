<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<?php include "templates/include/header.php" ?>

		<!--div class="buttons" id="editBottons">
			<input type="submit" name="newCategory" value="Добавить категорию" onclick="location='./?action=newCategory'"/>
		</div-->

	<table id="listCategories">
		<tr>
			<th>Опубликовано</th>
			<th>Название</th>
			<th>Описание</th>
		</tr>

	<?php function drawCat($list, $par=0, $mark=""){ 

		foreach($list as $category) {

			if($category->category_parent_id == $par){ ?>

				<tr onclick="location='./?action=editCategory&amp;categoryId=<?php echo $category->id?>'">
					<td width="10%">
						<?php if ($category->category_publish == 1) { ?>
						<script type="text/javascript">
						$(document).ready(function(){
								$('#category_publish_<?php echo $category->id?>').attr('checked','true');
						});
						</script>
						<?php } ?>
						<input type="checkbox" style="margin-left:30px; width: 20px" name="category_publish" id="category_publish_<?php echo $category->id?>" value="1"/>
					</td>
					<td><?php echo $mark . $category->name?></td>
					<td width="50%"><?php echo $category->description?></td>
				</tr>

		<?php 
				drawCat($list, $category->id, $mark . "|--");
			}
		} 
	}
		drawCat($results['categories']);
	?>
	</table>

	<div style="color:#ccc">
		<?php echo $results['totalRows']?> категори<?php
		if($results['totalRows'] % 100 < 10 || $results['totalRows'] % 100 >= 20){
			switch($results['totalRows'] % 10){
				case 1:
					echo "я";
					break;
				case 2:
				case 3:
				case 4:
					echo "и";
					break;
				default:
					echo "й";
			}
		}else{
			echo "й";
		}
		?> всего.

	</div>

	<!--p><a href="./?action=newArticle">Добавить новую статью</a></p-->

<?php include "templates/include/footer.php" ?>