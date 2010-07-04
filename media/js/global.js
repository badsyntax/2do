
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

			$( 'body' ).attr( 'role', 'application' );

			this.elements = {
				completedList: $('#list-completed'),
				sortableLists: $('.task-list.sortable'),
				removeIcon: $( '<span></span>' )
					.addClass( 'ui-icon ui-icon-closethick task-remove' ),
				timeIcon: $( '<span></span>' )
					.addClass( 'ui-icon ui-icon-time task-time' ),
				taskTime: $( '#task-time-container' )
			};

			this.checkboxConfig = {

				select: function(widget, event){

					try {

						var checkbox = $( this ), 
							listitem = checkbox.parents('li:first'), 
							id = listitem.attr('id').replace(/task-/, ''),
							action = checkbox.is(':checked') ? '_taskComplete' : '_taskIncomplete';

						self[ action ]( id, listitem, checkbox );

					} catch(error) { }
				}
			};

			$(':checkbox:not(.system)').checkbox( this.checkboxConfig );

			this.elements.sortableLists
			.sortable({
				containment: $('#content'),
				items: 'li:not(.active):not(.task-new)',
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

					self._taskUpdate( '', ui.item, list[0].id.replace(/list-/, ''), false );

					self._saveSequences( list, function(){

						if ( !active ) {

							ui.item.effect( 'highlight', {}, 800 )
						}
					});
				}
			}); //.disableSelection();
			
			$( '.task-list' )
			.delegate( '.task-remove', 'click', function( event ){
					
				var item = $( this ).parents( 'li:first' );

				self._taskRemove( event, item );
			})
			.delegate( '.task-time', 'click', function( event ){
					
				var item = $( this ).parents( 'li:first' );
				
				self._taskTime( event, item );
			})
			.delegate( 'li.task-new', 'click', function( event ){

				!/task-content/.test( event.target.className ) && 
					self._contentClickHandler( event, $( this ).find('.task-content')[0] );
			})
			.delegate( '.task-content', 'click', function( event ){
					
				self._contentClickHandler( event, this );		
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

			$('.list-toggle').click(function(){

				$( this ).parent().siblings( 'ul' )
				.animate({
					height: ['toggle', 'swing']
				}, 400, 'linear');
			});
		},

		_saveSequences : function( list, callback ){

			var param = [
				list.sortable( 'serialize' ),
				$.param({
					listid: list[0].id.replace(/list-/, '')
				}) ].join( '&' );

			$.post( this.options.baseurl + '/reorder', param , callback );
		},

		_taskComplete : function( id, item, checkbox ){
			
			var self = this;

			item.fadeOut('fast', function(){
					
				function show(){

					item.fadeIn( 'fast', function(){
						item.effect( 'highlight', {}, 800 );
					});
				}

				if ( self.elements.completedList.find('ul').children().length === 0 ) {

					self.elements.completedList
						.css({
							height: 'auto', 
							display: 'block'
						});
					
					self.elements.completedList.find( 'ul' ).append( item );

					item.show();
							
					var height = self.elements.completedList.height();

					item.hide();

					self.elements.completedList
						.hide().css( { height: 0 } )
						.animate({
							height: height,
							opacity: 1
						}, function(){
	
							$( this ).css({ height: 'auto' });
						});

					show();

				} else {

					self.elements.completedList.find( 'ul' ).append( item );

					show();
				}

				checkbox.blur();
			});

			$.post( self.options.baseurl + '/complete', { id: id } );
		},

		_taskIncomplete : function( id, listitem, checkbox ){

			var self = this;

			listitem.fadeOut('fast', function(){

				$( '.task-list.task:first .task-new' ).after( listitem );
	
				listitem.fadeIn( 'fast', function(){

					$( this ).effect( 'highlight', {}, 800 );
				});

				checkbox.blur();

				if ( self.elements.completedList.find('ul').children().length === 0 ) {

					self.elements.completedList.slideUp('slow').fadeOut();
				}
			});

			$.post( self.options.baseurl + '/incomplete', { id: id } );
		},

		_taskSave : function( text, item, listId ){

			var self = this;

			if ( !text ) return;

			$.post(this.options.baseurl + '/save', { 
				task: text, 
				list: listId 
			}, function( response ){

				if ( response.outcome == 'success' ) {

					var 
					list = item.parents('ul:first'),
					newitem = 
						$( '<li></li>' )
						.html( '<label><input type="checkbox" /></label><div class="task-content">' + text + '</div>' )
						.attr('id', 'task-' + response.id)
						.find(':checkbox').checkbox( self.checkboxConfig )
						.end();
						
					item.after( newitem )
						.find( '.task-content' ).html( 'New todo' );
						
					newitem.effect( 'highlight', {}, 800, function(){
	
						self._saveSequences( list );
					});
				} else {
			
					alert( response.message );
				}
			});
		},

		_taskUpdate: function( text, item, listId, animate ){
			
			$.post(this.options.baseurl + '/save', { 
				task: text, 
				list: listId, 
				id: item[0].id.replace(/task-/, '') 
			}, function( response ){

				if ( response.outcome == 'success' ) {

					animate && item.effect( 'highlight', {}, 800 );
				} else {

					alert( response.message );
				}
			});
		},
		
		_taskRemove : function( event, item ){

			var id = item.attr('id').replace(/task-/, '');

			$.post( this.options.baseurl + '/remove', { id: id }, function( data ){

				item.fadeOut('fast', function(){

					$( this ).remove();
				});
			});
		},

		_taskTime : function( event, item, animate ){

			animate = animate === undefined ? true : animate;

			var self = this, offset = $( event.target ).offset();

			this.elements.taskTime
			.css({
				left: offset.left - this.elements.taskTime.innerWidth() - 10,
				top: offset.top - ( this.elements.taskTime.height() / 2 )
			})
			.find( 'input' )
				.focus()
				.unbind( 'blur.time' )
				.bind( 'blur.time', function( event ){

					var input = this;
					
					self.elements.taskTime.css({ left: -9999 });

					if ( !input.value ) return;

					$.post( self.options.baseurl + '/savetime', { 
						time: $.trim( input.value ),
						id: item[0].id.replace(/task-/, '') 
					}, function( response ){

						if ( response.outcome == 'success' ) {

							animate && item.effect( 'highlight', {}, 800 );
						} else {

							alert( response.message );
						}
					});

					input.value = '';
				})
				.bind('keydown.time', function(event){

					// return key
					( event.keyCode == 13 ) && $( this ).trigger( 'blur' );
				});
		},

		_contentClickHandler: function( event, content ){

			content = $( content );

			var self = this,
				text = $.trim( content.text() ), 
				item = content.parents('li:first').addClass( 'active' ),
				list = content.parents('ul:first');

			if ( content.attr('contentEditable') == 'true' ) return;

			$( '.task-content' ).not( content )
				.trigger( 'blur.edit' );

			this.elements.sortableLists
                        .sortable('refresh');
			
			$.data( content[0], 'value', $.trim( content.text() ) );

			content
			.addClass( 'task-editing' )
			.attr('contentEditable', true)
			.html( text == 'New todo' ? '&nbsp;' : text )
			.focus()
			.bind('blur.edit', function(){

				item.removeClass('active');
				
				content
				.attr( 'contentEditable', false )
				.unbind( 'blur.edit keydown.edit' )
				.removeClass( 'task-editing task-hover' );

				(function( self ){

					var text = $.trim( content.text() ), listId = list[0].id.replace(/list-/, '');

					( text != content.data( 'value' ) ) && 
						self[ item.hasClass('task-new') ? '_taskSave' : '_taskUpdate' ]( text, item, listId ); 

				})( self );

				if ( !$.trim( $(this).text() ) ) {
					$( this ).html( $(this).data('value') );
				}
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

	// TODO incorporate html5 local storage
	function cookie(opt){

		this.config = $.extend({
			name: 'cookie',
			path: '/',
			expiredays: 1
		}, opt);
	}

	cookie.prototype = {
		set : function(name, val, expiredays){

			expiredays = expiredays || this.config.expiredays;

			var exdate = new Date();

			exdate.setDate( exdate.getDate() + expiredays );

			document.cookie = 
				name 
				+ '=' + escape(val) 
				+ ( ( expiredays == null ) ? '' : ';expires=' + exdate.toGMTString() ) 
				+ ';path=' + this.config.path;
		},
		get : function(name){

			if ( document.cookie.length ){
				return '';
			}

			var start = document.cookie.indexOf( name + '=' );

			if (start === -1) {
				return '';
			}

			start = start + name.length + 1;

			var end = document.cookie.indexOf( ';', start );

			if (end === -1) { 
				end = document.cookie.length;
			}

			return unescape( document.cookie.substring(start, end) );
		}
	}

	$.cookie = new cookie();

	$('#content.lists').listeditor({
		baseurl: '/task'
	});

	$('button').button();

})( jQuery, window, window.document );

