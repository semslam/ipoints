<style>
.text-value,.textarea-value,.number-value,.all{
 display:none;
}
</style>
<div class="content with-top-banner">
    <div class="panel">
			    <div class="content-box">
			        <div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Create Gifting Config</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">

                                        <form class="form-horizontal giftingConfigForm">
                                            <div class="form-group">
                                                <label for="Wallet" class="col-sm-2 control-label">Wallet</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control wallet" name='wallet' >
                                                        <option value="">Choose Wallet</option>
                                                        
                                                    </select>
                                                    <div class="validation-message" data-field="wallet"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="ProcessType" class="col-sm-2 control-label">Process Type</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control process_type" name='process_type' >
                                                        <option value="">Choose Process Type</option>
                                                        <option value="default">Default</option>
                                                        <option value="espi">Espi</option>
                                                    </select>
                                                    <div class="validation-message" data-field="process_type"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="MessageDesignate" class="col-sm-2 control-label">Message Designate</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control message_designate" name='message_designate' >
                                                    <option value="">Choose Message Designate</option>
                                                        <option value="all">Send To All User</option>
                                                        <option value="old">Send To Old User Only</option>
                                                        <option value="new">Send To New User Only</option>
                                                    </select>
                                                    <div class="validation-message" data-field="message_designate"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="OldUserMessage" class="col-sm-2 control-label">Old User Message</label>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control message_temp_type_1" readonly  name="message_temp_type_1" value="old">
                                                    <div class="validation-message" data-field="message_temp_type_1"></div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <select class="form-control message_temp_id_1" name='message_temp_id_1' >
                                                        <option value="">Choose new user message template</option>
                                                        
                                                    </select>
                                                    <div class="validation-message" data-field="message_temp_id_1"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="NewUserMessage" class="col-sm-2 control-label">New User Message</label>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control message_temp_type_2" readonly  name="message_temp_type_2" value="new">
                                                    <div class="validation-message" data-field="message_temp_type_2"></div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <select class="form-control message_temp_id_2" placeholder="Choose message template that will be send to new user" name='message_temp_id_2' >
                                                        <option value="">Choose new user message template</option>
                                                       
                                                    </select>
                                                    <div class="validation-message" data-field="message_temp_id_2"></div>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                <div class="checkbox">
                                                    <label>
                                                    <input type="checkbox" class="send_message" name="send_message" value="0"> Send Message
                                                    <div class="validation-message" data-field="send_message"></div>
                                                    </label>
                                                </div>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="ProcessType" class="col-sm-2 control-label">Send Message</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control send_message" name='send_message' >
                                                        <option value="">Choose Send Message</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                    <div class="validation-message" data-field="send_message"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                <input type="hidden" class="id" name="id" value="">
                                                <input type="hidden" class="user_id" name="user_id" value="<?php echo $user['userId'] ?>">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                        <button type="submit" class="btn btn-success giftingConfigSubmit">Save</button>
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
					<div class="col-md-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-download"></i> Gifting Configuration Queue</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<table id="giftingConfigTable" class="display" cellspacing="0" width="100%">
											<thead id="giftingConfigHeader">
												<tr>
													<th>S\N</th>
													<th>W</th>
													<th>Wallet</th>
													<th>Process Type</th>
													<th>Message Designate</th>
													<th>Send Message</th>
													<th>Config Type</th>
													<th>Action</th>
													<th>Created Date</th>
													<th class="none">Updated Date</th>
													<th class="none">Message Template</th>
													<th class="none">Modified By</th>
												</tr>
											</thead>
											<tbody id="giftingConfigData">
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

	  var tableGiftingConfig = $('#giftingConfigTable')
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

    walletOption()
    function walletOption(){
		var url = BASE_URL + "/offline/walletList"
        $.get(url, function(data) {
			console.log('List Of Wallet');
           let wallets = data.wallets;
            console.log(wallets)
				for(x in wallets){
                    $(".wallet").append('<option value="'+wallets[x].id+'">'+wallets[x].name+'</option>');
                }
        });
     }

     templateManager();
    function templateManager(){
		 var url = BASE_URL + "/messageTemplate/loadAndFitterMessageTemplate"
         console.log('List Of Templates');
         $.get(url, function(data) {
             
             let templates = data.templates;
             for(x in templates){
                console.log(templates[x].action);
                    $(".message_temp_id_1").append('<option value="'+templates[x].id+'">'+templates[x].action+'</option>');
                    $(".message_temp_id_2").append('<option value="'+templates[x].id+'">'+templates[x].action+'</option>');
                }
         });
     }

     giftingConfigsManager();
    function giftingConfigsManager(){
		 var url = BASE_URL + "/UserConfigSetting/giftingConfigs/<?php echo $user['userId'] ?>"
		 $('#giftingConfigHeader').hide();
         $("#giftingConfigData").html('<tr><td align="center" colspan="12"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.giftingConfs);
             giftingConfigTable(data.giftingConfs)
         });
     }		

    //  $('body').on("click",'.send_message', function(evt){
    //     evt.preventDefault();
    
    //     var checker = $(evt.target);
    //     const checkerSib = checker.siblings('input');
    //     checker.attr("checked", "checked")
        
    //     if (checker.is(':checked')) {
    //         console.log('is checked'); checkerSib.removeAttr('checked')
    //     }else {
    //             checkerSib.attr("checked", "checked")
    //     }
        
    //    let isChecked = checker.is(':checked') ? "1" : "0";

    //     $('.send_message').val(isChecked);

    //     checker.toggle('checked')
    // }); 			

	$("body").on('click', '.doGiftingConfig', function(ev) {
		ev.preventDefault();
		$(".giftingConfigForm")[0].reset();
	var $row = $(this).closest("tr");    // Find the row
	//console.log($row);
    $('.id').val($row.find(".gc_id").text());
    $('.wallet').val($row.find(".gc_wallet_id").text());
    $('.process_type').val($row.find(".gc_process_type").text());
    $('.message_designate').val($row.find(".gc_message_designate").text());
    $('.send_message').val($row.find(".gc_send_message").text());
	$('.config_type').val($row.find(".gc_config_type").text());
	$('.message_temp').val($row.find(".gc_message_temp").text());
	// if($row.find(".mt_message_channel").text()== 'Sms'){
	// 	hideFormInputAndReset('.all');
	// 	$('.sms').show();
	// 	$('.message_template_sms').val($row.find(".mt_message_template").text());
	// }else{
	// 	hideFormInputAndReset('.all');
	// 	$('.email').show();
	// 	$('.message_subject').val($row.find(".mt_message_subject").text());
	// 	$(".summertext").summernote("code",$.parseHTML($row.find(".mt_message_template").html()));
	// }
    
    $("html, body").animate({ scrollTop: 0 }, "slow");
     return false;
  });

	function giftingConfigTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#giftingConfigHeader').show();
           var giftingConfigData =''; 
                      for(x in data){
                        giftingConfigData+='<tr>'+
                                '<td class="gc_id">'+data[x].id+'</td>'+
                                '<td class="gc_wallet_id">'+data[x].wallet_id+'</td>'+
                                '<td >'+data[x].name+'</td>'+
                                '<td class="gc_process_type">'+data[x].process_type+'</td>'+
                                '<td class="gc_message_designate">'+data[x].message_designate+'</td>'+
                                '<td class="gc_send_message">'+data[x].send_message+'</td>'+
                                '<td class="gc_config_type">'+data[x].config_type+'</td>'+
                                '<td><a  class="btn btn-primary doGiftingConfig">'+
                                '<i class="fa fa-pencil"></i> '+
                                    ' Edit'+
                                '</a>'+
                                '<td> '+(data[x].created_at ||'00-00-0000')+'</td>'+
                                '<td> '+(data[x].updated_at ||'00-00-0000')+'</td>'+
                                '<td class="gc_message_temp"> '+data[x].message_temp +'</td>'+
                                '<td><br> '+data[x].modified_by +'</td>'+
                            '</tr>';
                      }
                      $("#giftingConfigData").html(giftingConfigData);
                      tableGiftingConfig.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#giftingConfigData").html('<tr><td align="center" colspan="12">NO RECORD FOUND</td></tr>');
				  }
				}
				
				
						


    $('body').on("click",'.giftingConfigSubmit',function(evt){
        evt.preventDefault();
        createGiftingConfig();
    });
	
	

    function createGiftingConfig() {
        $('.giftingConfigSubmit').html("Processing...").attr('disabled', true);
        var obj;
			obj = $('.giftingConfigForm').serialize();
			$('#giftingConfigHeader').hide();
			$('.validation-message').hide();
        var url = BASE_URL + "/UserConfigSetting/createAndUpdateGiftingConfig"
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
						$("#giftingConfigData").html('<tr><td align="center" colspan="12"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');

                        title = "Success";
                        message ="The gifting config was successfully saved";
                        type = success;
                        const info = {title:title, message:message, type:type, position:topfullwidth};
                        toastNotification(info);
                        $(".giftingConfigForm")[0].reset();
						$('.giftingConfigSubmit').html("Save").attr('disabled', false);
						if ($.fn.DataTable.isDataTable("#giftingConfigTable")) {
							$("#giftingConfigTable").DataTable().destroy();
						}
						giftingConfigsManager();
                    } else {
                        $('.giftingConfigSubmit').html("Save").attr('disabled', false);
                        $('.validation-message').html('');
                        $('.validation-message').each(function() {
                            for (var key in data) {
                                if($(this).attr('data-field') == key) {
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
    </script>
