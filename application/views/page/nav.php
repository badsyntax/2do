<nav class="helper-clearfix">

	<ul>	
	<?php if (Auth::instance()->logged_in()) {?>
		<li>
			<a id="projects-link" href="<?php echo URL::site('projects') ?>" class="ui-state-default">
				Projects
			</a>
			|
			<a href="<?php echo URL::site('reports') ?>">Reports</a>
			|
			<a href="<?php echo URL::site('sign-out') ?>">Sign out</a>
		</li>
	<?php } else {?>
		<li>
			<a href="<?php echo URL::site('info') ?>">info</a>
		</li>
	<?php } ?>
	</ul>
</nav>
