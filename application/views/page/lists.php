<h3>Today</h3>

<ul class="todo-list">
	<li class="todo-new">New todo</li>
</ul>

<h3>Someday</h3>
<ul class="todo-list">
	<li class="todo-new">New todo</li>
</ul>

<script type="text/javascript">

	$('.todo-new').live('click', function(){

		(function( self ){

			var textarea = $( '<textarea></textarea>' )
			.blur(function(){

				if ( this.value.replace(/^\s+|\s+$/, '').length ) {

					var textarea = this;
					
					$.post('<?php echo Url::site('todo/save') ?>', { todo: this.value }, function(data){

						if ( data.outcome == 'success' ) {

							var item = $( '<li></li>' ).html( textarea.value );
							
							self.after( item );
						}
					});

					self.html( 'New todo ').addClass( 'todo-new' );
				}
			});

			self.empty().append( textarea );

			textarea.focus();

		})( $( this ).removeClass() );
	});
</script>
