<a href="http://dev.2do.me.uk" data-role="button">classic</a>
<a href="<?php echo URL::site('feedback') ?>" data-role="button" data-transition="fade">feedback</a>
<?php if (Auth::instance()->logged_in()) {?>
	<a href="<?php echo URL::site('sign-out') ?>" data-role="button">sign out</a>
<?php }?>
