<nav class="helper-clearfix">
	
	<?php if (Auth::instance()->logged_in()) {?>
	
		<a href="<?php echo URL::site('profile') ?>">Profile</a>

		<a href="<?php echo URL::site('sign-out') ?>">Sign out</a>
	<?php }?>
</nav>
