jQuery.noConflict();
jQuery(document).ready(function($){
	
	$('#master_switch').children('.neg').hide();
	$('.maintable .subheading a').children('.neg').hide();
	$('.feature-box .subheading a').parents('.subheading').siblings('.options-box').hide();
	
	$('#master_switch').click(function() {
		$(this).toggleClass('active');
		$(this).children('.pos').toggle();
		$(this).children('.neg').toggle();
		$('.subheading a').toggleClass('active');
		$('.subheading a').children('.pos').toggle();
		$('.subheading a').children('.neg').toggle();
		$('.options-box').toggle();
		return false;
	});
	
	$('.feature-box .subheading a').click(function() {
		$(this).toggleClass('active');
		$(this).children('.pos').toggle();
		$(this).children('.neg').toggle();
		$(this).parents('.subheading').siblings('.options-box:first').toggle();
		return false;
	});
	
	// Sortable behaviors
	$(function() {
		$("div[id*=sortme]").sortable({});
	});
			
	// Description Tooltip Toggle
	$('span.trigger').click(function() {
		$(this).parent('.bubbleInfo').children('.popup').toggle();
		return false;
	});
			
	// Hide Blocks if clicked outside
	$(document).click(function() {
		$('.popup').hide();
	});

	// toggle title icon
	$('a.tittoggle').live('click', function() {
		$(this).parents().find('.wid_toggle').toggle();
		$(this).parents().find('.wid_toggle').click();
		return false;
	});
	// toggle custom logo upload
	$('a.custom_logo').live('click', function() {
		$(this).parents().find('.wid_upload_wrap').toggle();
		$(this).parents().find('.wid_upload_button').click();
		return false;
	});
	$('.bizzwidget_ops input.radio').live('click', function(){
		if ( $('.bizzwidget_ops input.radio').length > 1 ) {
			$('div.ops_box').filter(':visible').slideUp(250);
			if ( $(this).is(':checked') )
				$('div.ops_box.' + $(this).attr('ID')).slideDown(250);
		} else {
			$('div.ops_box').show();
		}
	});
	$('.bizzwidget_ops label').live('click', function(){
		$(this).parent().find("input").attr("checked", true);
	});
	
	// toggle custom link
	$('a.custom_link').live('click', function() {
		$(this).parents().find('.wid_logo_link').toggle();
		$(this).parents().find('.wid_logo_link').click();
		return false;
	});
	// Contact Form Widget
	$('span.translate').live('click', function() {
		$(this).parents('.widget-content').find('.tog').toggle();
		return false;
	});
	// Show/hide on select option
	$('.showhidediv').hide();
	$('input.showhide').each(function() {
		if( $(this).attr("value") == "cus_logo" && $(this).attr("checked") )
			$(this).parents(".widget-content").find('.showhidediv').show();
	});
	$('input.showhide').click(function(){
		if ($(this).attr("value") == "cus_logo")
			$(this).parents(".widget-content").find('.showhidediv').show();
    });
	
	// Prepare form to be ajaxified
	$('form#post').attr('enctype','multipart/form-data');
	$('form#post').attr('encoding','multipart/form-data');
			
	// AJAX Upload for Widgets		
	$('.wid_upload_button').live('click', function() {
		// $('.wid_upload_button').each(function() {
		var clickedObject = $(this),
			clickedID = $(this).attr('id');
		new AjaxUpload(clickedID, {
			action: ajaxurl,
			name: clickedID, // File upload name
			data: { // Additional data to send
				action: 'bizz_ajax_post_action',
				type: 'upload',
				data: clickedID },
			autoSubmit: true, // Submit file after selection
			responseType: false,
			onChange: function(file, extension){},
			onSubmit: function(file, extension){
				clickedObject.text('Choose File'); // change button text, when user selects file
				this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
				interval = window.setInterval(function(){
					var text = clickedObject.text();
					if (text.length < 13){	
						clickedObject.text(text + '.'); 
					} else { 
						clickedObject.text('Choose File'); 
					}
				}, 200);
			},
			onComplete: function(file, response) {
				window.clearInterval(interval);
				clickedObject.text('Choose File');
				this.enable(); // enable upload button
				// AJAX response before saving
				$(".upload-error").remove();
				clickedObject.next('input').val(response);
				// alert("Data Loaded: " + response);
			}
		});
	});
	
	// UPDATE MESSAGE POPUP
	// animation positioning
	$.fn.center = function () {
		this.animate({
			"top" : ( $(window).height() - this.height() - 100 ) / 2+$(window).scrollTop() + "px",
			"left" : ( $(window).width() - this.width() + $('#adminmenuwrap').width() ) / 4+$(window).scrollLeft() + "px"
		}, 50);
		return this;
	}
	// animation class to call
	$('#bizz-popup-save').center();
	$(window).scroll(function() {
		$('#bizz-popup-save').center();
	});
	$(window).resize(function() {
		$('#bizz-popup-save').center();
	});
			
	// AJAX upload for theme options
	//$('.upload_button').live('click', function(e) { 
	$('.upload_button').each(function() {
		var clickedObject = $(this),
			clickedID = $(this).attr('id');
		new AjaxUpload(clickedID, {
			action: ajaxurl,
			name: clickedID, // File upload name
			data: { // Additional data to send
				action: 'bizz_ajax_post_action',
				type: 'upload',
				data: clickedID },
			autoSubmit: true, // Submit file after selection
			responseType: false,
			onChange: function(file, extension){},
			onSubmit: function(file, extension){
				clickedObject.text('Choose File'); // change button text, when user selects file
				this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
				interval = window.setInterval(function(){
					var text = clickedObject.text();
					if (text.length < 13){	
						clickedObject.text(text + '.'); 
					} else { 
						clickedObject.text('Choose File'); 
					}
				}, 200);
			},
			onComplete: function(file, response) {
				window.clearInterval(interval);
				clickedObject.text('Choose File');
				this.enable(); // enable upload button
				// AJAX response before saving
				var buildReturn = '<a class="img-preview" href="'+response+'"><img src="'+response+'" width="10" height="10" alt="Image Preview" /></a>';
				$(".upload-error").remove();
				$("#image_" + clickedID).remove();
				clickedObject.next('input').after(buildReturn);
				$('img#image_'+clickedID).fadeIn();
				clickedObject.next('input').val(response);
				// alert("Data Loaded: " + response);
			}
		});
	});
	
	// SAVING ALL OPTIONS:design
	$('#bizz_form_design').submit(function(){
		function newValues() {
			var serializedValues = $("#bizz_form_design").serialize();
			return serializedValues;
		}
		var success = $('#bizz-popup-save'),
			loading = $('#ajax-loading');
		$(":checkbox, :radio").click(newValues);
		$("select").change(newValues);
		loading.center().fadeIn();
		var serializedReturn = newValues(),
			ajax_url = ajaxurl,
			data = {
				type: 'bizz-design',
				action: 'bizz_ajax_post_action',
				data: serializedReturn
			};
		$.post(ajax_url, data, function(response) {
			loading.fadeOut();  
			success.fadeIn();
			window.setTimeout(function(){
				success.fadeOut(); 
			}, 2000);
			// alert("Data Loaded: " + response);
		});
		return false; 
	});

	// SAVING ALL OPTIONS:options
	$('#bizz_form').submit(function(){
		function newValues() {
			var serializedValues = $("#bizz_form").serialize();
			return serializedValues;
		}
		var success = $('#bizz-popup-save'),
			loading = $('#ajax-loading');
		$(":checkbox, :radio").click(newValues);
		$("select").change(newValues);
		loading.center().fadeIn();
		var serializedReturn = newValues(),
			ajax_url = ajaxurl,
			data = {
				type: 'bizz-all',
				action: 'bizz_ajax_post_action',
				data: serializedReturn
			};
		$.post(ajax_url, data, function(response) {
			loading.fadeOut();  
			success.fadeIn();
			window.setTimeout(function(){
				success.fadeOut(); 
			}, 2000);
			// alert("Data Loaded: " + response);
		});
		return false; 
	});
	
	// SAVING CLOSED INFO BOX
	$('.info-box a.btn-close').live('click',function(){
	    var ajax_url = ajaxurl,
			data = {
				type: 'bizz-info-layout',
				action: 'bizz_ajax_post_action',
				data: ''
			};
	    $.post(ajax_url, data, function(response) {
			$('.info-box').slideUp('slow',function(){ $(this).remove();});
	    });
	    return false; 
	});
	
	// Treeview
	$(".menu-tabs ul ul").treeview({
		persist: "location",
		collapsed: true,
		unique: true
	});
	// Treeview: ajax
	$('li.single_tab li .hitarea').click(function(){
		var current = $(this),
			parent = $(this).parent(),
			data = {
				type: 'bizz-treeview',
				action: 'bizz_ajax_post_action',
				data: parent.attr('id')
			};
		if ( !current.hasClass('menu-loaded') ) {
			$.post( ajaxurl, data, function(response) {
				parent.find('ul').remove();
				parent.append(response);
				parent.find('ul').addClass('treeview');
				parent.find('ul').css('display', 'block');
				parent.find('ul').css('background', 'none');
				parent.find('li:last-child').addClass('last');
				current.addClass('menu-loaded');
				// alert("Data Loaded: " + response);
			});
		}
		return false; 
	});
	// Treeview: ajax paginateme
	$('div[class*=linkedp]').live('click', function() {		
		var current = $(this),
			parent = $(this).parent(),
			loading = parent.find('.ajax-loader'),
			data = {
				type: 'bizz-treeview-paginateme',
				action: 'bizz_ajax_post_action',
				data: parent.attr('id'),
				paged: $(this).attr('rel')
			};
		current.removeClass('linkedp');
		loading.fadeIn();
		$.post( ajaxurl, data, function(response) {
			loading.remove();
			parent.append(response);
			parent.find('ul').addClass('treeview');
			parent.find('ul').css('display', 'block');
			parent.find('ul').css('background', 'none');
			parent.find('li:last-child').addClass('last');
			// alert("Data Loaded: " + response);
		});
		return false; 
	});
	
	// SAVING CLOSED INFO BOX
	$('.recover a').live('click',function(){
	    var ajax_url = ajaxurl,
			serializedReturn = $(this).attr('id'),
			data = {
				type: 'bizz-info-recover',
				action: 'bizz_ajax_post_action',
				data: serializedReturn
			};
	    $.post(ajax_url, data, function(response) {
			alert(response);
			$('.recover').fadeOut('slow',function(){ $(this).remove();});
	    });
	    return false; 
	});
	
	// SAVING CLOSED INFO BOX
	$('a.dropdown-toggle').live('click',function(){
		$(this).parent().toggleClass('tgl');
		$(this).toggleClass('active');
		$(this).parent().children('.dropdown-menu').toggleClass('open');
		return false;
	});
	// Hide Blocks if clicked outside
	$(document).click(function(e) {
		if ( $(e.target).closest('.dropdown-menu').length === 0 ) {
			$('.dropdown-menu').parent().removeClass('tgl');
			$('a.dropdown-toggle').removeClass('active');
			$('.dropdown-menu').removeClass('open');
		}
	});
		
});