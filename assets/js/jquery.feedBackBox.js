/*
	Copyright (c) 2013 
	Willmer, Jens (http://jwillmer.de)	
	
	Permission is hereby granted, free of charge, to any person obtaining
	a copy of this software and associated documentation files (the
	"Software"), to deal in the Software without restriction, including
	without limitation the rights to use, copy, modify, merge, publish,
	distribute, sublicense, and/or sell copies of the Software, and to
	permit persons to whom the Software is furnished to do so, subject to
	the following conditions:
	
	The above copyright notice and this permission notice shall be
	included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
	LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
	OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
	WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
	
	
	feedBackBox: A small feedback box realized as jQuery Plugin.
	@author: Willmer, Jens
	@url: https://github.com/jwillmer/feedBackBox
	@documentation: https://github.com/jwillmer/feedBackBox/wiki
	@version: 0.0.1
*/
; (function ($) {
    $.fn.extend({
        feedBackBox: function (options) {

            // default options
            this.defaultOptions = {
                title: 'WhatsApp',
                titleMessage: 'Please feel free to leave us feedback.',
                userName: '',
                isUsernameEnabled: true,
            };

            var settings = $.extend(true, {}, this.defaultOptions, options);

            return this.each(function () {
                var $this = $(this);
                var thisSettings = $.extend({}, settings);

                var diableUsername;
                if (!thisSettings.isUsernameEnabled) {
                    diableUsername = 'disabled="disabled"';
                }

                // add feedback box
                $this.html('<span id="fpi_feedback">'
                    + '<span id="fpi_ajax_message">'
					+ '<a class="w3-button w3-account w3-clearfix" data-number="+2349099387285"'
					+ 'data-message="Hi! I would like to know more about the iPoints?" target="_blank">'					
					+ '<span class="w3-copy"><i class="fa fa-comments"></i> Chat via WhatsApp</span></a></span></span>');

                // open and close animation
                var isOpen = false;
                $('#fpi_title').click(function () {
                    if (isOpen) {
                        $('#fpi_feedback').animate({ "width": "+=5px" }, "fast")
                        .animate({ "width": "55px" }, "slow")
                        .animate({ "width": "60px" }, "fast");
                        isOpen = !isOpen;
                    } else {
                        $('#fpi_feedback').animate({ "width": "-=5px" }, "fast")
                        .animate({ "width": "365px" }, "slow")
                        .animate({ "width": "360px" }, "fast");

                        // reset properties
                        $('#fpi_submit_loading').hide();
                        $('#fpi_content form').show()
                        $('#fpi_content form .error').removeClass("error");
                        $('#fpi_submit_submit input').removeAttr('disabled');
                        isOpen = !isOpen;
                    }
                });

            });
        }
    });
})(jQuery);
$(document).ready(function () {
    $('#ipoints').feedBackBox();
});
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
	if(isMobile.any()) {
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
		