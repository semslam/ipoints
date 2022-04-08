
	<style>
        .response, .process{
            display:none;
        }
        .no-record{
            display:none;
        }
        .blink_me {
            color:red;
            animation: blinker 1s linear infinite;
            }

            @keyframes blinker {
            50% {
                opacity: 0;
            }
            }
    </style>
    <div class="content with-top-banner">
		<div class="panel">
			<div class="content-box">
			<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" aria-hidden="true"></i> Subscriber Message</h5>
                                        <p style="color:#1aae88" class="process blink_me">This process is going to take <strong class="process_time"></strong> seconds to complete</p>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form id="subscriberMessageForm">
												<div class="row">
                                                    <div class="form-group col-sm-3">
														<select class="form-control wallet" name="wallet">
															<option value="">Choose Wallet</option>
														</select>
														<div class="validation-message" data-field="wallet"></div>
													</div>
													<div class="form-group col-sm-3">
                                                        <select class="form-control filter logic_option" name="filter">
															<option value="">Choose wallet's filter</option>
															<option value="=">EqualTo (=)</option>
															<option value=">=">GreaterThan And EqualTo (&gt;=)</option>
															<option value="<=">LessThan And EqualTo (&lt;=)</option>
															<option value="!=">NotEqual (!=)</option>
														</select>
														<div class="validation-message" data-field="filter"></div>
													</div>
                                                    <div class="form-group col-sm-3">
														<input title="Enter Point" type="text" placeholder="Enter Point " name="point" class="form-control point" min="1" >
														<div class="validation-message" data-field="point"></div>
													</div>
													<div class="form-group col-sm-12">
														<textarea title="SMS Message" name="message"  placeholder="Enter SMS message" rows="5" cols="100"></textarea>
														<div class="validation-message" data-field="message"></div>
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success"  id="sendMessage">Send</button>
													</div>
											</div>
                                            <div class="row response">
                                                <div class="form-group col-sm-6">
                                                    <h4 style="color:blue">
                                                    <p class="counter">0
                                                    </p>Record(s) has been processed
                                                    </h4>
												</div>
                                            </div>
                                            <div class="row no-record">
                                                <div class="form-group col-sm-3">
                                                    <h4 class="norecord blink_me ">   
                                                    </h4>
												</div>
                                            </div>
										</form>
									</div>
								</div>
						</div>
				</div>
			</div>
		</div>
	</div>
    <script type="text/javascript" >

    wallteOption()
     function wallteOption(){
        var url = BASE_URL + "/offline/walletList"
        $.get(url, function(data) {
            wallets = '';
            for(p in data.wallets){
                wallets +='<option value="'+data.wallets[p].id+'">'+data.wallets[p].name+'</option>';
            }
            $('.wallet').append(wallets)
        });
     }


	$('body').on("click",'#sendMessage', function(evt){
        $('.no-record').hide();
        $('.response').hide();
        evt.preventDefault();
		sendMessage(evt);
	});

	function sendMessage(evt) {
		$('#sendMessage').html("Sending.....").attr('disabled', true);
           var obj = $('#subscriberMessageForm').serialize();

        var url = BASE_URL + "/messageTemplate/notification_message"
       
        swal({   
            title: "Please check the message",   
            text: "Note!!! Sending this message cannot be irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Save",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Message successfully sent",
                            type: "success"
                        })
                    
                        $('.counter').html(data.counter);
                        $('.process').show();
                        $('.process_time').html(data.counter*4);
                        $('.response').show();
                        $('#subscriberMessageForm')[0].reset();
                        $('#sendMessage').html("Successful Sent").attr('disabled', true);
                    } else {
                        if(data.value =='no_record'){
                            $('.no-record').show();
                            $('#sendMessage').html("Send").attr('disabled', false);
                            $('.norecord').html(data.empty); return;
                        }
                        $('#sendMessage').html("Send").attr('disabled', false);
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
 
</script>
