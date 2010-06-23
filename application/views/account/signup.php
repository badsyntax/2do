<div id="content">
	<h3>Sign up</h3>

	<br />
	<form method="post" action="<?php echo Url::site('sign-up') ?>">
		<fieldset>

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
</div>
