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
			<label for="username">Username</label>
			<?php echo form::input('username', @$_POST['username'], array('id'=>'username')) ?>

			<label for="password">Password</label>
			<?php echo form::password('password', '', array('id' => 'password')) ?>
		</p>

		<button type="submit">Sign in</button>
	</fieldset>
</form>

<script type="text/javascript">

	$('input[name=username]').focus();
</script>
