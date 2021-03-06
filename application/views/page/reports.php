<h2>Reports</h2>

<p>
	<strong>Total tasks:</strong> <?php echo $total_tasks ?>

</p>

<p>
	<strong>Total completed tasks:</strong> <?php echo $total_completed ?>

</p>

<br />

<h2>Time logs</h2>

<p>Get a break-down of complete task times per day. Start by selecting the day:</p>
<div id="datepicker" style="font-size:80%"></div>

<script type="text/javascript">

	$(function(){

		$("#datepicker").datepicker({
			showButtonPanel: false,
			onSelect: function(dateval, ui){
				window.location = '<?php echo URL::site('reports/time') ?>/' + dateval;
			}
		});

	});


</script>

<br /><br />

<h2>Download</h2>

<br />

<p>You can download your tasks in csv format.</p>

<form method="get" action="<?php echo URL::site('reports/download') ?>">
	<button type="submit">
		Download tasks
	</button>
</form>
