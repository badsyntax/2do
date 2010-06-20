
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

		function edittodo(){

			(function( self ){

				var contents = self.html(), text = $.trim( self.text() ), todo = self.parents('li:first');

				var input = $( '<textarea></textarea>' ).height( self.height() ).val( $.trim( self.text() ) == 'New todo' ? '' : text )
				.blur(function(){

					if ( ( $.trim( this.value ) != text ) && this.value.replace(/^\s+|\s+$/, '').length ) {

						var input = this, 
							list = self.parents('ul:first')[0].id.replace(/list-/, '');

						if ( self.hasClass('todo-new') ){
						
							$.post(baseurl + '/save', { todo: this.value, list: list }, function( data ){

								if ( data.outcome == 'success' ) {

									var item = $( '<li></li>' ).html( input.value ).attr('id', 'todo-' + data.id);
								
									self.after( item );

									item.effect( 'highlight', {}, 800 );
								
									itembind.call( item );
								}
							});
						} else {
							$.post(baseurl + '/save', { todo: this.value, list: list, id: todo[0].id.replace(/todo-/, '') }, function( data ){

								self.html( input.value );

								itembind.call( self );

							});
						}
					}
						
					self.html( contents ).addClass( 'todo-new' ).removeClass( 'border' );
				})
				.keydown(function(event){

					if ( event.keyCode == 13 ) {

						$( this ).trigger( 'blur' );
					}
				});

				self.empty().append( input ).parents('li:first').addClass( 'active' );

				input.focus();

			})( $( this ) );
		}

		$('.todo-new').live('click', edittodo);
		
		var removeicon = $( '<span></span>' ).addClass('ui-icon ui-icon-closethick ui-helper-hidden-accessible helper-right');

		function itembind(){

			$( this ).prepend( removeicon.clone() )
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

				edittodo.call( this );		
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
