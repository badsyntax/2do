<div id="task-time-container" class="ui-state-default ui-corner-all ui-helper-hidden-accessible">
	<span class="ui-icon ui-icon-help helper-right"></span>
	<label for="task-time">
		Time:
	</label>
	<input type="text" name="task-time" id="task-time" />

	<div id="task-time-help">
		<h4>Examples:</h4>
		<ul>
			<li>3.5 hrs</li>
			<li>34 min</li>
			<li>1 hr 45 min</li>
		</ul>
	</div>
</div>

<div id="lists">
<?php foreach($lists as $l => $list){?>

	<section id="<?php echo strtolower($list['list']->name) ?>" class="list">
		<h2><?php echo $list['list']->name ?></h2>

		<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b" id="list-<?php echo $list['list']->id ?>" class="task-list task sortable<?php echo in_array($list['list']->id, $hidden_lists) ? ' ui-helper-hidden' : '';?>">
			<li class="task-new">
				<span class="ui-icon ui-icon-plusthick helper-left task-add"></span>
				<div class="task-content">
					New todo
				</div>
			</li>
			<?php 
			foreach($list['tasks'] as $t => $task){?>
				<li id="task-<?php echo $task->id ?>" class="helper-clearfix<?php if ($task->complete){?> task-complete<?php }?><?php if ($t == count($list['tasks'])-1){?> task-last<?php }?>">
					<label>	<input type="checkbox" /> &nbsp;</label>
					<div class="task-content">
						<?php echo $task->content ?>
					</div>
				</li>
			<?php }?>
		</ul>
	</section>

<?php } ?>

<?php if (count($complete)){?>
<section id="completed" class="list">
<?php } else {?>
<section id="completed" class="list helper-hidden">
<?php }?>

<h2>Completed</h2>

<ul id="list-completed" class="task-list completed<?php echo in_array('completed', $hidden_lists) ? ' ui-helper-hidden' : '';?>">
	<?php foreach($complete as $c => $task){?>
		<li class="task-complete<?php if ($c == count($complete)-1){?> task-last<?php }?>" id="task-<?php echo $task->id ?>">
			<label><input type="checkbox" checked="checked" /> &nbsp;</label>
			<div class="task-content">
				<?php echo $task->content ?>
			</div>
		</li>
	<?php }?>
</ul>
</div>
