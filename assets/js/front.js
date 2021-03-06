/*================================================================================
	Item Name: Pongo - Simple & Clean Admin Template
	Version: 1.0
	Author: Native Theme
	Author URL: http://www.themeforest.net/user/native-theme
================================================================================*/

"use strict";

$(function () {

	// Mobile nav
	var main_nav = $('.main-nav')
	$(main_nav).find('.mobile-nav').on('click', function() {
		$(main_nav).find('.menu').fadeToggle()
	})

	// On window resized
	var responsive_mobile_nav = function() {
		if ($(window).width() >= 920) {
			$('.main-nav').find('.menu').removeAttr('style')
		}
	}

	responsive_mobile_nav()
	$(window).resize(function() {
		responsive_mobile_nav()
	})

	// Main menu
	$('.main-nav').find('a').each(function() {
		$(this).on('click', function() {
			var link = $(this).attr('data-link')
			if ($(link).length) {
				$('html, body').animate({
					scrollTop: $(link).offset().top
				}, 700);
			}
		})
	})

	// Move top
	var move_top = function() {
		if ($(window).scrollTop() > 0) {
			$(".move-top").show();
		} else {
			$(".move-top").hide();
		}
	}

	move_top()
	$(window).scroll(function() {
		move_top()
	});

	$('.move-top').click(function() {
		$('html, body').animate({
			scrollTop: $('body').offset().top
		}, 700);
	});
	/* Tool Tip */
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
})
jQuery(function($) {
	var isMobile = {
	    Android: function() {
	        return navigator.userAgent.match(/Android/i);
	    },
	    BlackBerry: function() {
	        return navigator.userAgent.match(/BlackBerry/i);
	    },
	    iOS: function() {
	        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	    },
	    Opera: function() {
	        return navigator.userAgent.match(/Opera Mini/i);
	    },
	    Windows: function() {
	        return navigator.userAgent.match(/IEMobile/i);
	    },
	    any: function() {
	        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	    }
	};

$(".w3-button").on("click", function(){
	 		var text = $(this).attr("data-message");
            var phone = $(this).attr("data-number");
            var message = encodeURIComponent(text);

	if( isMobile.any() ) {
			//mobile device
            var whatsapp_API_url = "whatsapp://send";
            $(this).attr( 'href', whatsapp_API_url+'?phone=' + phone + '&text=' + message );
        } else {
        	//desktop
            var whatsapp_API_url = "https://web.whatsapp.com/send";
            $(this).attr( 'href', whatsapp_API_url+'?phone=' + phone + '&text=' + message );
        }

});
        
});