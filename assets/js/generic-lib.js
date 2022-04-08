$(document).ready(function (){


    $('.customerName').typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: BASE_URL + "users/autocompleteusers",
            data: "{ 'customerName': '" + request + "'}",
            dataType: "json",
            type: "GET",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                items = []; map = {};
                let name = ''
                $.each(data, function (i, value) {
                    name = value.name||'';
                    map[name] = { id: value.id, name: name };
                    items.push(map[name]);
                });
                response(items);
            },
            // error: OnError,
            // failure: OnError,
        });
    },
    updater: function (item) {
        $('.customerId').val(item.id);
        return item;
    }
	}).on("keyup",function() {
		if (!this.value) {
			$('.customerId').val('');
			$('.customerName').val('');
			// alert('The box is empty');
		}
	
	});
   
    function filter(data, term) {
        var objs =[];
        const flags = "gi";
        const regx = new RegExp(term, flags);
        for (x in data) {
          if (data[x].match(regx)) {
            value = data
            objs.push(data[x]); 
          }
        }
            return objs;
    }
        
        $(".lga").autocomplete({
          width: 300,
          max: 10,
          minLength: 2,
          autoFocus: true,
          cacheLength: 0,
          scroll: true,
          highlight: false,
          source:  function (request, response) {
                const term = request.term || '*'
            $.ajax({
                url: BASE_URL + 'auth/lga',// domain url
                type:"POST",
                dataType: "json",
                data: { name: $('.lga').val() },
                  success: function (data) {
                      console.log(data);
                      const objs = filter(data, term);
                      response(objs);
                  },
                  error: function () {
                      response([]);
                  }
              });
            },
          select: function (event, ui) {
            lable = ui.item.value;
            $.each(value, function(key, value){
                  if(lable == value) {
                      $("#lg").val(key);
                  }
                });
          },
        });



        $(".state").autocomplete({
            width: 300,
            max: 10,
            minLength: 2,
            autoFocus: true,
            cacheLength: 0,
            scroll: true,
            highlight: false,
            source:  function (request, response) {
                  const term = request.term || '*'
              $.ajax({
                  url: BASE_URL + 'auth/states',// domain url
                  type:"POST",
                  dataType: "json",
                  data: { name: $('.state').val() },
                    success: function (data) {
                        console.log(data);
                        const objs = filter(data, term);
                        response(objs);
                    },
                    error: function () {
                        response([]);
                    }
                });
              },
            select: function (event, ui) {
              lable = ui.item.value;
              $.each(value, function(key, value){
                    if(lable == value) {
                        $("#states").val(key);
                    }
                  });
            },
          });


         


        // Get the button that opens the modal
        //var btn = $('.slam-modal');
       

        // Get the <span> element that closes the modal
        //var span = $('.close');
        // When the user clicks the button, open the modal 

        //console.log(btn,modal,span)
        $('body').on('click','.slam-modal', function(evt){
            evt.preventDefault()
            // Get the modal
            
            const modalId = $(evt.target).attr("data-modal"), modal = $('#'+modalId);
            console.log(modal)
            modal.css({display: 'block'})

            $('.close').on('click',function(){
                modal.css({display: 'none'})
                $('.close').off('click')
            })
        })

        // When the user clicks on <span> (x), close the modal
       

        // When the user clicks anywhere outside of the modal, close it
        // window.onclick = function(event) {
        //     if (event.target == modal) {
        //         modal.style.display = "none";
        //     }
        // }

       
    

     
});

 // info['position']// toast-top-full-width, toast-top-right, toast-bottom-right, toast-bottom-left, toast-top-left, toast-bottom-full-width, toast-top-center,toast-bottom-center
// info['type'] //success, info, warning, error
// info['title']
// info['message']

// Message display position        
const topfullwidth = 'toast-top-full-width';       
const topright = 'toast-top-right';       
const bottomright = 'toast-bottom-right';      
const bottomleft = 'toast-bottom-left';      
const topleft = 'toast-top-left';      
const bottomfullwidth = 'toast-bottom-full-width';     
const topcenter = 'toast-top-center';     
const bottomcenter = 'toast-bottom-center'; 
// Message Type
const success = 'success';
const info = 'info';
const warning = 'warning';
const error = 'error';
function toastNotification(info){

    switch (info['type']) {
        case 'success':
            toastr.success(info['title']+", "+info['message']);
            break;
        case 'info':
            toastr.info(info['title']+", "+info['message']);
            break;
        case 'warning':
            toastr.warning(info['title']+", "+info['message']);
            break;
        case 'error':
            toastr.error(info['title']+", "+info['message']);
            break;      
        default: 
            alert('info[type] info type not match, please pass right info type '+info['type']);
    }

    toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": true,
    "preventDuplicates": false,
    "positionClass": info['position'],
    "showDuration": "400",
    "hideDuration": "1000",
    "timeOut": "7000",
    "extendedTimeOut": "10000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    }
} 


function IsEmptyOrUndefined(MyVar){ 
    return (
      (typeof MyVar== 'undefined')        //undefined
                  ||
      (MyVar == null)                     //null
                  ||
      (MyVar == false)  //!MyVariable     //false
                  ||
      (MyVar.length == 0)                 //empty
                  ||
      (MyVar == "")                       //empty
                  ||
      (!/[^\s]/.test(MyVar))              //empty
                  ||
      (/^\s*$/.test(MyVar))                //empty
    );
  }


  function stripcslashes(str) {

    //alert(typeof str);
    if(typeof str){
        str =  String(str);
    }
   
    var target = '', i = 0, sl = 0, s = '', next = '', hex = '', oct = '', hex2DigMax = /[\dA-Fa-f]{1,2}/, rest = '', seq = '',
            oct3DigMaxs = /([0-7]{1,3})((\\[0-7]{1,3})*)/, oct3DigMax = /(\\([0-7]{1,3}))*/g, escOctGrp = [];
    
    for (i = 0, sl = str.length; i < sl; i++) {
        s = str.charAt(i)
        next = str.charAt(i+1)
        if (s !== '\\' || !next) {
            target += s;
            continue;
        }
        switch (next) {
            case 'r':  target +='\u000D'; break;
            case 'a':  target +='\u0007'; break;
            case 'n':  target +='\n'; break;
            case 't':  target +='\t'; break;
            case 'v':  target +='\v'; break;
            case 'b':  target +='\b'; break;
            case 'f':  target +='\f'; break;
            case '\\': target +='\\'; break;
            case 'x': // Hex (not used in addcslashes)
                rest = str.slice(i+2);
                if (rest.search(hex2DigMax) !== -1) { // C accepts hex larger than 2 digits (per http://www.php.net/manual/en/function.stripcslashes.php#34041 ), but not PHP
                    hex = (hex2DigMax).exec(rest);
                    i += hex.length; // Skip over hex
                    target += String.fromCharCode(parseInt(hex, 16));
                    break;
                }
                // Fall-through
            default: // Up to 3 digit octal in PHP, but we may have created a larger one in addcslashes
                rest = str.slice(i+2);
                if (rest.search(oct3DigMaxs) !== -1) { // C accepts hex larger than 2 digits (per http://www.php.net/manual/en/function.stripcslashes.php#34041 ), but not PHP
                    oct = (oct3DigMaxs).exec(rest);
                    i += oct[1].length; // Skip over first octal
                    // target += String.fromCharCode(parseInt(oct[1], 8)); // Sufficient for UTF-16 treatment

                    // Interpret int here as UTF-8 octet(s) instead, produce non-character if none
                    rest = str.slice(i+2); // Get remainder after the octal (still need to add 2, since before close of iterating loop)
                    seq = '';
                    
                    if ((escOctGrp = oct3DigMax.exec(rest)) !== null) {
                        seq += '%'+parseInt(escOctGrp[2], 8).toString(16);
                    }
                    /* infinite loop
                    while ((escOctGrp = oct3DigMax.exec(rest)) !== null) {
                        seq += '%'+parseInt(escOctGrp[2], 8).toString(16);
                    }

                    dl('stripcslashes');
                    alert(
                        stripcslashes('\\343\\220\\201')
                    )
                    */
                   
                    try {
                        target += decodeURIComponent(seq);
                    }
                    catch(e) { // Bad octal group
                        target += '\uFFFD'; // non-character
                    }

                    break;
                }
                target += next;
                break;
        }
        ++i; // Skip special character "next" in switch
    }
}