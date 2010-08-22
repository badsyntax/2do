<footer>

	<?php if (Kohana::$environment === Kohana::DEVELOPMENT){?>
		<div id="application-profiler">
			<?php echo View::factory('profiler/stats') ?>
		</div>
	<?php }?>

</footer>

