<nav class="helper-clearfix">

	<ul>	
	<?php if (Auth::instance()->logged_in()) {?>
		<li>
			<a id="projects-link" href="<?php echo URL::site('projects') ?>" class="ui-state-default">
				<span class="ui-icon ui-icon-triangle-1-s helper-right"></span>
				Projects
			</a>
			<ul class="ui-helper-hidden-accessible">
				<li><a href="#">Project 1</a></li>
				<li><a href="#">Project 2</a></li>
				<li><a href="#">New project</a></li>
			</ul>
		</li>
		<li>
			<a href="<?php echo URL::site('reports') ?>">Reports</a>
		</li>
		<li>
			<a href="<?php echo URL::site('sign-out') ?>">Sign out</a>
		</li>
	<?php } else {?>
		<li>
			<a href="<?php echo URL::site('help') ?>">help</a>
		</li>
		<li>
			<a href="<?php echo URL::site('info') ?>">info</a>
		</li>
	<?php } ?>
	</ul>
</nav>
