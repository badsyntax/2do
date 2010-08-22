<nav class="helper-clearfix">

	<ul>	
	<?php if (Auth::instance()->logged_in()) {?>
		<li>
			<a id="projects-link" href="<?php echo URL::site('projects') ?>" class="ui-state-default">
				projects
			</a>
			|
			<a href="<?php echo URL::site('reports') ?>">reports</a>
			|
			<a href="<?php echo URL::site('sign-out') ?>">sign out</a>
			|
			<a href="<?php echo URL::site('contact')?>">contact</a>
		</li>
	<?php } else {?>
		<li>
			<a href="<?php echo URL::site('info') ?>">info</a>
			|
			<a href="<?php echo URL::site('contact')?>">contact</a>
		</li>
	<?php } ?>
	</ul>
</nav>
