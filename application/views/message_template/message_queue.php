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
						<i style="color: #5bc0de;" class="fa fa-envelope-o"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= (!empty($pending))? $pending : 0?>">
								<?=(!empty($pending))? $pending:0?></span>
							</div>
							<div class="card-subtitle"><strong >Pending Message</strong> </div>
						</div>
						<div class="card-subtitle">
							<!-- <a  href="#" class="btn btn-info">Process</a> -->
						</div>
					</div>
				</div>
                <div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #449d44;" class="fa  fa-envelope-open-o"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= (!empty($sent))? $sent : 0 ?>">
									
									<?=(!empty($sent))? $sent : 0 ?>
								</span>
							</div>
							<div class="card-subtitle"><strong>Sent Message</strong></div>
						</div>
                        <div class="card-subtitle">
							
						</div>
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #dd1e32;"  class="fa fa-envelope"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= (!empty($failed))? $failed : 0?>">
									<?=(!empty($failed))? $failed : 0?>
								</span>
							</div>
							<div class="card-subtitle"><strong>Failed  Message</strong></div>
						</div>
                        <div class="card-subtitle">
							
						</div>
					</div>
				</div>
                <div title="Pending SMS charges that still need to be process" class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #449d44;"  class="fa fa-credit-card"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= (!empty($paid))? $paid :0?>">
									
									<?= (!empty($paid))? $paid : 0 ?>	
								</span>
							</div>
							<div class="card-subtitle"><strong>Paid SMS Message</strong></div>
						</div>
                        <div class="card-subtitle">
							
						</div>
					</div>
				</div>
                <div title="An unpaid overdue SMS debt that was sent successfully" class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #ec971f;"  class="fa fa-credit-card-alt"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= (!empty($arrears))? $arrears :0?>">
									
									<?= (!empty($arrears))? $arrears : 0 ?>	
								</span>
							</div>
							<div class="card-subtitle"><strong>Arrears SMS Message</strong></div>
						</div>
                        <div class="card-subtitle">
							
						</div>
					</div>
				</div>
                <div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #5bc0de;" class="fa fa-comments-o"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="0">
								<?=(!empty($balance))? $balance : 0?></span> 
							</div>
						<div class="card-subtitle"><strong>SMS Balance</strong></div>
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
							<form id="messageQueueForm">
								<div class="row">
                                    <div class="form-group col-sm-3">
                                        <input title="Enter User Email Or Phone" type="text" placeholder="User Email Or Phone" name="customerName" class="form-control customerName">
                                        <input type="hidden" name="customerId" class="customerId">
                                        <div class="validation-message" data-field="customerId"></div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <select title="Choose if is paid or free" class="form-control charge" placeholder="Choose charge" name="charge">
											<option value="">Choose charges ?</option>
											<option value="free">Free</option>
											<option value="paid">Paid</option>
											<option value="arrears">Arrears</option>
										</select>
										<div class="validation-message" data-field="charge"></div>
									</div>
                                    <div class="form-group col-sm-3">
                                        <select title="Choose if is paid or free" class="form-control recipient_type" placeholder="Choose if is paid or free" name="recipient_type">
											<option value="">Choose Recipient Type ?</option>
											<option value="single">Single</option>
											<option value="multi">Multi</option>
										</select>
										<div class="validation-message" data-field="recipient_type"></div>
									</div>
                                    <div class='form-group col-sm-3'>
										<select title="Choose Type" class="form-control type" placeholder="Choose Type" name="type">
											<option value="">Choose Type?</option>
											<option value="Sms">Sms</option>
											<option value="Email">Email</option>
										</select>
										<div class="validation-message" data-field="type"></div> 
									</div>
									<div class='form-group col-sm-3'>
										<select title="Enter Message Type" class="form-control message_type" placeholder="Enter Message Type" name="message_type">
											<option value="">Choose Message Type?</option>
											<option value="static">Static</option>
											<option value="dynamic">Dynamic</option>
										</select>
										<div class="validation-message" data-field="message_type"></div>
									</div>
                                    <div class='form-group col-sm-3'>
										<select title="Enter Status" class="form-control mq-status" placeholder="Enter Status" name="mq-status">
											<option value="">Choose Status?</option>
											<option value="pending">Pending</option>
											<option value="sent">Sent</option>
											<option value="failed">Failed</option>
										</select>
										<div class="validation-message" data-field="mq-status"></div>
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
										<button class="btn btn-success" id="messageQueueSearch">Search</button>
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
								<i class="fa fa-download"></i> Message Queuing</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
                            <a  href="<?php echo site_url('messageTemplate/messageQueueExportReport')?>" class="btn btn-success messageQueueExportReport url">EXPORT TO EXCEL (.XLS)</a>
							<hr>
							<table id="messageQueueTable" class="display" cellspacing="0" width="100%">
								<thead id="messageQueueHeader">
									<tr>
										<th>Recipient</th>
										<th>Recipient Type</th>
										<th>Message Type</th>
                                        <th>Charge</th>
										<th>Type</th>
										<th>Attempt Set</th>
										<th>Attempt No</th>
										<th>Priority</th>
										<th>Status</th>
										<th class="none">Message Variable</th>
										<th class="none">Message Subject</th>
										<th class="none">Message Body</th>
										<th class="none">Attachment Url</th>
										<th class="none">Created Date</th>
										<th class="none">Updated Date</th>
									</tr>
								</thead>
								<tbody id="messageQueueData">
								</tbody>
							</table>
								<!-- Paginate -->
							<div id='pagination'></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" >

   var tableMessageQueue = $('#messageQueueTable')
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

		$('#messageQueueSearch').on("click", function (evt) {
			evt.preventDefault();
			if ($.fn.DataTable.isDataTable("#messageQueueTable")) {
				$("#messageQueueTable").DataTable().destroy();
			}
			var pageno = $(this).attr('data-ci-pagination-page');
			pageno = (IsEmptyOrUndefined(pageno))? 0:pageno;
			console.log(pageno);
			getFitterMessageQueue(pageno)
		}); 
	

    function messageQueueTable(data){
         if(!IsEmptyOrUndefined(data)){
			 $('#messageQueueHeader').show();
           var messageQueueData =''; 
                      for(x in data){
                        messageQueueData+='<tr>'+
                                '<td>'+data[x].recipient+'</td>'+
                                '<td>'+data[x].recipient_type+'</td>'+
                                '<td>'+data[x].message_type+'</td>'+
                                '<td>'+
                                charge(data[x].charge)+
                                '</td>'+
                                '<td>'+data[x].type+'</td>'+
                                '<td>'+data[x].attempt_set+'</td>'+
                                '<td>'+data[x].attempt_no+'</td>'+
                                '<td>'+data[x].priority+'</td>'+
                                '<td>'+
                                status(data[x].status)+
								'</td>'+
								'<td> '+data[x].message_variable+'</td>'+
								'<td> '+data[x].message_subject+'</td>'+
								'<td> '+data[x].message_body+'</td>'+
								'<td> '+data[x].attachment_url+'</td>'+
								'<td> '+(data[x].created_at ||'00-00-0000')+'</td>'+
								'<td> '+(data[x].updated_at ||'00-00-0000')+'</td>'+
                            '</tr>';
                      }
                      $("#messageQueueData").html(messageQueueData);
                      tableMessageQueue.DataTable({
                          'destroy':true,
						  'responsive': true,
						  'bInfo': false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
						  'paging': false,//Dont want paging 
						  'bPaginate': false,//Dont want paging 
                         });
                  }else{
                      $("#messageQueueData").html('<tr><td align="center" colspan="15">NO RECORD FOUND</td></tr>');
                  }

     }
     
     function status(status){
        if(status == 'pending'){
           return '<span class="pull-right claimed label-info">Pending</span>';
        }else if(status == 'sent'){
           return '<span class="pull-right claimed label-success">Sent</span>';
        }else if(status == 'failed'){
           return '<span class="pull-right claimed label-danger">Failed</span>';
        }
     }
     
     function charge(charge){
        if(charge == 'free'){
           return '<span class="pull-right claimed label-info">Free</span>';
        }else if(charge == 'paid'){
           return '<span class="pull-right claimed label-success">Paid</span>';
        }else if(charge == 'arrears'){
           return '<span class="pull-right claimed label-warning">Arrears</span>';
        }
	 }
	 

	function getFitterMessageQueue(pageno) {
       $('#messageQueueSearch').html("Searching...").attr('disabled', true);
	   var obj = $('#messageQueueForm').serialize();
	   $('#messageQueueHeader').hide();
       console.log(obj);
       $("#messageQueueData").html('<tr><td align="center" colspan="15"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/messageTemplate/filterQueueMessages/"+pageno
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   $('#messageQueueSearch').html("Search").attr('disabled', false);
				   //console.log(data.messageQueueing);
				   $('#pagination').html(data.result.pagination);
                   messageQueueTable(data.result.messageQueueing)
               } else {
                   $('#messageQueueSearch').html("Search").attr('disabled', false);
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

$('.messageQueueExportReport').on("click", messageQueueExportReport);
   function messageQueueExportReport(evt){
        evt.preventDefault();
        var params = $.param({
            start_date: $('.start_date').val(),
            end_date: $('.end_date').val(),
            charge: $('.charge').val(),
            recipient_type: $('.recipient_type').val(),
            type: $('.type').val(),
            message_type: $('.message_type').val(),
            mqstatus: $('.mq-status').val(),
            customerId: $('.customerId').val()
		});
		const exportUrl = $('.messageQueueExportReport').attr('href')+'?'+params
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
	 
	 $('#pagination').on('click','a',function(e){
       e.preventDefault(); 
	   var pageno = $(this).attr('data-ci-pagination-page');
	   if ($.fn.DataTable.isDataTable("#messageQueueTable")) {
			$("#messageQueueTable").DataTable().destroy();
		}
       getFitterMessageQueue(pageno);
	 });
	 
	 getFitterMessageQueue(0)
     
</script>