<div id="content">

	<h3>Sign in</h3>

	<br />

	<p>
		Choose a service you'd like to sign in with:
	</p>

	<form method="get" action="<?php echo Url::site('auth/try') ?>">
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
			 <input type="hidden" name="action" value="verify" />

			<button type="submit" name="openid_identity" value="https://www.google.com/accounts/o8/id">
				Google
			</button>

			<button type="submit" name="openid_identity" value="https://me.yahoo.com">Yahoo</button>

			<button type="submit" name="openid_identity" value="openid">OpenID</button>

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
