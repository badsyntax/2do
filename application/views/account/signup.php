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

<form method="post" action="<?php echo Url::site('signup') ?>">
	<fieldset>
		<p>
			<label for="email">Email</label>
			<input type="text" name="email" />
			
			<label for="username">Username</label>
			<input type="text" name="username" />

			<label for="password">Password</label>
			<input type="password" name="password" />

			<label for="password_confirm">Confirm password</label>
			<input type="pasword" name="password_confirm" />
		</p>

		<button type="submit">Signup</button>
	</fieldset>
</form>