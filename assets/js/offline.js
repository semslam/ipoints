$(document).ready(function (){

   
    var tableOffline = $('#offlineTable')
    var tableOfflineUser = $('#offlineUserTable')

    $('.services').change(function() {
        const type = $('.services').val()
         if(type == 'wallet') { 
             $('.wallets').show();
             $('.products').hide().find('select').val('');
         }else if(type == 'product'){
            $('.products').show();
            $('.wallets').hide().find('select').val('');
         }else{
            $('.wallets, .products').hide().find('select').val('');
         }
    });

    $('.fitter').change(function() {
       const type = $('.fitter').val()
        if(type == 'date_range') { 
           hideFormInputAndReset('.all');
           $('.date_range').show();
           
        } 
        else if(type == 'wallets'){
           hideFormInputAndReset('.all');
           $('.wallets').show();
        }
        else if(type == 'reference'){
           hideFormInputAndReset('.all');
           $('.reference').show();
        }
        else if(type == 'products'){
           hideFormInputAndReset('.all');
           $('.products').show();
        }
        else if(type == 'status-s'){
            hideFormInputAndReset('.all');
            $('.status-s').show();
        }
        else if(type == 'processor'){
            hideFormInputAndReset('.all');
            $('.processor').show();
        }
        else {
           hideFormInputAndReset('.all');		
        }
   });

   function hideFormInputAndReset(mclass) {
	    $(mclass).hide().find('input,select').val('');
    }

    $('body').on('keyup change','.ipoint',function(evt){
        evt.preventDefault();
        ipoints = $(".ipoint").val();
        if(ipoints < 0 ){
            title = "IPOINT VALUE";
            message ="The iPoint Value Can\'t  be Negative";
            type = warning;
            const info = {title:title, message:message, type:type, position:topfullwidth};
            toastNotification(info);
        }else{
            $('.amount').val(ipoints * iPoint_unit);
        }
        
    })

    $('body').on('change','.process-payment', function(evt){
        evt.preventDefault()
        var isChecked = $('.process-payment').is(':checked');
        var isVoidChecked = $('.void-payment').is(':checked');
        if(isVoidChecked){
            alert('Void request is currently checked');return;
        }
        if(isChecked){
            $('.process-payment').val('1')
        }else{
            $('.process-payment').val('0')
        }
      });


      $('body').on('change','.void-payment', function(evt){
        evt.preventDefault()
        var isChecked = $('.void-payment').is(':checked');
        var isProcessChecked = $('.process-payment').is(':checked');
         if(isProcessChecked){
            alert('Approve request is currently checked');return;
        }
        if(isChecked){
            $('.void-payment').val('2')
        }else{
            $('.void-payment').val('0')
        }
      });

      $('body').on('click','.is_settled',function(evt){
        evt.preventDefault()
          
        var isChecked = $('.is_settled').is(':checked');
        if(isChecked){
            $('.is_settled').val('1')
          $('.date_settled').show();
        }else{
            $('.is_settled').val('0')
          $('.date_settled').hide().find('input').val('');
        }
      });

 // Handle click on "Expand All" button
 $('#btn-show-all-children').on('click', function(){
     // Expand row details
     table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
 });

 // Handle click on "Collapse All" button
 $('#btn-hide-all-children').on('click', function(){
     // Collapse row details
     table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
 });


 $('#offlineSearch').on("click", function(evt){
    evt.preventDefault();
    if ($.fn.DataTable.isDataTable("#offlineTable")) {
        $("#offlineTable").DataTable().destroy();
    }
    getFitterOffline();
});

$('#offlineUserSearch').on("click", function(evt){
    evt.preventDefault();
    if ($.fn.DataTable.isDataTable("#offlineUserTable")) {
        $("#offlineUserTable").DataTable().destroy();
    }
    getUserFitterOffline();
});

$('body').on("click",'.approvedSearch', function(evt){
    evt.preventDefault();
    //const purchaseId = $(evt.target).data('purchase');
    //console.log('appred request id:: '+purchaseId);
    //postApprovedPayment(purchaseId);
     var isVoidChecked = $('.void-payment').is(':checked');
        var isProcessChecked = $('.process-payment').is(':checked');
         if(isProcessChecked && isVoidChecked){
            alert('Duplicate checked, You can only checked one');return;
        }
        
        request =(isProcessChecked)? 'approve' : 'void'
    postApprovedPayment(evt,request);
});
$('#createPaymentSearch').on("click", creatPaymentOffline);
 
$('.paymentExportReport').on("click", paymentExportReport);

 function paymentExportReport(evt){
    evt.preventDefault();
    
    var params = $.param({
        start_date: $('.start_date').val(),
        end_date: $('.end_date').val(),
        fitter: $('.fitter').val(),
        reference: $('.reference').val(),
        wallet: $('.wallet').val(),
        product: $('.product').val(),
        amount: $('.amount').val(),
        purchase_status: $('.purchase_status').val(),
        payment_processor: $('.payment_processor').val(),
        customerId: $('.customerId').val()
    });
    // console.log(params);return;
    const exportUrl = $('.paymentExportReport').attr('href')+'?'+params
    window.location = exportUrl;
 }

     function offlineTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#offlineHeader').show();
           var offlineData =''; 
                      for(x in data){
                        offlineData+='<tr>'+
                                '<td>'+data[x].user_name +'</td>'+
                                '<td>'+data[x].payment_ref +'</td>'+
                                '<td>'+data[x].quantity+'</td>'+
                                '<td>&#x20A6; '+data[x].amount+'</td>'+
                                '<td>'+status(data[x].processing_status)+'</td>'+
                                '<td>'+data[x].payment_processor+'</td>'+
                                '<td> <a href="#" class="btn btn-primary slam-modal" '+actionProcess('modal-'+data[x].id,data[x].processing_status)+' >'+
                                    'Process'+
                                '</a>'+
                                '<div id="modal-'+data[x].id+'" class="modal">'+
                                        '<div class="modal-content m_small">'+
                                            '<div class="modal-header">'+
                                            '<h4 class="text-left">Offline Payment Process</h4>'+
                                            '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                        '</div>'+
                                    '<div class="modal-body">'+
                                        '<form class="approvedForm">'+
											'<div class="row">'+
												'<div class="col-md-6 col-center-block">'+
													'<div class="form-group">'+
                                                        '<div class="input-group" >'+
                                                        '<h4>Name: </h4>'+
                                                        '<h4 class="offline-pay-unique">'+data[x].user_name+'</h4>'+
                                                        '</div>'+
                                                        '<br>'+
                                                        '<div class="input-group" >'+
                                                            '<h4>Amount: </h4>'+
                                                            '<h4 class="offline-pay-unique">&#x20A6; '+data[x].amount+'</h4>'+
                                                        '</div>'+
                                                        '<br>'+
                                                        '<div class="input-group" >'+
                                                        '<h4>Reference: </h4>'+
                                                            '<h4 class="offline-pay-unique"> '+data[x].payment_ref +'</h4>'+
                                                        '</div>'+
                                                        '<br>'+
                                                        '<br>'+propose(data[x])+
                                                        '<div class="i-checks"><label class="customcheck"> <input class="void-payment" name="void-payment" type="checkbox" value="0">  Void Request <span class="checkmark"></span></label></div>'+
														'<div class="validation-message" data-field="void-payment"></div>'+
                                                        '<label></label>'+
														'<div class="i-checks"><label class="customcheck"> <input class="process-payment" name="process-payment" type="checkbox" value="0">  Approve Request <span class="checkmark"></span></label></div>'+
														'<div class="validation-message" data-field="process-payment"></div>'+
                                                        '<label></label>'+
                                                        '<input type="hidden" name="id" class="id" value="'+data[x].id+'">'+
														'<button class="btn btn-success approvedSearch" data-purchase="'+data[x].id+'" >Process</button>'+
													'</div>'+
												'</div>'+
											'</div>'+
										'</form>'+
                                    '</div>'+
                                '</div>'+
                                '</td>'+
                                '<td> '+(data[x].wallet_name ||'N/A')+'</td>'+
                                // '<td> '+(data[x].product_name ||'N/A')+'</td>'+
                                '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                                '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                                '<td> '+(data[x].requester_name|| 'N/A')+'</td>'+
                                '<td> '+(data[x].approver_name || 'N/A') +'</td>'+
                                '<td> '+(data[x].description || 'N/A')+'</td>'+
                            '</tr>';
                      }
                      $("#offlineData").html(offlineData);
                      tableOffline.DataTable({
                          'destroy':true,
                          'responsive': true,
                          'order': [[8, 'desc']]
                          
                         });
                  }else{
                      $("#offlineData").html('<tr><td align="center" colspan="13">NO RECORD FOUND</td></tr>');
                  }

                function propose(data){
                      
                    if(data.wallet_name != null || data.wallet_name != ''){
                        return '<div class="input-group" >'+
                        '<h4>Wallet: </h4>'+
                            '<h4 class="offline-pay-unique"> '+data.wallet_name +'</h4>'+
                        '</div>'+
                        '<br>'+
                        '<br>';
                    }else{
                        return '<div class="input-group" >'+
                        '<h4>Product: </h4>'+
                            '<h4 class="offline-pay-unique"> '+data.product_name +'</h4>'+
                        '</div>'+
                        '<br>'+
                        '<br>';
                    }
                }
     }


     function status(status){
                    if(status == 1){
                        return '<span class="pull-right claimed label-primary">Processed</span>';
                    }else if(status == 0){
                        return '<span class="pull-right claimed label-danger">Pending</span>';
                    }else if(status == 2){
                        return '<span class="pull-right claimed label-info">Voided</span>';
                    }
                }

     function offlineUserTable(data){
        if(!IsEmptyOrUndefined(data)){
            $('#offlineUserHeader').show();
          var offlineUserData =''; 
                     for(x in data){
                        offlineUserData+='<tr>'+
                               '<td>'+data[x].payment_ref +'</td>'+
                               '<td>'+data[x].quantity+'</td>'+
                               '<td>&#x20A6; '+data[x].amount+'</td>'+
                               '<td>'+status(data[x].processing_status)+'</td>'+
                               '<td>'+data[x].payment_processor+'</td>'+
                               '<td> '+(data[x].wallet_name ||'N/A')+'</td>'+
                            //    '<td> '+(data[x].product_name ||'N/A')+'</td>'+
                               '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                               '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                               '<td> '+(data[x].requester_name|| 'N/A')+'</td>'+
                               '<td> '+(data[x].description || 'N/A')+'</td>'+
                           '</tr>';
                     }
                     $("#offlineUserData").html(offlineUserData);
                     tableOfflineUser.DataTable({
                         'destroy':true,
                         'responsive': true,
                         'order': [[6, 'desc']]
                        });
                 }else{
                     $("#offlineUserData").html('<tr><td align="center" colspan="9">NO RECORD FOUND</td></tr>');
                 }

                 function propose(data){
                     
                   if(data.wallet_name != null || data.wallet_name != ''){
                       return '<div class="input-group" >'+
                       '<h4>Wallet: </h4>'+
                           '<h4 class="offline-pay-unique"> '+data.wallet_name +'</h4>'+
                       '</div>'+
                       '<br>'+
                       '<br>';
                   }else{
                       return '<div class="input-group" >'+
                       '<h4>Product: </h4>'+
                           '<h4 class="offline-pay-unique"> '+data.product_name +'</h4>'+
                       '</div>'+
                       '<br>'+
                       '<br>';
                   }
               }
    }


     $('.productExportExcel').on("click", productExportExcel);

     function actionProcess(id,action){
        if(action == 1 || action == 2){
           return 'disabled';
        }else{
            return 'data-modal="'+id+'"';
        }
     }

     loadOfflinePayment();
     function loadOfflinePayment(){
         var url = BASE_URL + "/offline/loadPaymentPurchase"
         $('#offlineHeader').hide();
         $("#offlineData").html('<tr><td align="center" colspan="13"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
            offlineTable(data.paymentPurchases)
             
         });
     }

     loadUserOfflinePayment();
     function loadUserOfflinePayment(){
         var url = BASE_URL + "/offline/userPaymentPurchase"
         $('#offlineUserHeader').hide();
         $("#offlineUserData").html('<tr><td align="center" colspan="9"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.paymentPurchases);
            offlineUserTable(data.paymentPurchases)
         });
     }

     function productExportExcel(evt){
        evt.preventDefault();
        var params = $.param({
            pro_start_date: $('.pro_start_date').val(),
            pro_end_date: $('.pro_end_date').val(),
            product: $('.product').val(),
            period: $('.period').val(),
            fitter: $('.logic_option').val(),
            value: $('.value').val(),
            merchant: $('#merchantId').val()
        });
        // console.log(params);return;
        const exportUrl = $('.productExportExcel').attr('href')+'?'+params
        window.location = exportUrl;
    }
     
     function getFitterOffline() {
        $('.validation-message').html('')
         $('#offlineSearch').html("Searching...").attr('disabled', true);
         var obj = $('#offlineForm').serialize();
         $('#offlineHeader').hide();
         console.log(obj);
         $("#offlineData").html('<tr><td align="center" colspan="13"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         var url = BASE_URL + "/offline/getFitterPaymentPurchase"
         $.post(url, obj).done(function(data) {
             try{
                 if (data.value == "success") {
                     $('#offlineSearch').html("Search").attr('disabled', false);
                     //console.log('success:: ',data);
                     if ($.fn.DataTable.isDataTable("#offlineTable")) {
                        $("#offlineTable").DataTable().destroy();
                    }
                     offlineTable(data.paymentPurchases)
                     
                 } else {
                     $('#offlineSearch').html("Search").attr('disabled', false);
                     $('.validation-message').html('');
                     $('.validation-message').each(function() {
                         for (var key in data) {
                             if ($(this).attr('data-field') == key) {
                                 $(this).html(data[key]);
                             }
                         }
                     });
                 }
              } catch(e){
                  console.log('Exp error: ',e)
              }
         });
     }

     function getUserFitterOffline() {
        $('.validation-message').html('')
        $('#offlineUserSearch').html("Searching...").attr('disabled', true);
        var obj = $('#offlineUserForm').serialize();
        $('#offlineUserHeader').hide();
        console.log(obj);
        $("#offlineUserData").html('<tr><td align="center" colspan="9"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/offline/userPaymentPurchase"
        $.post(url, obj).done(function(data) {
            try{
                if (data.value == "success") {
                    $('#offlineUserSearch').html("Search").attr('disabled', false);
                    offlineUserTable(data.paymentPurchases)

                } else {
                    $('#offlineUserSearch').html("Search").attr('disabled', false);
                    $('.validation-message').html('');
                    $('.validation-message').each(function() {
                        for (var key in data) {
                            if ($(this).attr('data-field') == key) {
                                $(this).html(data[key]);
                            }
                        }
                    });
                }
             } catch(e){
                 console.log('Exp error: ',e)
             }
        });
    }

     function postApprovedPayment(evt,requests) {
        $('.approvedSearch').html("Processing...").attr('disabled', true);
        $('.validation-message').html('')
        $("#offlineData").html('<tr><td align="center" colspan="13"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        request = "";title="";type="";
        if(requests == 'void'){
            request="The transaction was successfully voided"
            title="Are you sure, you want to void this request?"
        }else{
            request="The transaction was approved successfully"
            title="Are you sure, you want to approve this request?"
        }
        
        
        obj = $(evt.currentTarget).parents('form.approvedForm').serialize();
       
         console.log('ipoint: ',obj);
         var url = BASE_URL + "/offline/approveRequest"
        swal({   
            title: title,   
            text: "Note!!! This action is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            cancelButtonText: "Cancel",
            confirmButtonText: "Continue",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: request,
                            type: "success"
                        })
                        $('.approvedSearch').html("Approved").attr('disabled', true);
                        //console.log('success:: ',data);
                        if ($.fn.DataTable.isDataTable("#offlineTable")) {
                            $("#offlineTable").DataTable().destroy();
                        }
                        loadOfflinePayment();
                    } else {
                        swal({   
                            title: "Failed",
                            text: "Your request wasn't successful.",
                            type: "error"
                        })
                        $('.approvedSearch').html("Process").attr('disabled', false);
                        $('.validation-message').html('');
                        $('.validation-message').each(function() {
                            for (var key in data) {
                                if ($(this).attr('data-field') == key) {
                                    $(this).html(data[key]);
                                }
                            }
                        });
                        if ($.fn.DataTable.isDataTable("#offlineTable")) {
                            $("#offlineTable").DataTable().destroy();
                        }
                        loadOfflinePayment();
                    }
                 } catch(e){
                     console.log('Exp error: ',e)
                 }
            });

        });
        
    }

     function creatPaymentOffline() {
        $('.validation-message').html("")
        $('#createPaymentSearch').html("Processing...").attr('disabled', true);
        var obj = $('#createPaymentForm').serialize();
        console.log(obj);
        var url = BASE_URL + "/offline/createOfflinePayment"
       
        swal({   
            title: "Are you sure you want to continue?",   
            text: "Note!!! This request is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            cancelButtonText: "Cancel",
            confirmButtonText: "Continue",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Your request was successful.",
                            type: "success"
                        })
                        $('#createPaymentSearch').html("Submitted").attr('disabled', true);
                       // productTable(data.products)
                       $('#createPaymentForm')[0].reset();
                    } else {
                        swal({   
                            title: "Failed",
                            text: "Your request wasn't successful.",
                            type: "error"
                        })
                        $('#createPaymentSearch').html("Submit").attr('disabled', false);
                        $('.validation-message').html('');
                        $('.validation-message').each(function() {
    
                            for (var key in data) {
                                if ($(this).attr('data-field') == key) {
                                    $(this).html(data[key]);
                                }
                            }
                        });
                    }
                 } catch(e){
                     console.log('Exp error: ',e)
                 }
            });
    
        });
    }

      wallteOption()
     function wallteOption(){
        var url = BASE_URL + "/offline/walletList"
        $.get(url, function(data) {
            wallets = '';
            for(p in data.wallets){
                wallets +='<option value="'+data.wallets[p].id+'">'+data.wallets[p].name+'</option>';
                console.log(wallets)
            }
            $('.wallet').append(wallets)
        });
     }

     productOption()
     function productOption(){
        var url = BASE_URL + "/offline/productList"
        $.get(url, function(data) {
            product = '';
            for(p in data.products){
                product +='<option value="'+data.products[p].id+'">'+(data.products[p].product_name +' ( '+data.products[p].price+' iPoint = &#x20A6; '+data.products[p].price * iPoint_unit +' ) ')+'</option>';
                console.log(product)
            }
            $('.product').append(product)
        });
     }


     paymentProcessorOption()
     function paymentProcessorOption(){
        var url = BASE_URL + "/offline/paymentProcessorList"
        $.get(url, function(data) {
            paymentProcessor = '';
            for(p in data.paymentProcessors){
                paymentProcessor +='<option value="'+data.paymentProcessors[p].id+'">'+data.paymentProcessors[p].name+'</option>';
                console.log(paymentProcessor)
            }
            $('.payment_processor').append(paymentProcessor)
        });
     }


     $('#start').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
     $('#end').datetimepicker({
         useCurrent: false, //Important! See issue #1075
         format: 'YYYY-MM-DD HH:mm:ss'
     });
     $("#start").on("dp.change", function (e) {
         $('#end').data("DateTimePicker").minDate(e.date);
     });
     $("#end").on("dp.change", function (e) {
         $('#start').data("DateTimePicker").maxDate(e.date);
     });

     $('#date_approved').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
     $('#date_settled').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});

     $('#start_services').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
     $('#end_services').datetimepicker({
         useCurrent: false, //Important! See issue #1075
         format: 'YYYY-MM-DD HH:mm:ss'
     });
     $("#start_services").on("dp.change", function (e) {
         $('#end_services').data("DateTimePicker").minDate(e.date);
     });
     $("#end_services").on("dp.change", function (e) {
         $('#start').data("DateTimePicker").maxDate(e.date);
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
});