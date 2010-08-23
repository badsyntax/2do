<h2>Feedback</h2>

<form method="post" action="<?php echo URL::site( Request::instance()->uri )?>">

	<?php if (@$_REQUEST['status'] == 'sent') {?>
		<p class="form-success">
			Message successfully sent!
		</p>
	<?php }?>

	<div>
		<label for="subject">
			Subject
			<?php if (isset($errors['subject'])){?>
				<span class="form-error">
					<?php echo $errors['subject']?>
				</span>
			<?php }?>
		</label>

		<?php echo Form::input('subject', $_POST['subject'], array('id' => 'subject'))?>
	</div>

	<div>
		<label for="field-message">
			Message
			<?php if (isset($errors['message'])){?>
				<span class="form-error">
					<?php echo $errors['message']?>
				</span>
			<?php }?>
		</label>
		<?php echo Form::textarea('message', $_POST['message'], array('id' => 'field-message'))?>
	</div>
	
	<div>
		<button type="submit">
			<span class="button-text">
				Send
			</span>
		</button>
	</div>
</form>
