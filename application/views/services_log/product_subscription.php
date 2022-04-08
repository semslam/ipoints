<style>
	.label-success {
     background-color: #5cb85c;
}

.users{
    /* text-align: center; */
    font-size: x-large;
    color: darkgreen;
}
</style>
<div class="content with-top-banner">
	<div class="panel">
		<div class="content-box">
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>
								<i class="fa fa-search-plus" aria-hidden="true"></i> Insurance Product/Service Subscription And Report</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<form id="productSubscriptionForm">
								<div class="row">
                                    <div class="form-group col-sm-3">
                                        <label for="balance">Beneficiaries</label>
										<span class="form-control users">
                                            <b>0</b>
                                        </span>
                                        <div class="validation-message" data-field="value"></div>
									</div>
                                    <div class="form-group col-sm-2">
                                        <label for="kyc">Valid KYC</label>
                                        <div class="i-checks">
                                            <label class="customcheck"> 
                                                <input class="validkyc" name="validkyc" type="checkbox" value="0">
                        
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
										<div class="validation-message" data-field="validkyc"></div>
									</div>
                                    <div class='form-group col-sm-3'>
                                        <label for="period">Starting Period</label>
										<select title="Choose Period" class="form-control period" placeholder="Choose Period" name="period">
											<option value="">--Select--</option>
										</select>
										<div class="validation-message" data-field="period"></div>
									</div>
                                    <div class='form-group col-sm-3'>
                                        <label for="product">Billing Product</label>
										<select title="Choose product" class="form-control product" placeholder="Choose product" name="product">
											<option value="">--Select--</option>
										</select>
										<div class="validation-message" data-field="product"></div>
									</div>
									<div class="form-group col-sm-3">
										<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
										<button class="btn btn-success" id="productSubscriptionRequest">Subscribe</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="panel">
		<div class="content-box">
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>
								<i class="fa fa-search-plus" aria-hidden="true"></i> Filter Search</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<form id="subscriptionForm">
								<div class="row">
									<div class="form-group col-sm-3">
										<input title="Enter Batch ID" type="text" placeholder="Enter Batch ID" name="batch_id" class="form-control batch_id">
										<div class="validation-message" data-field="batch_id"></div>
									</div>
                                    <div class='form-group col-sm-3'>
										<select title="Choose Product" class="form-control products" placeholder="Choose Product" name="products">
											<option value="">--Select--</option>
										</select>
										<div class="validation-message" data-field="products"></div>
									</div>
									
                                    <div class='col-md-3'>
										<div class="form-group">
											<div class='input-group date' id='start'>
												<input type='text' placeholder="Start Date" name="start_date" class="form-control clear start_date" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
											</div>
											<div class="validation-message" data-field="start_date"></div>
										</div>
									</div>
									<div class='col-md-3'>
										<div class="form-group">
											<div class='input-group date' id='end'>
												<input type='text'  placeholder="End Date" name="end_date"  class="form-control clear end_date" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
											</div>
											<div class="validation-message" data-field="end_date"></div>
										</div>
									</div>
									<div class="form-group col-sm-3">
										<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
										<button class="btn btn-success" id="subscriptionSearch">Search</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>
								<i class="fa fa-download"></i> Product Subscription Batch Queue</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
                            <a  href="<?php echo site_url('productSubscription/productSubscriptionGroupExportReport')?>" class="btn btn-success productSubscriptionGroupExportReport url">EXPORT TO EXCEL (.XLS)</a>
							<hr>
							<table id="subscriptionTable" class="display" cellspacing="0" width="100%">
								<thead id="subscriptionHeader">
									<tr>
										<th>Batch ID</th>
										<th>Provider Name</th>
										<th>Beneficiaries</th>
										<th>Product Name</th>
										<th>Billing Price</th>
										<th>Total Billing</th>
										<th>Cover Period</th>
										<th>Is Active</th>
										<th class="none">Export Batch List To Excel (.Xls)</th>
										<th class="none">Purchase Date </th>
										<th class="none">Expiring Date </th>
									</tr>
								</thead>
								<tbody id="subscriptionData">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" >
var tableSubscription = $('#subscriptionTable')
   
    // Handle click on "Expand All" button
    $('#btn-show-all-children').on('click', function () {
        // Expand row details
        table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    });

    // Handle click on "Collapse All" button
    $('#btn-hide-all-children').on('click', function () {
        // Collapse row details
        table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
    });



		$('#subscriptionSearch').on("click", function (evt) {
            evt.preventDefault();
            $('.validation-message').html('');
			if ($.fn.DataTable.isDataTable("#subscriptionTable")) {
				$("#subscriptionTable").DataTable().destroy();
			}
			getFitterProductSubscriptionInBatch()
		});
	

    function productSubscriptionTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#subscriptionHeader').show();
           var subscriptionData =''; 
                      for(x in data){
                        subscriptionData+='<tr>'+
                                '<td>'+(data[x].batch_id ||'N/A')+'</td>'+
                                '<td>'+(data[x].provider_name ||'N/A')+'</td>'+
                                '<td>'+data[x].users+'</td>'+
                                '<td>'+data[x].product_name+'</td>'+
                                '<td>'+data[x].billing_price+'</td>'+
                                '<td>'+data[x].total_billing+'</td>'+
                                '<td>'+data[x].cover_period+'</td>'+
                                '<td>'+(data[x].is_active == 1 ? '<span class="pull-right claimed label-success">Active</span>':'<span class="pull-right claimed label-danger">Expire</span>')+'</td>'+ 
                                '<td>'+
                                '<a href="'+BASE_URL+'/productSubscription/productSubscriptionBatchExportReport" class="btn btn-success productSubscriptionBatchExportReport url" data-product="'+data[x].product_id+'"  data-batch="'+data[x].batch_id+'">'+
                                    'Export'+
                                '</a>'+
                                '</td>'+
								'<td> '+data[x].purchase_date+'</td>'+
								'<td> '+data[x].expiring_date+'</td>'+
                            '</tr>';
                      }
                      $("#subscriptionData").html(subscriptionData);
                      tableSubscription.DataTable({
                          'destroy':true,
                          'responsive': true,
                          'order': [[0, 'desc']]
                         });
                  }else{
                      $("#subscriptionData").html('<tr><td align="center" colspan="11">NO RECORD FOUND</td></tr>');
                  }

     }
     
     $('body').on("click",'#productSubscriptionRequest', function(evt){
		evt.preventDefault();
		productSubscriptionRequest(evt);
    });
    

     $('body').on('change','.validkyc', function(evt){
        evt.preventDefault()
        var isChecked = $('.validkyc').is(':checked');
        if(isChecked){
            $('.validkyc').val('1')
        }else{
            $('.validkyc').val('0')
        }
      });
	
    function productSubscriptionRequest(e) {
       $('#productSubscriptionRequest').html("Process....").attr('disabled', true);
       $('.validation-message').html('');
       var obj = $('#productSubscriptionForm').serialize();
       console.log(obj);
       //$("#withdrawerData").html('<tr><td align="center" colspan="10"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/ProductSubscription/productSubscriptionWithComptKyc"
       swal({   
            title: "Are you sure you want to continue?",   
            text: "Note!!! This product subscription is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5cb85c",
            cancelButtonText: "Cancel",
            confirmButtonText: "Subscribe",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if(data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "The product subscription was successfully submited. You'll be notified on completion via email shortly.",
                            type: "success"
                        })
                        $('#productSubscriptionRequest').html("Subscribed").attr('disabled', true);
                        $('.users').html(data.users);
                        $('#productSubscriptionForm')['0'].reset();
    
                    } else {
                        $('#productSubscriptionRequest').html("Subscribe").attr('disabled', false);
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
   getFitterProductSubscriptionInBatch();
	function getFitterProductSubscriptionInBatch() {
        $('.validation-message').html('');
       $('#subscriptionSearch').html("Searching...").attr('disabled', true);
       var obj = $('#subscriptionForm').serialize();
       $('#subscriptionHeader').hide();
       console.log(obj);
       $("#subscriptionData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/ProductSubscription/filterProductSubscriptionBatch"
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   $('#subscriptionSearch').html("Search").attr('disabled', false);
                   console.log(data.subscriptions);
                   productSubscriptionTable(data.subscriptions)
               } else {
                   $('#subscriptionSearch').html("Search").attr('disabled', false);
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

$('.productSubscriptionGroupExportReport').on("click", productSubscriptionGroupExportReport);
   function productSubscriptionGroupExportReport(evt){
        evt.preventDefault();
        const form = $('#subscriptionForm');
        console.log(evt.target, 'form: ', form);
        var params = $.param({
            start_date: $('.start_date', form).val(),
            end_date: $('.end_date',form).val(),
            batch_id: $('.batch_id',form).val(),
            products: $('.products',form).val(),
        });
		const exportUrl = $('.productSubscriptionGroupExportReport').attr('href')+'?'+params
        window.location = exportUrl;
    }
    
    
    $('body').on("click", '.productSubscriptionBatchExportReport', productSubscriptionBatchExportReport);
   function productSubscriptionBatchExportReport(evt){
        evt.preventDefault();
        const batch = $(evt.currentTarget).data('batch');
        const product = $(evt.currentTarget).data('product');
        var params = $.param({
            batch_id: batch,
            product_id:product
		});
		const exportUrl = $('.productSubscriptionBatchExportReport').attr('href')+'?'+params
        window.location = exportUrl;
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

    //  subscription();
    // function subscription(){
    //      var url = BASE_URL + "/productSubscription/filterProductSubscriptionBatch"
    //      $("#subscriptionData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
    //      $.get(url, function(data) {
    //          console.log(data.subscriptions);
    //          productSubscriptionTable(data.subscriptions)
    //      });
    //  }
     
     insuranceProduct()
     function insuranceProduct(){
        var url = BASE_URL + "/productSubscription/insuranceProduct"
        $.get(url, function(data) {
            product = '';
            for(p in data.products){
                product +='<option value="'+data.products[p].id+'"  data-tenure="'+data.products[p].allowable_tenure+'" >'+(data.products[p].product_name +' ( iPoints '+data.products[p].price+' ) ')+'</option>';
                console.log(product)
            }
            $('.product').append(product)
        });
     }

     products()
     function products(){
        var url = BASE_URL + "/productSubscription/insuranceProduct"
        $.get(url, function(data) {
            product = '';
            for(p in data.products){
                product +='<option value="'+data.products[p].id+'" >'+data.products[p].product_name +'</option>';
                console.log(product)
            }
            $('.products').append(product)
        });
     }
     period()
     function period(){
        var url = BASE_URL + "/productSubscription/period"
        $.get(url, function(data) {
            period = '';
            for(p in data.periods){
                period +='<option value="'+data.periods[p].value+'">'+data.periods[p].name+'</option>';
                console.log(period)
            }
            $('.period').append(period)
        });
     }
</script>