<div data-role="header" data-position="inline" data-nobackbtn="false">
	<h2>Sign in</h2>
</div>
<div data-role="content" data-theme="c">

<p>
	<a href="<?php echo URL::site('sign-up')?>">Sign up</a> for a new account. 
</p>

<form method="post" action="<?php echo Url::site('sign-in') ?>">
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

		<div data-role="fieldcontain">

			<label for="email">Email</label>
			<?php echo form::input('username', @$_POST['username'], array('id'=>'email')) ?>

			<label for="password">Password</label>
			<?php echo form::password('password', '', array('id' => 'password')) ?>
</div>

		<button type="submit">Sign in</button>
	</fieldset>
</form>
</div>


<script type="text/javascript">

	var elem = 
		$.trim( $( 'input[name=username]' ).val() ) ? 
		$( 'input[name=password]' ) : 
		$( 'input[name=username]' );

	elem.focus();
</script>
