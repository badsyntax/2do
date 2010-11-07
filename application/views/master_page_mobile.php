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

		<?php echo $content ?>

        <div data-role="footer">
		<div data-role="controlgroup" data-type="horizontal">
			<?php echo View::factory('page/units/footer_mobile') ?>
		</div>
        </div><!-- /footer -->

</div>

<!-- {execution_time} -->
</body>
</html>
