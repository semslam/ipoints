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
								<i class="fa fa-search-plus" aria-hidden="true"></i> Filter Search</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<form id="wipTransferForm">
								<div class="row">
									<div class="form-group col-sm-3">
										<input title="Enter Request ID" type="text" placeholder="Enter Request ID" name="request_id"
										    class="form-control request_id">
										<div class="validation-message" data-field="request_id"></div>
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
										<button class="btn btn-success" id="wipTransferSearch">Search</button>
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
								<i class="fa fa-download"></i>Bulk Ipoint Transfer Batch Queue</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
                            <a  href="<?php echo site_url('wipBulkTransfer/groupWipTransferExportReport')?>" class="btn btn-success groupWipTransferExportReport url">EXPORT TO EXCEL (.XLS)</a>
							<hr>
							<table id="wipTransferTable" class="display" cellspacing="0" width="100%">
								<thead id="wipTransferHeader">
									<tr>
										<th>Request ID </th>
										<th>Name</th>
										<th>Total Value</th>
										<th>Recipients Count</th>
										<th>Status</th>
										<th>Completed</th>
										<th>Pending</th>
										<th>Invalid</th>
										<th>Cancel</th>
										<th class="none">Cancel Action</th>
										<th class="none">Created Date</th>
										<th class="none">Updated Date</th>
										<!-- <th class="none">Export Batch List To Excel (.Xls)</th> -->
										<th class="none">Reference</th>
									</tr>
								</thead>
								<tbody id="wipTransferData">
								</tbody>
							</table>
                             <!-- Paginate -->
							<div id='pagination-wipTransfer'></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" >
var tableIWipTransfer = $('#wipTransferTable')  
   
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


		$('#wipTransferSearch').on("click", function (evt) {
            evt.preventDefault();
            $('.validation-message').html('');
            var pageno = $(this).attr('data-ci-pagination-page');
            pageno = (IsEmptyOrUndefined(pageno))? 0:pageno;
			if ($.fn.DataTable.isDataTable("#wipTransferTable")) {
				$("#wipTransferTable").DataTable().destroy();
			}
            fitterWipTransferBatch(pageno)
        });
        
        $('#pagination-wipTransfer').on('click','a',function(e){
            e.preventDefault(); 
            var pageno = $(this).attr('data-ci-pagination-page');
            if ($.fn.DataTable.isDataTable("#wipTransferTable")) {
                $("#wipTransferTable").DataTable().destroy();
            }
            fitterWipTransferBatch(pageno);
        });
	

    function wipTransferTable(data){
         if(!IsEmptyOrUndefined(data)){
            $('#wipTransferHeader,#pagination-wipTransfer').show();
           var wipTransferData =''; 
                      for(x in data){
                        wipTransferData+='<tr>'+
                                '<td>'+data[x].request_id+'</td>'+
                                '<td>'+(data[x].name ||'N/A')+'</td>'+
                                '<td>'+data[x].total_transaction_value+'</td>'+
                                '<td>'+data[x].recipients_count+'</td>'+
                                '<td>'+data[x].status+'</td>'+
                                '<td><a href="'+BASE_URL+'/wipBulkTransfer/wipTransferExportReport" data-request="'+data[x].request_id+'" data-status="completed" data-count="'+data[x].completed+'" title="Export Invalid Records" class="pull-right claimed label-success wipTransferExportReport url">'+data[x].completed+'</a></td>'+
                                '<td><span class="pull-right claimed label-info">'+data[x].pending+'</span></td>'+
                                '<td><a href="'+BASE_URL+'/wipBulkTransfer/wipTransferExportReport" data-request="'+data[x].request_id+'" data-status="invalid" data-count="'+data[x].invalid+'" title="Export Invalid Records" class="pull-right claimed label-warning wipTransferExportReport url">'+data[x].invalid+'</a></td>'+
                                '<td><a href="'+BASE_URL+'/wipBulkTransfer/wipTransferExportReport" data-request="'+data[x].request_id+'" data-status="cancel" data-count="'+data[x].cancel+'" title="Export Cancel Records" class="pull-right claimed label-danger wipTransferExportReport url" >'+data[x].cancel+'</a></td>'+
                                '<td>'+action(data[x].invalid, data[x].request_id)+'</td>'+
								'<td> '+(data[x].created_at ||'N/A')+'</td>'+
								'<td> '+(data[x].updated_at ||'N/A')+'</td>'+
								'<td> '+(data[x].txn_reference ||'N/A')+'</td>'+
                            '</tr>';
                      }
                      $("#wipTransferData").html(wipTransferData);
                      tableIWipTransfer.DataTable({
                          'destroy':true,
                          'responsive': true,
                          'order': [[10, 'desc']],
                          'bInfo': false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
                          'paging': false,//Dont want paging 
                          'bPaginate': false,//Dont want paging 
                         });
                  }else{
                      $("#wipTransferData").html('<tr><td align="center" colspan="13">NO RECORD FOUND</td></tr>');
                  }

     }
     
	 
	 function action(invalid, request_id){
         //
        if( invalid > 0){
            //ON to activate
        return  '<div class="switch">'+
                    '<div class="onoffswitch">'+
                        '<input type="checkbox" '+((invalid >0) ? 'checked' : '')+' data-id="'+request_id+'"  data-count="'+invalid+'" class="onoffswitch-checkbox" id="status_'+request_id+'">'+
                            '<label class="onoffswitch-label" for="status_'+request_id+'">'+
                                '<span title="Cancel Invalid Bulk Transfer" class="onoffswitch-inner"></span>'+
                                '<span title="Cancel Invalid Bulk Transfer" class="onoffswitch-switch"></span>'+
                            '</label>'+
                    '</div>'+
                '</div>'; 
        }else  {
            return  '<a href="#" class="pull-right claimed label-warning" disabled >'+
                        'None'+
                    '</a>'; 
        }
     }
     
     $('body').on("click",'#generateRequest', function(evt){
		evt.preventDefault();
		generateIpinsInBatchRequest();
    });
    

    $("body").on("change",'.onoffswitch-checkbox',function (e) {
        const id = $(e.target).data('id')
        const count = $(e.target).data('count');
        $('#wipTransferHeader,#pagination-wipTransfer').hide();
        $("#wipTransferData").html('<tr><td align="center" colspan="13"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/wipBulkTransfer/cancelIvalidBulkTransfer"
        var obj = {
            request_id: id,
            count:count
        };
        swal({   
            title:"Are you sure you want to cancel invalid ipoint transfer?",   
            text: "Note!!! This cancellation is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ed5565",
            cancelButtonText:  "Back",
            confirmButtonText: "Continue",
            closeOnConfirm: true 
        }, function() {
           
            $.post(url, obj).done(function (data) {
                try {
                    var title;
                    var message;
                    var type;
                    if (data.value == "success") {

                            title = "Invalid Cancellation";
                            message = "Invalid transfer cancellation was successful";
                            type = success;
                        const info = {title:title, message:message, type:type, position:topfullwidth};
                        toastNotification(info);
                        if ($.fn.DataTable.isDataTable("#wipTransferTable")) {
                            $("#wipTransferTable").DataTable().destroy();
                        }
                        fitterWipTransferBatch();
                    }else{
                        const info = {title:"Cancel Error", message:data.value, type:error, position:topfullwidth};
                        toastNotification(info);
                        fitterWipTransferBatch();
                    }
                } catch (e) {
                    console.log('Exp error: ', e)
                }
            });
        });
        
    });
	 	
    fitterWipTransferBatch(0)
	function fitterWipTransferBatch(pageno) {
       $('#wipTransferSearch').html("Searching...").attr('disabled', true);
       $('#wipTransferHeader,#pagination-wipTransfer').hide();
       var obj = $('#wipTransferForm').serialize();
       console.log(obj);
       $("#wipTransferData").html('<tr><td align="center" colspan="13"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/wipBulkTransfer/filterWipTransfer/"+pageno
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   $('#wipTransferSearch').html("Search").attr('disabled', false);
                   $('#pagination-wipTransfer').html(data.result.pagination);
                   wipTransferTable(data.result.WipBulkTransfers)
               } else {
                   $('#wipTransferSearch').html("Search").attr('disabled', false);
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

$('.groupWipTransferExportReport').on("click", groupWipTransferExportReport);
   function groupWipTransferExportReport(evt){
        evt.preventDefault();
        const form = $('#wipTransferForm');
        console.log(evt.target, 'form: ', form);
        var params = $.param({
            start_date: $('.start_date', form).val(),
            end_date: $('.end_date',form).val(),
            request_id: $('.request_id',form).val()
        });
		const exportUrl = $('.groupWipTransferExportReport').attr('href')+'?'+params
        window.location = exportUrl;
    }
    
    
    $('body').on("click", '.wipTransferExportReport', wipTransferExportReport);
   function wipTransferExportReport(evt){
        evt.preventDefault();
        const request = $(evt.currentTarget).data('request');
        const status = $(evt.currentTarget).data('status');
        const count = $(evt.currentTarget).data('count');
        if(count == 0){
            alert("Empty "+status+" con't be export..."); return;
        }
        var params = $.param({
            request_id: request,
            status: status
        });
        //console.log(params);return;
		const exportUrl = $('.wipTransferExportReport').attr('href')+'?'+params
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

     
</script>