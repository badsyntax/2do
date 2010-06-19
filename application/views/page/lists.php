<h3>Today</h3>

<ul class="todo-list">
	<li class="todo-new">
		<span class="ui-icon ui-icon-plusthick helper-left todo-add"></span>
		New todo
	</li>
	<?php 
	$done = array();
	foreach($lists as $todo){
		if ($todo->done) {
			array_push($done, $todo);
			continue;
		}?>
		<li id="todo-<?php echo $todo->id ?>" class="helper-clearfix<?php if ($todo->done){?> todo-done<?php }?>">
			<label>	<input type="checkbox" /> Check 1 </label>
			<div style="top:.4em;margin-left:24px">
				<?php echo $todo->content ?>
			</div>
		</li>
	<?php }?>
</ul>

<?php if (count($done)){?>

<h3>Completed</h3>

<ul class="todo-list">
	<?php foreach($done as $todo){?>
	<li class="todo-done"><?php echo $todo->content ?></li>
	<?php }?>
</ul>

<?php }?>

<h3>Someday</h3>
<ul class="todo-list">
	<li class="todo-new">
		<span class="ui-icon ui-icon-plusthick helper-left todo-add"></span>
		New todo
	</li>
</ul>

<script type="text/javascript">

	(function( $, window, document, undefined ){

		$(function(){
		$(":checkbox").checkbox();
		});

		var baseurl = '<?php echo Url::site('todo') ?>';
		
		function edittodo(){

			(function( self ){

				var contents = self.html(), text = $.trim( self.text() );

				var input = $( '<textarea></textarea>' ).height( self.height() ).val( $.trim( self.text() ) )
				.blur(function(){

					if ( ( $.trim( this.value ) != text ) && this.value.replace(/^\s+|\s+$/, '').length ) {

						var input = this;
						
						$.post(baseurl + '/save', { todo: this.value }, function( data ){

							if ( data.outcome == 'success' ) {

								var item = $( '<li></li>' ).html( input.value ).attr('id', 'todo-' + data.id);
								
								self.after( item );

								item.effect( 'highlight', {}, 800 );
								
								itembind.call( item );
							}
						});
					}
						
					self.html( contents ).addClass( 'todo-new' ).removeClass( 'border' );
				})
				.keydown(function(event){

					if ( event.keyCode == 13 ) {

						$( this ).trigger( 'blur' );
					}
				});

				self.empty().append( input ).addClass( 'border' );

				input.focus();

			})( $( this ) );
		}

		$('.todo-new').live('click', edittodo);
		
		
		var 
			doneicon = $( '<span></span> ').addClass('ui-icon ui-icon-check ui-helper-hidden-accessible helper-right'),
			removeicon = $( '<span></span>' ).addClass('ui-icon ui-icon-closethick ui-helper-hidden-accessible helper-right');

		function itembind(){

			$( this ).prepend( doneicon.clone() ).prepend( removeicon.clone() )
			.bind('mouseenter mouseleave', function(){

				$( this ).toggleClass('todo-hover');

			})
			.click(itemclickhandler);
		}

		function itemclickhandler(event){

			var self = $( this );

			if ( new RegExp(removeicon[0].className).test( event.target.className ) ){
					
				$.post(baseurl + '/remove', { id: $( this ).attr('id').replace(/todo-/, '') }, function( data ){

					self.fadeOut('fast', function(){

						$( this ).remove();
					});
				});
			} else

			if ( new RegExp(doneicon[0].className).test( event.target.className ) ){

				$.post(baseurl + '/done', { id: $( this ).attr('id').replace(/todo-/, '') }, function( data ){

					self.addClass('todo-done');
				});
			} else 

			{

				edittodo.call( this );		
			}
		}
		
		$('.todo-list li').not('.todo-new, .todo-done').each(function(){

			//itembind.call( $( this ).find('div')[0] );
		});

		return;

		$('.todo-list').sortable({
			containment: 'parent',
			items: 'li:not(.todo-new)',
			distance: 5,
			update: function(event, ui) { 

				ui.item.unbind('click');

				ui.item.one('click', function (event) { 

					event.stopImmediatePropagation();

					$(this).click(itemclickhandler);
				});
			},
			stop: function(event, ui){

				ui.item.removeClass('todo-hover');

				var list = $('.todo-list').sortable( 'serialize' );

				$.post(baseurl + '/reorder', list);
			}
		}).disableSelection();

	})( jQuery, window, window.document );

</script>

