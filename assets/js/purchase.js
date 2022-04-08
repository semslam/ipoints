var onLoad = (function () {
  $('.btn-link').on('click', (evt) => {
    const parent = $(evt.target).parents('.accordion').find('.card').has('.collapsed');
    parent.find('select').val('');
    parent.find('input[type!=hidden]').val('');
  });

  $('.loading-panel').hide();
  $('.form-panel').show();
})();

function validate(formData) {
  let modal = false;
  $('#submit-action').disable([".action"]);
  $("button[title='buyipoints']").html("Processing, please wait...");
  $.ajax({
    url: BASE_URL + "purchase/getPaymentFingerprint",
    type: 'GET',
    data: formData,
    success: (resp, textStatus, jqXHR) => {
      const flwPayload = resp.data.payload;
      flwPayload.onclose = (x, b) => {
        $("button[title='buyipoints']").html("Make Payment");
        $('#submit-action').enable([".action"]);
      }
      flwPayload.callback = (resp) => {
        verifyPayment(resp.data.tx || resp.tx, () => {
          modal.close();
        });
      }
      flwPayload.onclose = ()=>{
        // $('#submit-action')[0].reset();
        // $("button[title='buyipoints']").html("Make Payment");
        //  $('#submit-action').enable([".action"]);
        window.location.reload(true)
      }
      console.log('computed payment payload: ', flwPayload);
      modal = getpaidSetup(flwPayload);
    },
    error: (err) => {
      const msg = err.responseJSON && err.responseJSON.message || 'An error occurred! Please try again';
      $('#notifier').addClass('alert alert-danger').html(msg);
      $("button[title='buyipoints']").html("Make Payment");
      $('#submit-action').enable([".action"]);
    }
  });
}

function verifyPayment(data, callback) {
  $.ajax({
    url: BASE_URL + "purchase/processPayment",
    type: 'POST',
    data,
    success: (resp, textStatus, jqXHR) => {
      console.log(resp)
      if (resp.status === 'succeeded') {
        // window.location = BASE_URL + "template/blank_layout";
        $("#notifier")
          .addClass("alert alert-success")
          .html(msg);
      }
      else {
        const msg = resp.message || 'Could not complete verification';
        $('#notifier').addClass('alert alert-info').html(msg);
      }
    },
    error: (err) => {
      const msg = err.responseJSON && err.responseJSON.message || 'Could not complete verification';
      $('#notifier').addClass('alert alert-danger').html(msg);
      $("button[title='buyipoints']").html("Send");
      $('#submit-action').enable([".action"]);
    }
  })
}

// $("body").on("click",'#web-close-btn', function(evt){
//   evt.preventDefault();
//   alert('Close Pop up');
//   console.log('Close flutter pop up');
//   window.location.reload(true)
// });

$('#flwpugpaidid').load(function(){

  var iframe = $('div#flwpugpaidid.checkout__close hide-xs').contents();

  iframe.find("#web-close-btn").click(function(){
    alert('Close Pop up');
    console.log('Close flutter pop up');
    window.location.reload(true)  
  });
});

// $("#flwpugpaidid").contents().find("#web-close-btn").click(function(){
//   //do something
//   alert('Close Pop up');
//   console.log('Close flutter pop up');
//   window.location.reload(true)      
// }); 

function cancel() {
  $('.datagrid-panel').fadeIn();
  $('.form-panel').fadeOut();
}

function form_routes(action) {
  if (action == 'buyipoints') {
    let formData = $('#submit-action').serialize();
    validate(formData);
  }
  else {
    cancel();
  }
}