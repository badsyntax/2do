<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<title><?php echo $title ?></title>
	<meta charset="utf-8" />
	<?php foreach ($styles as $file => $type) echo HTML::style($file, array('media' => $type)), "\n\t" ?>
<!--[if IE]><?php echo HTML::script('/js/html5.js')?><![endif]-->
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
	
	<?php foreach ($scripts as $file) echo HTML::script($file), "\n" ?>
</body>
</html>
