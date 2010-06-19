<nav class="helper-clearfix">
	<ul class="helper-left">
		<li><a href="<?php echo URL::site() ?>">Home</a></li>
		<li><a href="<?php echo URL::site('lists') ?>">Todo</a></li>
		<li><a href="<?php echo URL::site('profile') ?>">Profile</a></li>
		<?php if (Auth::instance()->logged_in()) {?>
			<li><a href="<?php echo URL::site('signout') ?>">Sign out</a></li>
		<?php } else { ?>
			<li><a href="<?php echo URL::site('signin') ?>">Sign in</a></li>
		<?php }?>
	</ul>
</nav>
