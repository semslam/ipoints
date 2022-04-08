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
										<h5><i class="fa fa-bars" ></i> Create Message Template</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form class="messageTemplateForm">
												<div class="row">
													<div class="form-group col-sm-4">
														<label>Is Charge </lable>
                                                        <select title="Choose if is paid or free" class="form-control charge" placeholder="Choose if is paid or free" name="charge">
															<option value="">Choose charges ?</option>
															<option value="free">Free</option>
															<option value="paid">Paid</option>
														</select>
														<div class="validation-message" data-field="charge"></div>
													</div>
                                                    <div class='form-group col-sm-4'>
														<label> Number Of Attempt </lable>
														<select title="Choose The Number Of Attempt Message Process Will Fail" class="form-control attempt_no" placeholder="Choose Number Of Attempt To Be Process If Fail" name="attempt_no">
															<option value="">Choose Attempt Number ?</option>
															<option value="1">1</option>
															<option value="2">2</option>
															<option value="3">3</option>
															<option value="4">4</option>
															<option value="5">5</option>
															<option value="6">6</option>
															<option value="7">7</option>
															<option value="8">8</option>
															<option value="9">9</option>
														</select>
														<div class="validation-message" data-field="attempt_no"></div> 
													</div>
                                                    <div class='form-group col-sm-4'>
														<label> Priority </lable>
														<select title="Choose Message Priority" class="form-control priority" placeholder="Choose Message Priority" name="priority">
															<option value="">Choose Priority</option>
															<option value="1">5 Second</option>
															<option value="2">10 Seconds</option>
															<option value="3">30 Seconds</option>
															<option value="4">50 Seconds</option>
															<option value="5">1 Minute</option>
															<option value="6">15 Minutes</option>
															<option value="7">30 Minutes</option>
															<option value="8">1 Hour</option>
															<option value="9">2 Hours</option>
														</select>
														<div class="validation-message" data-field="priority"></div>
													</div>
													<div class='form-group col-sm-4'>
														<label>Action</lable>
														<select class="form-control action" name="action">
															<option value="">Choose Action</option>
															<?php
																foreach($actions as $action){
																echo '<option value="'.$action.'">'.$action.'</option>'; 
																} 
															?>
															
														</select>
														<div class="validation-message" data-field="action"></div>
													</div>
													<div class='form-group col-sm-4'>
														<label>Message Channel</lable>
														<select title="Choose Message Channel" class="form-control message_channel" placeholder="Choose Message Channel" name="message_channel">
															<option value="">Choose Message Channel?</option>
															<option value="Sms">Sms</option>
															<option value="Email">Email</option>
														</select>
														<div class="validation-message" data-field="message_channel"></div> 
													</div>
                                                    <div class="form-group all email col-sm-4">
														<label>Message Subject</lable>
														<input title="Enter Message Subject" type="text" placeholder="Enter Message Subject" name="message_subject"  class="form-control message_subject">
														<div class="validation-message" data-field="message_subject"></div>
													</div>
													<div class="form-group all sms col-sm-12">
														<label>SMS Template </lable>
														<textarea title="Enter message template in text" name="message_template_sms"  placeholder="Enter message template in text" class="form-control message_template_sms" rows="6" cols="100"></textarea>
														<div class="validation-message" data-field="message_template_sms"></div>
													</div>
                                                    <div class="form-group all email col-sm-12">
                                                        <label>Email Template </lable>
                                                        <textarea class="form-control summertext message_template_email" placeholder="Enter message template in html format" title="Enter message template in html format"  name="message_template_email"  rows="10" cols="100"></textarea>   
                                                        <div class="validation-message" data-field="message_template_email"></div>
                                                    </div>
													<div class="form-group col-sm-3">
														<br>
														<input type="hidden" class="id" name="id" value="">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success messageTemplateSubmit">Submit</button>
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
										<h5><i class="fa fa-download"></i> Message Template Queue</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<table id="messageTemplateTable" class="display" cellspacing="0" width="100%">
											<thead id="messageTemplateHeader">
												<tr>
													<th>S\N</th>
													<th>Action</th>
													<th>Channel</th>
													<th>Charge</th>
													<th>Attempt Number</th>
													<th>Priority</th>
													<th>Created Date</th>
													<th>Updated Date</th>
													<th>Action</th>
													<th class="none">Subject</th>
													<th class="none">Template</th>
													<!-- <th class="none">Updated By</th> -->
												</tr>
											</thead>
											<tbody id="messageTemplateData">
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

	  var tableMessageTemplate = $('#messageTemplateTable')
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
	$("body").on('click', '.doTemplateMessage', function(ev) {
		ev.preventDefault();
		$(".messageTemplateForm")[0].reset();
	var $row = $(this).closest("tr");    // Find the row
	//console.log($row);
    $('.id').val($row.find(".mt_id").text());
    $('.action').val($row.find(".mt_action").text());
    $('.charge').val($row.find(".mt_charge").text());
    $('.message_channel').val($row.find(".mt_message_channel").text());
    $('.attempt_no').val($row.find(".mt_attempt_no").text());
	$('.priority').val($row.find(".mt_priority").text());
	if($row.find(".mt_message_channel").text()== 'Sms'){
		hideFormInputAndReset('.all');
		$('.sms').show();
		$('.message_template_sms').val($row.find(".mt_message_template").text());
	}else{
		hideFormInputAndReset('.all');
		$('.email').show();
		$('.message_subject').val($row.find(".mt_message_subject").text());
		$(".summertext").summernote("code",$.parseHTML($row.find(".mt_message_template").html()));
	}
    
    $("html, body").animate({ scrollTop: 0 }, "slow");
     return false;
  });

	function messageTemplateTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#messageTemplateHeader').show();
           var messageTemplateData =''; 
                      for(x in data){
                        messageTemplateData+='<tr>'+
                                '<td class="mt_id">'+data[x].id+'</td>'+
                                '<td class="mt_action">'+data[x].action+'</td>'+
                                '<td class="mt_message_channel">'+data[x].message_channel+'</td>'+
                                '<td class="mt_charge">'+data[x].charge+'</td>'+
                                '<td class="mt_attempt_no">'+data[x].attempt_no+'</td>'+
                                '<td class="mt_priority">'+data[x].priority+'</td>'+
                                '<td> '+(data[x].created ||'00-00-0000')+'</td>'+
                                '<td> '+(data[x].updated ||'00-00-0000')+'</td>'+
                                '<td><a  class="btn btn-primary doTemplateMessage">'+
                                '<i class="fa fa-pencil"></i> '+
                                    ' Edit'+
                                '</a>'+
                                '<td class="mt_message_subject"> '+data[x].message_subject +'</td>'+
                                '<td class="mt_message_template"><br> '+data[x].message_template +'</td>'+
                            '</tr>';
                      }
                      $("#messageTemplateData").html(messageTemplateData);
                      tableMessageTemplate.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#messageTemplateData").html('<tr><td align="center" colspan="10">NO RECORD FOUND</td></tr>');
				  }
				}
				
				
				messageTemplateManager();
    function messageTemplateManager(){
		 var url = BASE_URL + "/messageTemplate/loadAndFitterMessageTemplate"
		 $('#messageTemplateHeader').hide();
         $("#messageTemplateData").html('<tr><td align="center" colspan="10"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.templates);
             messageTemplateTable(data.templates)
         });
     }				

var $placeholder = $('.placeholder');
$(".summertext").summernote({
  height: 300,
  codemirror: {
      mode: 'text/html',
      htmlMode: true,
      lineNumbers: true,
      theme: 'monokai'
  },
  callbacks: {
      onInit: function() {
          $placeholder.show();
      },
      onFocus: function() {
          $placeholder.hide();
      },
      onBlur: function() {
          var $self = $(this);
          setTimeout(function() {
              if ($self.summernote('isEmpty') && !$self.summernote('codeview.isActivated')) {
                  $placeholder.show();
              }
          }, 300);
      }
  }
});


    $('body').on("click",'.messageTemplateSubmit',function(evt){
        evt.preventDefault();
        createMessageTemplate();
    });

    $('.message_channel').change(function() {
       const type = $('.message_channel').val()
	   console.log(type);
        if(type == 'Sms') { 
           hideFormInputAndReset('.all');
           $('.sms').show();
           
        }else if(type == 'Email'){
           hideFormInputAndReset('.all');
           		
           $('.email').show();		
        }else{
            hideFormInputAndReset('.all');	
        }
   });

   function hideFormInputAndReset(mclass) {
	    $(mclass).hide().find('.message_template_sms,.message_template_email').val('');
        $(".summertext").summernote("code", '');
	}
	
	

    function createMessageTemplate() {
        $('.messageTemplateSubmit').html("Processing...").attr('disabled', true);
        var obj;
			obj = $('.messageTemplateForm').serialize();
			$('#messageTemplateHeader').hide();
        var url = BASE_URL + "/messageTemplate/createAndUpdate"
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
						$("#messageTemplateData").html('<tr><td align="center" colspan="10"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');

                        title = "Configuration";
                        message ="The message was successfully saved";
                        type = success;
                        const info = {title:title, message:message, type:type, position:topfullwidth};
                        toastNotification(info);
                        $(".messageTemplateForm")[0].reset();
                        $(".summertext").summernote("code", '');
						$('.messageTemplateSubmit').html("Submit").attr('disabled', false);
						if ($.fn.DataTable.isDataTable("#messageTemplateTable")) {
							$("#messageTemplateTable").DataTable().destroy();
						}
						messageTemplateManager();
                    } else {
                        $('.messageTemplateSubmit').html("Submit").attr('disabled', false);
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
