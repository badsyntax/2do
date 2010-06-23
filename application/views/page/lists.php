<div id="content" style="padding-bottom:0">

	<?php foreach($lists as $list){?>

		<section id="<?php echo strtolower($list['list']->name) ?>" class="list">
			<h3><?php echo $list['list']->name ?></h3>

			<ul id="list-<?php echo $list['list']->id ?>" class="task-list task sortable">
				<li class="task-new">
					<span class="ui-icon ui-icon-plusthick helper-left task-add"></span>
					<div class="task-content">
						New todo
					</div>
				</li>
				<?php 
				foreach($list['tasks'] as $task){?>
					<li id="task-<?php echo $task->id ?>" class="helper-clearfix<?php if ($task->complete){?> task-complete<?php }?>">
						<label>	<input type="checkbox" /> Check 1 </label>
						<div class="task-content">
							<?php echo $task->content ?>
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

	<ul class="task-list completed">
		<?php foreach($complete as $task){?>
		<li class="task-complete" id="task-<?php echo $task->id ?>">
			<label>	<input type="checkbox" checked="checked" /> Check 1 </label>
			<div class="task-content">
				<?php echo $task->content ?>
			</div>
		</li>
		<?php }?>
	</ul>

</div>
