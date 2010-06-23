<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<title><?php echo $title ?></title>
	<meta charset="utf-8" />
	<?php foreach ($styles as $file => $type) echo HTML::style($file, array('media' => $type)), "\n" ?>
	<?php foreach ($scripts as $file) echo HTML::script($file), "\n" ?>
	<!--[if IE]><?php echo HTML::script('media/js/html5.js')?><![endif]-->
</head>
<body>
	<header>
		<?php echo View::factory('page/nav') ?>
		<div id="logo">
			<a href="/">2do</a>
		</div>
	</header>
	<div class="wrapper">
		

		<div id="content">
			<?php echo $content ?>
		</div>
	</div>

	<?php if (Request::instance()->uri() == 'home/index' and !Auth::instance()->logged_in()){?>

		<footer>
			<a href="<?php echo URL::site('info') ?>">what?</a>
		</footer>
	<?php }?>

</body>
</html>
