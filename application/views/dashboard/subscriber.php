<style>
.blinking{
    animation:blinkingText 0.8s infinite;
}
@keyframes blinkingText{
	0%{     color: #000;    }
	49%{    color: transparent; }
	50%{    color: transparent; }
	99%{    color:transparent;  }
	100%{   color: #000;    }
}
</style>
	<div class="content with-top-banner">
		<div class="panel">
			<div class="row">
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<div class="clear">
							<div class="card-title">
							<?php $iSavings = (empty($subscriber_iSavings))? 0 : $subscriber_iSavings?>
							<!-- <h4>(&#8381;) iSavings Balance</h4> -->
							<h4> iSavings Book Balance in (&#8358;) Naira</h4>
								<span class="" data-from="0" data-to="<?=$iSavings?>">
									<?php echo ' &#8358; '.number_format($iSavings, 2, '.', ','); ?>
								</span>
							</div>
						</div>
						<!-- &nbsp;&nbsp;
						<div class="clear">
							<div class="card-title">
								<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h4>
								<span class ="blinking">
										<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
								</span>
							</div>
						</div>
						&nbsp;&nbsp;
						<div class="clear">
							<div class="card-title">
							<?php $naira = (empty($sub_iSavings_naira))? 0 : $sub_iSavings_naira?>
							<h4>Equivalent in (&#8358;) Naira</h4>
								<span class="" data-from="0" data-to="<?=$naira?>">
									<?php echo ' &#8358; '.number_format($naira, 2, '.', ','); ?>
								</span>
							</div>
						</div>
					</div> -->
					
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-12">
					<div class="content-header">
						<i class="fa fa-newspaper-o"></i>
						<div class="content-header-title">Wallets Balance</div>
					</div>
					<div class="content-box">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<!-- <th><input type="checkbox"></th> -->
										<!-- <th class="text-center">Name</th> -->
										<th class="text-left">Wallet Name</th>
										<!-- <th class="text-center">Images</th> -->
										<th class="text-center">Status</th>
										<th class="text-right">Value</th>
										<th class="text-right">Last Transaction Date</th>
									</tr>
								</thead>
								<tbody>
								<?php if(!empty($userWalletsBalance)){ ?>
								<?php echo $status =''; ?>
								<?php foreach($userWalletsBalance as $userWallets){ ?>
									
									<tr>
										<td class="nowrap"><?= $userWallets->product_name; ?></td>
										<!-- <td class="nowrap"><?= $userWallets->name; ?></td> -->
										<!-- <td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/asparagus.jpg'; ?>"></td> -->
										<td class="text-center">
										<?php  $status =  ($userWallets->balance <= 0)?  'red': 'green'?>	
											<div class="status-pill <?php echo $status;?>"></div>
										</td>
										<td class="text-right"><?= $userWallets->balance; ?></td>
										<td class="text-right"><?= $userWallets->updated; ?></td>
										
									</tr>
								<?php }?>
								<?php }else{?>
									<tr><td align="center"  colspan="5"  style="padding-top:10px; color:red"> No Wallets Balance Found...</td></tr>
								<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="content-box">
				<div class="row">
					<div class="col-md-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h4>
									<i class="fa fa-newspaper-o"></i> My Subscriptions</h4>
								<div class="ibox-tools">
								</div>
							</div>
							<div class="ibox-content">
								<table id="productsTable" class="display" cellspacing="0" width="100%">
									<thead id="productHeader">
										<tr>
											<th>Service Name</th>
											<th>More?</th>
											<th>Service Provider</th>
											<th>Value</th>
											<th>Status</th>
											<th>Cover Period</th>
											<th>Start Date</th>
											<th>Expiration Date</th>
										</tr>
									</thead>
									<tbody id="productsData">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="content-box">
				<div class="row">
					<div class="col-md-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h4>
									<i class="fa fa-newspaper-o"></i> iPoints Transfers Log</h4>
								<div class="ibox-tools">
								</div>
							</div>
							<div class="ibox-content">
								<table id="transactionTable" class="display" cellspacing="0" width="100%">
									<thead id="transactionHeader">
										<tr>
											<th>Type</th>
											<th>Wallet</th>
											<th>Value</th>
											<th>Sender</th>
											<th>Receiver</th>
											<th>Current Balance</th>
											<th>Transaction Reference</th>
											<th>Transfers Date</th>
										</tr>
									</thead>
									<tbody id="transactionData">
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
    // $(document).ready(function (){
    var tableProducts = $('#productsTable')
    var tableTransaction = $('#transactionTable')
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

   function hideFormInputAndReset(mclass) {
	    $(mclass).hide().find('input,select').val('');
    }

    function productsTable(data){
         if(!IsEmptyOrUndefined(data)){
			$('#productHeader').show();
           var productsData =''; 
                      for(x in data){
			
                        productsData+='<tr>'+
								'<td>'+data[x].product_name+' </td>'+
								'<td> <a href="#" class="slam-modal" data-modal="modal-'+data[x].id+'"  title="Click to read more about the product">'+
									//  '<i class="fa fa-question-circle"></i>'+
									'Click'+
									'</a>'+
									'<div id="modal-'+data[x].id+'" class="modal">'+
										'<div class="modal-content m_big">'+
											'<div class="modal-header">'+
												'<h4 class="text-left">'+data[x].product_name+' Product</h4>'+
												'<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
											'</div>'+
											'<div class="modal-body">'+ 
												// '<div class="row"> '+$('.modal-body').html(String(data[x].description))+'</div>'+
												'<div style="text-align: center" class="row"><div class="col-md-12 "> '+data[x].description+'</div></div>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</td>'+
                                '<td>'+(data[x].business_name || 'N/A')+'</td>'+
                                '<td>'+data[x].value+'</td>'+
								'<td>'+(data[x].is_active == 1
                                    ?'<span class="pull-right claimed label-primary">Active</span>'
                                    :'<span class="pull-right claimed label-danger">Expired</span>')+'</td>'+
                                '<td>'+data[x].cover_period+' Month(s)</td>'+
                                '<td>'+data[x].purchase_date+'</td>'+
                                '<td>'+data[x].expiring_date+'</td>'+
                            '</tr>';
                      }
                      $("#productsData").html(productsData);
                      tableProducts.DataTable({
                          'destroy':true,
						  'responsive': true,
						  'order': [[7, 'desc']]
                         });
                  }else{
                      $("#productsData").html('<tr><td style="padding-top:10px; color:red" align="center" colspan="7">No subscription service found...</td></tr>');
                  }

     }

	function transactionTable(data){
         if(!IsEmptyOrUndefined(data)){
			$('#transactionHeader').show()
           var transactionData =''; 
                      for(x in data){
			
                        transactionData+='<tr>'+
                                '<td>'+data[x].type+'</td>'+
                                '<td>'+data[x].product_name+'</td>'+
                                '<td>'+data[x].value+'</td>'+
                                '<td>'+(data[x].name_sender || 'N/A')+'</td>'+
                                '<td>'+(data[x].name_receiver || 'N/A')+'</td>'+
                                '<td>'+data[x].current_balance+'</td>'+
                                '<td>'+data[x].reference+'</td>'+
                                '<td>'+data[x].created_at+'</td>'+
                            '</tr>';
                      }
                      $("#transactionData").html(transactionData);
                      tableTransaction.DataTable({
                          'destroy':true,
						  'responsive': true,
						  'order': [[7, 'desc']]
                         });
                  }else{
                      $("#transactionData").html('<tr><td style="padding-top:10px; color:red" align="center" colspan="8">No iPoints transaction log found...</td></tr>');
                  }

     } 

    
    loadProduct();
    function loadProduct(){
		$('#productHeader').hide();
         var url = BASE_URL + "/dashboard/getProductServices"
         $("#productsData").html('<tr><td align="center" colspan="7"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             productsTable(data.productServices)
         });
	 }
	 
	 loadTransaction();
    function loadTransaction(){
		$('#transactionHeader').hide()
         var url = BASE_URL + "/dashboard/getTransactionLog"
         $("#transactionData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data){
             transactionTable(data.transfers)
         });
     }
    </script>