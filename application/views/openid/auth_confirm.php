<div id="content" class="auth-confirm">

	<h3>Confirm</h3>

	<p>
		As this is your first login with this account, you will need
		to agree to the terms and conditions:
	</p>
	<div id="terms" class="ui-corner-all">
	</div>
	<form method="post" id="auth-confirm-form" action="<?php echo URL::site('/auth/confirm'); ?>" class="ui-helper-reset helper-clearfix">
		<input type="hidden" name="openid" value="<?php echo $openid ?>" />
		<input type="checkbox" id="agree" name="agree" /> <label for="agree">I agree to the terms and conditions!</label>
	</form>
</div>

<script type="text/javascript">

	$( '#agree' ).change(function(){

		setTimeout(function(){

			$( '#auth-confirm-form' ).submit();
		}, 300);
	});

	$(function(){

		$( ':checkbox' ).checkbox();
	});
</script>
