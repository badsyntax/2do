<h3>Today</h3>

<ul class="todo-list">
	<li class="todo-new">
		New todo
	</li>
	<?php foreach($lists as $list){?>
		<li id="todo-<?php echo $list->id ?>"><?php echo $list->content ?></li>
	<?php }?>
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
					
					$.post('<?php echo Url::site('todo/save') ?>', { todo: this.value }, function( data ){

						if ( data.outcome == 'success' ) {

							var item = $( '<li></li>' ).html( textarea.value ).attr('id', 'todo-' + data.id);
							
							self.after( item );

							item.effect( 'highlight', {}, 1400 );
							
							itembind.call( item );
						}
					});
				}
					
				self.html( 'New todo ').addClass( 'todo-new' ).removeClass( 'noborder' );
			});

			self.empty().append( textarea ).addClass( 'noborder' );

			textarea.focus();

		})( $( this ).removeClass() );
	});
	
	var remove = $( '<span></span>' ).addClass('ui-icon ui-icon-closethick ui-helper-hidden-accessible helper-right');

	function itembind(){

		$( this ).prepend( remove.clone() )
		.bind('mouseenter mouseleave', function(){

			$( this ).toggleClass('todo-hover');

		})
		.click(function( event ){

			var self = $( this );

			if ( /ui-icon/.test( event.target.className ) ){
				
				$.post('<?php echo Url::site('todo/remove') ?>', { id: $( this ).attr('id').replace(/todo-/, '') }, function( data ){

					self.fadeOut(function(){

						$( this ).remove();
					});
				});
			}
		});
	}
	
	$('.todo-list li').not('.todo-new').each(function(){

		itembind.call( this );
	
	});

	$('.todo-list').sortable({
		containment: 'parent',
		items: 'li:not(.todo-new)',
		stop: function(event, ui){

			ui.item.removeClass('todo-hover');

			var list = $('.todo-list').sortable( 'serialize' );

			$.post('<?php echo Url::site('todo/reorder') ?>', list, function(data){

			});
		}
	});

</script>
