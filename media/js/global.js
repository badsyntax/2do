
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
		
			this.checkboxConfig = {

				select: function(widget, event){

					var checkbox = $( this ), 
						listitem = checkbox.parents('li:first'), 
						id = listitem.attr('id').replace(/todo-/, ''),
						action = checkbox.is(':checked') ? '_todoComplete' : '_todoIncomplete';

					self[ action ]( id, listitem, checkbox );
				}

			};

			$(':checkbox').checkbox( this.checkboxConfig );

			$('.todo-list li').not('.todo-done').each(function(){

				self._contentBind( $( this ).find('.todo-content')[0] );
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

		_todoSave : function( text, item, listId ){

			var self = this;

			if ( !text ) return;

			$.post(this.options.baseurl + '/save', { 
				todo: text, 
				list: listId 
			}, function( data ){

				if ( data.outcome == 'success' ) {

					var newitem = 
						$( '<li></li>' )
						.html( '<label><input type="checkbox" /></label><div class="todo-content">' + text + '</div>' )
						.attr('id', 'todo-' + data.id)
						.find(':checkbox').checkbox( self.checkboxConfig )
						.end();
						
					item.after( newitem ).find('.todo-content').html('New todo');
						
					newitem.effect( 'highlight', {}, 800 );
						
					self._contentBind( newitem.find('.todo-content') );
				}
			});
		},

		_todoUpdate: function( text, item, listId ){
			
			if ( !text ) return;

			$.post(this.options.baseurl + '/save', { 
				todo: text, 
				list: listId, 
				id: item[0].id.replace(/todo-/, '') 
			}, function( data ){

				item.effect( 'highlight', {}, 800 );
			});
		},

		_listItemClickHandler: function( content ){

			var self = this;

			content = $( content );

			if (content.attr('contentEditable') == 'true') return;

			var text = $.trim( content.text() ), 
				item = content.parents('li:first'),
				list = content.parents('ul:first'),
				listId = list[0].id.replace(/list-/, '');
			
			item.addClass('active');

			content
			.data('origval', content.text() )
			.addClass('todo-editing')
			.attr('contentEditable', true)
			.html( text == 'New todo' ? '&nbsp;' : text )
			.focus()
			.unbind('blur.edit keydown.edit')
			.bind('blur.edit', function(){
			
				item.removeClass('active');
				
				content
				.attr('contentEditable', false)
				.removeClass('todo-editing todo-hover');


				(function( self ){

					var text = $.trim( content.text() );

					self[ item.hasClass('todo-new') ? '_todoSave' : '_todoUpdate' ]( text, item, listId ); 

				})( self );
				
				if ( !$.trim( $(this).text() ) ) {
					$( this ).html( $(this).data('origval') );
				}
			})
			.bind('keydown.edit', function(event){

				if ( event.keyCode == 13 ) {

					$( this ).trigger( 'blur' );
				}
			});
		},


		_contentBind : function( item ){

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
