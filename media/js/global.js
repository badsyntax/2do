
(function( $, window, document, undefined ){

	$.ajaxSetup({
		dataType: 'json',
		error: function(xhr, textStatus, error, callback) {
				alert('Something went wrong! Please try again.');
			}
	});

	window.managetodos = function(baseurl){

		$(":checkbox").checkbox({
		
			select: function(event){

				var checkbox = $( this ), 
					listparent = checkbox.parents('li:first'), 
					id = listparent.attr('id').replace(/todo-/, '');

				if ( checkbox.is(':checked') ) {

					$.post(baseurl + '/complete', { id: id }, function( data ){

						setTimeout(function(){
					
							listparent.fadeOut('fast', function(){

								$( '.todo-list.completed' ).prepend( this );

								$( this ).fadeIn( 'fast' ).effect( 'highlight', {}, 800 );

								checkbox.blur();

							});

						}, 140);
					
					});
				} else {

					$.post(baseurl + '/incomplete', { id: id }, function( data ){

						setTimeout(function(){

							listparent.fadeOut('fast', function(){

								var index = data.sequence;

								if ( index > 0 ) {

									$( '.todo-list.todo li:eq(' + ( data.sequence - 1 )+ ')' ).after( this );
								} else {
								
									$( '.todo-list.todo .todo-new' ).after( this );
								}

								$( this ).fadeIn( 'fast' ).effect( 'highlight', {}, 800 );
		
								checkbox.blur();
							});

						}, 140);
					});
				}
			}
		});

		function edithandler(){

			(function( self ){

				var contents = self.html(), 
					text = $.trim( self.text() ), 
					todo = self.parents('li:first'),
					list = self.parents('ul:first'),
					listId = list[0].id.replace(/list-/, '');
				
				todo.addClass('active');

				self
				.addClass('todo-editing')
				.attr('contentEditable', true)
				.html( text == 'New todo' ? '' : text )
				.focus()
				.blur(function(){
				
					todo.removeClass('active');
					
					self.removeClass('todo-hover');

					if ( self.hasClass('todo-new') ){
						
						$.post(baseurl + '/save', { todo: self.text(), list: listId }, function( data ){

							if ( data.outcome == 'success' ) {

								var item = $( '<li></li>' ).html( self.text() ).attr('id', 'todo-' + data.id);
								
								self.after( item );

								item.effect( 'highlight', {}, 800 );
								
								itembind.call( item );
							}
						});
					} else {

						$.post(baseurl + '/save', { todo: self.text(), list: listId, id: todo[0].id.replace(/todo-/, '') }, function( data ){

							todo.effect( 'highlight', {}, 800 );

							itembind.call( self );

						});
					}
				})
				.keydown(function(event){

					if ( event.keyCode == 13 ) {

						$( this ).trigger( 'blur' );
					}
				});

			})( $( this ) );
		}

		$('.todo-new').live('click', edithandler);
		
		var removeicon = $( '<span></span>' ).addClass('ui-icon ui-icon-closethick ui-helper-hidden-accessible helper-right');

		function itembind(){

			$( this )
			.prepend( removeicon.clone() )
			.unbind('mouseenter mouseleave click')
			.bind('mouseenter mouseleave', function(){

				$( this ).toggleClass('todo-hover');
			})
			.click( itemclickhandler );
		}


		function itemclickhandler(event){

			var self = $( this ), 
				listparent = self.parents('li:first'), 
				id = listparent.attr('id').replace(/todo-/, '');

			if ( new RegExp(removeicon[0].className).test( event.target.className ) ){
					
				$.post(baseurl + '/remove', { id: id }, function( data ){

					listparent.fadeOut('fast', function(){

						$( this ).remove();
					});
				});
			} else	{

				edithandler.call( this );		
			}
		}
		
		$('.todo-list li').not('.todo-new, .todo-done').each(function(){

			itembind.call( $( this ).find('.todo-content')[0] );
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
		
	};

})( jQuery, window, window.document );
