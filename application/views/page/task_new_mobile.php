<!DOCTYPE html>
<html lang="en" class="no-js" dir="ltr">
        <head>
        <meta charset="utf-8" /> 
        <title>2do</title> 
	<link type="text/css" href="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css" rel="stylesheet" />
<link type="text/css" href="/media/css/main_mobile.css" rel="stylesheet" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
<script type="text/javascript" src="/media/js/jquery.mobile.js"></script></head>
<body>

<div data-role="page">
	
		<div data-role="header" data-theme="d" data-position="inline">
			<h1>New todo</h1>
		</div>

		<div data-role="content" data-theme="c">
			<form method="post" action="<?php echo URL::site('task/save');?>">
				<input type="hidden" name="list" value="1" />
				<textarea name="task" style="height:100px"></textarea>
				<button type="submit" data-role"button" data-theme="b">
					Save
				</button>
				<a href="/" data-role="button" data-theme="c">Cancel</a>    
			</form>
		</div>

	</div>


</body>
</html>
