<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8" />
	<title><?php echo htmlspecialchars($title) ?></title>
	<?php echo implode("\n", array_map('HTML::style', $styles)), "\n"?>
	<!--[if IE]>
		<?php echo HTML::script('js/html5.js'), "\n"?>
	<![endif]-->
	<?php echo HTML::style('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/black-tie/jquery-ui.css'), "\n"?>
	<script type="text/javascript" src="/js/jquery.js"></script>
</head>
<body>
	<div id="notification" class="ui-helper-hidden helper-clearfix">
		<span class="ui-icon ui-icon-alert"></span>
		<span class="message"></span>
	</div>

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
		</footer>
	<?php }?>
	
	<?php echo implode("\n", array_map('HTML::script', $scripts)) ?>
</body>
</html>
