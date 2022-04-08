$(document).ready(function (){
    var tableUser = $('#userTable')
    var tableProduct = $('#productTable')

    $('.fitter').change(function() {


       const type = $('.fitter').val()
        if(type == 'date_range') { 
           hideFormInputAndReset('.all');
           $('.date_range').show();
           
        } 
        else if(type == 'kyc'){
           hideFormInputAndReset('.all');
           $('.kyc').show();
        }
        else if(type == 'state'){
           hideFormInputAndReset('.all');
           $('.state').show();
        }
        else if(type == 'status'){
           hideFormInputAndReset('.all');
           $('.status').show();
        }
        else if(type == 'group'){
            hideFormInputAndReset('.all');
            $('.group').show();
         }
        else {
           hideFormInputAndReset(' .all');		
        }
   });

   function hideFormInputAndReset(mclass) {
	    $(mclass).hide().find('input,select').val('');
    }

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

 $('#userSearch').on("click", function(evt){
     evt.preventDefault();
     $('#userHeader,#pagination').hide();
     var pageno = $(this).attr('data-ci-pagination-page');
	pageno = (IsEmptyOrUndefined(pageno))? 0:pageno;
     if ($.fn.DataTable.isDataTable("#userTable")) {
         $("#userTable").DataTable().destroy();
     }
     getUsers(pageno);
 });
 $('#pagination').on('click','a',function(e){
    e.preventDefault(); 
    $('#userHeader,#pagination').hide()
    var pageno = $(this).attr('data-ci-pagination-page');
    if ($.fn.DataTable.isDataTable("#userTable")) {
         $("#userTable").DataTable().destroy();
     }
     getUsers(pageno);
  });

 $('#productSearch').on("click", function(evt){
    evt.preventDefault();
    $('#productHeader,#pagination-product').hide()
    var pageno = $(this).attr('data-ci-pagination-page');
	pageno = (IsEmptyOrUndefined(pageno))? 0:pageno;
    if ($.fn.DataTable.isDataTable("#productTable")) {
        $("#productTable").DataTable().destroy();
    }
    getServicesProduct(pageno);
});

$('#pagination-product').on('click','a',function(e){
    e.preventDefault(); 
    $('#productHeader,#pagination-product').hide()
    var pageno = $(this).attr('data-ci-pagination-page');
    if ($.fn.DataTable.isDataTable("#productTable")) {
         $("#productTable").DataTable().destroy();
     }
     getServicesProduct(pageno);
  });

  getServicesProduct(0);
 $('.subscriberBotton').on("click",function(){
     const ctrlBtn = $(".sub");
     ctrlBtn.toggleClass("fa-eye fa-eye-slash");
    if (ctrlBtn.hasClass('fa-eye')) {
        $('.subscriberFitter').show()
    }else{
        $('.subscriberFitter').hide()
    }
 })

 $('.productBotton').on("click",function(){
    const ctrlBtn = $(".pro");
    ctrlBtn.toggleClass("fa-eye fa-eye-slash");
   if (ctrlBtn.hasClass('fa-eye')) {
       $('.productFitter').show()
   }else{
       $('.productFitter').hide()
   }
})
 $('.userExportExcel').on("click", userExportExcel);

     function userTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#userHeader,#pagination').show()
           var usersData =''; 
                      for(x in data){
                usersData+='<tr>'+
                                '<td>'+(data[x].name || 'N/A')+'</td>'+
                                '<td>'+(data[x].gender || 'N/A')+'</td>'+
                                '<td>'+data[x].mobile_number+'</td>'+
                                '<td>'+data[x].group_name+'</td>'+
                                '<td>'+(data[x].birth_date||'00-00-0000')+'</td>'+
                                '<td>'+data[x].created_at+'</td>'+
                                '<td> '+(data[x].address|| 'N/A')+'</td>'+
                                '<td>'+(data[x].state ||'N/A')+'</td>'+
                                '<td>'+(data[x].lga ||'N/A')+'</td>'+
                                '<td> '+(data[x].email ||'N/A')+'</td>'+
                                '<td> '+ (data[x].next_of_kin || 'N/A')+'</td>'+
                                '<td> '+(data[x].next_of_kin_phone|| 'N/A')+'</td>'+
                            '</tr>';
                      }
                      $("#userData").html(usersData);
                      tableUser.DataTable({
                        'destroy':true,
                        'responsive': true,
                        'order': [[5, 'desc']],
                        'bInfo': false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
                        'paging': false,//Dont want paging 
                        'bPaginate': false,//Dont want paging 
                         });
                  }else{
                      $("#userData").html('<tr><td align="center" colspan="12">NO RECORD FOUND</td></tr>');
                    
                  }
     }


     function numOfSubProUserTable(data){
        if(!IsEmptyOrUndefined(data)){
            
          var numOfSubProUserData =''; 
                     for(x in data){
                numOfSubProUserData+='<tr>'+
                               '<td class="text-left">'+(data[x].product_name || 'N/A')+'</td>'+
                               '<td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>'+
                               '<td class="text-right">'+data[x].users+'</td>'+
                           '</tr>';
                     }
                     $("#numOfSubProUserData").html(numOfSubProUserData);
                 }else{
                     $("#numOfSubProUserData").html('<tr><td align="center" colspan="3">NO RECORD FOUND</td></tr>');
                   
                 }
    }

    function groupUserByWalletTable(data){
        if(!IsEmptyOrUndefined(data)){
            
          var groupUserByWalletData =''; 
                     for(x in data){
                        groupUserByWalletData+='<tr>'+
                               '<td class="text-left">'+(data[x].name || 'N/A')+'</td>'+
                               '<td class="text-right">'+data[x].users+'</td>'+
                               '<td class="text-right">'+parseFloat(data[x].balance).toLocaleString(undefined, {maximumFractionDigits:2})+'</td>'+
                           '</tr>';
                     }
                     $("#groupUserByWalletData").html(groupUserByWalletData);
                 }else{
                     $("#groupUserByWalletData").html('<tr><td align="center" colspan="3">NO RECORD FOUND</td></tr>');
                   
                 }
    }

     $('.productExportExcel').on("click", productExportExcel);

     function productTable(data){
         if(!IsEmptyOrUndefined(data)){
            $('#productHeader,#pagination-product').show()
           var productsData =''; 
                      for(x in data){
                productsData+='<tr>'+
                                '<td>'+(data[x].name || 'N/A')+'</td>'+
                                '<td>'+data[x].mobile_number +'</td>'+
                                '<td>'+data[x].product_name+'</td>'+
                                '<td>'+data[x].value+'</td>'+
                                '<td>'+data[x].cover_period+' Months</td>'+
                                '<td>'+(data[x].is_active == 1 ? '<span class="pull-right claimed label-success">Active</span>':'<span class="pull-right claimed label-danger">Expire</span>')+'</td>'+
                                '<td> '+data[x].purchase_date+'</td>'+
                                '<td> '+data[x].expiring_date+'</td>'+
                                '<td> '+data[x].batch_id+'</td>'+
                            '</tr>';
                      }
                      $("#productData").html(productsData);
                      tableProduct.DataTable({
                        'destroy':true,
                        'responsive': true,
                        'bInfo': false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
                        'paging': false,//Dont want paging 
                        'bPaginate': false,//Dont want paging 
                         });
                  }else{
                      $("#productData").html('<tr><td align="center" colspan="9">NO RECORD FOUND</td></tr>');
                  }
     }

     fetchgroupUserByWallet();
     function fetchgroupUserByWallet(){
         var url = BASE_URL + "/dashboard/fetchgroupUserByWallet"
         $("#groupUserByWalletData").html('<tr><td align="center" colspan="3"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.gorup_user_by_wallets)
            groupUserByWalletTable(data.gorup_user_by_wallets);
            //x.toFixed(2)
             
         });
     }

     fetchNumOfProductSubUsers();
     function fetchNumOfProductSubUsers(){
         var url = BASE_URL + "/dashboard/fetchNumOfProductSubUsers"
         $("#numOfSubProUserData").html('<tr><td align="center" colspan="3"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
            numOfSubProUserTable(data.num_of_subscrier_on_products)
             
         });
     }


     function userExportExcel(evt){
         evt.preventDefault();
         var params = $.param({
            // hidden_start_date: $('.hidden_start_date').val(),
            // hidden_end_date: $('.hidden_end_date').val(),
             start_date: $('.start_date').val(),
             end_date: $('.end_date').val(),
             kyc_status: $('.kyc_status').val(),
             lga: $('#lg').val(),
             status: $('.status').val(),
             group: $('.actor').val(),
             fitter: $('.fitter').val(),
             customerId: $('.customerId').val(),
             states: $('#states').val()
             
         });
        //   console.log(params);return;
         const exportUrl = $('.userExportExcel').attr('href')+'?'+params
         window.location = exportUrl;
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
            customerId: $('.customerId').val()
        });
        // console.log(params);return;
        const exportUrl = $('.productExportExcel').attr('href')+'?'+params
        window.location = exportUrl;
    }
     productOption()
     function productOption(){
        var url = BASE_URL + "/dashboard/productOption"
        $.get(url, function(data) {
            products = '';
            for(p in data.products){
                products +='<option value="'+data.products[p].id+'">'+data.products[p].name+'</option>';
            }
            $('.product').append(products)
        });
     }
   
     periodOption()
     function periodOption(){
        var url = BASE_URL + "/dashboard/periodOption"
        $.get(url, function(data) {
            periods = '';
            for(p in data.periods){
                periods +='<option value="'+data.periods[p].cover_period+'">'+data.periods[p].cover_period+'</option>';
            }
            $('.period').append(periods)
        });
     }
     getUsers(0);

     function getUsers(pageno) {
         $('#userHeader,#pagination').hide()
         $('#userSearch').html("Searching...").attr('disabled', true);
         var obj = $('#userForm').serialize();
         console.log(obj);
         $("#userData").html('<tr><td align="center" colspan="12"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         var url = BASE_URL + "/dashboard/getFitterUsers/"+pageno
         $.post(url, obj).done(function(data) {
             try{
                 if (data.value == "success") {
                     $('#userSearch').html("Search").attr('disabled', false);
                     //console.log('success:: ',data);
                     $('#pagination').html(data.result.pagination);
                     userTable(data.result.users)
                 } else {
                     $('#userSearch').html("Search").attr('disabled', false);
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

     function getServicesProduct(pageno) {
        $('#productSearch').html("Searching...").attr('disabled', true);
        $('#productHeader,#pagination-product').hide()
        var obj = $('#productForm').serialize();
        $("#productData").html('<tr><td align="center" colspan="9"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/dashboard/getFitterServicesProducts/"+pageno
        $.post(url, obj).done(function(data) {
            try{
                if (data.value == "success") {
                    $('#productSearch').html("Search").attr('disabled', false);
                    $('#pagination-product').html(data.result.pagination);
                    productTable(data.result.products)
                } else {
                    $('#productSearch').html("Search").attr('disabled', false);
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