
<?php foreach($lists as $list){?>

	<section id="<?php echo strtolower($list['list']->name) ?>" class="list">
		<h3><?php echo $list['list']->name ?></h3>

		<ul id="list-<?php echo $list['list']->id ?>" class="todo-list todo">
			<li class="todo-new">
				<span class="ui-icon ui-icon-plusthick helper-left todo-add"></span>
				New todo
			</li>
			<?php 
			foreach($list['todos'] as $todo){?>
				<li id="todo-<?php echo $todo->id ?>" class="helper-clearfix<?php if ($todo->complete){?> todo-complete<?php }?>">
					<label>	<input type="checkbox" /> Check 1 </label>
					<div class="todo-content">
						<?php echo $todo->content ?>
					</div>
					</li>
			<?php }?>
		</ul>
	</section>

<?php } ?>

<?php if (count($complete)){?>
<section id="list-completed" class="list">
<?php } else {?>
<section id="list-completed" class="list helper-hidden">
<?php }?>

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

<script type="text/javascript">

	$('#content').listeditor({
		baseurl: '<?php echo Url::site('todo') ?>'
	});
</script>

