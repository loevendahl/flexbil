jQuery(document).ready(function($) {

	//single products are added via ajax //doesnt work currently
	//$('.summary .cart .button[type=submit]').addClass('add_to_cart_button product_type_simple');
	
	//tooltips
	$('.star-rating').tooltip();
	
	//downloadable products are now added via ajax as well
	$('.product_type_downloadable, .product_type_virtual').addClass('product_type_simple');
	
	//clicking tabs dont activate smoothscrooling
	$('.woocommerce_tabs .tabs a, .woocommerce-tabs .tabs a').addClass('no-scroll');
	
	//connect thumbnails on single product page via lightbox
	$('.prev_image_container>.images a').attr('rel','product_images');
	
	//add thumbnail class on single product page
	$('.prev_image_container>.images a').addClass('thumbnail');
		
	//equal height and width for thumbnail container
	var thumbContainer = $('.thumbnail_container .thumbnail');
	thumbContainer.each(function()
	{
		var container = $(this);
			container.css({'min-height':container.width()});
	});
	//star click
	$('body')
		.on( 'click', '#respond p.stars a', function(){ 
			$(this).parents('p.stars').addClass('selected');
			return false;
		}); 
	
});
