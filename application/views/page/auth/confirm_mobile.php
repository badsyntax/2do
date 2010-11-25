<div data-role="header" data-position="inline" data-nobackbtn="true">
<h1>Sign up</h1>
</div>
        <div data-role="content"  data-theme="c">

<p>
	As this is your first login with this account, you will need
	to agree to the terms and conditions:
</p>
<div id="terms" class="ui-corner-all">
	Terms go here.
</div>
<form method="post" id="auth-confirm-form" action="<?php echo URL::site('/auth/confirm'); ?>" class="ui-helper-reset helper-clearfix">
	<input type="hidden" name="id" value="<?php echo $id ?>" />
	<button type="submit" name="agree" id="agree" value="yup, i agree">Sign up &raquo;</button>
</form>
</div>
