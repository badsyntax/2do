<nav class="helper-clearfix">

	<ul>	
	<?php if (Auth::instance()->logged_in()) {?>
		<li class="ui-helper-hidden">
			<a id="projects-link" href="<?php echo URL::site('projects') ?>" class="ui-state-default">
				<span class="ui-icon ui-icon-triangle-1-s helper-right"></span>
				Projects
			</a>

			<ul class="ui-helper-hidden-accessible">
				<?php if (isset($projects)){
					foreach($projects as $project){?>
						<li><a href="#"><?php echo $project->name ?></a></li>
					<?php }
				} ?>
				<li id="projects-new">
					<a href="#">New project</a>
					<input type="text" name="project-name" />
				</li>
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
			<a href="<?php echo URL::site('info') ?>">info</a>
		</li>
	<?php } ?>
	</ul>
</nav>
