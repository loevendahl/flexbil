/*

*	@name							Valid8

*	@descripton						An input field validation plugin for Jquery

*	@version						1.3

*	@requires						Jquery 1.3.2+

*

*	@author							Jan Jarfalk

*	@author-email					jan.jarfalk@unwrongest.com

*	@author-website					http://www.unwrongest.com

*

*	@licens							MIT License - http://www.opensource.org/licenses/mit-license.php

*/

(function($){$.fn.extend({valid8:function(b){return this.each(function(){$(this).data('valid',false);var a={regularExpressions:[],ajaxRequests:[],jsFunctions:[],validationEvents:['keyup','blur'],validationFrequency:1000,values:null,defaultErrorMessage:'Required'};if(typeof b=='string')a.defaultErrorMessage=b;if(this.type=='checkbox'){a.regularExpressions=[{expression:/^true$/,errormessage:a.defaultErrorMessage}];a.validationEvents=['click']}else a.regularExpressions=[{expression:/^.+$/,errormessage:a.defaultErrorMessage}];$(this).data('settings',$.extend(a,b));initialize(this)})},isValid:function(){var a=true;this.each(function(){validate(this);if($(this).data('valid')==false)a=false});return a}});function initializeDataObject(a){$(a).data('loadings',new Array());$(a).data('errors',new Array());$(a).data('valids',new Array());$(a).data('keypressTimer',null)}function initialize(a){initializeDataObject(a);activate(a)};function activate(b){var c=$(b).data('settings').validationEvents;if(typeof c=='string')$(b)[c](function(e){handleEvent(e,b)});else{$.each(c,function(i,a){$(b)[a](function(e){handleEvent(e,b)})})}};function validate(a){initializeDataObject(a);var b;if(a.type=='checkbox')b=a.checked.toString();else b=a.value;regexpValidation(b.replace(/^[ \t]+|[ \t]+$/,''),a)};function regexpValidation(b,c){$.each($(c).data('settings').regularExpressions,function(i,a){if(!a.expression.test(b))$(c).data('errors')[$(c).data('errors').length]=a.errormessage;else if(a.validmessage)$(c).data('valids')[$(c).data('valids').length]=a.validmessage});if($(c).data('errors').length>0)onEvent(c,'error',false);else if($(c).data('settings').jsFunctions.length>0){functionValidation(b,c)}else if($(c).data('settings').ajaxRequests.length>0){fileValidation(b,c)}else{onEvent(c,'valid',true)}};function functionValidation(c,d){$.each($(d).data('settings').jsFunctions,function(i,a){var v;if(a.values){if(typeof a.values=='function')v=a.values()}var b=v||c;handleLoading(d,a);if(a['function'](b).valid)$(d).data('valids')[$(d).data('valids').length]=a['function'](b).message;else $(d).data('errors')[$(d).data('errors').length]=a['function'](b).message});if($(d).data('errors').length>0)onEvent(d,'error',false);else if($(d).data('settings').ajaxRequests.length>0){fileValidation(c,d)}else{onEvent(d,'valid',true)}};function fileValidation(e,f){$.each($(f).data('settings').ajaxRequests,function(i,c){var v;if(c.values){if(typeof c.values=='function')v=c.values()}var d=v||{value:e};handleLoading(f,c);$.post(c.url,d,function(a,b){if(a.valid){$(f).data('valids')[$(f).data('valids').length]=a.message||c.validmessage||""}else{$(f).data('errors')[$(f).data('errors').length]=a.message||c.errormessage||""}if($(f).data('errors').length>0)onEvent(f,'error',false);else{onEvent(f,'valid',true)}},"json")})};function handleEvent(e,a){if(e.keyCode&&$(a).attr('value').length>0){clearTimeout($(a).data('keypressTimer'));$(a).data('keypressTimer',setTimeout(function(){validate(a)},$(a).data('settings').validationFrequency))}else if(e.keyCode&&$(a).attr('value').length<=0)return false;else{validate(a)}};function handleLoading(a,b){if(b.loadingmessage){$(a).data('loadings')[$(a).data('loadings').length]=b.loadingmessage;onEvent(a,'loading',false)}};function onEvent(a,b,c){var d=b.substring(0,1).toUpperCase()+b.substring(1,b.length),messages=$(a).data(b+'s');$(a).data(b,c);setStatus(a,b);setParentClass(a,b);setMessage(messages,a);$(a).trigger(b,[messages,a,b])}function setParentClass(a,b){var c=$(a).parent();c[0].className=(c[0].className.replace(/(^\s|(\s*(loading|error|valid)))/g,'')+' '+b).replace(/^\s/,'')}function setMessage(a,b){var c=$(b).parent();var d=b.id+"ValidationMessage";var e='validationMessage';if(!$('#'+d).length>0){c.append('<span id="'+d+'" class="'+e+'"></span>')}$('#'+d).html("");$('#'+d).text(a[0])};function setStatus(a,b){if(b=='valid'){$(a).data('valid',true)}else if(b=='error'){$(a).data('valid',false)}}})(jQuery);



/*

 *	Booking pop-up dialog

 */

jQuery.noConflict();

jQuery(document).ready(function($) {

	

	// AJAX on initial load

	var ajax_url = bizz_localize.ajaxurl,

		cookie = { action: 'booking_cookie' };



	// cookie (if available)

	$.getJSON(ajax_url, cookie, function(response) {

		var cookie = response,

			pickup_f = jQuery(".pickup_group").contents(),

			return_f = jQuery(".return_group").contents();

		

		// read from cookie

		if ( cookie != 'nocookie') {

						

			// step 1

			pickup_f.find(".location_p option[value='" + cookie.location_of_pickup +"']").attr("selected", "selected");

			pickup_f.find("input.pickup_l").val(cookie.location_of_pickup);

			pickup_f.find(".booking_date").val(cookie.date_of_pickup_locale);

			pickup_f.find(".pickup_d").val(cookie.date_of_pickup_d);

			pickup_f.find(".pickup_m").val(cookie.date_of_pickup_m);

			pickup_f.find(".pickup_y").val(cookie.date_of_pickup_y);

			pickup_f.find(".pickup_dn").val(cookie.date_of_pickup_dn);

			pickup_f.find(".booking_date, .time_field").prop('disabled', false);

			pickup_f.find(".time_field").val(cookie.hour_of_pickup);

			if ( cookie.location_of_return != cookie.location_of_pickup ) {

				jQuery(".return_checkbox").contents().find(".return_l_c").attr("checked", "checked") ;

				jQuery(".return_group_location").removeClass("hidden") ;

				return_f.find(".location_r option[value='" + cookie.location_of_return +"']").attr("selected", "selected") ;

			}

			return_f.find("input.return_l").val(cookie.location_of_return);

			return_f.find(".booking_date").val(cookie.date_of_return_locale);

			return_f.find(".return_d").val(cookie.date_of_return_d);

			return_f.find(".return_m").val(cookie.date_of_return_m);

			return_f.find(".return_y").val(cookie.date_of_return_y);

			return_f.find(".return_dn").val(cookie.date_of_return_dn);

			return_f.find(".booking_date, .time_field").prop('disabled', false);

			return_f.find(".time_field").val(cookie.hour_of_return);

			

			// alert(cookie);

		

		}

		

		// create own default data

		else {

			

			// pickup

			var date_p = new Date();

			var min_date_p = ( parseInt( objectL10n.minDateP ) != 0 ) ? parseInt( objectL10n.minDateP ) : 1;

			date_p.setDate( date_p.getDate() + min_date_p );

			var date_locale_p = $.datepicker.formatDate( objectL10n.dateFormat, date_p, {

				monthNames: objectL10n.monthNames,

				monthNamesShort: objectL10n.monthNamesShort,

				dayNames: objectL10n.dayNames,

				dayNamesShort: objectL10n.dayNamesShort,

			});

			var day_p = date_p.getDate();

			var month_p = date_p.getMonth() + 1;

			var year_p = date_p.getFullYear();

			var dayn_p = date_p.getDay();

						

			// return

			var date_r = new Date();

			var min_date_r = ( parseInt( objectL10n.minDateR ) != 1 ) ? parseInt( objectL10n.minDateR ) : 2;

			date_r.setDate( date_r.getDate() + min_date_r );

			var date_locale_r = $.datepicker.formatDate( objectL10n.dateFormat, date_r, {

				monthNames: objectL10n.monthNames,

				monthNamesShort: objectL10n.monthNamesShort,

				dayNames: objectL10n.dayNames,

				dayNamesShort: objectL10n.dayNamesShort,

			});

			var day_r = date_r.getDate();

			var month_r = date_r.getMonth()+1;

			var year_r = date_r.getFullYear();

			var dayn_r = date_r.getDay();

		

			// step 1: pickup

			pickup_f.find(".booking_date").val(date_locale_p);

			pickup_f.find(".pickup_d").val(day_p);

			pickup_f.find(".pickup_m").val(month_p);

			pickup_f.find(".pickup_y").val(year_p);

			pickup_f.find(".pickup_dn").val(dayn_p);

			pickup_f.find(".booking_date, .time_field").prop('disabled', false);

			

			// step 1: return

			return_f.find(".booking_date").val(date_locale_r);

			return_f.find(".return_d").val(day_r);

			return_f.find(".return_m").val(month_r);

			return_f.find(".return_y").val(year_r);

			return_f.find(".return_dn").val(dayn_r);

			return_f.find(".booking_date, .time_field").prop('disabled', false);

		

		}

		

		return false;

		

	});

	

	$('.return_l_c').click( function() {

		if( $(this).is(':checked')) {

			$(".return_group_location").removeClass("hidden")

		} else {

			$(".return_group_location").addClass("hidden");

		}

	});

	

	$('.coupon_checkbox').click( function() {

		if( $(this).is(':checked')) {

			$(".coupon_group").removeClass("hidden")

		} else {

			$(".coupon_group").addClass("hidden");

		}

	}); 

	

	// Tab links

	$(".tablink, a.chng").live('click', function (e) {

		e.preventDefault();

		

		var thistab = this,

			current_rel = thistab.getAttribute("data-rel");

			

		if ( $(this).hasClass('disabled') ) {

			return;

		}

		

		// only animate, when 1 car list is present

		if ( $("ul[id*='car_list']").length < 2 ) {

			$('html, body').animate({scrollTop:$('#booktop').offset().top}, 'slow');

		}

			

		$( document ).triggerHandler( 'book-tab-links', [ thistab, current_rel ] );



	});

	

	// Ajax submission | step 1

	$('form#book_form button[type=submit]').live('click', function(e) {

		e.preventDefault();

		

		// validate form

		var form = $(this).parents('form#book_form'),

			form_data = $(this).parents('form#book_form').serialize(),

			ajax_url = bizz_localize.ajaxurl,

			loading = $(this).parents('form#book_form').find('.loading'),

			messages = $(this).parents('.bookwrap').find('.messages'),

			data = {

				action: 'booking_form_action',

				data: form_data

			};

			

		// loading show

		loading.css('display', 'inline-block');

		

		$.post(ajax_url, data, function(response) {

			// alert(response);

			

			// loading hide

			loading.css('display', 'none');

			

			// load step 2

			if (response == "SUCCESS") {

				messages.empty();

				// $("#book_form").dialog("open");

				var params_array = {

					'date_of_pickup': form.find("input[name='date_pickup']").val(),

					'pickup_d': form.find("input[name='pickup_d']").val(),

					'pickup_m': form.find("input[name='pickup_m']").val(),

					'pickup_y': form.find("input[name='pickup_y']").val(),

					'pickup_dn': form.find("input[name='pickup_dn']").val(),

					'hour_of_pickup': form.find("select[name='time_pickup'] option:selected").val(),

					'date_of_return': form.find("input[name='date_return']").val(),

					'return_d': form.find("input[name='return_d']").val(),

					'return_m': form.find("input[name='return_m']").val(),

					'return_y': form.find("input[name='return_y']").val(),

					'return_chk': form.find("input[name='return_l_checkbox']").prop('checked'),

					'return_dn': form.find("input[name='return_dn']").val(),

					'hour_of_return': form.find("select[name='time_return'] option:selected").val(), 

					'location_of_pickup': form.find("input[name='pickup_l']").val(), 

					'location_of_return': form.find("input[name='return_l']").val(),

					'coupon_code': ( form.find("input[name='coupon_checkbox']").prop('checked') ) ? form.find("input[name='coupon_code']").val() : ''

				};

				

				$( document ).triggerHandler( 'load-step-two', [ params_array ] );

				

			}

			// error

			else {

				messages.html('<div class="alert alert-danger">' + response + '</div>');

			}

			

		});



		return false;

		

	});

	

	// Reset form

	$('form#book_form button[type=reset]').live('click', function() {

		$(this).parents('form').find(':input:not(.location_input)',':select').val('').removeAttr('checked').removeAttr('selected');

		$(this).parents('.bookwrap').find('.messages').empty();

		//$(this).parents('.bookwrap').find('.time_field').append($("<option></option>"));

		//$(this).parents('.bookwrap').find('.booking_date, .time_field').prop('disabled', true);

		clearUserCookie();

		return false;

	});

	

	// Remove disabled on location select

	$('.location_p').live('change', function() {

		var selected = $( this ).find("option:selected").val();

		$(this).parents('.table_group').find('.booking_date, .time_field').prop('disabled', false);

		$(this).parents('.table_group').find('.location_input').val( selected );

		// alert( selected );

	});

	

	$('.location_r').live('change', function() {

		var selected = $( this ).find("option:selected").val();

		$(this).parents('.table_group').find('.return_group').contents().find('.booking_date, .time_field').prop('disabled', false);

		$(this).parents('.table_group').find('.return_group').contents().find('.booking_date').val('');

		$(this).parents('.table_group').find('.return_l').val( selected );

		// alert( selected );

	});

	

	// Preselect extra checkbox, when user types in input field

	$(".extra_field").live("change", function() {

		var ele = $(this).parents('li').find(':checkbox');

		ele.prop('checked', true);

	});

	

	// Add date picker

	$('.booking_date[name="date_pickup"]').datepicker({

		minDate: objectL10n.minDateP,

		closeText: objectL10n.closeText,

		currentText: objectL10n.currentText,

		monthNames: objectL10n.monthNames,

		monthNamesShort: objectL10n.monthNamesShort,

		dayNames: objectL10n.dayNames,

		dayNamesShort: objectL10n.dayNamesShort,

		dayNamesMin: objectL10n.dayNamesMin,

		dateFormat: objectL10n.dateFormat,

		firstDay: objectL10n.firstDay,

		isRTL: objectL10n.isRTL,

		onSelect: function(selectedDate,inst) {

									

			var newDate = $(this).datepicker('getDate');

			var startDate = new Date(newDate);

			var selDay = startDate.getDay();

			var timeSelect = $(this).parents('.grp').find('.time_field');

			var locationSelect = $('.location_input.pickup_l').val();

						

			// ajaxed time selector								

			var ajax_url = bizz_localize.ajaxurl,

				data = {

					action: 'booking_time_action',

					dature: selectedDate,

					day: selDay,

					d: parseInt( inst.selectedDay ),

					m: parseInt( inst.selectedMonth + 1 ), // +1 fix

					y: parseInt( inst.selectedYear ),

					date: newDate,

					location: locationSelect,

					location_type: 'pickup'

					

				};

			

			$.post(ajax_url, data, function(response) {

				// alert("Data Loaded: " + response);

				

				if (response == "EMPTY") {

					alert(bizzlang.book_empty);

				}

				else if (response == "CLOSED") {

					alert(bizzlang.book_closed);

				}

				else if (response == "PAST") {

					alert(bizzlang.book_past);

				}

				else {

					timeSelect.find("option").remove();

					timeSelect.append(response);

				}

				

			});



		},

		onClose: function( selectedDate, inst ) {

			var newDate = $(this).datepicker('getDate');

			var startDate = new Date(newDate);

			var nextDay = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate()+1);

			var selDay = startDate.getDay();			

			$('#book_form [name="pickup_d"]').val( parseInt( inst.selectedDay ) );

			$('#book_form [name="pickup_m"]').val( parseInt( inst.selectedMonth + 1 ) );

			$('#book_form [name="pickup_y"]').val( parseInt( inst.selectedYear ) );

			$('#book_form [name="pickup_dn"]').val( selDay );

			$('.booking_date[name="date_return"]').datepicker( "option", "minDate", selectedDate );

			$(this).attr("readonly", false);

		},

		beforeShow: function(input, inst) {

			setTimeout(function(){

				$('.ui-datepicker').css('z-index', 99999999999999);

			}, 0);

			$(this).attr('readonly', 'readonly');

		}

	}).on( 'focus', function() {

        $(this).trigger('blur');

    });

	

	// Add date picker

	$('.booking_date[name="date_return"]').datepicker({

		minDate: objectL10n.minDateR,

		closeText: objectL10n.closeText,

		currentText: objectL10n.currentText,

		monthNames: objectL10n.monthNames,

		monthNamesShort: objectL10n.monthNamesShort,

		dayNames: objectL10n.dayNames,

		dayNamesShort: objectL10n.dayNamesShort,

		dayNamesMin: objectL10n.dayNamesMin,

		dateFormat: objectL10n.dateFormat,

		firstDay: objectL10n.firstDay,

		isRTL: objectL10n.isRTL,

		onSelect: function(selectedDate, inst) {

									

			var newDate = $(this).datepicker('getDate');

			var startDate = new Date(newDate);

			var selDay = startDate.getDay();

			var timeSelect = $(this).parents('.grp').find('.time_field');

			var locationSelect = $('.location_input.return_l').val();

						

			// ajaxed time selector								

			var ajax_url = bizz_localize.ajaxurl,

				data = {

					action: 'booking_time_action',

					dature: selectedDate,

					day: selDay,

					d: parseInt( inst.selectedDay ),

					m: parseInt( inst.selectedMonth + 1 ), // +1 fix

					y: parseInt( inst.selectedYear ),

					date: newDate,

					location: locationSelect,

					location_type: 'return'

					

				};

			

			$.post(ajax_url, data, function(response) {

				// alert("Data Loaded: " + response);

				

				if (response == "EMPTY") {

					alert(bizzlang.book_empty);

				}

				else if (response == "CLOSED") {

					alert(bizzlang.book_closed);

				}

				else if (response == "PAST") {

					alert(bizzlang.book_past);

				}

				else {

					timeSelect.find("option").remove();

					timeSelect.append(response);

				}

				

			});



		},

		onClose: function( selectedDate, inst ) {

			var newDate = $(this).datepicker('getDate');

			var startDate = new Date(newDate);

			var selDay = startDate.getDay();

			$('#book_form [name="return_d"]').val( parseInt( inst.selectedDay ) );

			$('#book_form [name="return_m"]').val( parseInt( inst.selectedMonth + 1 ) );

			$('#book_form [name="return_y"]').val( parseInt( inst.selectedYear ) );

			$('#book_form [name="return_dn"]').val( selDay );

			$('.booking_date[name="date_pickup"]').datepicker( "option", "maxDate", selectedDate );

			$(this).attr("readonly", false);

		},

		beforeShow: function(input, inst) {

			setTimeout(function(){

				$('.ui-datepicker').css('z-index', 99999999999999);

			}, 0);

			$(this).attr('readonly', 'readonly');

		}

	}).on( 'focus', function() {

        $(this).trigger('blur');

    });

	

	// Vehicle details

	$("a.toggled").live('click', function (e) {

		e.preventDefault();

		var toggled = $(this).data('toggled');

		$(this).data('toggled', !toggled);

		if (!toggled) {

			$(this).parents('.details').find(".car_details_tooltip").slideDown('fast');

		}

		else {

			$(this).parents('.details').find(".car_details_tooltip").slideUp('fast');

		}

	});

	

	// Filter cars

	$(".car_type").live('change',function(){

		filterVehicleList( $(this) );

	});

	$(".car_sort").live('change',function(){

		sortVehicleList( $(this) );

	});

	$(".car_extras_check").live("click", function() {

		fillVehicleExtrasArray();

		$(this).parent("div").parent("li").toggleClass("selected");

	});

	$(".extra_count").live("change", function() {

		fillVehicleExtrasArray();

	});

	$(".extra_field").live("change", function() {

		fillVehicleExtrasArray();

	});

	

	// load step 3

	$(".button_select_car").live("click", function(e) {

		e.preventDefault();

		var $selected_car_li = $(this).parent("div").parent("li");

			//alert($selected_car_li.find(".car_id").val());

		var	params_array = {

			'car_id': $selected_car_li.find(".car_id").val(), 

			'car_cost': $selected_car_li.find(".car_cost").val(),

			'car_count': $selected_car_li.find("select[name='car_count'] option:selected, input[name='car_count']").val()

		};

		

		$(".carlist > li").removeClass("selected");

		$selected_car_li.addClass('selected');

		

		// move selected car to top

		$(".carlist").find("li.selected").prependTo(".carlist");

/*KIM hack		if ($('body').hasClass('logged-in')) 

		{*/

			 $( document ).triggerHandler( 'load-step-three', [ params_array ] );			

/*		}		 

		else

		{

			   window.location.assign("http://www.ok-billeje.dk/test/register/");		

		} */

		return false;

	});

	

	// load step 4

	$("#submit_car_extras").live("click", function(e) {

		e.preventDefault();

		var params_array = {

			'car_extras': $("#selected_extras").val()

		};

		

		// all fine?

		if ( $('.extras_field .validate').isValid() ) {

			$( document ).triggerHandler( 'load-step-four', [ params_array ] );

		}

	});

	

	// validate step 4

	$('.validate').valid8(bizzlang.book_required);

	$('#email').valid8({

		regularExpressions: [

			{expression: /^[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+(aero|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel.ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|.fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|.il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw|AERO|ASIA|BIZ|CAT|COM|COOP|EDU|GOV|INFO|INT|JOBS|MIL|MOBI|MUSEUM|NAME|NET|ORG|PRO|TEL|TRAVEL.AC|AD|AE|AF|AG|AI|AL|AM|AN|AO|AQ|AR|AS|AT|AU|AW|AX|AZ|BA|BB|BD|BE|BF|BG|BH|BI|BJ|BM|BN|BO|BR|BS|BT|BV|BW|BY|BZ|CA|CC|CD|CF|CG|CH|CI|CK|CL|CM|CN|CO|CR|CU|CV|CX|CY|CZ|DE|DJ|DK|DM|DO|DZ|EC|EE|EG|ER|ES|ET|EU|FI|FJ|FK|FM|.FO|FR|GA|GB|GD|GE|GF|GG|GH|GI|GL|GM|GN|GP|GQ|GR|GS|GT|GU|GW|GY|HK|HM|HN|HR|HT|HU|ID|IE|.IL|IM|IN|IO|IQ|IR|IS|IT|JE|JM|JO|JP|KE|KG|KH|KI|KM|KN|KP|KR|KW|KY|KZ|LA|LB|LC|LI|LK|LR|LS|LT|LU|LV|LY|MA|MC|MD|ME|MG|MH|MK|ML|MM|MN|MO|MP|MQ|MR|MS|MT|MU|MV|MW|MX|MY|MZ|NA|NC|NE|NF|NG|NI|NL|NO|NP|NR|NU|NZ|OM|PA|PE|PF|PG|PH|PK|PL|PM|PN|PR|PS|PT|PW|PY|QA|RE|RO|RS|RU|RW|SA|SB|SC|SD|SE|SG|SH|SI|SJ|SK|SL|SM|SN|SO|SR|ST|SU|SV|SY|SZ|TC|TD|TF|TG|TH|TJ|TK|TL|TM|TN|TO|TP|TR|TT|TV|TW|TZ|UA|UG|UK|US|UY|UZ|VA|VC|VE|VG|VI|VN|VU|WF|WS|YE|YT|YU|ZA|ZM|ZW)\b$/, errormessage: bizzlang.email_required}

		]

	});

	

	// payment step 4

	$('#payment_method').change(function() {

		var value = $(this).val();

		if ( value == 'paypal') {

			$('.paypal-payment').show();

			$('.credit-card-payment').hide();

			$('.no-payment').hide();

			$('.credit-card-payment').find('input, select').removeClass('validate');

		}

		else if ( value == 'creditcard') {

			$('.paypal-payment').hide();

			$('.credit-card-payment').show();

			$('.no-payment').hide();

			$('.credit-card-payment').find('input, select').addClass('validate');

		}

		else {

			$('.paypal-payment').hide();

			$('.credit-card-payment').hide();

			$('.no-payment').show();

			$('.credit-card-payment').find('input, select').removeClass('validate');

		}

	});

	

	// load step 5

	$("#submit_checkout, #submit_paypal, #submit_quickpay").live("click", function(e) {

		e.preventDefault();

		$('.validate').isValid();

		var params_array = $('form#check_form').serializeArray();

		

		// all fine?

		if ( $('.validate').isValid() ) {

			$( document ).triggerHandler( 'load-step-five', [ params_array ] );

		}

	});

	

	// Add car ID to modal for skipping if needed

	$(".btn-bookingmodal").live("click", function(e) {

		var carID = $(this).parents("li").find(".car_id").val();

		$("#bookingmodal").removeAttr( "data-carid" );

		$("#bookingmodal").attr( "data-carid", carID );

	});

	

});



// Tab links

jQuery(document).bind( 'book-tab-links', function(e, thistab, current_rel) {

	tabLinks(thistab, current_rel);

});

	

function tabLinks(thistab, current_rel) {	

	

	if (current_rel == '1') {

		jQuery(".step_wrapper[data-step='1']").show().removeClass("hidden").fadeIn("slow");

		jQuery(".step_wrapper[data-step='2']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='3']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='4']").hide().addClass("hidden");

		jQuery(".steps_tabs li").removeClass("active");

		jQuery(".step1_tab").addClass("active");

	}

	else if (current_rel == '2') {

		jQuery(".step_wrapper[data-step='1']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='2']").show().removeClass("hidden").fadeIn("slow");

		jQuery(".step_wrapper[data-step='3']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='4']").hide().addClass("hidden");

		jQuery(".steps_tabs li").removeClass("active");

		jQuery(".step2_tab").addClass("active");

	}

	else if (current_rel == '3') {

		jQuery(".step_wrapper[data-step='1']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='2']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='3']").show().removeClass("hidden").fadeIn("slow");

		jQuery(".step_wrapper[data-step='4']").hide().addClass("hidden");

		jQuery(".steps_tabs li").removeClass("active");

		jQuery(".step3_tab").addClass("active");

	}

	else if (current_rel == '4') {

		jQuery(".step_wrapper[data-step='1']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='2']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='3']").hide().addClass("hidden");

		jQuery(".step_wrapper[data-step='4']").show().removeClass("hidden").fadeIn("slow");

		jQuery(".steps_tabs li").removeClass("active");

		jQuery(".step4_tab").addClass("active");

	}

	

}



function filterVehicleList( selected ) {

	// selected list

	var selected_list = selected.parents(".wrapper, .step_wrapper").find("#car_list");

	// show all

	selected_list.children("li:not('.dummy')").removeClass("hidden");

	// loop

	selected_list.children("li:not('.dummy')").each(function() {

		var jQueryli_car_type = jQuery(this).find(".car_type").val();

		var types_array = (typeof(jQueryli_car_type) != "undefined" && jQueryli_car_type.indexOf(',') !== -1) ? jQueryli_car_type.split(",") : jQueryli_car_type;

		

		if (selected.val() == "-1") {

			jQuery(this).removeClass("hidden");

		} else {

			if (typeof(jQueryli_car_type) != "undefined" && types_array.indexOf(selected.val()) !== -1) {

				jQuery(this).removeClass("hidden");

			} else if (typeof(jQueryli_car_type) != "undefined" && types_array == selected.val()) {

				jQuery(this).removeClass("hidden");

			} else {

				jQuery(this).addClass("hidden");

			}

		}

	});

		

	// no cars?

	if ( selected_list.children("li:not('.hidden')").size() == 0)

		selected_list.append("<li class=\"nocars\">"+bizzlang.book_nocars+"</li>");

	else

		selected_list.children("li.nocars").remove();

		

	// last list

	var lastLisNoX = selected_list.map(function() {

		return jQuery(this).children("li:not('.hidden')").get(-1);

	});

	selected_list.children("li").css('border', '');

	lastLisNoX.css( 'border', 'none' )	

	

}



function sortVehicleList( selected ) {

	var liContents = [];

	

	// selected list

	var selected_list = selected.parents(".wrapper, .step_wrapper").find("#car_list");

		

	// stop if one or none cars listed

	if ( selected_list.children("li:not('.hidden')").size() <= 1)

		return false;

	

	// show | hide

	selected_list.children("li:not('.hidden')").each(function() {

		// push unsorted list

		liContents.push( jQuery(this).html() );

	});

	// sort list

	var selected_sort = selected.val();

	

	// sort by ID

	if (selected_sort == "-1")

		liContents.sort(carSorterId);

	// sort by price

	else if (selected_sort == "price-low")

		liContents.sort(carSorterPriceAsc);

	else if (selected_sort == "price-high")

		liContents.sort(carSorterPriceDesc);

	// sort by name

	else if (selected_sort == "name-asc")

		liContents.sort(carSorterNameAsc);

	else if (selected_sort == "name-desc")

		liContents.sort(carSorterNameDesc);

	// loop again

	selected_list.children("li:not('.hidden')").each(function() {

		// pop sorted list

		jQuery(this).html(liContents.pop());

	});

	

}



function carSorterId(a, b) {		

	// car ID

	var aId = parseInt(jQuery(a).find('.car_id').val(), 10);

    var bId = parseInt(jQuery(b).find('.car_id').val(), 10);

	

	// sort by ID

	return bId - aId;

}



function carSorterNameAsc(a, b) {		

	// car name

	var aName = jQuery(a).find('.car_name').text();

    var bName = jQuery(b).find('.car_name').text();

	

	// sort by name ASC

	return aName.toLowerCase() < bName.toLowerCase() ? 1 : -1; // Asc

}



function carSorterNameDesc(a, b) {		

	// car name

	var aName = jQuery(a).find('.car_name').text();

    var bName = jQuery(b).find('.car_name').text();

	

	// sort by name DESC

	return aName.toLowerCase() > bName.toLowerCase() ? 1 : -1; // Desc

}



function carSorterPriceAsc(a, b) {		

	// car price

	var aPrice = parseInt(jQuery(a).find('.car_val').val(), 10);

    var bPrice = parseInt(jQuery(b).find('.car_val').val(), 10);

	

	// sort by price ASC

	return aPrice < bPrice ? 1 : -1; // Asc

}



function carSorterPriceDesc(a, b) {		

	// car price

	var aPrice = parseInt(jQuery(a).find('.car_val').val(), 10);

    var bPrice = parseInt(jQuery(b).find('.car_val').val(), 10);

	

	// sort by price DESC

	return aPrice > bPrice ? 1 : -1; // Desc

}



function fillVehicleExtrasArray() {

	var selected_extras = [];

	

	jQuery(".car_extras_check").each(function() {

		if (jQuery(this).is(":checked")) {

			var extras = [

				jQuery(this).parents("li").find(".car_extras_id").val(),

				jQuery(this).parents("li").find("select[name='extra_count'] option:selected, input[name='extra_count']").val(),

				jQuery(this).parents("li").find("input[name='extra_field']").val(),

			];

			selected_extras.push( extras.join("|") );

		}

	});

	

	jQuery("#selected_extras").val(selected_extras.join("~"));

}



function clearUserCookie() { // clears the user selection entirely

	var ajax_url = bizz_localize.ajaxurl,

		data = {

			action: 'validate_booking',

			step: 'dc',

			params: ''

		};

	jQuery.getJSON(ajax_url, data, function(response) {

		// no response

	}).error(function(response){});

}



// Step 2 add action

jQuery(document).bind( 'load-step-two', function(e, params_array) {

	loadStepTwo(params_array);

});

	

function loadStepTwo(params_array) { // Choose car

	jQuery(".step_wrapper").hide();

	jQuery(".steps_tabs li").removeClass("active");

	jQuery(".loading_wrapper").show();

	jQuery(".tablink[data-rel=1],[data-rel=2]").removeClass("disabled");

	

	// remove all sections except booking

	jQuery(".widget_booking").parents("div[class*='col-md-']").animate('slow', function() {

		var span_class = jQuery(this).attr("class").match(/col-md-([\d])/),

			span_num = parseInt(span_class[1],10);

		jQuery(this).removeClass("col-md-"+span_num).addClass("col-md-12").fadeIn("fast");

		// remove all sections

		jQuery(this).nextAll("div[class*='col-md-']").css('display', 'none');

		jQuery(this).prevAll("div[class*='col-md-']").css('display', 'none');

		// remove all widgets

		jQuery(this).find('.widget_booking').nextAll('.widget').css('display', 'none');

		jQuery(this).find('.widget_booking').prevAll('.widget').css('display', 'none');

	});

	

	// reset car filters

	jQuery('.car_type').find("option:first").attr("selected", true);



	// prevent duplicates

	jQuery(".list_wrapper .clist > li:not('.dummy')").remove();

	jQuery(".list_wrapper .clist > li.dummy").removeClass("hidden");

	

	// ajaxed time selector								

	var ajax_url = bizz_localize.ajaxurl,

		data = {

			action: 'validate_booking',

			step: '2',

			params: params_array

		};

	

	jQuery.getJSON(ajax_url, data, function(response) {

		var json_cars = response;

		

		// alert(response);

		

		// only animate, when 1 car list is present

		if ( jQuery("ul[id*='car_list']").length < 2 ) {

			jQuery('html, body').animate({scrollTop:jQuery('#booktop').offset().top}, 'slow');

		}

		jQuery(".loading_wrapper").fadeOut('fast', function() {

			jQuery(".step_wrapper[data-step=2]").hide().removeClass("hidden").fadeIn("slow");

		});

		jQuery(".step2_tab").addClass("active");

		

		// remove all but dummy

		jQuery(".carlist > li:not('.dummy')").remove();

		

		// loop cars

		if ( !jQuery.isEmptyObject(json_cars.cars) ) { // empty?

			jQuery.each(json_cars.cars, function() {

				var $car = this;

				var li_element = jQuery("ul.carlist li.dummy:first").clone();

				var li_element_equipment = li_element.find("ul#car_properties_"+$car.id);

				li_element.attr("id", "li_car_"+$car.id);

				li_element.removeClass("dummy");

				li_element.removeClass("hidden");

				li_element.find("ul.car_properties").attr("id", "car_properties_"+$car.id);

				li_element.find(".car_image").attr("src", $car.picture_src);

				li_element.find(".car_name").html($car.name);

				li_element.find(".car_id").val($car.id);

				li_element.find(".car_type").val($car.type);

				li_element.find(".car_transmission").val($car.equipment.transmission);

				li_element.find(".car_details_tooltip").html($car.description);

				if ( li_element.find(".car_edit").length ) {

					li_element.find(".car_edit").attr("href", data = $car.edit.replace(/&amp;/, "&"));

				}

				if ( $car.count > 1 ) {

					var carqty = "<select name='car_count' class='form-control car_count'>";

					for (var x = 1; x <= $car.count; x++) {

						carqty += "<option value=" + x + ">" + x + " &times;</option>";

					}

					carqty += "</select>";

					li_element.find(".car_count").html(carqty);

				}

				else if ( $car.count == 1 ) {

					var carqty = "<input type='hidden' name='car_count' class='car_count' value='1' />";

					li_element.find(".car_count").html(carqty);

				}

				if ($car.cost=='not-set') {

					li_element.find(".car_cost").val(0);

					li_element.find(".car_val").val(0);

					li_element.find(".car_price").html(bizzlang.price_not_defined);          

					li_element.find(".button_select_car").remove();

					li_element.find(".avail.no").removeClass('hidden');

				} else {

					li_element.find(".car_cost").val($car.cost);

					li_element.find(".car_val").val($car.cost_val);

					li_element.find(".car_price").html($car.cost);

					li_element.find(".car_price_int").html($car.cost_int);

					// coupon

					if ( $car.cost_disc != $car.cost ) {

						li_element.find(".car_price").addClass('discount');

						li_element.find(".car_price").html($car.cost_disc+'<strike>'+$car.cost+'</strike>');

					}

					if ( $car.cost_int_disc != $car.cost_int ) {

						li_element.find(".car_price_int").addClass('discount');

						li_element.find(".car_price_int").html($car.cost_int_disc+'<strike>'+$car.cost_int+'</strike>');

					}

					// availability

					if ( $car.avail_date != 'ok' ) {

						li_element.find(".car_availability").html($car.avail_date);

						li_element.find(".button_select_car").remove();

						li_element.find(".avail.no").removeClass('hidden');

					} else if ( $car.avail_location != 'ok' ) {

						li_element.find(".car_availability").html($car.avail_location);

						li_element.find(".button_select_car").remove();

						li_element.find(".avail.no").removeClass('hidden');

					} else {

						li_element.find(".avail.yes").removeClass('hidden');

					}

				}

				

				// loaded to modal?

				var modal_carid = jQuery("#bookingmodal").data("carid");

				if ( modal_carid  ) {

					li_element.removeClass("selected");

					if ( modal_carid === $car.id  ) {

						li_element.addClass("selected");

					}

				}

				

				// loop equipment

				jQuery.each($car.equipment, function() {

					var $equipment = this;

					var li_element_equipment = li_element.find("ul#car_properties_"+$car.id);				

					li_element_equipment.find("li.seats .eq_value").html($car.equipment.seats);

					li_element_equipment.find("li.doors .eq_value").html($car.equipment.doors);

					li_element_equipment.find("li.transmission .eq_value").html($car.equipment.transmission);

					jQuery("#car_properties_"+$car.id).append(jQuery(li_element_equipment));

				});			

				

				jQuery(".carlist").append(jQuery(li_element));

				

			});



			// move selected car to top

			jQuery(".carlist").find("li.selected").prependTo(".carlist");



		}

		// No cars?

		else {

			jQuery("#car_list").append("<li class=\"nocars\">"+bizzlang.book_nocars+"</li>");

		}

		

		// hide dummy

		jQuery(".carlist li.dummy").addClass("hidden");

		

		// last list

		var lastLisNoX = jQuery("ul.clist").map(function() {

			return jQuery(this).children("li:not('.hidden')").get(-1);

		});

		jQuery(".clist > li").css('border', '');

		lastLisNoX.css( 'border', 'none' );

		

	}).error(function(response){});



}



// Step 3 add action

jQuery(document).bind( 'load-step-three', function(e, params_array) {

	loadStepThree(params_array);

});



function loadStepThree(params_array) { // Choose extras

	jQuery("#selected_extras").val("");

	jQuery(".step_wrapper").hide();

	jQuery(".steps_tabs li").removeClass("active");

	jQuery(".loading_wrapper").show();

	jQuery(".tablink[data-rel=1],[data-rel=2],[data-rel=3]").removeClass("disabled");

		

	// ajaxed time selector								

	var ajax_url = bizz_localize.ajaxurl,

		data = {

			action: 'validate_booking',

			step: '3',

			params: params_array

		};

	

	jQuery.getJSON(ajax_url, data, function(response) {

		var json_car_extras = response;

		

		// only animate, when 1 car list is present

		if ( jQuery("ul[id*='car_list']").length < 2 ) {

			jQuery('html, body').animate({scrollTop:jQuery('#booktop').offset().top}, 'slow');

		}

		

		// empty?

		if ( jQuery.isEmptyObject(json_car_extras.car_extras) ) {

			jQuery("#extras_list").append("<li class=\"noextras\">"+bizzlang.book_noextras+"</li>");

			var params_array = {

				'car_extras': ''

			};

			loadStepFour(params_array);

			return;

		}

		

		jQuery(".loading_wrapper").fadeOut('fast', function() {

			jQuery(".step_wrapper[data-step=3]").hide().removeClass("hidden").fadeIn("slow");

		});

		jQuery(".step3_tab").addClass("active");

		

		// remove all but dummy

		jQuery("#extras_list > li:not('.dummy')").remove();



		// loop extras

		jQuery.each(json_car_extras.car_extras, function() {

			var $car_extras = this;

			var li_element = jQuery("ul#extras_list li.dummy:first").clone();

			li_element.attr("id", "li_car_extras_"+$car_extras.id);

			li_element.removeClass("dummy");

			li_element.removeClass("hidden");

			li_element.find(".extras_image").attr("src", $car_extras.picture_src);

			li_element.find(".extras_name").html($car_extras.name);

			if ( $car_extras.field ) {

				var extrafield = "<input type='text' name='extra_field' class='form-control extra_field' />";

				li_element.find(".extras_field").html(extrafield);

				if ( $car_extras.required ) {

					li_element.find(".extra_field").addClass('validate').valid8();

				}

				if ( $car_extras.field_placeholder ) {

					li_element.find(".extra_field").attr('placeholder', $car_extras.field_placeholder);

				}

			}

			else if ( ! $car_extras.field ) {

				var extrafield = "<input type='hidden' name='extra_field' class='extra_field' value='' />";

				li_element.find(".extra_field").html(extrafield);

			}

			li_element.find(".car_extras_slug").val($car_extras.slug);

			li_element.find(".car_extras_id").val($car_extras.id);				

			li_element.find(".extras_details").html($car_extras.description);

			if ( $car_extras.required ) {

				li_element.find(".car_extras_check").attr('checked', true);

				li_element.find(".car_extras_check").attr('readonly',true);

				li_element.find(".car_extras_check").attr('disabled', 'disabled');

				li_element.addClass('selected req_extra');

			}

			if ( $car_extras.count > 1 ) {

				var extraqty = "<select name='extra_count' class='form-control extra_count'>";

				for (var x = 1; x <= $car_extras.count; x++) {

					extraqty += "<option value=" + x + ">" + x + " &times;</option>";

				}

				extraqty += "</select>";

				li_element.find(".extras_count").html(extraqty);

			}

			else if ( $car_extras.count == 1 ) {

				var extraqty = "<input type='hidden' name='extra_count' class='extra_count' value='1' />";

				li_element.find(".extras_count").html(extraqty);

			}

			// Free?

			if ( $car_extras.cost_n == 0 ) {

				li_element.find(".extras_cost").html(bizzlang.free);

			} else {

				li_element.find(".extras_cost").html($car_extras.cost);

				li_element.find(".extras_cost_int").html($car_extras.cost_int);

			}

			// coupon

			if ( $car_extras.cost_disc != $car_extras.cost ) {

				li_element.find(".extras_cost").addClass('discount');

				li_element.find(".extras_cost").html($car_extras.cost_disc+'<strike>'+$car_extras.cost+'</strike>');

			}

			if ( $car_extras.cost_int_disc != $car_extras.cost_int ) {

				li_element.find(".extras_cost_int").addClass('discount');

				li_element.find(".extras_cost_int").html($car_extras.cost_int_disc+'<strike>'+$car_extras.cost_int+'</strike>');

			}

			jQuery("#extras_list").append(jQuery(li_element));			

		});

		

		// go through each extra and fill the values if autochecked

		fillVehicleExtrasArray();

				

		// hide dummy

		jQuery("#extras_list li.dummy").addClass("hidden");

		

		// last list

		var lastLisNoX = jQuery("ul.clist").map(function() {

			return jQuery(this).children("li:not('.dummy')").get(-1);

		});

		jQuery(".clist > li").css('border', '');

		lastLisNoX.css( 'border', 'none' );

		

	}).error(function(response){});



}



// Step 4 add action

jQuery(document).bind( 'load-step-four', function(e, params_array) {

	loadStepFour(params_array);

});



function loadStepFour(params_array) { // Personal Data

	jQuery(".step_wrapper[data-step=3]").hide();

	jQuery(".loading_wrapper").show();

	jQuery(".steps_tabs li").removeClass("active");

	jQuery(".tablink[data-rel=1],[data-rel=2],[data-rel=3],[data-rel=4]").removeClass("disabled");

	

	// ajaxed time selector								

	var ajax_url = bizz_localize.ajaxurl,

		data = {

			action: 'validate_booking',

			step: '4',

			params: params_array

		};

	

	jQuery.getJSON(ajax_url, data, function(response) {

		var cookie_data = response;

		

		// only animate, when 1 car list is present

		if ( jQuery("ul[id*='car_list']").length < 2 ) {

			jQuery('html, body').animate({scrollTop:jQuery('#booktop').offset().top}, 'slow');

		}

		

		jQuery(".loading_wrapper").fadeOut('fast', function() {

			jQuery(".step_wrapper[data-step=4]").hide().removeClass("hidden").fadeIn("slow");

		});

		jQuery(".step4_tab").addClass("active");

		

		// remove all but dummy

		jQuery(".selected_extras > .form-group:not('.dummy')").remove();

		

		// extras?

		if ( !jQuery.isEmptyObject(cookie_data.car_extras) ) { // empty?

			jQuery.each(cookie_data.car_extras, function() {

				var $car_extras = this;

        

				var li_element = jQuery(".selected_extras .form-group:first").clone();

				li_element.removeClass("dummy");

				li_element.removeClass("hidden");

				li_element.find(".extra_name").html($car_extras[1]);

				// li_element.find(".extra_cost").html($car_extras[2] + ' &times; ' + $car_extras[5]);

				if ( $car_extras[6] ) {

					li_element.find(".extra_cost").html('&times; ' + $car_extras[5] + ' (' + $car_extras[6] + ')');

				} else {

					li_element.find(".extra_cost").html('&times; ' + $car_extras[5]);

				}

				jQuery(".selected_extras").append(jQuery(li_element));			

			});

		}

		else

			jQuery(".selected_extras").append("<div class=\"form-group\"><div class=\"col-sm-7 col-sm-offset-5\">"+bizzlang.book_noextra+"</div></div>");



		

		// hide dummy

		jQuery(".selected_extras .dummy").addClass("hidden");

		

		// data

		jQuery("#pickup_location").html(cookie_data.location_of_pickup_name);

		jQuery("#pickup_date").html(cookie_data.date_of_pickup_locale+", "+cookie_data.hour_of_pickup_locale);

		jQuery("#return_location").html(cookie_data.location_of_return_name);

		jQuery("#return_date").html(cookie_data.date_of_return_locale+", "+cookie_data.hour_of_return_locale);

		jQuery("#days_details").html(cookie_data.count_days);

		jQuery("#car_name").html(cookie_data.car_name);

		jQuery("#car_dealer").html(cookie_data.dealer_id);

		jQuery("#car_dealer_email").html(cookie_data.car_dealer_email_id);

		jQuery("#car_count").html('&times; ' + cookie_data.car_count);

		jQuery(".checkout_form .car_image").attr("src", cookie_data.car_image);

		jQuery("#car_pay").html(cookie_data.car_total_payment_output.car_total);

		if ( cookie_data.car_total_payment_output.car_total_disc != cookie_data.car_total_payment_output.car_total ) {

			jQuery("#car_pay").addClass('discount');

			jQuery("#car_pay").html(cookie_data.car_total_payment_output.car_total_disc+'<strike>'+cookie_data.car_total_payment_output.car_total+'</strike>');

		}

		jQuery("#extras_pay").html(cookie_data.car_total_payment_output.extras_total);

		if ( cookie_data.car_total_payment_output.extras_total_disc != cookie_data.car_total_payment_output.extras_total ) {

			jQuery("#extras_pay").addClass('discount');

			jQuery("#extras_pay").html(cookie_data.car_total_payment_output.extras_total_disc+'<strike>'+cookie_data.car_total_payment_output.extras_total+'</strike>');

		}

		jQuery("#deposit_pay").html(cookie_data.car_total_payment_output.deposit);

		jQuery("#tax_pay").html(cookie_data.car_total_payment_output.tax_total);

		jQuery("#total_pay").html(cookie_data.car_total_payment_output.total);

		

		// Remove tax field if 0

		if ( cookie_data.car_total_payment.tax_percentage == 0 )

			jQuery("#tax_pay").parents('.control-group').hide();

		else

			jQuery("#tax_pay").parents('.control-group').show();

			

		// Remove deposit field if 0

		if ( cookie_data.car_total_payment.deposit_percentage == 0 )

			jQuery("#deposit_pay").parents('.control-group').hide();

		else

			jQuery("#deposit_pay").parents('.control-group').show();

		

	}).error(function(response){});



}



// Step 5 add action

jQuery(document).bind( 'load-step-five', function(e, params_array) {

	loadStepFive(params_array);

});



function loadStepFive(params_array) { // Checkout

	// ajaxed time selector								

	var ajax_url = bizz_localize.ajaxurl,

		loading = jQuery('form#check_form').find('.loading'),

		messages = jQuery('.bookwrap').find('.messages'),

		delay = 5000, //Your delay in milliseconds

		data = {

			action: 'validate_booking',

			step: '5',

			params: params_array

		};

		

	// loading show

	loading.css('display', 'inline-block');

	

	jQuery.getJSON(ajax_url, data, function(response) {

		var cookie_data = response;

		

		// alert(cookie_data.book_id);

				

		// loading hide

		loading.css('display', 'none');

		

		if (cookie_data.process == "success") {

		

			// Custom redirect

			if ( cookie_data.redirect && cookie_data.payment_method != "paypal" ) {

				window.location = cookie_data.redirect;

				return;

			}

					

			// fill PayPal

			jQuery("input[name=amount]").val(cookie_data.car_total_payment.deposit_paypal); // deposit amount

			jQuery("input[name=invoice]").val(cookie_data.track_id); // tracking ID

			jQuery("input[name=custom]").val(cookie_data.book_id); // post ID

			

			// submit PayPal

			if (cookie_data.payment_method == "paypal")

				jQuery('form#crPaypal').submit();

			

			// only animate, when 1 car list is present

			if ( jQuery("ul[id*='car_list']").length < 2 ) {

				jQuery('html, body').animate({scrollTop:jQuery('#booktop').offset().top}, 'slow');

			}

			jQuery('.steps_tabs_container, .step_wrapper').remove();

			messages.empty();

			messages.html('<div class="alert alert-block alert-success">' + bizzlang.book_success + '</div>');

			jQuery('.bookwrap').removeClass('bookwrap navbar-inner');

			

			if ( bizzlang.thankyou_page && cookie_data.payment_method != "paypal" ) {

				jQuery('<div id="book_overlay" />').prependTo('body'); // add overlay

				setTimeout(function(){ 

					window.location = bizzlang.thankyou_page; 

				}, delay);

			}



		}

		

	}).error(function(response){});



}