<div id="content">

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
				
				<button type="button" name="openid_identity" value="openid">OpenID</button>
	
				<button type="button" name="other" value="Other">Other</button>
			</div>

		</fieldset>
	</form>

	<form method="get" id="openid-url-form" action="<?php echo URL::site('auth/try') ?>">
		<fieldset>
			<div id="openid-login" class="helper-clearfix">
		
				<p>
					<label for="openid-url">
						Please enter your OpenID url below:
					</label>
				</p>

				<input name="openid_identity" type="text" id="openid-url" />

				<button type="submit" style="font-size:90%">Go</button>
			</div>
		</fieldset>
	</form>

</div>

<script type="text/javascript">
	$('#openid-url-form').submit(function(){

		if ( !$( '#openid-url' ).val() ) {

			alert('Field cannot be empty!');
			
			$( '#openid-url' ).focus();

			return false;
		}
	});
</script>
