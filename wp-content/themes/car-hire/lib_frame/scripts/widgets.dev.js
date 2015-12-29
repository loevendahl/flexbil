/*global ajaxurl, isRtl */
var wpWidgets;
(function($) {

wpWidgets = {

	init : function() {
		var rem, the_id,
			self = this,
			chooser = $('.widgets-chooser'),
			selectSidebar = chooser.find('.widgets-chooser-sidebars'),
			sidebars = $('div.widgets-sortables'), 
			isRTL = !! ( 'undefined' != typeof isRtl && isRtl ),
			margin = ( isRtl ? 'marginRight' : 'marginLeft' );

		$('#widgets-right').children('.widgets-holder-wrap').children('.sidebar-name').click(function(){
			var c = $(this).siblings('.widgets-sortables'), p = $(this).parent();
			if ( !p.hasClass('closed') ) {
				c.sortable('disable');
				p.addClass('closed');
			} else {
				p.removeClass('closed');
				c.sortable('enable').sortable('refresh');
			}
		});
		
		$('#widgets-right').find('.container-arrow').click(function(){
			var c = $(this).parents('.container-area').find('.widgets-sortables'), p = $(this).parents('.container-area');
			if ( $(this).parents('.container-area').hasClass('enabled-box') ) {
				if ( !p.hasClass('closed') ) {
					c.sortable('disable');
					p.addClass('closed');
				} else {
				    p.removeClass('closed');
					c.sortable('enable').sortable('refresh');
				}
			} else {
				p.addClass('closed');
				c.sortable('disable');
			}
		});

		$('#widgets-left').children('.widgets-holder-wrap').children('.sidebar-name').click(function() {
			$(this).parent().toggleClass('closed');
		});

		// custom code --- START ---------------
		sidebars.live('mouseenter', function(e) {
		    $(this).sortable('enable').sortable('refresh');
			e.preventDefault();
		});
		
		// container sorting
		$(function() {
			$("div[class*=sortme]:not('[class*=sortme-disabled]')").sortable({
			    connectWith: '.sortme',
				handle: 'h3.container-name',
				cursor: 'move',
				axis: 'y',
				update : function () {					
					$(this).find('.container-name .spinner').addClass( 'is-active' );
					wpWidgets.saveLayouts();
				}
			});
		});
		// disable | enable container
		$(".container-area h3 .title-action").live('click', function(e){
		    if ( $(this).parents('.container-area').hasClass('enabled-box') ) {
				$(this).parents('.container-area').find('.container-name .spinner').addClass( 'is-active' );
				$(this).parents('.container-area').find('input[value][value="true"]').attr("value","false");
				$(this).parents('.container-area').removeClass('enabled-box');
				$(this).parents('.container-area').addClass('disabled-box closed');
				$(this).find('a').text('Enable');
			} else {
				$(this).parents('.container-area').find('.container-name .spinner').addClass( 'is-active' );
				$(this).parents('.container-area').find('input[value][value="false"]').attr("value","true");
				$(this).parents('.container-area').removeClass('disabled-box closed');
				$(this).parents('.container-area').addClass('enabled-box');
				$(this).find('a').text('Disable');
			}
			wpWidgets.saveLayouts();
			e.preventDefault();
		});
		// hide widgets from unrelated layouts
		$('.container-area').find('input.widget-id').each(function(){
		    var thisWidget = $(this);
			
			// hide all widgets
			$(thisWidget).closest('div.widget').hide();
			$(thisWidget).closest('div.widget').addClass('hidden');
			
			// available widgets
			$("input.available_widget").each(function(){
			    var thisAvail = $(this),
					thisName = $(this).attr("name");
			    // show available widgets
				if ( $(thisWidget).val() == $(thisAvail).val() ){
				    $(thisWidget).closest('div.widget').show();
				    $(thisWidget).closest('div.widget').removeClass('hidden');
					if ( thisName == 'true' ){
					    $(thisWidget).closest('div.widget').addClass('parent');
					}
				}
			});
			
			// closed widgets
			$("input.hidden_widget").each(function(){
			    var thisHidd = $(this),
					thisNamed = $(this).attr("name");
			    // show available widgets
				if ( $(thisWidget).val() == $(thisHidd).val() ){
				    $(thisWidget).closest('div.widget').show();
				    $(thisWidget).closest('div.widget').removeClass('hidden');
					if ( thisNamed == 'true' ){
					    $(thisWidget).closest('div.widget').addClass('closed');
					}
				}
			});
		});
		// only disable child widgets
		$('#widgets-right').find('.widget').each(function() {
			if ($(this).hasClass('parent')){
			    $(this).find('a.widget-control-remove').text('Disable');
			}
			if ($(this).hasClass('closed')){
			    $(this).find('a.widget-control-remove').text('Enable');
			}
		});
		// custom code --- END ---------------
				
		$(document.body).bind('click.widgets-toggle', function(e){
			var target = $(e.target), css = {}, widget, inside, w;

			if ( target.parents('.widget-top').length && ! target.parents('#available-widgets').length ) {
				widget = target.closest('div.widget');
				inside = widget.children('.widget-inside');
				w = parseInt( widget.find('input.widget-width').val(), 10 );
				ww = parseInt( widget.find('.widget-top').width(), 10 );
				www = ww - 30;

				if ( inside.is(':hidden') ) {
					if ( w > inside.closest('div.widgets-sortables').width() && inside.closest('div.widgets-sortables').length ) {
						css['width'] = w + 30 + 'px';
						css['z-index'] = 99;
						if ( inside.closest('div.widget-liquid-right').length )
							css[margin] = www - w + 'px';
						widget.css(css);
					}
					wpWidgets.fixLabels(widget);
					inside.slideDown('fast');
				} else {
					inside.slideUp('fast', function() {
						widget.css({'width':'', margin:'','z-index':''});
					});
				}
				e.preventDefault();
			} else if ( target.hasClass('widget-control-save') ) {
				wpWidgets.save( target.closest('div.widget'), 0, 1, 0 );
				e.preventDefault();
			} else if ( target.hasClass('widget-control-remove') ) {
				widget = target.closest('div.widget');
				if ( widget.hasClass('parent') ){
					wpWidgets.saveWidgetlogic( widget, 1, 0, 0 ); // widget,parent,deleting,enable
					widget.addClass('closed'); // hide widget
					widget.removeClass('parent'); // hide widget
					widget.removeAttr("style"); // remove style from widget
					widget.css("display","block"); // remove style from widget
					widget.find('.widget-inside').css("display","none"); // remove style from widget
					widget.find('.spinner').addClass( 'is-active' );
					target.text('Enable');
				} else if ( widget.hasClass('closed') ){
					wpWidgets.saveWidgetlogic( widget, 1, 0, 1 ); // widget,parent,deleting,enable
					widget.removeClass('closed'); // hide widget
					widget.addClass('parent'); // hide widget
					widget.addClass('parent'); // hide widget
					widget.css("display","block"); // remove style from widget
					widget.find('.widget-inside').css("display","block"); // remove style from widget
					widget.find('.spinner').addClass( 'is-active' );
					target.text('Disable');
				} else {
					wpWidgets.save( widget, 1, 1, 0 );
					wpWidgets.saveWidgetlogic( widget, 1, 1, 0 ); // widget,parent,deleting,enable
				}
				e.preventDefault();
			} else if ( target.hasClass('widget-control-close') ) {
				wpWidgets.close( target.closest('div.widget') );
				e.preventDefault();
			}
		});
		
		sidebars.children('.widget').each(function() {
			wpWidgets.appendTitle(this);
			if ( $('p.widget-error', this).length ) {
				$('a.widget-action', this).click();
			}
		});
		
		$('#widget-list').children('.widget').draggable({
			connectToSortable: 'div.widgets-sortables',
			handle: '> .widget-top > .widget-title',
			distance: 2,
			helper: 'clone',
			zIndex: 100,
			containment: 'document',
			start: function( event, ui ) {
				var chooser = $(this).find('.widgets-chooser');

				ui.helper.find('div.widget-description').hide();
				the_id = this.id;

				if ( chooser.length ) {
					// Hide the chooser and move it out of the widget
					$( '#wpbody-content' ).append( chooser.hide() );
					// Delete the cloned chooser from the drag helper
					ui.helper.find('.widgets-chooser').remove();
					self.clearWidgetSelection();
				}
			},
			stop: function() {
				if ( rem ) {
					$(rem).hide();
				}

				rem = '';
			}
		});

		sidebars.sortable({
			placeholder: 'widget-placeholder',
			items: '> .widget',
			handle: '> .widget-top > .widget-title',
			cursor: 'move',
			distance: 2,
			cursorAt: { left: 125 },
			tolerance: 'pointer',
			start: function( event, ui ) {
				var height, $this = $(this),
					$wrap = $this.parent(),
					inside = ui.item.children('.widget-inside');

				if ( inside.css('display') === 'block' ) {
					inside.hide();
					$(this).sortable('refreshPositions');
					ui.item.width('250px');
				}

				if ( ! $wrap.hasClass('closed') ) {
					// Lock all open sidebars min-height when starting to drag.
					// Prevents jumping when dragging a widget from an open sidebar to a closed sidebar below.
					height = ui.item.hasClass('ui-draggable') ? $this.height() : 1 + $this.height();
					$this.css( 'min-height', height + 'px' );
					ui.item.width('250px');
				}
			},
			stop: function( event, ui ) {
				var addNew, widgetNumber, $sidebar, $children, child, item,
					$widget = ui.item,
					id = the_id;
					
				if ( $widget.hasClass('deleting') ) {
					if ( $widget.hasClass('parent')){
					    wpWidgets.saveWidgetlogic( $widget, 1, 0, 0 ); // widget,parent,deleting,enable
						$widget.addClass('closed'); // hide widget
						$widget.removeClass('parent'); // hide widget
						$widget.removeAttr("style"); // remove style from widget
						$widget.css("display","block"); // remove style from widget
						$widget.find('.widget-inside').css("display","none"); // remove style from widget
						$widget.find('.spinner').addClass( 'is-active' );
						$widget.find('a.widget-control-remove').text('Enable');
						return;
					} else if ( $widget.hasClass('closed') ){
					    wpWidgets.saveWidgetlogic( $widget, 1, 0, 1 ); // widget,parent,deleting,enable
						$widget.removeClass('closed'); // hide widget
						$widget.addClass('parent'); // hide widget
						$widget.css("display","block"); // remove style from widget
						$widget.find('.widget-inside').css("display","block"); // remove style from widget
						$widget.find('.spinner').addClass( 'is-active' );
						$widget.find('a.widget-control-remove').text('Disable');
						return;
					} else {
						wpWidgets.save( $widget, 1, 0, 1 ); // delete widget
						wpWidgets.saveWidgetlogic( $widget, 1, 1, 0 );
						$widget.remove();
						return;
					}
				}

				addNew = $widget.find('input.add_new').val();
				widgetNumber = $widget.find('input.multi_number').val();

				$widget.attr( 'style', '' ).removeClass('ui-draggable');
				the_id = '';
				
				if ( addNew ) {
					if ( 'multi' === addNew ) {
						$widget.html(
							$widget.html().replace( /<[^<>]+>/g, function( tag ) {
								return tag.replace( /__i__|%i%/g, widgetNumber );
							})
						);

						$widget.attr( 'id', id.replace( '__i__', widgetNumber ) );
						widgetNumber++;

						$( 'div#' + id ).find( 'input.multi_number' ).val( widgetNumber );
					} else if ( 'single' === addNew ) {
						$widget.attr( 'id', 'new-' + id );
						rem = 'div#' + id;
					}

					wpWidgets.save( $widget, 0, 0, 1 );
					wpWidgets.saveWidgetlogic( $widget, 0, 0, 1 );
					$widget.find('input.add_new').val('');
					$( document ).trigger( 'widget-added', [ $widget ] );
					
					// trigger ajax upload for widgets
					$widget.find('.wid_upload_button').each(function() {
					    jQuery(this).click();
					});
				}
				
				$sidebar = $widget.parent();

				if ( $sidebar.parent().hasClass('closed') ) {
					$sidebar.parent().removeClass('closed');
					$children = $sidebar.children('.widget');

					// Make sure the dropped widget is at the top
					if ( $children.length > 1 ) {
						child = $children.get(0);
						item = $widget.get(0);

						if ( child.id && item.id && child.id !== item.id ) {
							$( child ).before( $widget );
						}
					}
				}
				
				// move widget to correct condition form inactive section
				if ( $widget.hasClass('from_inactive') ) {
					wpWidgets.saveWidgetlogic( $widget, 1, 1, 0 ); // widget,parent,deleting,enable
					wpWidgets.saveWidgetlogic( $widget, 0, 0, 1 ); // widget,parent,deleting,enable
				}
				
				if ( addNew ) {
					$widget.find( 'a.widget-action' ).trigger('click');
				} else {
					wpWidgets.saveOrder( $sidebar.attr('id') );
				}
			},
			
			activate: function() {
				$(this).parent().addClass( 'widget-hover' );
			},

			deactivate: function() {
				// Remove all min-height added on "start"
				$(this).css( 'min-height', '' ).parent().removeClass( 'widget-hover' );
			},
			
			receive: function( event, ui ) {
				var $sender = $( ui.sender );

				// Don't add more widgets to orphaned sidebars
				if ( this.id.indexOf('orphaned_widgets') > -1 ) {
					$sender.sortable('cancel');
					return;
				}

				// If the last widget was moved out of an orphaned sidebar, close and remove it.
				if ( $sender.attr('id').indexOf('orphaned_widgets') > -1 && ! $sender.children('.widget').length ) {
					$sender.parents('.orphan-sidebar').slideUp( 400, function(){ $(this).remove(); } );
				}
			}
			
		}).sortable('option', 'connectWith', 'div.widgets-sortables').parent().filter('.closed').children('.widgets-sortables').sortable('disable');

		$('#available-widgets').droppable({
			tolerance: 'pointer',
			accept: function(o){
				return $(o).parent().attr('id') != 'widget-list';
			},
			drop: function(e,ui) {
				ui.draggable.addClass('deleting');
				$('#removing-widget').hide().children('span').html('');
			},
			over: function(e,ui) {
				ui.draggable.addClass('deleting');
				$('div.widget-placeholder').hide();

				if ( ui.draggable.hasClass('ui-sortable-helper') )
					$('#removing-widget').show().children('span')
					.html( ui.draggable.find('div.widget-title').children('h4').html() );
			},
			out: function(e,ui) {
				ui.draggable.removeClass('deleting');
				$('div.widget-placeholder').show();
				$('#removing-widget').hide().children('span').html('');
			}
		});
		
		// Area Chooser
		$( '#widgets-right .grid-area' ).each( function( index, element ) {
			var $element = $( element ),
				name = $element.find( '.grid-name h3' ).text(),
				id = $element.find( '.widgets-sortables' ).attr( 'id' ),
				li = $('<li tabindex="0">').text( $.trim( name ) );

			if ( index === 0 ) {
				li.addClass( 'widgets-chooser-selected' );
			}

			selectSidebar.append( li );
			li.data( 'sidebarId', id );
		});

		$( '#available-widgets .widget .widget-title' ).on( 'click.widgets-chooser', function() {
			var $widget = $(this).closest( '.widget' );

			if ( $widget.hasClass( 'widget-in-question' ) || $( '#widgets-left' ).hasClass( 'chooser' ) ) {
				self.closeChooser();
			} else {
				// Open the chooser
				self.clearWidgetSelection();
				$( '#widgets-left' ).addClass( 'chooser' );
				$widget.addClass( 'widget-in-question' ).children( '.widget-description' ).after( chooser );

				chooser.slideDown( 300, function() {
					selectSidebar.find('.widgets-chooser-selected').focus();
				});

				selectSidebar.find( 'li' ).on( 'focusin.widgets-chooser', function() {
					selectSidebar.find('.widgets-chooser-selected').removeClass( 'widgets-chooser-selected' );
					$(this).addClass( 'widgets-chooser-selected' );
				} );
			}
		});

		// Add event handlers
		chooser.on( 'click.widgets-chooser', function( event ) {
			var $target = $( event.target );

			if ( $target.hasClass('button-primary') ) {
				self.addWidget( chooser );
				self.closeChooser();
			} else if ( $target.hasClass('button-secondary') ) {
				self.closeChooser();
			}
		}).on( 'keyup.widgets-chooser', function( event ) {
			if ( event.which === $.ui.keyCode.ENTER ) {
				if ( $( event.target ).hasClass('button-secondary') ) {
					// Close instead of adding when pressing Enter on the Cancel button
					self.closeChooser();
				} else {
					self.addWidget( chooser );
					self.closeChooser();
				}
			} else if ( event.which === $.ui.keyCode.ESCAPE ) {
				self.closeChooser();
			}
		});
				
	},

	saveOrder : function( sidebarId ) {
		var data = {
			action: 'widgets-order',
			savewidgets: $('#_wpnonce_widgets').val(),
			sidebars: []
		};

		if ( sidebarId ) {
			//$('#' + sidebarId).closest('div.widgets-holder-wrap').find('.spinner:first').css('display', 'inline-block');
			$( '#' + sidebarId ).find( '.spinner:first' ).addClass( 'is-active' );
		}

		$('div.widgets-sortables').each( function() {
			if ( $(this).sortable ) {
				data['sidebars[' + $(this).attr('id') + ']'] = $(this).sortable('toArray').join(',');
			}
		});

		$.post( ajaxurl, data, function() {
			$( '.spinner' ).removeClass( 'is-active' );
		});
	},

	save : function(widget, del, animate, order) {
		var sb = widget.closest('div.widgets-sortables').attr('id'), data = widget.find('form').serialize(), a;
		widget = $(widget);
		$('.spinner', widget).addClass( 'is-active' );

		a = {
			action: 'save-widget',
			savewidgets: $('#_wpnonce_widgets').val(),
			sidebar: sb
		};

		if ( del )
			a['delete_widget'] = 1;

		data += '&' + $.param(a);

		$.post( ajaxurl, data, function(r){
			var id;

			if ( del ) {
				if ( !$('input.widget_number', widget).val() ) {
					id = $('input.widget-id', widget).val();
					$('#available-widgets').find('input.widget-id').each(function(){
						if ( $(this).val() == id )
							$(this).closest('div.widget').show();
					});
				}

				if ( animate ) {
					order = 0;
					widget.slideUp('fast', function(){
						$(this).remove();
						wpWidgets.saveOrder();
					});
				} else {
					widget.remove();
					wpWidgets.resize();
				}
			} else {
				$('.spinner').removeClass( 'is-active' );
				if ( r && r.length > 2 ) {
					$( 'div.widget-content', widget ).html( r );
					wpWidgets.appendTitle( widget );
					$( document ).trigger( 'widget-updated', [ widget ] );
					widget.find('.wid_upload_button').each(function() {
					    jQuery(this).click();
					});
				}
			}
			if ( order ) {
				wpWidgets.saveOrder();
			}
				
			// alert("Data Loaded: " + r);
		});
	},

	appendTitle : function(widget) {
		var title = $('input[id*="-title"]', widget);
		if ( title = title.val() ) {
			title = title.replace(/<[^<>]+>/g, '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
			$(widget).children('.widget-top').children('.widget-title').children()
				.children('.in-widget-title').html(': ' + title);
		}
	},

	resize : function() {
		$('div.widgets-sortables').not('#wp_inactive_widgets').each(function(){
			var h = 50, H = $(this).children('.widget').not('.hidden').length;
			h = h + parseInt(H * 48, 10);
			$(this).css( 'minHeight', h + 'px' );
		});
	},

	fixLabels : function(widget) {
		widget.children('.widget-inside').find('label').each(function(){
			var f = $(this).attr('for');
			if ( f && f == $('input', this).attr('id') )
				$(this).removeAttr('for');
		});
	},

	close : function(widget) {
		widget.children('.widget-inside').slideUp('fast', function(){
			widget.css({'width':'', margin:''});
		});
	},
	
	addWidget: function( chooser ) {
		var widget, widgetId, add, n, viewportTop, viewportBottom, sidebarBounds,
			sidebarId = chooser.find( '.widgets-chooser-selected' ).data('sidebarId'),
			sidebar = $( '#' + sidebarId );

		widget = $('#available-widgets').find('.widget-in-question').clone();
		widgetId = widget.attr('id');
		add = widget.find( 'input.add_new' ).val();
		n = widget.find( 'input.multi_number' ).val();

		// Remove the cloned chooser from the widget
		widget.find('.widgets-chooser').remove();

		if ( 'multi' === add ) {
			widget.html(
				widget.html().replace( /<[^<>]+>/g, function(m) {
					return m.replace( /__i__|%i%/g, n );
				})
			);

			widget.attr( 'id', widgetId.replace( '__i__', n ) );
			n++;
			$( '#' + widgetId ).find('input.multi_number').val(n);
		} else if ( 'single' === add ) {
			widget.attr( 'id', 'new-' + widgetId );
			$( '#' + widgetId ).hide();
		}

		// Open the widgets container
		sidebar.closest( '.widgets-holder-wrap' ).removeClass('closed');

		sidebar.append( widget );
		sidebar.sortable('refresh');

		wpWidgets.save( widget, 0, 0, 1 );
		wpWidgets.saveWidgetlogic( widget, 0, 0, 1 );
		// No longer "new" widget
		widget.find( 'input.add_new' ).val('');
		
		$( document ).trigger( 'widget-added', [ widget ] );

		/*
		 * Check if any part of the sidebar is visible in the viewport. If it is, don't scroll.
		 * Otherwise, scroll up to so the sidebar is in view.
		 *
		 * We do this by comparing the top and bottom, of the sidebar so see if they are within
		 * the bounds of the viewport.
		 */
		viewportTop = $(window).scrollTop();
		viewportBottom = viewportTop + $(window).height();
		sidebarBounds = sidebar.offset();

		sidebarBounds.bottom = sidebarBounds.top + sidebar.outerHeight();

		if ( viewportTop > sidebarBounds.bottom || viewportBottom < sidebarBounds.top ) {
			$( 'html, body' ).animate({
				scrollTop: sidebarBounds.top - 130
			}, 200 );
		}

		window.setTimeout( function() {
			// Cannot use a callback in the animation above as it fires twice,
			// have to queue this "by hand".
			widget.find( '.widget-title' ).trigger('click');
		}, 250 );
	},

	closeChooser: function() {
		var self = this;

		$( '.widgets-chooser' ).slideUp( 200, function() {
			$( '#wpbody-content' ).append( this );
			self.clearWidgetSelection();
		});
	},

	clearWidgetSelection: function() {
		$( '#widgets-left' ).removeClass( 'chooser' );
		$( '.widget-in-question' ).removeClass( 'widget-in-question' );
	},
	
	saveLayouts : function() {
		function newValues() {
			var serializedValues = $("#post-body-content :input[class][class='accepted']").serialize();
			return serializedValues;
		}
		var serializedReturn = newValues(),
			data = {
				type: 'bizz-grids',
				action: 'bizz_ajax_post_action',
				data: serializedReturn
			};
		$.post( ajaxurl, data, function(response) {
			$('.spinner').removeClass( 'is-active' );
			// alert("Data Loaded: " + response);
		});
	},
	
	saveWidgetlogic : function(widget,parent,deleting,enable) {
		var serializedReturn1 = widget.find('form').serialize(),
			serializedReturn2 = $("#post-body-content :input[class][class='cond_item']").serialize(),
			serializedReturn3 = ( parent ) ? $("#post-body-content :input[class][class='is_parent'],:input[class][class='empty_parent']").serialize() : $("#post-body-content :input[class][class='not_parent'],:input[class][class='empty_parent']").serialize(), // deleting widget from parent condition?
			serializedReturn4 = ( enable ) ? $("#post-body-content :input[class][class='is_enabled'],:input[class][class='empty_parent']").serialize() : $("#post-body-content :input[class][class='not_enabled'],:input[class][class='empty_parent']").serialize(), // enabled?
			ajax_type = ( deleting ) ? 'bizz-widgetlogic-delete' : 'bizz-widgetlogic', // deleting or adding widget?
			serializedReturn = serializedReturn1+serializedReturn2+serializedReturn3+serializedReturn4, // merge arrays
			data = {
				type: ajax_type,
				action: 'bizz_ajax_post_action',
				data: serializedReturn
			};
		$.post( ajaxurl, data, function(response) {
			$('.spinner').removeClass( 'is-active' );
			// alert("Data Loaded: " + response);
		});
	},
	
	sidebarsBackup : function() {
		var data = {
			type: 'bizz-sidebars-backup',
			action: 'bizz_ajax_post_action'
		};
		$.post( ajaxurl, data, function(response) {
			// alert("Data Loaded: " + response);
		});
	}
	
};

$(document).ready(function($){ wpWidgets.init(); });

})(jQuery);
