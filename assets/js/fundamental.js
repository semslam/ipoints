$(document).ready(function (){

   
    var tableAnnual = $('#annualTable')
    var tableProduct = $('#productTable')
    var tableBenefit = $('#benefitTable')
    var tableWallet = $('#walletTable')
    var tableServiceGroup = $('#serviceGroupTable')

    var pathname = window.location.pathname; // Returns path only
    var url      = window.location.href;     // Returns full URL
    var origin   = window.location.origin;   // Returns base URL
    console.log(pathname,url, origin);

    if(pathname == '/uici_repo/fundamental/products' || pathname == '/fundamental/products'){
        loadProduct();
    }else if(pathname == '/uici_repo/fundamental/annual_fee' || pathname == '/fundamental/annual_fee'){
        getFitterAnnnual(0);
    }else if(pathname == '/uici_repo/fundamental/benefits' || pathname == '/fundamental/benefits'){
        loadBenefit();
    }else if(pathname == '/uici_repo/fundamental/wallets' || pathname == '/fundamental/wallets'){
        loadWallet();
    }else if(pathname == '/uici_repo/fundamental/wallet_service_group' || pathname == '/fundamental/wallet_service_group'){
        loadServiceGroup();
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


$('body').on("click",'#fitterProductSearch', function(evt){
    evt.preventDefault();
    if ($.fn.DataTable.isDataTable("#productTable")) {
        $("#productTable").DataTable().destroy();
    }
    getFitterProduct();
});

$('#annualSearch').on("click", function(evt){
    evt.preventDefault();
    var pageno = $(this).attr('data-ci-pagination-page');
    pageno = (IsEmptyOrUndefined(pageno))? 0:pageno;
    if ($.fn.DataTable.isDataTable("#annualTable")) {
        $("#annualTable").DataTable().destroy();
    }
    getFitterAnnnual(pageno);
});

$('#pagination-annual').on('click','a',function(e){
    e.preventDefault(); 
    var pageno = $(this).attr('data-ci-pagination-page');
    if ($.fn.DataTable.isDataTable("#annualTable")) {
         $("#annualTable").DataTable().destroy();
     }
     getFitterAnnnual(pageno);
  });


$('#walletSearch').on("click", function(evt){
    evt.preventDefault();
    if ($.fn.DataTable.isDataTable("#walletTable")) {
        $("#walletTable").DataTable().destroy();
    }
    getWallet();
});

$('#fitterBenefitSearch').on("click", function(evt){
    evt.preventDefault();
    if ($.fn.DataTable.isDataTable("#benefitTable")) {
        $("#benefitTable").DataTable().destroy();
    }
    getFitterBenefit()
});

$('#createServiceGroupSearch').on("click", function(evt){
    evt.preventDefault();
    if ($.fn.DataTable.isDataTable("#serviceGroupTable")) {
        $("#serviceGroupTable").DataTable().destroy();
    }
    getFitterServiceGroup()
});



// $('body').on("click",'#approvedSearch', function(evt){
//     evt.preventDefault();
//     const purchaseId = $(evt.target).data('purchase');
//     postApprovedPayment(purchaseId);
// });

 $('body').on("click",'.createWalletSearch',function(evt){
    evt.preventDefault();
    createWallet(evt);
 });

 $('body').on("click", '.productSubmit',function(evt){
    evt.preventDefault();
    createProduct(evt);
 } );

 $('body').on("click",'.productBenefitSubmit',function(evt){
    evt.preventDefault();
     createBenefit(evt);
    });

    $('body').on("click",'.createServiceGroupSubmit',function(evt){
        evt.preventDefault();
        createServiceGroup(evt);
    });  

 $('.annualExportReport').on("click", annualExportReport);


     function walletTable(data){
         if(!IsEmptyOrUndefined(data)){
            $('#walletHeader').show();
           var walletData =''; 
                      for(x in data){
                        walletData+='<tr>'+
                                '<td>'+data[x].name+'</td>'+
                                '<td>'+data[x].type+'</td>'+
                                '<td>'+(data[x].can_user_inherit == 1
                                    ?'<span class="pull-right claimed label-primary">Yes</span>'
                                    :'<span class="pull-right claimed label-danger">No</span>')+'</td>'+
                                    '<td>'+(data[x].can_topup == 1
                                        ?'<span class="pull-right claimed label-primary">Yes</span>'
                                        :'<span class="pull-right claimed label-danger">No</span>')+'</td>'+
                                '<td>'+(data[x].product_name != null ? data[x].product_name : 'None')+'</td>'+
                                '<td>'+(data[x].service_group_name != null ? data[x].service_group_name : 'None')+'</td>'+
                                '<td> <a href="#" class="btn btn-primary slam-modal" data-modal="modal-'+data[x].id+'" >'+
                                '<i class="fa fa-pencil"></i> '+
                                    ' Edit'+
                                '</a>'+
                                '<div id="modal-'+data[x].id+'" class="modal">'+
                                        '<div class="modal-content m_small">'+
                                            '<div class="modal-header">'+
                                            '<h4 class="text-left">'+data[x].name+' Wallet</h4>'+
                                            '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                        '</div>'+
                                    '<div class="modal-body">'+
                                        '<form class="createWalletForm">'+
                                            '<div class="row" style="margin: 50px;">'+
                                                '<div class="form-group row">'+
                                                    '<label for="Wallet" class="col-sm-4 col-form-label">Wallet Name</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input title="Enter Wallet Name" type="text" placeholder="Enter Wallet Name" value="'+data[x].name+'" name="wallet" class="form-control wallet">'+
                                                        '<div class="validation-message" data-field="wallet"></div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                    '<label for="Product" class="col-sm-4 col-form-label">Product</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<select class="form-control product" name="product">'+
                                                            '<option value="'+data[x].product_id+'">'+data[x].product_name+'</option>'+
                                                        '</select>'+
                                                        '<div class="validation-message" data-field="product"></div>'+ 
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                    '<label for="Type" class="col-sm-4 col-form-label">Type</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<select title="Enter Type" class="form-control type" placeholder="Type" name="type">'+
                                                            type(data[x].type)+
                                                            '<option value="product">Product</option>'+
                                                            '<option value="system">System</option>'+
                                                            '<option value="savings">Savings</option>'+
                                                            '<option value="commission">Commission</option>'+
                                                        '</select>'+
                                                        '<div class="validation-message" data-field="type"></div>'+  
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                    '<label for="CanUserInherit" class="col-sm-4 col-form-label">Can User Inherit</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<select title="Can User Inherit" class="form-control can_user_inherit" placeholder="Enter Can User Inherit" name="can_user_inherit">'+
                                                            (data[x].can_user_inherit == 1 ? '<option value="1">Yes</option>' : '<option value="0">No</option>')+
                                                            '<option value="1">Yes</option>'+
                                                            '<option value="0">No</option>'+
                                                        '</select>'+
                                                        '<div class="validation-message" data-field="can_user_inherit"></div>'+  
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                    '<label for="CanTopup" class="col-sm-4 col-form-label">Can Topup</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<select title="Can Topup" class="form-control can_topup" placeholder="Enter Can Topup" name="can_topup">'+
                                                            (data[x].can_topup == 1 ? '<option value="1">Yes</option>' : '<option value="0">No</option>')+
                                                            '<option value="1">Yes</option>'+
                                                            '<option value="0">No</option>'+
                                                        '</select>'+
                                                        '<div class="validation-message" data-field="can_topup"></div>'+  
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                '<label for="" class="col-sm-4 col-form-label"></label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">'+
                                                        '<input type="hidden" name="id" class="id" value="'+data[x].id+'">'+
                                                        '<button class="btn btn-success createWalletSearch">Submit</button>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>'+
                                '</td>'+
                                '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                                '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                            '</tr>';
                      }
                      $("#walletData").html(walletData);
                      tableWallet.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#walletData").html('<tr><td align="center" colspan="8">NO RECORD FOUND</td></tr>');
                  }

     }

     function type(type){
        if(type == 'product'){
           return '<option value="product">Product</option>';
        }else if(type == 'system'){
            return '<option value="system">System</option>';
        }else if(type == 'savings'){
            return '<option value="savings">Savings</option>';
        }else if(type == 'commission'){
            return '<option value="commission">Commission</option>';
        }
     }

     function annualTable(data){
        if(!IsEmptyOrUndefined(data)){
            $('#annualHeader,#pagination-annual').show();
          var annualData =''; 
                for(x in data){
                       annualData+='<tr>'+
                       '<td>'+data[x].user_name +'</td>'+
                       '<td>'+data[x].contact +'</td>'+
                       '<td>'+data[x].txn_reference +'</td>'+
                       '<td>&#x20A6; '+data[x].cost+'</td>'+
                       '<td>&#x20A6; '+data[x].total_paid+'</td>'+
                       '<td>'+(data[x].start_date ||'0000-00-00')+'</td>'+
                       '<td>'+(data[x].end_date ||'0000-00-00')+'</td>'+
                       '<td>'+data[x].is_complete+'</td>'+
                       '<td>'+data[x].is_latest+'</td>'+
                       '<td>'+data[x].is_active+'</td>'+
                   '</tr>';
                          
                }
                     $("#annualData").html(annualData);
                     tableAnnual.DataTable({
                        'destroy':true,
                        'responsive': true,
                        'bInfo': false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
                        'paging': false,//Dont want paging 
                        'bPaginate': false,//Dont want paging 
                        });
                 }else{
                     $("#annualData").html('<tr><td align="center" colspan="10">NO RECORD FOUND</td></tr>');
                 }
    }

     function productTable(data){
        if(!IsEmptyOrUndefined(data)){
            $('#productHeader').show();
          var productData =''; 
                     for(x in data){
                        productData+='<tr>'+
                               '<td>'+data[x].product_name+'</td>'+
                               '<td>'+data[x].price+'</td>'+
                               '<td>'+data[x].provider_name+'</td>'+
                               '<td>'+(data[x].is_insurance_prod == 1 ? 'Yes' : 'No')+'</td>'+
                               '<td>'+(data[x].base_product_yn == 1 ? 'Yes' : 'No')+'</td>'+
                               '<td>'+data[x].allowable_tenure +' Month(s)</td>'+
                               '<td>'+data[x].value +' % </td>'+
                               '<td> <a href="#" class="btn btn-primary slam-modal" data-modal="modal-'+data[x].id+'" >'+
                                '<i class="fa fa-pencil"></i> '+
                                    ' Edit'+
                                '</a>'+
                                '<div id="modal-'+data[x].id+'" class="modal">'+
                                        '<div class="modal-content m_big">'+
                                            '<div class="modal-header">'+
                                            '<h4 class="text-left">'+data[x].product_name+' Product</h4>'+
                                            '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                        '</div>'+
                                    '<div class="modal-body">'+
                                        '<div class="panel">'+
                                            '<div class="content-box">'+
                                                '<div class="row">'+
                                                    '<form class="createProductForm">'+
                                                        '<div class="row">'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<input title="Enter Product Name" type="text" placeholder="Enter Product Name" value="'+data[x].product_name+'" name="product_name" class="form-control product_name">'+
                                                                    '<div class="validation-message" data-field="product_name"></div>'+
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<input title="Enter Price" type="number" placeholder="&#x20A6; Price" value="'+data[x].price+'" name="price" min="1" class="form-control price">'+
                                                                    '<div class="validation-message" data-field="price"></div>'+
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<select title="Enter Insurance Product" class="form-control insurance_prod" placeholder="Insurance Product" name="insurance_prod">'+
                                                                        (data[x].is_insurance_prod == 1 ? '<option value="1">Yes</option>' : '<option value="0">No</option>')+
                                                                        '<option value="1">Yes</option>'+
                                                                        '<option value="0">No</option>'+
                                                                    '</select>'+
                                                                    '<div class="validation-message" data-field="insurance_prod"></div>'+ 
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<select title="Payment base_product" class="form-control base_product" placeholder="Enter Base Product" name="base_product">'+
                                                                        (data[x].base_product_yn == 1 ? '<option value="1">Yes</option>' : '<option value="0">No</option>')+
                                                                        '<option value="1">Yes</option>'+
                                                                        '<option value="0">No</option>'+
                                                                    '</select>'+
                                                                    '<div class="validation-message" data-field="base_product"></div>'+
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<select title="Is Group Product" class="form-control group_prod" placeholder="Enter Is Group Product" name="group_prod">'+
                                                                        (data[x].is_group_prod == 1 ? '<option value="1">Yes</option>' : '<option value="0">No</option>')+
                                                                        '<option value="1">Yes</option>'+
                                                                        '<option value="0">No</option>'+
                                                                    '</select>'+
                                                                    '<div class="validation-message" data-field="group_prod"></div>'+ 
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<select title="Is Charge Commission" class="form-control charge_commission" placeholder="Enter Charge Commission" name="charge_commission">'+
                                                                        (!IsEmptyOrUndefined(data[x].value)? 
                                                                        '<option value="'+data[x].levies_id+'">'+data[x].value+' %</option>' : 
                                                                        '<option value="">Choose commission</option>')+
                                                                    '</select>'+
                                                                    '<div class="validation-message" data-field="charge_commission"></div>'+ 
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<select title="Is Commission Wallet" class="form-control commission_wallet" placeholder="Enter Commission Wallet" name="commission_wallet">'+
                                                                        (!IsEmptyOrUndefined(data[x].commission_wallet)? 
                                                                        '<option value="'+data[x].coms_wallet_id+'">'+data[x].commission_wallet+'</option>' : 
                                                                        '<option value="">Choose commission Wallet</option>')+
                                                                    '</select>'+
                                                                    '<div class="validation-message" data-field="commission_wallet"></div>'+ 
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<input title="Allowable Tenure" type="number" placeholder="Enter Allowable Tenure" value="'+data[x].allowable_tenure +'" name="allowable_tenure" min="1" max="12" class="form-control allowable_tenure">'+
                                                                    '<div class="validation-message" data-field="allowable_tenure"></div>'+
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<input title="Enter Provider Email" type="text" value="'+data[x].provider_name+'" placeholder="Provider Email" name="customerName" class="form-control customerName">'+
                                                                    '<input type="hidden" name="customerId" value="'+data[x].provider_id+'" class="customerId">'+
                                                                    '<div class="validation-message" data-field="customerId"></div>'+
                                                                '</div>'+
                                                                '<div class="form-group col-sm-12">'+
                                                                    '<textarea title="Product description" name="description"  placeholder="Enter Product description" class="form-control description" rows="6" cols="100">'+data[x].description +'</textarea>'+
                                                                    '<div class="validation-message" data-field="description"></div>'+
                                                                '</div>'+
                                                                '<div class="form-group col-sm-3">'+
                                                                    '<input type="hidden" name="id" value="'+data[x].id+'" class="id">'+
                                                                    '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">'+
                                                                    '<button class="btn btn-success productSubmit">Update</button>'+
                                                                '</div>'+
                                                        '</div>'+
                                                    '</form>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '</td>'+
                               '<td>'+(data[x].is_group_prod == 1 ? 'Yes' : 'No')+'</td>'+
                               '<td>'+(data[x].created_at ||'0000-00-00')+'</td>'+
                               '<td>'+(data[x].updated_at ||'0000-00-00')+'</td>'+
                           '</tr>';
                     }
                     $("#productData").html(productData);
                     tableProduct.DataTable({
                         'destroy':true,
                         'responsive': true
                        });
                 }else{
                     $("#productData").html('<tr><td align="center" colspan="11">NO RECORD FOUND</td></tr>');
                 }
    }

    function benefitTable(data){
        if(!IsEmptyOrUndefined(data)){
            $('#benefitHeader').show();
          var benefitData =''; 
                     for(x in data){
                        benefitData+='<tr>'+
                               '<td>'+data[x].product_name +'</td>'+
                               '<td>&#x20A6; '+data[x].amount+'</td>'+
                               '<td>'+data[x].name+'</td>'+
                               '<td>'+(data[x].status == 1
                                   ?'<span class="pull-right claimed label-primary">Active</span>'
                                   :'<span class="pull-right claimed label-danger">Unactive</span>')+'</td>'+
                                   '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                                   '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                                   '<td> <a href="#" class="btn btn-primary slam-modal" data-modal="modal-'+data[x].id+'" >'+
                                   '<i class="fa fa-pencil"></i> '+
                                       ' Edit'+
                                   '</a>'+
                                   '<div id="modal-'+data[x].id+'" class="modal">'+
                                           '<div class="modal-content m_big">'+
                                               '<div class="modal-header">'+
                                               '<h4 class="text-left">'+data[x].product_name +' Product Benefit</h4>'+
                                               '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                           '</div>'+
                                       '<div class="modal-body">'+
                                       '<div class="ibox-content">'+
                                       '<form class="productBenefitForm">'+
                                           '<div class="row">'+
                                               '<div class="form-group col-sm-3">'+
                                                   '<input title="Enter Amount" type="number" placeholder="&#x20A6; Amount" value="'+data[x].amount+'" name="amount" min="1" class="form-control amount">'+
                                                   '<div class="validation-message" data-field="amount"></div>'+
                                               '</div>'+
                                               '<div class="form-group col-sm-3">'+
                                                   '<input title="Enter Name" type="text" placeholder="Enter Name" name="name" value="'+data[x].name+'" class="form-control name">'+
                                                   '<div class="validation-message" data-field="name"></div>'+
                                               '</div>'+
                                               '<div class="form-group col-sm-3">'+
                                                   '<select title="Enter Status" class="form-control pb-status" placeholder="Enter Status" name="pb-status">'+
                                                   (data[x].status == 1 ? '<option value="1">Active</option>' : '<option value="0">Inactive</option>')+
                                                       '<option value="1">Active</option>'+
                                                       '<option value="0">Inactive</option>'+
                                                   '</select>'+
                                                   '<div class="validation-message" data-field="pb-status"></div>'+
                                               '</div>'+
                                               '<div class="form-group col-sm-12">'+
                                                   '<textarea title="Product note" name="note" placeholder="Enter Benefit note" class="form-control note" rows="6" cols="100">'+data[x].note+'</textarea>'+
                                                   '<div class="validation-message" data-field="note"></div>'+
                                               '</div>'+
                                               '<div class="form-group col-sm-3">'+
                                               '<input type="hidden" value="'+data[x].id+'" name="id"  class="id">'+
                                                   '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">'+
                                                   '<button class="btn btn-success productBenefitSubmit">Submit</button>'+
                                               '</div>'+
                                           '</div>'+
                                        '</form>'+
                                        '</div>'+
                                       '</div>'+
                                   '</div>'+
                                   '</td>'+  
                                   '<td>'+data[x].note+'</td>'+
                           '</tr>';
                     }
                     $("#benefitData").html(benefitData);
                     tableBenefit.DataTable({
                         'destroy':true,
                         'responsive': true
                        });
                 }else{
                     $("#benefitData").html('<tr><td align="center" colspan="8">NO RECORD FOUND</td></tr>');
                 }
    }


    function serviceGroupTable(data){
        if(!IsEmptyOrUndefined(data)){
            $('#serviceGroupHeader').show();
          var serviceGroupData =''; 
                     for(x in data){
                       serviceGroupData+='<tr>'+
                               '<td>'+data[x].name+'</td>'+
                               '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                               '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                               '<td> <a href="#" class="btn btn-primary slam-modal" data-modal="modal-'+data[x].id+'" >'+
                               '<i class="fa fa-pencil"></i> '+
                                   ' Edit'+
                               '</a>'+
                               '<div id="modal-'+data[x].id+'" class="modal">'+
                                       '<div class="modal-content  m_big">'+
                                           '<div class="modal-header">'+
                                           '<h4 class="text-left">'+data[x].name+'</h4>'+
                                           '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                       '</div>'+
                                   '<div class="modal-body">'+
                                       '<form class="createServiceGroupForm">'+
                                           '<div class="row" style="margin: 50px;">'+
                                               '<div class="form-group row">'+
                                                   '<label for="Wallet" class="col-sm-4 col-form-label">Wallet Name</label>'+
                                                   '<div class="col-sm-6">'+
                                                       '<input title="Enter Wallet Service Group Name" type="text" placeholder="Enter Wallet Service Group Name" value="'+data[x].name+'" name="name" class="form-control name">'+
                                                       '<div class="validation-message" data-field="name"></div>'+
                                                   '</div>'+
                                               '</div>'+
                                               '<div class="form-group col-sm-12">'+
                                                   '<textarea title="Product note" name="description" placeholder="Enter Service Group description" class="form-control description" rows="6" cols="100">'+data[x].description+'</textarea>'+
                                                   '<div class="validation-message" data-field="description"></div>'+
                                               '</div>'+
                                               '<div class="form-group row">'+
                                               '<label for="" class="col-sm-4 col-form-label"></label>'+
                                                   '<div class="col-sm-6">'+
                                                       '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">'+
                                                       '<input type="hidden" name="id" class="id" value="'+data[x].id+'">'+
                                                       '<button class="btn btn-success createServiceGroupSubmit">Submit</button>'+
                                                   '</div>'+
                                               '</div>'+
                                           '</div>'+
                                       '</form>'+
                                   '</div>'+
                               '</div>'+
                               '</td>'+
                               '<td> '+(data[x].description ||'N/A')+'</td>'+
                           '</tr>';
                     }
                     $("#serviceGroupData").html(serviceGroupData);
                     tableServiceGroup.DataTable({
                         'destroy':true,
                         'responsive': true
                        });
                 }else{
                     $("#serviceGroupData").html('<tr><td align="center" colspan="8">NO RECORD FOUND</td></tr>');
                 }

    }
     
     function loadWallet(){
         var url = BASE_URL + "/fundamental/loadWallets"
         $('#walletHeader').hide();
         $("#walletData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.wallets);
            walletTable(data.wallets)
         });
     }

     
     function loadProduct(){
         var url = BASE_URL + "/fundamental/loadProduct"
         $('#productHeader').hide();
         $("#productData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
            productTable(data.products)
             
         });
     }

     
     function loadBenefit(){
         var url = BASE_URL + "/fundamental/loadBenefit"
         $('#benefitHeader').hide();
         $("#benefitData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.productBenefits);
            benefitTable(data.productBenefits)
         });
     }

     function loadServiceGroup(){
        var url = BASE_URL + "/fundamental/loadServiceGroup"
        $('#serviceGroupHeader').hide();
        $("#serviceGroupData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        $.get(url, function(data) {
            console.log(data.serviceGroups);
            serviceGroupTable(data.serviceGroups)
        });
    }

     function annualExportReport(evt){
        evt.preventDefault();
        var params = $.param({
            start_date: $('.start_date').val(),
            end_date: $('.end_date').val(),
            txn_reference: $('.txn_reference').val(),
            total_paid: $('.total_paid').val(),
            customerId: $('.customerId').val()
        });
       
        const exportUrl = $('.annualExportReport').attr('href')+'?'+params
        window.location = exportUrl;
    }
     
    function getFitterAnnnual(pageno) {
         $('#annualSearch').html("Searching...").attr('disabled', true);
         $('#annualHeader,#pagination-annual').hide();
         var obj = $('#annualForm').serialize();
         console.log(obj);
         $("#annualData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         var url = BASE_URL + "/fundamental/annual/"+pageno
         $.post(url, obj).done(function(data) {
             try{
                 if (data.value == "success") {
                     $('#annualSearch').html("Search").attr('disabled', false);
                     $('#pagination-annual').html(data.result.pagination);
                     annualTable(data.result.annuals)
                 } else {
                     $('#annualSearch').html("Search").attr('disabled', false);
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

     function getFitterProduct() {
        $('#productSearch').html("Searching...").attr('disabled', true);
         $('#productHeader').hide();
        var obj = $('#productForm').serialize();
        console.log(obj);
        $("#productData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/fundamental/productsList"
        $.post(url, obj).done(function(data) {
            try{
                if (data.value == "success") {
                    $('#productSearch').html("Search").attr('disabled', false);
                    productTable(data.products)
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

    function getFitterWallet() {
        $('#walletSearch').html("Searching...").attr('disabled', true);
        $('#walletHeader').hide();
        var obj = $('#walletForm').serialize();
        console.log(obj);
        $("#walletData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/fundamental/walletsList"
        $.post(url, obj).done(function(data) {
            try{
                if (data.value == "success") {
                    $('#walletSearch').html("Search").attr('disabled', false);
                    walletTable(data.annnuals)
                } else {
                    $('#walletSearch').html("Search").attr('disabled', false);
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

    function getFitterBenefit() {
       $('#fitterBenefitSearch').html("Searching...").attr('disabled', true);
       $('#benefitHeader').hide();
       var obj = $('#fitterBenefitForm').serialize();
       console.log(obj);
       $("#benefitData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/fundamental/productsBenefitList"
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   $('#fitterBenefitSearch').html("Search").attr('disabled', false);
                   benefitTable(data.productBenefits)
               } else {
                   $('#fitterBenefitSearch').html("Search").attr('disabled', false);
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

    function getFitterProduct() {
        $('#fitterProductSearch').html("Searching...").attr('disabled', true);
        $('#productHeader').hide();
        var obj = $('#fitterProductForm').serialize();
        console.log(obj);
        $("#productData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/fundamental/productsList"
        $.post(url, obj).done(function(data) {
            try{
                if (data.value == "success") {
                    $('#fitterProductSearch').html("Search").attr('disabled', false);
                    productTable(data.products)
                } else {
                    $('#fitterProductSearch').html("Search").attr('disabled', false);
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

    function getFitterServiceGroup(){
        $('#fitterServiceGroupSearch').html("Searching...").attr('disabled', true);
        var obj = $('#fitterServiceGroupForm').serialize();
        $('#serviceGroupHeader').hide();
        console.log(obj);
        $("#serviceGroupData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/fundamental/serviceGroupList"
        $.post(url, obj).done(function(data) {
            try{
                if (data.value == "success") {
                    $('#fitterServiceGroupSearch').html("Search").attr('disabled', false);
                    serviceGroupTable(data.serviceGroups)
                } else {
                    $('#fitterServiceGroupSearch').html("Search").attr('disabled', false);
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

    function createProduct(evt) {
        $('.productSubmit').html("Submit").attr('disabled', false);
        
        var obj;
            obj = $(evt.currentTarget).parents('form.createProductForm').serialize();
         var url = BASE_URL + "/fundamental/createProduct"

        swal({   
            title: "Please check this info",   
            text: "Note!!! Saved this info can not be irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Save",
            closeOnConfirm: true 
        }, function() {
            $('.productSubmit').html("Processing...").attr('disabled', true);
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Transaction was successful",
                            type: "success"
                        })
                        $('.productSubmit').html("Saved").attr('disabled', true);
                        $('.createProductForm')[0].reset();
                        loadProduct();
                    } else {
                        $('.productSubmit').html("Submit").attr('disabled', false);
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

    function createWallet(evt) {
        $('.createWalletSearch').html("Processing...").attr('disabled', true);
        var obj;
            obj = $(evt.currentTarget).parents('form.createWalletForm').serialize();
        var url = BASE_URL + "/fundamental/createWallet"
        swal({   
            title: "Please check this info",   
            text: "Note!!! Saved this info can not be irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Save",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Your profile successfully saved",
                            type: "success"
                        })
                        $('.createWalletSearch').html("Saved").attr('disabled', true);
                        $('.createWalletForm')[0].reset();
                        loadWallet();
                    } else {
                        $('.createWalletSearch').html("Submit").attr('disabled', false);
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

    function createBenefit(evt) {
        $('.productBenefitSubmit').html("Processing...").attr('disabled', true);
        $('#benefitHeader').hide();
        var obj;
       
            obj = $(evt.currentTarget).parents('form.productBenefitForm').serialize();
       
        console.log(obj);
        var url = BASE_URL + "/fundamental/createBenefit"
       
        swal({   
            title: "Please check this info",   
            text: "Note!!! Saved this info can not be irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Save",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Your profile successfully saved",
                            type: "success"
                        })
                        $('.productBenefitSubmit').html("Saved").attr('disabled', true);
                        $('.productBenefitForm')[0].reset();
                        loadBenefit(); 
                    } else {
                        $('.productBenefitSubmit').html("Submit").attr('disabled', false);
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

    function createServiceGroup(evt) {
        $('.createServiceGroupSubmit').html("Processing...").attr('disabled', true);
        var obj;
       
            obj = $(evt.currentTarget).parents('form.createServiceGroupForm').serialize();
       
        console.log(obj);
        var url = BASE_URL + "/fundamental/createServiceGroup"
       
        swal({   
            title: "Please check this info",   
            text: "Note!!! Saving this info can not be irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Save",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Your service group successfully saved",
                            type: "success"
                        })
                        $('.createServiceGroupSubmit').html("Saved").attr('disabled', true);
                        $('.createServiceGroupForm')[0].reset();
                        loadServiceGroup(); 
                    } else {
                        $('.createServiceGroupSubmit').html("Submit").attr('disabled', false);
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
                product +='<option value="'+data.products[p].id+'">'+data.products[p].product_name +'</option>';
                //console.log(product)
            }
            $('.product').append(product)
        });
     }

     serviceGroupsOption()
    function serviceGroupsOption(){
        var url = BASE_URL + "/offline/wallet_service_group"
        $.get(url, function(data) {
            service_groups = '';
            for(sg in data.service_group){
                service_groups +='<option value="'+data.service_group[sg].id+'">'+data.service_group[sg].name +'</option>';
            }
            $('.service_group').append(service_groups)
        });
     }

     chargeCommissionOption()
     function chargeCommissionOption(){
         var url = BASE_URL + "/offline/charge_commissions"
         $.get(url, function(data) {
            charge_commission = '';
             for(cc in data.charge_commissions){
                charge_commission +='<option value="'+data.charge_commissions[cc].id+'">'+data.charge_commissions[cc].name +' '+data.charge_commissions[cc].value+' % </option>';
                 //console.log(product)
             }
             $('.charge_commission').append(charge_commission)
         });
      }

      commissionWalletSelector()
     function commissionWalletSelector(){
         var url = BASE_URL + "/offline/commission_wallet"
         $.get(url, function(data) {
            commission_wallet = '';
             for(cc in data.commission_wallets){
                commission_wallet +='<option value="'+data.commission_wallets[cc].id+'">'+data.commission_wallets[cc].name +'</option>';
                 //console.log(product)
             }
             $('.commission_wallet').append(commission_wallet)
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