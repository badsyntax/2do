<h2>Feedback</h2>

<form method="post" action="<?php echo URL::site( Request::instance()->uri )?>">

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
		<?php echo Form::textarea('message', $_POST['message'], array('id' => 'field-message'))?>
		<script>
			document.getElementById('field-message').focus();
		</script>
	</div>
	
	<div>
		<button type="submit">
			<span class="button-text">
				Send
			</span>
		</button>
	</div>
</form>
