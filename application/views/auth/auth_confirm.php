<h3>Confirm</h3>

<p>
	As this is your first login with this account, you will need
	to agree to the terms and conditions:
</p>
<div id="terms" class="ui-corner-all">
	At this time we cannot guarentee what will happen with your data, and we cannot be help responsible
	for any sort of data loss. The application is still being developed so don't expect everything to work!
</div>
<form method="post" id="auth-confirm-form" action="<?php echo URL::site('/auth/confirm'); ?>" class="ui-helper-reset helper-clearfix">
	<input type="hidden" name="openid" value="<?php echo $openid ?>" />
	<input type="checkbox" id="agree" name="agree" /> <label for="agree">I agree to the terms and conditions!</label>
</form>

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
