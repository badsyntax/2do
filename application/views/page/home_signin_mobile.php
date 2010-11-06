<h2>Sign in</h2>

<div id="home-signin">

	<h4>
		Choose the service you'd like to sign in with:
	</h4>

	<form method="get" action="<?php echo URL::site('auth/openid_try') ?>">
		<div class="helper-cleafix">

			<a rel="external" href="<?php echo URL::site('auth/openid_try') ?>?openid_identity=https://www.google.com/accounts/o8/id" data-role="button">Google</a>

			<a rel="external" href="<?php echo URL::site('auth/openid_try') ?>?openid_identity=https://me.yahoo.com" data-role="button">Yahoo</a>
			
			<button type="button" id="button-twitter" value="oath-twitter">Twitter</button>
			
			<button type="button" id="button-openid" value="openid">OpenID</button>
			
			<a rel="external" href="<?php echo URL::site('sign-in') ?>" data-role="button">Site login</a>
		</div>
	</form>

	<form method="get" id="openid-url-form" action="<?php echo URL::site('auth/openid_try') ?>" class="ui-helper-hidden">
		<div id="openid-login" class="helper-clearfix">

			<p>
				<label for="openid-url">
					Enter your OpenID URL
				</label>
			</p>

			<input name="openid_identity" type="text" id="openid-url" />

			<button type="submit" style="font-size:90%">Go</button>
		</div>
	</form>

</div>

<script type="text/javascript">

	$('#openid-url-form').submit(function(){

		if ( !$( '#openid-url' ).val() ) {

			$.notification('alert', 'Field cannot be empty!' );
			
			$( '#openid-url' ).focus();

			return false;
		}
	});

	$( '#button-openid' ).click(function(){

		$( '#openid-url-form' ).toggle();
		$( '#openid-url' ).focus();

		if ( !$( '#openid-url-form' ).is(':hidden')){

			setTimeout(function(){
				$(window).scrollTop(1000);
			});
		}
	});

</script>
