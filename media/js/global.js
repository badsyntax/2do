
(function( $, window, document, undefined ){

	$.ajaxSetup({
		dataType: 'json',
		error: function( xhr, textStatus ) {

				alert('Something went wrong! Please try again.');
			}
	});

	$.widget('ui.listeditor', {

		_create : function(){
			
			var self = this;

			this.elements = {
				completedList: $('#list-completed'),
				sortableLists: $('.task-list.sortable'),
				removeIcon: $( '<span></span>' )
					.addClass( 'ui-icon ui-icon-closethick task-remove' ),
				timeIcon: $( '<span></span>' )
					.addClass( 'ui-icon ui-icon-time task-time' )
			};

			this.checkboxConfig = {

				select: function(widget, event){

					var checkbox = $( this ), 
						listitem = checkbox.parents('li:first'), 
						id = listitem.attr('id').replace(/task-/, ''),
						action = checkbox.is(':checked') ? '_taskComplete' : '_taskIncomplete';

					self[ action ]( id, listitem, checkbox );
				}
			};

			$(':checkbox').checkbox( this.checkboxConfig );

			this.elements.sortableLists
			.sortable({
				containment: $('#content'),
				items: 'li:not(.task-new)',
				connectWith: '.task-list.sortable',
				distance: 5,
				opacity: .6,
				placeholder: 'ui-state-highlight',
				update: function(event, ui) { 

					ui.item.find('.task-content').unbind('click');
				},
				stop: function(event, ui){

					var list = ui.item.parents( 'ul:first' ), active = /active/.test( ui.item[0].className );
			
					ui.item.removeClass('active')
						.find('.task-content')
							.removeClass('task-hover')
							.trigger('blur.edit');

					self._saveSequences( list, ui.item, function(){

						if ( !active ) {

							ui.item.effect( 'highlight', {}, 800 )
						}
					});
				}
			})
			.disableSelection();
			
			$( '.task-list' )
			.delegate( '.task-remove', 'click', function(){
					
				var item = $( this ).parents( 'li:first' );

				self._taskRemove( item );
			})
			.delegate( '.task-time', 'click', function(){
					
				var item = $( this ).parents( 'li:first' );
				
				self._taskTime( item );
			})
			.delegate( '.task-content', 'click', function(){
					
				self._contentClickHandler( this );		
			})
			.delegate( 'li:not(.task-new)', 'mouseenter', function(){

				$( this )
					.prepend( self.elements.timeIcon.show() )
					.prepend( self.elements.removeIcon.show() );
			})
			.delegate( 'li', 'mouseleave', function(){

				self.elements.removeIcon.hide();

				self.elements.timeIcon.hide();
			});
		},

		_saveSequences : function( list, item, callback ){

			var param = [
				list.sortable( 'serialize' ),
				$.param({
					taskid: item[0].id.replace(/task-/, ''),
					listid: list[0].id.replace(/list-/, '')
				}) ].join( '&' );


			$.post( this.options.baseurl + '/reorder', param , callback );
		},

		_taskComplete : function( id, listitem, checkbox ){
			
			var self = this;

			$.post(self.options.baseurl + '/complete', { id: id }, function( data ){

				listitem.fadeOut('fast', function(){

					var item = $( this );
						
					$( '.task-list.completed' ).prepend( this );

					function show(){

						item.fadeIn( 'fast', function(){
							item.effect( 'highlight', {}, 800 );
						});
					}

					if ( self.elements.completedList.find('ul').children().length === 1 ) {

						self.elements.completedList.css({height: 'auto', display: 'block'});

						item.show();
							
						var height = self.elements.completedList.height();

						item.hide();

						self.elements.completedList
						.css({height: 0, display: 'none'})
						.animate({
							height: height,
							opacity: 1
						}, function(){

							$( this ).css({ height: 'auto' });
						});
							
						show();

					} else show();

					checkbox.blur();
				});
			});
		},

		_taskIncomplete : function( id, listitem, checkbox ){

			var self = this;

			$.post(self.options.baseurl + '/incomplete', { id: id }, function( data ){

				listitem.fadeOut('fast', function(){

					var index = data.sequence;

					if ( index > 0 ) {

						$( '.task-list.task:first li:eq(' + ( data.sequence - 1 )+ ')' ).after( listitem );
					} else {
						
						$( '.task-list.task:first .task-new' ).after( listitem );
					}
	
					listitem.fadeIn( 'fast', function(){

						$( this ).effect( 'highlight', {}, 800 );
					});

					checkbox.blur();

					if ( self.elements.completedList.find('ul').children().length === 0 ) {

						self.elements.completedList.slideUp('slow').fadeOut();
					}
				});
			});
		},

		_taskSave : function( text, item, listId ){

			var self = this;

			if ( !text ) return;

			$.post(this.options.baseurl + '/save', { 
				task: text, 
				list: listId 
			}, function( response ){

				if ( response.outcome == 'success' ) {

					var list = item.parents('ul:first'),
						newitem = 
						$( '<li></li>' )
						.html( '<label><input type="checkbox" /></label><div class="task-content">' + text + '</div>' )
						.attr('id', 'task-' + response.id)
						.find(':checkbox').checkbox( self.checkboxConfig )
						.end();
						
					item.after( newitem )
						.find( '.task-content' ).html( 'New todo' );
						
					newitem.effect( 'highlight', {}, 800, function(){
	
						self._saveSequences( list, newitem );
					});
				} else {
			
					alert( response.message );
				}
			});
		},

		_taskUpdate: function( text, item, listId ){
			
			if ( !text ) return;

			$.post(this.options.baseurl + '/save', { 
				task: text, 
				list: listId, 
				id: item[0].id.replace(/task-/, '') 
			}, function( response ){

				if ( response.outcome == 'success' ) {

					item.effect( 'highlight', {}, 800 );
				} else {

					alert( response.message );
				}
			});
		},
		
		_taskRemove : function( listitem ){

			var id = listitem.attr('id').replace(/task-/, '');

			$.post( this.options.baseurl + '/remove', { id: id }, function( data ){

				listitem.fadeOut('fast', function(){

					$( this ).remove();
				});
			});
		},

		_contentClickHandler: function( content ){
			
			content = $( content );

			var self = this,
				text = $.trim( content.text() ), 
				item = content.parents('li:first').addClass( 'active' ),
				list = content.parents('ul:first');

			if ( content.attr('contentEditable') == 'true' ) return;

			$( '.task-content' ).not( content )
				.trigger( 'blur.edit' );
			
			$.data( content[0], 'origval', content.text() );

			content
			.addClass( 'task-editing' )
			.attr('contentEditable', true)
			.html( text == 'New todo' ? '&nbsp;' : text )
			.focus()
			.bind('blur.edit', function(){
			
				item.removeClass('active');
				
				content
				.attr( 'contentEditable', false )
				.removeClass( 'task-editing task-hover' );

				(function( self ){

					var text = $.trim( content.text() ), listId = list[0].id.replace(/list-/, '');

					self[ item.hasClass('task-new') ? '_taskSave' : '_taskUpdate' ]( text, item, listId ); 

				})( self );
				
				if ( !$.trim( $(this).text() ) ) {
					$( this ).html( $(this).data('origval') );
				}

				$( this ).unbind( 'blur.edit keydown.edit' );
			})
			.bind('keydown.edit', function(event){

				// return key
				( event.keyCode == 13 ) && $( this ).trigger( 'blur' );
			});
		},

		destroy : function(){
				
			$.Widget.prototype.destroy.apply(this, arguments);

			this.element.find( 'checkbox' )
				.checkbox( 'destroy' );
		}

	});

	$('#content').listeditor({
		baseurl: '/task'
	});

})( jQuery, window, window.document );
