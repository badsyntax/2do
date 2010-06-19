<?php if (isset($errors)) {?>
	<p class="error">
		Errors:
	</p>
	<ul>
	<?php foreach($errors as $field => $error){?>
		<li><?php echo $error ?></li>
	<?php }?>
	</ul>
<?php }?>

<form method="post" action="<?php echo Url::site('signin') ?>">
	<fieldset>
		<p>
			<label for="email">Email</label>
			<input type="text" name="email" />

			<label for="password">Password</label>
			<input type="password" name="password" />
		</p>

		<button type="submit">Signin</button>
	</fieldset>
</form>
