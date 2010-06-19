<!DOCTYPE html>
<html lang="en" dir="ltr">
<head profile="http://gmpg.org/xfn/11">
	<title><?php echo $title ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<?php foreach ($styles as $file => $type) echo HTML::style($file, array('media' => $type)), "\n" ?>
	<?php foreach ($scripts as $file) echo HTML::script($file), "\n" ?>
	<!--[if IE]><?php echo HTML::script('media/js/html5.js')?><![endif]-->
</head>
<body>

	<div id="wrapper">

		<?php // echo View::factory('page/nav') ?>
		
		<!--<header>
			<h1><?php echo $title ?></h2>
		</header>-->

		<section id="content">
			<?php echo $content ?>
		</section>
	</div>
</body>
</html>
