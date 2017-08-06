<?php if (!defined("CONSTANT"))  die ('Доступ запрещен!');?>
<?php include "templates/include/header.php" ?>
	<form action="./?action=login" method="post" style="width:50%">
		<input type="hidden" name="login" value="true"/>

		<?php if($results['errorMessage']) { ?>
			<div class="errorMassege"><?php echo $results['errorMessage'] ?></div>
		<?php } ?>

		<ul>
			<li>
				<label for="username">Админ</label>
				<input type="text" id="username" name="username" placeholder="Username" required autofocus maxlength="20"/>
			</li>
			<li>
				<label for="password">Пароль</label>
				<input type="password" id="password" name="password" placeholder="Пароль" required maxlength="20"/>
			</li>
		</ul>
		<div class="buttons">
			<input type="submit" name="login" value="Войти" />
		</div>

	</form>
<?php include "templates/include/footer.php" ?>