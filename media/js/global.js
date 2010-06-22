
(function( $, window, document, undefined ){

	$.ajaxSetup({
		dataType: 'json',
		error: function( xhr, textStatus ) {

				alert('Something went wrong! Please try again.');
			}
	});

	$.widget('ui.listeditor', {

		_create : function(){

			this.elements = {
				removeicon: $( '<span></span>' )
					.addClass('ui-icon ui-icon-closethick ui-helper-hidden-accessible helper-right'),
				listCompleted: $('#list-completed')
			};

			var self = this;
		
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

			$('.task-list li').not('.task-done').each(function(){

				self._contentBind( $( this ).find('.task-content')[0] );
			});
		},

		_taskComplete : function( id, listitem, checkbox ){
			
			var self = this;

			$.post(self.options.baseurl + '/complete', { id: id }, function( data ){

				setTimeout(function(){
			
					listitem.fadeOut('fast', function(){

						var item = $( this );
						
						$( '.task-list.completed' ).prepend( this );

						function show(){
							item.fadeIn( 'fast', function(){
								item.effect( 'highlight', {}, 800 );
							});
						}

						if ( self.elements.listCompleted.find('ul').children().length === 1 ) {

							self.elements.listCompleted.css({height: 'auto', display: 'block'});

							item.show();
							
							var height = self.elements.listCompleted.height();

							item.hide();

							self.elements.listCompleted
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

				}, 140);
			});
		},

		_taskIncomplete : function( id, listitem, checkbox ){

			var self = this;

			$.post(self.options.baseurl + '/incomplete', { id: id }, function( data ){

				setTimeout(function(){
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

						if ( self.elements.listCompleted.find('ul').children().length === 0 ) {

							self.elements.listCompleted.slideUp('slow').fadeOut();
						}
					});

				}, 140);
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

					var newitem = 
						$( '<li></li>' )
						.html( '<label><input type="checkbox" /></label><div class="task-content">' + text + '</div>' )
						.attr('id', 'task-' + response.id)
						.find(':checkbox').checkbox( self.checkboxConfig )
						.end();
						
					item.after( newitem ).find('.task-content').html('New task');
						
					newitem.effect( 'highlight', {}, 800 );
						
					self._contentBind( newitem.find('.task-content') );
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
			.addClass('task-editing')
			.attr('contentEditable', true)
			.html( text == 'New task' ? '&nbsp;' : text )
			.focus()
			.unbind('blur.edit keydown.edit')
			.bind('blur.edit', function(){
			
				item.removeClass('active');
				
				content
				.attr('contentEditable', false)
				.removeClass('task-editing task-hover');


				(function( self ){

					var text = $.trim( content.text() );

					self[ item.hasClass('task-new') ? '_taskSave' : '_taskUpdate' ]( text, item, listId ); 

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

				$( this ).toggleClass('task-hover');
			})
			.click( function( event ){

				var item = $( this ), 
					listparent = item.parents('li:first'), 
					id = listparent.attr('id').replace(/task-/, '');

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

			$('.task-list').sortable({
				containment: 'parent',
				items: 'li:not(.task-new)',
				distance: 5,
				update: function(event, ui) { 

					ui.item.unbind('click');

					ui.item.one('click', function (event) { 

						event.stopImmediatePropagation();

						$(this).click(itemclickhandler);
					});
				},
				stop: function(event, ui){

					ui.item.removeClass('task-hover');

					var list = $('.task-list').sortable( 'serialize' );

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
