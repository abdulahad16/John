(function($, $window, $document) {

jQuery(document).ready(function() {
	
	var ua = navigator.userAgent,
	isMobileWebkit = /WebKit/.test(ua) && /Mobile/.test(ua);
	
	
	$('.nw-cart-click #nw-cart-drop-toggle').click(function(e) {
		if($('#nw-cart-drop-content').hasClass('nw-hidden')) {
			$('#nw-cart-drop-content').animate({opacity:1},300,"easeInQuart").removeClass('nw-hidden');
		}else {
			
			if(isMobileWebkit && !$('#nw-cart-drop-content').hasClass('nw-hidden')) {
				$('#nw-cart-drop-content').stop().animate({opacity:0},300,"easeOutQuart",function() {
					$(this).addClass('nw-hidden');
				});
			}
			
		}
	});
	
	if(isMobileWebkit) {
		$('.nw-cart-hover #nw-cart-drop-toggle').click(function(e) {
			if($('#nw-cart-drop-content').hasClass('nw-hidden')) {
				$('#nw-cart-drop-content').animate({opacity:1},300,"easeInQuart").removeClass('nw-hidden');
			}else {
				
				if(isMobileWebkit && !$('#nw-cart-drop-content').hasClass('nw-hidden')) {
					$('#nw-cart-drop-content').stop().animate({opacity:0},300,"easeOutQuart",function() {
						$(this).addClass('nw-hidden');
					});
				}
				
			}
		});
	}else {
		$('.nw-cart-hover #nw-cart-drop-toggle').mouseenter(function(e) {
			if($('#nw-cart-drop-content').hasClass('nw-hidden')) {
				$('#nw-cart-drop-content').animate({opacity:1},300,"easeInQuart").removeClass('nw-hidden');
			}
		});
	}
	
	$('.nw-cart-container').mouseleave(function(e) {
		if(!$('#nw-cart-drop-content').hasClass('nw-hidden')) {
			$('#nw-cart-drop-content').stop().animate({opacity:0},300,"easeOutQuart",function() {
				$(this).addClass('nw-hidden');
			});
		}
	});
	
	$(document).on('click','.ajax-remove-item', function(e) { 
		e.preventDefault();		
		
		var $thisbutton = $(this);
		
		var data = {
				action: 		'woocommerce_remove_from_cart',
				remove_item: 	$thisbutton.attr('rel')
		};
		
		var $window_width = $window.width();
		var $document_scroll = $(document).scrollTop();
		var offset = $('#nw-cart-drop-toggle').offset();
		var message = $("<span class='nw-message'></span>").text('Removed from cart');
		
		if($('.nw-message').length > 0) {
			$('.nw-message').remove();
		}
		
		$.post( woocommerce_params.ajax_url, data, function( response ) {
			
			fragments = response.fragments;
			cart_hash = response.cart_hash;
			
			if ( fragments ) {
				$.each(fragments, function(key, value) {
					$(key).replaceWith(value);
				});
				
				$('#nw-drop-cart').after(message);
				
				if($window_width >= 320 && $window_width <=568) {
					message.css({left: 0, right: 0, top:offset.top - $('.nw-message').height() - parseInt($('.nw-message').css('padding-top')) - parseInt($('.nw-message').css('padding-bottom')) - 10, width : '60%'}).stop().animate({opacity:1},100,"easeInOutExpo",function() {  });
					message.addClass('arrow_bottom');
				}else if($window_width >= 600 && $window_width <=1024){
					message.css({left: offset.left - $('.nw-message').width() - parseInt($('.nw-message').css('padding-left')) - parseInt($('.nw-message').css('padding-right')) -25 , top:offset.top + $('.nw-message').height()/2 - 7}).stop().animate({opacity:1},100,"easeInOutExpo",function() {  });
				}else {
					message.css({left: offset.left - $('.nw-message').width() - parseInt($('.nw-message').css('padding-left')) - parseInt($('.nw-message').css('padding-right')) - 10 , top:offset.top + $('.nw-message').height()/2}).stop().animate({opacity:1},100,"easeInOutExpo",function() {  });
				}
				
				setTimeout(function(){
		
					message.clearQueue().stop().animate({opacity:0},100,"easeInOutExpo",function() { message.remove(); });
					
				},1300);
			}
			
		});
		
	});
	
	$('body').on('added_to_cart', function() {
	
		var $window_width = $window.width();
		var $document_scroll = $(document).scrollTop();
		var offset = $('.nw-cart-container').offset();
		var message = $("<span class='nw-message'></span>").text('Added to cart');

		if($('.nw-message').length > 0) {
			$('.nw-message').remove();
		}
		
		$('#nw-drop-cart').after(message);
		
		if(offset.top < $document_scroll) {
			//console.log("higher");
			message.css({left: 0, right: 0, top:10, width : '40%', position : 'fixed'}).stop().animate({opacity:1},100,"easeInOutExpo",function() {  });
			message.addClass('arrow_top');
		}else if($window_width >= 320 && $window_width <=568) {
			message.css({left: 0, right: 0, top:offset.top + $('.nw-cart-container').height(), width : '60%'}).stop().animate({opacity:1},100,"easeInOutExpo",function() {  });
			message.addClass('arrow_top');
		}else {
			message.css({left: offset.left - $('.nw-message').width() - parseInt($('.nw-message').css('padding-left')) - parseInt($('.nw-message').css('padding-right')) - 10 , top:offset.top + $('.nw-message').height()/2}).stop().animate({opacity:1},100,"easeInOutExpo",function() {  });
		}
		
		setTimeout(function(){
		
			message.clearQueue().stop().animate({opacity:0},100,"easeInOutExpo",function() { message.remove(); });
			
		},1300);
		
	});
	
});

})(jQuery, jQuery(window), jQuery(document));