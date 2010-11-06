<footer>

	<a href="http://m.dev.2do.me.uk">mobile</a>
	|
	<a href="http://dev.2do.me.uk">classic</a>

	<?php if (Kohana::$environment === Kohana::DEVELOPMENT and $_SERVER['HTTP_HOST'] !== 'm.dev.2do.me.uk'){?>
		<div id="application-profiler">
			{profiler}
			{execution_time}
		</div>
	<?php }?>

</footer>

