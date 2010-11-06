<!DOCTYPE html>
<html lang="en" class="no-js" dir="ltr">
        <head>
        <meta charset="utf-8" /> 
        <title><?php echo htmlspecialchars($title) ?></title> 
	<?php echo implode("\n", array_map('HTML::style', $styles)), "\n";?>
	<?php echo implode("\n", array_map('HTML::script', $scripts)) ?>
</head>
<body>

<div data-role="page">

        <div data-role="header" data-position="inline" data-nobackbtn="true">
		<?php echo View::factory('page/units/nav_mobile') ?>
        </div><!-- /header -->

        <div data-role="content">
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
		<?php echo $content ?>
        </div><!-- /content -->

        <div data-role="footer">
		<div data-role="controlgroup" data-type="horizontal">
			<?php echo View::factory('page/units/footer_mobile') ?>
		</div>
        </div><!-- /footer -->

</div>

<!-- {execution_time} -->
</body>
</html>
