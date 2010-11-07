<div data-role="controlgroup" data-type="horizontal" class="ui-btn-right">
<a href="<?php echo URL::site() ?>" data-role="button" data-icon="back" data-iconpos="notext" rel="external">Home</a>
<a href="<?php echo URL::site('info') ?>" data-role="button" data-icon="info" data-iconpos="notext">Info</a>
<a href="<?php echo URL::site('lists/newtodo')?>" data-role="button" data-icon="plus" data-iconpos="notext" data-rel="dialog" 
data-transition="fade">New Todo</a>
</div>
<h1><?=$title?></h1>
<?php if (Auth::instance()->logged_in()){?>
<?php }?>
