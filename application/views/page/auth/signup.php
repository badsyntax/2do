<form method="post" action="<?php echo Url::site('sign-up') ?>" rel="external">
	<fieldset>

		<?php if (isset($errors)) {?>
			<ul class="errors">
			<?php foreach($errors as $field => $error){?>
				<li><?php echo $error ?></li>
			<?php }?>
			</ul>
		<?php }?>

		<p>
			<label for="email">Email</label>
			<input type="text" name="email" />
		
			<!--
			<label for="username">Username</label>
			<input type="text" name="username" />
			-->

			<label for="password">Password</label>
			<input type="password" name="password" />

			<label for="password_confirm">Confirm password</label>
			<input type="password" name="password_confirm" />
		</p>

		<button type="submit">Signup</button>
	</fieldset>
</form>
