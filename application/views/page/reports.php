<h3>Reports</h3>

<br />

<p>
	<strong>Total tasks:</strong> <?php echo $total_tasks ?>

</p>

<p>
	<strong>Total completed tasks:</strong> <?php echo $total_completed ?>

</p>

<br />

<h3>Download</h3>

<br />

<form method="get" action="<?php echo URL::site('reports/download') ?>">
	<button type="submit">
		Download tasks
	</button>
</form>
