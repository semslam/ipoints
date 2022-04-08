<style>
	.label-success {
     background-color: #5cb85c;
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
								<i class="fa fa-search-plus" aria-hidden="true"></i> Withdrawer Request Process</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<form id="requestForm">
								<div class="row">
                                    <div class='form-group col-sm-3'>
                                        <select class="form-control bank_name" name="bank_name">
                                            <option value="">Choose Bank</option>
                                            <?php
                                                foreach($banks as $bank){
                                                   echo '<option value="'.$bank.'">'.$bank.'</option>'; 
                                                } 
                                            ?>
                                            
                                        </select>
                                        <div class="validation-message" data-field="bank_name"></div>
                                    </div>
                                    <div class="form-group col-sm-3">
										<input title="Enter Account Number" type="number" placeholder="Enter Account Number" name="account_number" 
										    class="form-control amount">
										<div class="validation-message" data-field="account_number"></div>
									</div>
                                    <div class="form-group col-sm-3">
										<input title="Enter Amount" type="number" placeholder="Enter Amount" name="amount" min="1"
										    class="form-control amount">
										<div class="validation-message" data-field="amount"></div>
									</div>
									<div class="form-group col-sm-3">
										<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
										<button class="btn btn-success" id="withdrawerRequest">Request</button>
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
							<form id="withdrawerForm">
								<div class="row">
									<div class="form-group col-sm-3">
										<input title="Enter Transaction Reference" type="text" placeholder="Enter Transaction Reference" name="w-reference" class="form-control w-reference">
										<div class="validation-message" data-field="w-reference"></div>
									</div>
									<div class="form-group col-sm-3">
										<input title="Enter Amount" type="number" placeholder="Enter Amount" name="amount" min="1"
										    class="form-control amount">
										<div class="validation-message" data-field="amount"></div>
									</div>
									<div class='form-group col-sm-3'>
										<select title="Enter Status" class="form-control w-status" placeholder="Enter Status" name="w-status">
											<option value="">Choose Status</option>
											<option value="approved">Approved</option>
											<option value="pending">Pending</option>
											<option value="processing">Processing</option>
											<option value="cancel">Cancel</option>
										</select>
										<div class="validation-message" data-field="w-status"></div>
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
										<button class="btn btn-success" id="withdrawerSearch">Search</button>
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
								<i class="fa fa-download"></i> Withdrawer Queue</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
                            <!-- <a  href="<?php echo site_url('withdraw/withdrawerExportReport')?>" class="btn btn-success withdrawerExportReport url">EXPORT TO EXCEL (.XLS)</a> -->
							<hr>
							<table id="withdrawerTable" class="display" cellspacing="0" width="100%">
								<thead id="withdrawerHeader">
									<tr>
										<th>Name</th>
										<th>Contact</th>
										<th>Wallet</th>
										<th>Bank</th>
										<th>Account</th>
										<th>Amount</th>
										<th>Status</th>
										<th>Action</th>
										<th class="none">Reference</th>
										<th class="none">Created Date</th>
										<th class="none">Updated Date</th>
										<th class="none">Author_By</th>
									</tr>
								</thead>
								<tbody id="withdrawerData">
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

   var tableWithdrawer = $('#withdrawerTable')
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


		$('#withdrawerSearch').on("click", function (evt) {
			evt.preventDefault();
			if ($.fn.DataTable.isDataTable("#withdrawerTable")) {
				$("#withdrawerTable").DataTable().destroy();
			}
			getFitterWithdrawer()
		});
	

    function withdrawerTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#withdrawerHeader').show();
           var withdrawerData =''; 
                      for(x in data){
                        withdrawerData+='<tr>'+
                                '<td>'+(data[x].user_name ||'N/A')+'</td>'+
                                '<td>'+(data[x].contact ||'N/A')+'</td>'+
                                '<td>'+data[x].wallet_name+'</td>'+
                                '<td>'+data[x].bank_name+'</td>'+
                                '<td>'+data[x].account_number+'</td>'+
                                '<td>'+data[x].amount+'</td>'+
                                '<td>'+
                                status(data[x].status)+
								'</td>'+
								'<td> <a href="#" class="btn btn-primary slam-modal" '+action('modal-'+data[x].id,data[x].status)+' >'+
                                    ((data[x].status =='pending')? 'Process':'Conclude')+
                                '</a>'+
                                '<div id="modal-'+data[x].id+'" class="modal">'+
                                        '<div class="modal-content m_small">'+
                                            '<div class="modal-header">'+
                                            '<h4 class="text-left">Withdrawer Process</h4>'+
                                            '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                        '</div>'+
                                    '<div class="modal-body">'+
                                        '<form id="approvedForm">'+
											'<div class="row">'+
												'<div class="col-md-6 col-center-block">'+
													'<div class="form-group">'+
                                                        '<div class="input-group" >'+
                                                            '<h4>Amount: </h4>'+
                                                            '<h4 class="offline-pay-unique">&#x20A6; '+data[x].amount+'</h4>'+
                                                        '</div>'+
                                                        '<br>'+
                                                        '<div class="input-group" >'+
                                                        '<h4>Reference: </h4>'+
                                                            '<h4 class="offline-pay-unique"> '+data[x].transaction_reference +'</h4>'+
                                                        '</div>'+
														'<div class="form-group">'+
															'<h4>Process Action: </h4>'+
															'<select title="Enter Status" class="form-control w-status" placeholder="Enter Status" name="w-status">'+
																'<option value="">Process Action</option>'+
																'<option value="cancel">Cancel</option>'+
															'</select>'+
															'<div class="validation-message" data-field="w-status"></div>'+
														'</div>'+
														'<br>'+
														'<br>'+
														'<input type="hidden" name="requestId" class="requestId" value="'+data[x].id+'">'+
														'<button class="btn btn-success withdrawerAction" data-withdrawer="'+data[x].transaction_reference+'">Action</button>'+
													'</div>'+
												'</div>'+
											'</div>'+
										'</form>'+
                                    '</div>'+
                                '</div>'+
                                '</td>'+
								'<td> '+data[x].transaction_reference+'</td>'+
								'<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
								'<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
								'<td> '+data[x].author_By +'</td>'+
                            '</tr>';
                      }
                      $("#withdrawerData").html(withdrawerData);
                      tableWithdrawer.DataTable({
                          'destroy':true,
                          'responsive': true,
                          'order': [[9, 'desc']]
                         });
                  }else{
                      $("#withdrawerData").html('<tr><td align="center" colspan="12">NO RECORD FOUND</td></tr>');
                  }

     }
     
     function status(status){
        if(status == 'pending'){
           return '<span class="pull-right claimed label-info">Pending</span>';
        }else if(status == 'processing'){
           return '<span class="pull-right claimed label-warning">Processing</span>';
        }else if(status == 'approved'){
           return '<span class="pull-right claimed label-success">Approved</span>';
        }else if(status == 'cancel'){
           return '<span class="pull-right claimed label-danger">Cancel</span>';
        }
	 }
	 
	 function action(id,action){
        if(action == 'approved' || action == 'cancel' ||  action == 'processing'){
           return 'disabled';
        }else{
            return 'data-modal="'+id+'"';
        }
     }
     
     $('body').on("click",'#withdrawerRequest', function(evt){
		evt.preventDefault();
		withdrawerRequest();
	});
	 
	 $('body').on("click",'.withdrawerAction', function(evt){
		evt.preventDefault();
		const target = $(evt.target)
		const reference = target.data('withdrawer');
		const ctx = target.parents('form');
		postWithdrawerRequest(reference, ctx);
	});

	 function postWithdrawerRequest(reference, ctx) {
        $('#withdrawerAction').html("Processing...").attr('disabled', true);
        $('#withdrawerHeader').hide();
        $('.validation-message').html('');
        obj = {'reference': reference, 'requestId': $('.requestId', ctx).val(),'w-status': $('.w-status', ctx).val()}
         
         var url = BASE_URL + "/withdraw/processWithdrawer"

        swal({   
            title: "Are you sure of this request?",   
            text: "Note!!! This request is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Continue",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Request was successful",
                            type: "success"
                        })
                        $('#withdrawerAction').html("Approved").attr('disabled', true);
                        withdrawerManager();
                    } else {
                        $('#withdrawerAction').html("Process").attr('disabled', false);
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
	
    function withdrawerRequest() {
       $('#withdrawerRequest').html("Processing....").attr('disabled', true);
       $('#withdrawerHeader').hide();
       var obj = $('#requestForm').serialize();
       console.log(obj);
       $("#withdrawerData").html('<tr><td align="center" colspan="12"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/withdraw/createWithdrawerRequest"
       swal({   
            title: "Are you sure of this request?",   
            text: "Note!!! This request is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Continue",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if(data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Request was successful",
                            type: "success"
                        })
                        $('#withdrawerRequest').html("Processed").attr('disabled', true);
                        $('.amount').val('');
                        withdrawerManager();
                    } else {
                        $('#withdrawerRequest').html("Request").attr('disabled', false);
                        $('.validation-message').html('');
                        $('.validation-message').each(function() {
                            for (var key in data) {
                                if ($(this).attr('data-field') == key) {
                                    $(this).html(data[key]);
                                }
                            }
                        });
                        withdrawerManager();
                    }
                    } catch(e){
                        console.log('Exp error: ',e)
                    }
            });
        });
       
   }

	function getFitterWithdrawer() {
       $('#withdrawerSearch').html("Searching...").attr('disabled', true);
       $('#withdrawerHeader').hide();
       var obj = $('#withdrawerForm').serialize();
       $("#withdrawerData").html('<tr><td align="center" colspan="10"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/withdraw/filterWithdrawer"
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   $('#withdrawerSearch').html("Search").attr('disabled', false);
             		withdrawerTable(data.withdrawers)
               } else {
                   $('#withdrawerSearch').html("Search").attr('disabled', false);
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

$('.withdrawerExportReport').on("click", withdrawerExportReport);
   function withdrawerExportReport(evt){
        evt.preventDefault();
        var params = $.param({
            start_date: $('.start_date').val(),
            end_date: $('.end_date').val(),
            reference: $('.w-reference').val(),
            amount: $('.amount').val(),
            status: $('.w-status').val()
		});
		const exportUrl = $('.withdrawerExportReport').attr('href')+'?'+params
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

     withdrawerManager();
    function withdrawerManager(){
        $('#withdrawerHeader').hide();
         var url = BASE_URL + "/withdraw/loadWithdrawers"
         $("#withdrawerData").html('<tr><td align="center" colspan="12"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             withdrawerTable(data.withdrawers)
         });
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
</script>