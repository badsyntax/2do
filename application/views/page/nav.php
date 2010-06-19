<nav class="helper-clearfix">
	<ul>
		<li><a href="<?php echo URL::site() ?>">Home</a></li>
		<li><a href="<?php echo URL::site('profile') ?>">Profile</a></li>
		<?php if (Auth::instance()->logged_in()) {?>
			<li><a href="<?php echo URL::site('sign-out') ?>">Sign out</a></li>
		<?php } else { ?>
			<li><a href="<?php echo URL::site('sign-in') ?>">Sign in</a></li>
		<?php }?>
	</ul>
</nav>
