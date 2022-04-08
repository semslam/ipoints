<style>
	.label-success {
     background-color: #5cb85c;
}
</style>
<div class="content with-top-banner">
	<div class="panel">
		<div class="content-box">
			<div class="row">
                <div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #449d44;" class="fa fa-retweet"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= (!empty($completed))? $completed : 0?>">
								<?=(!empty($completed))? $completed:0?></span>
							</div>
							<div class="card-subtitle"><strong >Completed Transaction</strong> </div>
						</div>
						<div class="card-subtitle">
							<!-- <a  href="#" class="btn btn-info">Process</a> -->
						</div>
					</div>
				</div>
                <div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #5bc0de;" class="fa  fa-retweet"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= (!empty($pending))? $pending : 0 ?>">
									
									<?=(!empty($pending))? $pending : 0 ?>
								</span>
							</div>
							<div class="card-subtitle"><strong>Pending Transaction</strong></div>
						</div>
                        <div class="card-subtitle">
							
						</div>
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #dd1e32;"  class="fa fa-retweet"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= (!empty($failed))? $failed : 0?>">
									<?=(!empty($failed))? $failed : 0?>
								</span>
							</div>
							<div class="card-subtitle"><strong>Failed Transaction</strong></div>
						</div>
                        <div class="card-subtitle">
							
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
							<form id="espiTransactionForm">
								<div class="row">
                                    <div class="form-group col-sm-3">
                                        <input title="Enter User Email Or Phone" type="text" placeholder="User Email Or Phone" name="customerName" class="form-control customerName">
                                        <input type="hidden" name="customerId" class="customerId">
                                        <div class="validation-message" data-field="customerId"></div>
                                    </div>
									<div class="form-group col-sm-3">
										<input title="Enter transaction request" type="text" placeholder="Enter Transaction Request" name="request" class="form-control request">
										<div class="validation-message" data-field="request"></div>
                                    </div>
									<div class="form-group col-sm-3">
										<input title="Enter payment reference" type="text" placeholder="Enter Payment Reference" name="et-reference" class="form-control et-reference">
										<div class="validation-message" data-field="et-reference"></div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <select title="Choose Status" class="form-control et-status" placeholder="Choose et-status" name="et-status">
											<option value="">Choose Status ?</option>
											<option value="Completed">completed</option>
											<option value="Pending">pending</option>
											<option value="Failed">failed</option>
										</select>
										<div class="validation-message" data-field="et-status"></div>
									</div>
                                    <div class="form-group col-sm-3">
                                        <select title="Choose Transaction Type" class="form-control type" placeholder="Choose Transaction Type" name="type">
											<option value="">Choose Type ?</option>
											<option value="credi">Credi</option>
											<option value="debit">Debit</option>
											<option value="deposit">Deposit</option>
											<!-- <option value="refund">refund</option> -->
										</select>
										<div class="validation-message" data-field="type"></div>
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
										<button class="btn btn-success" id="espiTransactionSearch">Search</button>
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
								<i class="fa fa-download"></i> Espi Transaction Queue</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
                            <a  href="<?php echo site_url('EspiTransactions/espiTransactionExportReport')?>" class="btn btn-success espiTransactionExportReport url">EXPORT TO EXCEL (.XLS)</a>
							<hr>
							<table id="espiTransactionTable" class="display" cellspacing="0" width="100%">
								<thead id="espiTransactionHeader">
									<tr>
										<th>Sender</th>
										<th>Request</th>
                                        <th>iPoints Value</th>
										<th>iSavings Amount</th>
										<th>Status</th>
										<th>Recipient No</th>
										<th>Type</th>
										<th class="none">Reference</th>
										<th class="none">Description</th>
										<th class="none">Created Date</th>
										<th class="none">Updated Date</th>
									</tr>
								</thead>
								<tbody id="espiTransactionData">
								</tbody>
							</table>
								<!-- Paginate -->
							<div id='espiTransactionPagination'></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" >

   var tableEspiTransaction = $('#espiTransactionTable')
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

		$('#espiTransactionSearch').on("click", function (evt) {
			evt.preventDefault();
			if ($.fn.DataTable.isDataTable("#espiTransactionTable")) {
				$("#espiTransactionTable").DataTable().destroy();
			}
			var pageno = $(this).attr('data-ci-pagination-page');
			pageno = (IsEmptyOrUndefined(pageno))? 0:pageno;
			console.log(pageno);
			getFitterEspiTransaction(pageno)
		}); 
	

    function espiTransactionTable(data){
         if(!IsEmptyOrUndefined(data)){
			 $('#espiTransactionHeader').show();
           var espiTransactionData =''; 
                      for(x in data){
                        espiTransactionData+='<tr>'+
                                '<td>'+data[x].sender_name+'</td>'+
                                '<td>'+data[x].request+'</td>'+
                                '<td>&#8381; '+data[x].value+'</td>'+
                                '<td>&#x20A6; '+data[x].amount+'</td>'+
                                '<td>'+
                                status(data[x].status)+
                                '</td>'+
                                '<td>'+data[x].recipient_count+'</td>'+
                                '<td>'+
                                type(data[x].type)+
								'</td>'+
                                '<td>'+data[x].reference+'</td>'+
                                '<td>'+data[x].description+'</td>'+
								'<td> '+(data[x].created_at ||'00-00-0000')+'</td>'+
								'<td> '+(data[x].updated_at ||'00-00-0000')+'</td>'+
                            '</tr>';
                      }
                      $("#espiTransactionData").html(espiTransactionData);
                      tableEspiTransaction.DataTable({
                          'destroy':true,
						  'responsive': true,
						  'bInfo': false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
						  'paging': false,//Dont want paging 
						  'bPaginate': false,//Dont want paging 
                         });
                  }else{
                      $("#espiTransactionData").html('<tr><td align="center" colspan="11">NO RECORD FOUND</td></tr>');
                  }

     }
     
     function status(status){
        if(status == 'pending'){
           return '<span class="pull-right claimed label-info">Pending</span>';
        }else if(status == 'completed'){
           return '<span class="pull-right claimed label-success">Completed</span>';
        }else if(status == 'failed'){
           return '<span class="pull-right claimed label-danger">Failed</span>';
        }
     }
     
     function type(type){
        if(type == 'deposit'){
           return '<span class="pull-right claimed label-info">Deposit</span>';
        }else if(type == 'credit'){
           return '<span class="pull-right claimed label-success">Credit</span>';
        }else if(type == 'refund'){
           return '<span class="pull-right claimed label-warning">Refund</span>';
        }else if(type == 'debit'){
           return '<span class="pull-right claimed label-danger">Debit</span>';
        }
	 }
	 

	function getFitterEspiTransaction(pageno) {
       $('#espiTransactionSearch').html("Searching...").attr('disabled', true);
	   var obj = $('#espiTransactionForm').serialize();
	   $('#espiTransactionHeader').hide();
       console.log(obj);
       $("#espiTransactionData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/EspiTransactions/filterEpsiTransaction/"+pageno
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   $('#espiTransactionSearch').html("Search").attr('disabled', false);
				   //console.log(data.messageQueueing);
				   $('#espiTransactionPagination').html(data.result.pagination);
                   espiTransactionTable(data.result.transactionQueue)
               } else {
                   $('#espiTransactionSearch').html("Search").attr('disabled', false);
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

$('.espiTransactionExportReport').on("click", espiTransactionExportReport);
   function espiTransactionExportReport(evt){
        evt.preventDefault();
        var params = $.param({
            start_date: $('.start_date').val(),
            end_date: $('.end_date').val(),
            request: $('.request').val(),
            reference: $('.et-reference').val(),
            type: $('.type').val(),
            status: $('.et-status').val(),
            customerId: $('.customerId').val()
		});
		const exportUrl = $('.espiTransactionExportReport').attr('href')+'?'+params
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
	 
	 $('#espiTransactionPagination').on('click','a',function(e){
       e.preventDefault(); 
	   var pageno = $(this).attr('data-ci-pagination-page');
	   if ($.fn.DataTable.isDataTable("#espiTransactionTable")) {
			$("#espiTransactionTable").DataTable().destroy();
		}
		getFitterEspiTransaction(pageno);
	 });
	 
	 getFitterEspiTransaction(0)
     
</script>