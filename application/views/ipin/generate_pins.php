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
								<i class="fa fa-search-plus" aria-hidden="true"></i> Generate Batch iPin</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<form id="generateForm">
								<div class="row">
                                    <div class="form-group col-sm-3">
                                        <label for="balance">Available Balance</label>
										<input title="iPoint Value" type="number" placeholder="iPoint Value"  name="ipoints"
										    class="form-control ipoints"  readonly>
										<div class="validation-message" data-field="ipoints"></div>
									</div>
                                    <div class="form-group col-sm-3">
                                        <label for="balance">iPin Denominator Value</label>
										<input title="Enter iPin value denominator " type="number" placeholder="Enter iPin value denominator" value='1' name="ipin_value" min="1"
										    class="form-control ipin_value">
										<div class="validation-message" data-field="ipin_value"></div>
									</div>
									<div class="form-group col-sm-3">
                                        <label for="quantity">iPoint Batch Quantity</label>
										<input title="Enter Batch Quantity" type="number" placeholder="Enter Batch Quantity" value='1' name="batch_quantities" min="1"
										    class="form-control batch_quantities">
										<div class="validation-message" data-field="batch_quantities"></div>
									</div>
                                    <div class='form-group col-sm-3'>
                                        <label for="quantity">Designation Wallet </label>
										<select title="Choose Wallet" class="form-control wallet" placeholder="Choose Wallet" name="wallet">
											<option value="">Choose Wallet</option>
										</select>
										<div class="validation-message" data-field="wallet"></div>
									</div>
									<div class="form-group col-sm-3">
										<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
										<button class="btn btn-success" id="generateRequest">Generate</button>
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
							<form id="iPinVoucherForm">
								<div class="row">
									<div class="form-group col-sm-3">
										<input title="Enter Batch ID" type="text" placeholder="Enter Batch ID" name="batch_id" class="form-control batch_id">
										<div class="validation-message" data-field="batch_id"></div>
									</div>
									<div class="form-group col-sm-3">
										<input title="Enter Ipin Value" type="number" placeholder="Enter Ipin Value" name="ipin_values"
										    class="form-control ipin_values">
										<div class="validation-message" data-field="ipin_values"></div>
									</div>
									<div class='form-group col-sm-3'>
										<select title="Choose Wallet" class="form-control wallet" placeholder="Choose Wallet" name="wallet_id">
											<option value="">Choose Wallet</option>
										</select>
										<div class="validation-message" data-field="wallet_id"></div>
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
										<button class="btn btn-success" id="iPinVoucherSearch">Search</button>
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
								<i class="fa fa-download"></i> iPin Voucher Batch Queue</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
                            <a  href="<?php echo site_url('ipingenerates/iPinBatchExportReport')?>" class="btn btn-success iPinBatchExportReport url">EXPORT TO EXCEL (.XLS)</a>
							<hr>
							<table id="ipingenerateTable" class="display" cellspacing="0" width="100%">
								<thead id="ipingenerateHeader">
									<tr>
										<th>Created Date </th>
										<th>Total Ipoints</th>
										<th>Ipins Value</th>
										<th>Batch Qty</th>
										<th>Wallet</th>
										<!-- <th>New Status</th>
										<th>Active Status</th> -->
										<th>Canceled Qty</th>
										<th>Used Qty</th>
										<th>Action</th>
										<th class="none">Export Batch List To Excel (.Xls)</th>
										<th class="none">Batch ID</th>
									</tr>
								</thead>
								<tbody id="ipingenerateData">
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
var tableIpingenerate = $('#ipingenerateTable')
   var ipoints = 0;
   Ipoints();
     function Ipoints(){
        var url = BASE_URL + "/ipingenerates/getIpointBalance"
        $.get(url, function(data) {
            ipoints = data.ipoints;
            $('.ipoints').val(ipoints);
        });
     }
   
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

    
    $('body').on('keyup change','.batch_quantities',function(evt){
        evt.preventDefault();
        batch_quantities =  $(".batch_quantities").val()
        ipin_value =  $(".ipin_value").val()
        if(batch_quantities <= 0 || ipin_value <= 0){
            title = "BATCH QUANTITY OR IPIN VALUE";
            message ="The Batch Quantity, Ipin Value Can't  Be Empty Or Negative Number";
            type = warning;
            const info = {title:title, message:message, type:type, position:topfullwidth};
            toastNotification(info);
        }else{
           $('.ipoints').val(ipoints -  (batch_quantities * ipin_value));
           balance =  $('.ipoints').val();
           console.log('Ipoint balance:  '+balance)
           if(balance < 0){
                title = "IPIN VALUE";
                message ="You Don't Have Enough Ipoint To Generate iPin";
                type = warning;
                const info = {title:title, message:message, type:type, position:topfullwidth};
                toastNotification(info);
           }
        }
    })

    $('body').on('keyup change','.ipin_value',function(evt){
        evt.preventDefault();
        ipin_value =  $(".ipin_value").val()
        if(ipin_value <= 0){
            title = "IPIN VALUE";
            message ="The Ipin Value  Can't  Be Empty Or Negative Number";
            type = warning;
            const info = {title:title, message:message, type:type, position:topfullwidth};
            toastNotification(info);
        }
    })
    



		$('#iPinVoucherSearch').on("click", function (evt) {
            evt.preventDefault();
            $('.validation-message').html('');
			if ($.fn.DataTable.isDataTable("#ipingenerateTable")) {
				$("#ipingenerateTable").DataTable().destroy();
			}
			getFitterIpinBatch()
		});
	

    function ipingenerateTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#ipingenerateHeader').show();
           var ipingenerateData =''; 
                      for(x in data){
                        ipingenerateData+='<tr>'+
                                '<td>'+(data[x].created_at ||'0000-00-00')+'</td>'+
                                '<td>'+(data[x].total_ipoints ||'N/A')+'</td>'+
                                '<td>'+data[x].ipin_value+'</td>'+
                                '<td>'+data[x].batch_count+'</td>'+
                                '<td>'+data[x].wallet_name+'</td>'+
                                // '<td><span class="pull-right claimed label-warning">'+data[x].new_status+'</span></td>'+
                                // '<td><span class="pull-right claimed label-info">'+data[x].active_status+'</span></td>'+
                                '<td><span class="pull-right claimed label-danger">'+data[x].cancel_status+'</span></td>'+
                                '<td><span class="pull-right claimed label-success">'+data[x].used_status+'</span></td>'+
                                '<td>'+action(parseInt(data[x].batch_count) ,parseInt(data[x].used_status), parseInt(data[x].new_status),parseInt(data[x].cancel_status),parseInt(data[x].active_status),data[x].batch_name)+'</td>'+
                                '<td>'+
                                '<a href="'+BASE_URL+'/ipingenerates/listOfBatchExportReport" class="btn btn-success listOfBatchExportReport url"   data-batch="'+data[x].batch_name+'">'+
                                    'Export'+
                                '</a>'+
                                '</td>'+
								'<td> '+(data[x].batch_name ||'N/A')+'</td>'+
                            '</tr>';
                      }
                      $("#ipingenerateData").html(ipingenerateData);
                      tableIpingenerate.DataTable({
                          'destroy':true,
                          'responsive': true,
                          'order': [[0, 'desc']]
                         });
                  }else{
                      $("#ipingenerateData").html('<tr><td align="center" colspan="12">NO RECORD FOUND</td></tr>');
                  }

     }
     
	 
	 function action(batch_count,used_status,new_status,cancel_status,active_status,batch_name){
         //
         var process = used_status + active_status;
        if( batch_count == new_status){
            //ON to activate
        return  '<div class="switch">'+
                    '<div class="onoffswitch">'+
                        '<input type="checkbox" '+((batch_count == new_status) ? 'checked' : '')+' data-id="'+batch_name+'"  data-count="'+batch_count+'" class="onoffswitch-checkbox" id="status_'+batch_name+'">'+
                            '<label class="onoffswitch-label" for="status_'+batch_name+'">'+
                                '<span title="The iPin Voucher Not Yet Activate, Please Activate Your Ipin Voucher" class="onoffswitch-inner"></span>'+
                                '<span title="The iPin Voucher Not Yet Activate, Please Activate Your Ipin Voucher" class="onoffswitch-switch"></span>'+
                            '</label>'+
                    '</div>'+
                '</div>'; 
        }else if(!cancel_status > 0 && used_status != batch_count || batch_count == active_status ){
            //OFF to cancel
            return  '<div class="switch">'+
                    '<div class="onoffswitch">'+
                        '<input type="checkbox" '+((!cancel_status > 0) ? '' : 'checked')+' data-id="'+batch_name+'"  data-count="'+batch_count+'" class="onoffswitch-checkbox" id="status_'+batch_name+'">'+
                            '<label class="onoffswitch-label" for="status_'+batch_name+'">'+
                                '<span title="Cancel Your Ipin Voucher" class="onoffswitch-inner"></span>'+
                                '<span title="Cancel Your Ipin Voucher" class="onoffswitch-switch"></span>'+
                            '</label>'+
                    '</div>'+
                '</div>';
           
        }else if(cancel_status > 0){
            return  ' <a href="#" title="The iPin Voucher Was Canceled" class="pull-right claimed label-danger" disabled >'+
                        'Canceled'+
                    '</a>';
        }else{
            return  ' <a href="#" title="All The iPin Voucher Was Used By Subscribers" class="pull-right claimed label-warning" disabled >'+
                        'Exhausted'+
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
        $("#ipingenerateData").html('<tr><td align="center" colspan="12"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/ipingenerates/cancelOrActivateIpinProcess"
        var status;
        var title; 
        var text;
        var confirmButtonText;
        var responseTitle;
        var responseMessage;
        var confirmButtonColor;
        var cancelButtonText;
        console.log('Check::...'+ this.checked);
        if (this.checked) {
            status = 1;
            title = "Are you sure you want to cancel iPin?"; 
            text = "Note!!! This cancellation is irreversible";
            confirmButtonText = "Continue";
            responseTitle = "Canceled"
            responseMessage = "The iPin Batch has been canceled successfully";
            confirmButtonColor: "#ed5565";
            cancelButtonText: "Back";
        } else {
            status = 0;
            title = "Are you sure you want to activate?"; 
            text = "Note!!! This Activation is irreversible";
            confirmButtonText = "Activate";
            responseTitle = "Activated"
            responseMessage = "The iPin Batch has been activated successfully";
            confirmButtonColor: "#1c84c6";
            cancelButtonText: "Cancel";
        }
        var obj = {
            id: id,
            count:count,
            status:status
        };
        swal({   
            title:title,   
            text: text,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonText: cancelButtonText,
            confirmButtonText: confirmButtonText,
            closeOnConfirm: true 
        }, function() {
            console.log(obj);
            
            $.post(url, obj).done(function (data) {
                try {
                    var title;
                    var message;
                    var type;
                    if (data.value == "success") {

                            title = responseTitle;
                            message = responseTitle;
                            type =success;
                        const info = {title:title, message:message, type:type, position:topfullwidth};
                        toastNotification(info);
                        if ($.fn.DataTable.isDataTable("#ipingenerateTable")) {
                            $("#ipingenerateTable").DataTable().destroy();
                        }
                        ipinLoader();
                    }else{
                        const info = {title:"Cancel Error", message:data.value, type:error, position:topfullwidth};
                        toastNotification(info);
                        ipinLoader();
                    }
                } catch (e) {
                    console.log('Exp error: ', e)
                }
            });
        });
        
    });

     walletOption()
     function walletOption(){
        var url = BASE_URL + "/offline/walletList"
        $('#ipingenerateHeader').hide();
        $.get(url, function(data) {
            wallets = '';
            for(p in data.wallets){

                if(data.wallets[p].name == '<?php echo I_POINT ?>' || data.wallets[p].name == '<?php echo I_INCOME ?>'){
                    continue;
                }

                wallets +='<option value="'+data.wallets[p].id+'">'+data.wallets[p].name+'</option>';
                console.log(wallets)
            }
            $('.wallet').append(wallets)
        });
     }
	 
	//  $('body').on("click",'.withdrawerAction', function(evt){
	// 	evt.preventDefault();
	// 	const target = $(evt.target)
	// 	const reference = target.data('withdrawer');
	// 	console.log('approved request id:: '+reference);
	// 	const ctx = target.parents('form');
	// 	postWithdrawerRequest(reference, ctx);
	// });

	//  function postWithdrawerRequest(reference, ctx) {
    //     $('#withdrawerAction').html("Processing...").attr('disabled', true);
    //     console.log('Post Process:: ',reference);
    //     console.log('Post Status:: ',$('.w-status', ctx).val());
    //     obj = {'reference': reference, 'requestId': $('.requestId', ctx).val(),'status': $('.w-status', ctx).val()}
    //      // var obj = $('#approvedForm').serialize();
    //      console.log('withdrawer:: ',obj);
    //      var url = BASE_URL + "/withdraw/processWithdrawer"

    //     swal({   
    //         title: "Are you sure of this request?",   
    //         text: "Note!!! This request is irreversible",
    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#DD6B55",
    //         cancelButtonText: "Cancel",
    //         confirmButtonText: "Save",
    //         closeOnConfirm: true 
    //     }, function() {
    //         $.post(url, obj).done(function(data) {
    //             try{
    //                 if (data.value == "success") {
    //                     swal({   
    //                         title: "Success",
    //                         text: "Request was successful.",
    //                         type: "success"
    //                     })
    //                     $('#withdrawerAction').html("Approved").attr('disabled', true);
    //                     withdrawerManager();
    //                 } else {
    //                     $('#withdrawerAction').html("Process").attr('disabled', false);
    //                     $('.validation-message').html('');
    //                     $('.validation-message').each(function() {
    //                         for (var key in data) {
    //                             if ($(this).attr('data-field') == key) {
    //                                 $(this).html(data[key]);
    //                             }
    //                         }
    //                     });
    //                 }
    //              } catch(e){
    //                  console.log('Exp error: ',e)
    //              }
    //         });

    //     });
        
	// }
	
    function generateIpinsInBatchRequest() {
       $('#generateRequest').html("Generating....").attr('disabled', true);
       $('.validation-message').html('');
       var obj = $('#generateForm').serialize();
       $('#ipingenerateHeader').hide();
       console.log(obj);
       //$("#withdrawerData").html('<tr><td align="center" colspan="10"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/Ipingenerates/generateIpinsInBatch"
       swal({   
            title: "Are you sure you want to continue?",   
            text: "Note!!! This generation of iPin is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5cb85c",
            cancelButtonText: "Cancel",
            confirmButtonText: "Generate",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if(data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Your request was successfully submited. You'll be notified on completion via email shortly.",
                            type: "success"
                        })
                        $('#generateRequest').html("Generated").attr('disabled', true);
                        $('#generateForm')['0'].reset();
                        Ipoints();
                        //ipinLoader();
                    } else {
                        $('#generateRequest').html("Generate").attr('disabled', false);
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

	function getFitterIpinBatch() {
       $('#iPinVoucherSearch').html("Searching...").attr('disabled', true);
       var obj = $('#iPinVoucherForm').serialize();
       $('#ipingenerateHeader').hide();
       console.log(obj);
       $("#ipingenerateData").html('<tr><td align="center" colspan="12"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/ipingenerates/filterIpinBatch"
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   $('#iPinVoucherSearch').html("Search").attr('disabled', false);
                   console.log(data.ipins);
                   ipingenerateTable(data.ipins)
               } else {
                   $('#iPinVoucherSearch').html("Search").attr('disabled', false);
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

$('.iPinBatchExportReport').on("click", ipinBatchExportReport);
   function ipinBatchExportReport(evt){
        evt.preventDefault();
        const form = $('#iPinVoucherForm');
        console.log(evt.target, 'form: ', form);
        var params = $.param({
            start_date: $('.start_date', form).val(),
            end_date: $('.end_date',form).val(),
            batch_id: $('.batch_id',form).val(),
            ipin_value: $('.ipin_values',form).val(),
            wallet: $('.wallet',form).val()
        });
		const exportUrl = $('.iPinBatchExportReport').attr('href')+'?'+params
        window.location = exportUrl;
    }
    
    
    $('body').on("click", '.listOfBatchExportReport', listOfBatchExportReport);
   function listOfBatchExportReport(evt){
        evt.preventDefault();
        const batch = $(evt.currentTarget).data('batch');
        var params = $.param({
            batch_id: batch
		});
		const exportUrl = $('.listOfBatchExportReport').attr('href')+'?'+params
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

     ipinLoader();
    function ipinLoader(){
         var url = BASE_URL + "/ipingenerates/loadIpinGenerations"
         $('#ipingenerateHeader').hide();
         $("#ipingenerateData").html('<tr><td align="center" colspan="12"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.ipins);
             ipingenerateTable(data.ipins)
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