<nav class="helper-clearfix">
	
	<?php if (Auth::instance()->logged_in()) {?>
		
		<a href="<?php echo URL::site('reports') ?>">Reports</a>

		<a href="<?php echo URL::site('sign-out') ?>">Sign out</a>
	<?php } else {?>
		<a href="<?php echo URL::site('help') ?>">help</a>

		<a href="<?php echo URL::site('info') ?>">info</a>
	<?php } ?>
</nav>
