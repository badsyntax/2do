<div id="content">
	<h2>PHP OpenID Authentication</h2>
	<p>
		This example consumer uses the <a href="http://github.com/openid/php-openid">PHP OpenID</a> 
		library. It just verifies that the URL that you enter is your identity URL.
	</p>

	<div id="verify-form">
		<form method="get" action="<?php echo URL::site('auth/try_auth') ?>">
			Identity&nbsp;URL:
			<input type="hidden" name="action" value="verify" />
			<input type="text" name="openid_identity" value="" />

			<p>Optionally, request these PAPE policies:</p>
			<p>
				<?php foreach ($pape_policy_uris as $i => $uri) {
					print "<input type=\"checkbox\" class=\"plain\" name=\"policies[]\" value=\"$uri\" />";
					print "$uri<br/>";
				}?>
			</p>
			<input type="submit" value="Verify" />
		</form>
	</div>
</div>
