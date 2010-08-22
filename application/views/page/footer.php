<footer>

	<?php if (Kohana::$environment === Kohana::DEVELOPMENT){?>

		<a href="#application-profiler">profiler</a>

		<div id="application-profiler">
			<?php echo View::factory('profiler/stats') ?>
		</div>
	<?php }?>

</footer>

