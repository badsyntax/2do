	<div data-role="header" data-position="inline" data-nobackbtn="false">
		<h2>Feedback</h2>
		<?php if (Auth::instance()->logged_in()){?>
		<?php }?>
	</div><!-- /header -->

	<div data-role="content"  data-theme="c">
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

<form method="post" action="<?php echo URL::site( Request::instance()->uri )?>">

	<fieldset>

	<?php if (@$_REQUEST['status'] == 'sent') {?>
		<p class="form-success">
			Message successfully sent!
		</p>
	<?php }?>

	<div>
		<label for="field-message">
			<?php if (isset($errors['message'])){?>
				<span class="form-error">
					<?php echo $errors['message']?>
				</span>
			<?php }?>
		</label>
		<?php echo Form::textarea('message', $_POST['message'], array('id' => 'field-message', 'style' => 'height:80px;display:block;width:95%;'))?>
		<script>
			document.getElementById('field-message').focus();
		</script>
	</div>
	
	<div>
		<button type="submit" data-role="button" data-theme="b">
			<span class="button-text">
				Send
			</span>
		</button>
	</div>
</fieldset>
</form>
</div>
