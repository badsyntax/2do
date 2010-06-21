
(function( $, window, document, undefined ){

	$.ajaxSetup({
		dataType: 'json',
		error: function(xhr, textStatus, error, callback) {
				alert('Something went wrong! Please try again.');
			}
	});

	$.widget('ui.listeditor', {

		elements: {
			removeicon: $( '<span></span>' )
				.addClass('ui-icon ui-icon-closethick ui-helper-hidden-accessible helper-right'),
			listCompleted: $('#list-completed')
		},

		_create : function(){

			var self = this;

			$(':checkbox').checkbox({
			
				select: function(event){

					var checkbox = $( this ), 
						listitem = checkbox.parents('li:first'), 
						id = listitem.attr('id').replace(/todo-/, ''),
						action = checkbox.is(':checked') ? '_todoComplete' : '_todoIncomplete';

					self[ action ]( id, listitem, checkbox );
				}
			});

			$('.todo-new').live('click', function(){

				self._listItemClickHandler( this );
			});
			
			$('.todo-list li').not('.todo-new, .todo-done').each(function(){

				self._listItemBind( $( this ).find('.todo-content')[0] );
			});
		},

		_todoComplete : function( id, listitem, checkbox ){
			
			var self = this;

			$.post(self.options.baseurl + '/complete', { id: id }, function( data ){

				setTimeout(function(){
			
					listitem.fadeOut('fast', function(){

						var item = $( this );
						
						$( '.todo-list.completed' ).prepend( this );

						function show(){
							item.fadeIn( 'fast', function(){
								item.effect( 'highlight', {}, 800 );
							});
						}

						if (!self.elements.listCompleted.find('ul').children().length-1) {

							self.elements.listCompleted.slideDown('slow', function(){
								show();
							});
						} else show();

						checkbox.blur();


					});

				}, 140);
			});
		},

		_todoIncomplete : function( id, listitem, checkbox ){

			var self = this;

			$.post(self.options.baseurl + '/incomplete', { id: id }, function( data ){

				setTimeout(function(){

					listitem.fadeOut('fast', function(){

						var index = data.sequence;

						if ( index > 0 ) {

							$( '.todo-list.todo:first li:eq(' + ( data.sequence - 1 )+ ')' ).after( this );
						} else {
						
							$( '.todo-list.todo:first .todo-new' ).after( this );
						}

						$( this ).fadeIn( 'fast', function(){
							$( this ).effect( 'highlight', {}, 800 );
						});
						
						if (!self.elements.listCompleted.find('ul').children().length) {

							self.elements.listCompleted.slideUp('slow');
						}

						checkbox.blur();
					});

				}, 140);
			});
		},

		_listItemClickHandler: function( item ){

			var self = this;

			item = $( item );

			if (item.attr('contentEditable') == 'true') return;

			var contents = item.html(), 
				text = $.trim( item.text() ), 
				todo = item.parents('li:first'),
				list = item.parents('ul:first'),
				listId = list[0].id.replace(/list-/, '');
			
			todo.addClass('active');

			item
			.addClass('todo-editing')
			.attr('contentEditable', true)
			.html( text == 'New todo' ? '' : text )
			.focus()
			.unbind('blur.edit keydown.edit')
			.bind('blur.edit', function(){
			
				todo.removeClass('active');
				
				item
				.attr('contentEditable', false)
				.removeClass('todo-editing todo-hover');

				if ( item.hasClass('todo-new') ){
					
					$.post(self.options.baseurl + '/save', { 
						todo: text, 
						list: listId 
					}, function( data ){

						if ( data.outcome == 'success' ) {

							var newitem = $( '<li></li>' ).html( text ).attr('id', 'todo-' + data.id);
								
							item.after( newitem );

							newitem.effect( 'highlight', {}, 800 );
								
							self._listItemBind( item );
						}
					});
				} else {

					$.post(self.options.baseurl + '/save', { 
						todo: text, 
						list: listId, 
						id: todo[0].id.replace(/todo-/, '') 
					}, function( data ){

						todo.effect( 'highlight', {}, 800 );

						self._listItemBind( item );
					});
				}
			})
			.bind('keydown.edit', function(event){

				if ( event.keyCode == 13 ) {

					$( this ).trigger( 'blur' );
				}
			});
		},


		_listItemBind : function( item ){

			var self = this;

			$( item )
			.prepend( this.elements.removeicon.clone() )
			.unbind('mouseenter mouseleave click')
			.bind('mouseenter mouseleave', function(){

				$( this ).toggleClass('todo-hover');
			})
			.click( function( event ){

				var item = $( this ), 
					listparent = item.parents('li:first'), 
					id = listparent.attr('id').replace(/todo-/, '');

				if ( new RegExp(self.elements.removeicon[0].className).test( event.target.className ) ){
						
					$.post(self.options.baseurl + '/remove', { id: id }, function( data ){

						listparent.fadeOut('fast', function(){

							$( this ).remove();
						});
					});
				} else	{
				
					self._listItemClickHandler( this );		
				}

			});
		},

		_sortable : function(){

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

					$.post(self.options.baseurl + '/reorder', list);
				}
			}).disableSelection();
		},

		destroy : function(){

			this.element.find( 'checkbox' ).checkbox( 'destroy' );

			$.Widget.prototype.destroy.apply(this, arguments);
		}

	});

})( jQuery, window, window.document );
