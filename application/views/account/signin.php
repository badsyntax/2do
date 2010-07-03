<div id="content">

	<h3>Sign in</h3>

	<br />

	<p>
		Choose the service you'd like to sign in with:
	</p>

	<form method="get" action="<?php echo Url::site('auth/try') ?>">
		<fieldset>

			<button type="submit" name="openid_identity" value="https://www.google.com/accounts/o8/id">
				Google
			</button>

			<button type="submit" name="openid_identity" value="https://me.yahoo.com">Yahoo</button>
			
			<button type="submit" name="openid_identity" value="openid">OpenID</button>

		</fieldset>
	</form>
</div>
