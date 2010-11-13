<div data-role="header" data-position="inline" data-nobackbtn="false">
	<h1>Lists</h1>
</div>

<div data-role="content">
	<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="a">
	<?php foreach($lists as $l => $list){?>
		<li>
			<a href="<?php echo URL::site('lists/view/' . $list['list']->id);?>"><?php echo $list['list']->name ?></a>
			<span class="ui-li-count"><? echo count($list['tasks'])?></span>
		</li>
	<?php }?>
	</ul>
</div>
