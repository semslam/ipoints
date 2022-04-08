
	<div class="content with-top-banner">
		<!-- <div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Quick View</div>
			
		</div> -->
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
										<th class="text-center">Product Name</th>
										<!-- <th class="text-center">Images</th> -->
										<th class="text-center">Status</th>
										<th class="text-center">Value</th>
										<th class="text-center">Over-draft Limit</th>
										<th class="text-center">Date</th>
									</tr>
								</thead>
								<tbody>
								<?php if(!empty($userWalletsBalance)){ ?>
								<?php echo $status =''; ?>
								<?php foreach($userWalletsBalance as $userWallets){ ?>
									
									<tr>
										<td class="text-center"><?= $userWallets->product_name; ?></td>
										<!-- <td class="nowrap"><?= $userWallets->name; ?></td> -->
										<!-- <td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/asparagus.jpg'; ?>"></td> -->
										<td class="text-center">
										<?php  $status =  ($userWallets->balance <= 0)?  'red': 'green'?>	
											<div class="status-pill <?php echo $status;?>"></div>
										</td>
										<td class="text-center"><?= $userWallets->balance; ?></td>
										<td class="text-center"><?= (int)$userWallets->overdraft_limit; ?></td>
										<td class="text-center"><?= $userWallets->updated; ?></td>
										
									</tr>
								<?php }?>
								<?php }else{?>
									<tr><td align="center" colspan="5"> No Wallets Balance Found</td></tr>
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
									<i class="fa fa-newspaper-o"></i> iPoint Transfers Log</h4>
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

  
	function transactionTable(data){
         if(!IsEmptyOrUndefined(data)){
			$('#transactionHeader').show();
           var transactionData =''; 
                      for(x in data){
			
                        transactionData+='<tr>'+
                                '<td>'+data[x].type+'</td>'+
                                '<td>'+data[x].product_name+'</td>'+
                                '<td>'+data[x].value+'</td>'+
                                '<td>'+(data[x].name_sender ||'N/A')+'</td>'+
                                '<td>'+(data[x].name_receiver ||'N/A')+'</td>'+
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
                      $("#transactionData").html('<tr><td align="center" colspan="8">NO iPOINTS TRANSACTION LOG FOUND</td></tr>');
                  }

     } 

    
	 loadTransaction();
    function loadTransaction(){
		$('#transactionHeader').hide();
         var url = BASE_URL + "/dashboard/getTransactionLog"
         $("#transactionData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data){
             transactionTable(data.transfers)
         });
     }

    </script>