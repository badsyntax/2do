
<?php foreach($lists as $list){?>
<h3><?php echo $list['list']->name ?></h3>

<ul id="list-<?php echo $list['list']->id ?>" class="todo-list todo">
	<li class="todo-new">
		<span class="ui-icon ui-icon-plusthick helper-left todo-add"></span>
		New todo
	</li>
	<?php 
	$complete = array();
	foreach($list['todos'] as $todo){
		if ($todo->complete) {
			array_push($complete, $todo);
			continue;
		}?>
		<li id="todo-<?php echo $todo->id ?>" class="helper-clearfix<?php if ($todo->complete){?> todo-complete<?php }?>">
			<label>	<input type="checkbox" /> Check 1 </label>
			<div class="todo-content">
				<?php echo $todo->content ?>
			</div>
		</li>
	<?php }?>
</ul>
<?php } ?>

<?php if (count($complete)){?>

<h3>Completed</h3>

<ul class="todo-list completed">
	<?php foreach($complete as $todo){?>
	<li class="todo-complete" id="todo-<?php echo $todo->id ?>">
		<label>	<input type="checkbox" checked="checked" /> Check 1 </label>
		<div class="todo-content">
			<?php echo $todo->content ?>
		</div>
	</li>
	<?php }?>
</ul>

<?php }?>

<script type="text/javascript">

	managetodos('<?php echo Url::site('todo') ?>');
</script>

