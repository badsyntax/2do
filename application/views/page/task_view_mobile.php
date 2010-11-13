        <div data-role="header" data-position="inline" data-nobackbtn="false">
                <h1>Info</h1>
		 <a href="<?php echo URL::site('lists/newtodo')?>" data-role="button" data-icon="gear" data-rel="dialog" 
		                 data-transition="fade" class="ui-btn-right">Edit</a>

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

<div class="task-view" data-role="fieldcontain">
 	<fieldset data-role="controlgroup">
		<input type="checkbox" name="checkbox-1" id="checkbox-1" class="custom" />
		<label for="checkbox-1">
			<?php
			echo $task->content;?>
		</label>
    </fieldset>
</div>
</div>
