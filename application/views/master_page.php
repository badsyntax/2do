<!doctype html>
<html lang="en" class="no-js" dir="ltr">
<head>
	<meta charset="utf-8" />
      	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo htmlspecialchars($title) ?></title>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
	<?php echo implode("\n", array_map('HTML::style', $styles)), "\n";?>
	<link rel="stylesheet" media="handheld" href="<?php echo URL::site('css/handheld.css')?>">
	<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
	<link rel="shortcut icon" href="/img/favicon.ico">
	<!--[if IE]>
		<?php echo HTML::script('js/html5.js'), "\n"?>
	<![endif]-->
	<?php echo HTML::style('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/black-tie/jquery-ui.css'), "\n"?>
</head>
<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->

	<div id="notification" class="ui-helper-hidden helper-clearfix">
		<span class="ui-icon ui-icon-alert"></span>
		<span class="message">
		<?php
			$notification = Session::instance()->get('notification', NULL);
			Session::instance()->delete('notification');
			echo $notification;
		?>
		</span>
	</div>

	<header>
		<?php echo View::factory('page/units/nav') ?>
		<div id="logo">
			<a href="/">2do</a>
		</div>
	</header>

	<div class="wrapper">
		<div id="content">	
			<?php echo $content ?>
		</div>
	</div>

	<?php echo View::factory('page/units/footer') ?>

	<?php echo implode("\n", array_map('HTML::script', $scripts)) ?>

	<!-- {execution_time} -->
</body>
</html>
