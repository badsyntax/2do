
        <div data-role="header" data-position="inline" data-nobackbtn="false">
		<h1><?php echo $list->name;?></h1>
		<a href="<?php echo URL::site('task/new')?>" data-role="button" data-icon="plus" data-rel="dialog" 
		data-transition="fade" class="ui-btn-right">New</a>
		<?php if (Auth::instance()->logged_in()){?>
		<?php }?>
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
<ul data-role="listview" data-inset="false" data-theme="c" data-dividertheme="a">
<?php foreach($tasks as $t => $task){
	if (trim($task->content)){?>
		<li>
			<!--
			<div style="width:20px;position:absolute;height:100%;left:0;top:0;">
				<input type="checkbox" style="position:absolute;left:4px;top:1.2em" />
			</div>
			-->
			<a href="<?php echo URL::site('task/view/' . $task->id);?>"><?php echo $task->content ?></a>
		</li>
	<?php }
}?>
</ul>
        </div><!-- /content -->


<?/*

<div id="lists">
<?php foreach($lists as $l => $list){?>

	<section id="<?php echo strtolower($list['list']->name) ?>" class="list">
	
		<div 
			data-role="fieldcontain" 
			id="list-<?php echo $list['list']->id ?>" 
			class="task-list task sortable<?php echo in_array($list['list']->id, $hidden_lists) ? ' ui-helper-hidden' : '';?>">
			<fieldset data-role="controlgroup">
		<legend><?php echo $list['list']->name ?></legend>

				<!--
			<li class="task-new">
				<span class="ui-icon ui-icon-plusthick helper-left task-add"></span>
				<div class="task-content">
					New todo
				</div>
			</li>
				-->
					<input type="checkbox" name="checkboxnew-<?php echo $l?>" id="checkboxnew-<?php echo $l?>" class="custom" />
					<label for="checkboxnew-<?php echo $l?>" id="checkboxlabel-<?php echo $l?>">
						New todo
					</label>

				<?php 
				foreach($list['tasks'] as $t => $task){?>
					<input type="checkbox" name="checkbox-<?php echo $task->id;?>" id="checkbox-<?php echo $task->id;?>" class="custom" />
					<label for="checkbox-<?php echo $task->id; ?>" 
					id="task-<?php echo $task->id ?>" 
					class="helper-clearfix<?php if ($task->complete){?> task-complete<?php }?><?php if ($t == count($list['tasks'])-1){?> task-last<?php }?>">
						 <?php echo $task->content ?>
					</label>
				<?php }?>
			</fieldset>
		</div>
	</section>

<?php } ?>

<?php if (count($complete)){?>
<section id="completed" class="list">
<?php } else {?>
<section id="completed" class="list helper-hidden">
<?php }?>

<div 
	data-role="fieldcontain" 
	id="list-completed" 
	class="task-list task sortable">
	<fieldset data-role="controlgroup">
		<legend>Completed</legend>
		<?php
		foreach($complete as $t => $task){?>
			<input type="checkbox" name="checkbox-<?php echo $task->id;?>" id="checkbox-<?php echo $task->id;?>" class="custom" />
			<label for="checkbox-<?php echo $task->id; ?>" 
			id="task-<?php echo $task->id ?>" 
			class="helper-clearfix<?php if ($task->complete){?> task-complete<?php }?><?php if ($t == count($list['tasks'])-1){?> task-last<?php }?>">
				 <?php echo $task->content ?>
			</label>
		<?php }?>
	</fieldset>
</div>

</section>

</div>
*/?>
