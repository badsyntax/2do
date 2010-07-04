<h3>Sign in</h3>

<br />

<p>
	Choose the service you'd like to sign in with:
</p>

<form method="get" action="<?php echo URL::site('auth/try') ?>">
	<fieldset>

		<div class="helper-cleafix">

			<button type="submit" name="openid_identity" value="https://www.google.com/accounts/o8/id">
				Google
			</button>

			<button type="submit" name="openid_identity" value="https://me.yahoo.com">Yahoo</button>
			
			<button type="button" id="button-openid" value="openid">OpenID</button>
			
			<button type="button" id="button-sitelogin" value="openid">Site login</button>
		</div>

	</fieldset>
</form>

<form method="get" id="openid-url-form" action="<?php echo URL::site('auth/try') ?>" class="ui-helper-hidden">
	<fieldset>
		<div id="openid-login" class="helper-clearfix">
	
			<p>
				<label for="openid-url">
					Enter your OpenID URL:
				</label>
			</p>

			<input name="openid_identity" type="text" id="openid-url" />

			<button type="submit" style="font-size:90%">Go</button>
		</div>
	</fieldset>
</form>

<div id="toggle-sitelogin" style="margin-top: 3em" class="ui-helper-hidden">
	<p>If you don't already have a site account, you can <a href="<?php echo URL::site('sign-up'); ?>">sign up</a> for one.</p>
	<?php echo new View('auth/signin_form') ?>
</div>

<script type="text/javascript">
	$('#openid-url-form').submit(function(){

		if ( !$( '#openid-url' ).val() ) {

			alert('Field cannot be empty!');
			
			$( '#openid-url' ).focus();

			return false;
		}
	});

	$( '#button-openid' ).click(function(){

		$( '#toggle-sitelogin' ).hide();
		$( '#openid-url-form' ).toggle();
		$( '#openid-url' ).focus();

	});

	$( '#button-sitelogin' ).click(function(){

		$( '#openid-url-form' ).hide();
		$( '#toggle-sitelogin' ).toggle();
		$( '#email' ).focus();
	});
</script>
