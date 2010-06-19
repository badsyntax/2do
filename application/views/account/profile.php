<h3>Profile</h3>

<br />
<form method="post" action="<?php echo Url::site('profile') ?>">
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
			<?php echo form::input('email', @$_POST['email'] ? $_POST['email'] : $user->email, array('id' => 'email')) ?>
			
			<label for="username">Username</label>
			<?php echo form::input('username', @$_POST['username'] ? $_POST['username'] : $user->username, array('id' => 'username')) ?>

			<label for="password">Password</label>
			<?php echo form::password('password', '', array('id' => 'password_confirm')) ?>

			<label for="password_confirm">Confirm password</label>
			<?php echo form::password('password_confirm', '', array('id' => 'password_confirm')) ?>
		</p>

		<button type="submit">Update</button>
	</fieldset>
</form>
