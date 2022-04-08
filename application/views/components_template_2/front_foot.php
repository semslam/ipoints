<style>
.loader {
  border: 10px solid #f3f3f3;
  border-radius: 50%;
  border-top: 10px solid #f96318;
  width: 80px;
  height: 80px;
  -webkit-animation: spin 1s linear infinite; /* Safari */
  animation: spin 1s linear infinite;
} */

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

 @keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
	.validation-message {
			color: #d9534f;
			margin-top: .50rem;
	}

	.blinking{
    	animation:blinkingText 0.8s infinite;
		}
		@keyframes blinkingText{
			0%{     color: #f96318;    }
			49%{    color: #969493; }
			50%{    color: #969493; }
			99%{    color:#969493;  }
			100%{   color: #f96318;    }
		}

</style>
	<!------------------------ aboutuici -------------------------------------------------->
	<div class="modal fade bd-example-modal-lg" id="about" tabindex="-1" role="dialog" aria-labelledby="aboutModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h2 class="modal-title" id="exampleModalLabel">About UICI</h2>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">  		  
			<?php echo $this->config->item('more_about_uici'); ?>			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!------------------------ ipensions -------------------------------------------------->
	<!-- Modal -->
	<div class="modal fade" id="ipensions" tabindex="-1" role="dialog" aria-labelledby="ipensionsModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">iPension</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		  <img class='img-fluid' alt="health cover" src="<?php echo base_url() . 'assets/images/ipension.jpg'; ?>"> <br /> <br />
		  <p>
			This product is also similar to the iSavings and it leverages on our socially smart business model 
			to provide innovative financing options to all socioeconomic classes. <br /><br />
			Here, the iPoints accumulated become a fund over a long period of time and 
			from which payments can be drawn periodically to support a person’s retirement from work.
		  </p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<!------------------------ iSavings -------------------------------------------------->
	<!-- Modal -->
	<div class="modal fade" id="isavings" tabindex="-1" role="dialog" aria-labelledby="isavingsModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">iSavings</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">  
		  <img class='img-fluid' alt="health cover" src="<?php echo base_url() . 'assets/images/isavings.jpg'; ?>"> <br /><br />
		  <p>This is an innovative savings option. The product aims at connecting or linking the 
		  daily purchase of goods and services to the accumulation of 
		  iPoints which can be redeemable as cash once the number of iPoints accumulated gets to a preset reward threshold.</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<!------------------------ iInsurance -------------------------------------------------->
	<div class="modal fade" id="iInsurance" tabindex="-1" role="dialog" aria-labelledby="iInsuranceModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">UICI Life & General Insurance</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">  
		  <img class='img-fluid' alt="health cover" src="<?php echo base_url() . 'assets/images/lg.jpg'; ?>"> <br /><br />
			<p><b>Product Cost:</b> =N=150.00 (i.e. 125 iPoints) <br />
					 
			<p><b>Product Benefits:</b></p>
			<ul>
				<li>Life Cover in the event of death – N300, 000</li>
				<li>Hospital Cash in the event of hospitalization in an NHIS approved hospital for three nights – N50, 000</li>
			</ul>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<!------------------------ T&C -------------------------------------------------->
	<!-- Modal -->
	<div class="modal fade bd-example-modal-lg" id="terms" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Terms & Conditions</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<?php echo $this->config->item('terms'); ?>		
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<!------------------------ Privacy -------------------------------------------------->
	<!-- Modal -->
	<div class="modal fade bd-example-modal-lg" id="privacy" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Privacy Policy</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<?php echo $this->config->item('privacy'); ?>		
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<!------------------------ FAQ -------------------------------------------------->
	<!-- Modal -->
	<div class="modal fade bd-example-modal-lg" id="faq" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Frequently Asked Questions":</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<?php echo $this->config->item('faq'); ?>		
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<!------------------------ View Stats -------------------------------------------------->
	<!-- Modal -->
	<div class="modal fade bd-example-modal-lg" id="ipinvoucher" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-spinner" aria-hidden="true"></i> Load iPin Voucher</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<!-- <?php echo $this->config->item('statistics'); ?>		 -->
			 <!-- login form -->
			 <form id="loadVoucherForm">
						<div class="auth-body">
								<div class="auth-content">
									<div class="form-group">
												<label for="">Phone Number</label>
												<input class="form-control phone_number" placeholder="Load the voucher with your phone number" name="phone_number" type="number">
												<div class="validation-message" data-field="phone_number"></div>
										</div>
										<div class="form-group">
												<label for="">iPin Voucher</label>
												<input class="form-control voucher" title="Enter iPin Voucher" type="text" placeholder="Enter iPin Voucher"  name="voucher" type="text">
												<div class="validation-message" data-field="voucher"></div>
										</div>
										<!-- <div class="form-group">
											<label for=""></label>
											<div class="g-recaptcha" data-sitekey="<?php echo RE_CAPTCHA_SITE_KEY?>"></div>
											<div class="validation-message" data-field="g-recaptcha"></div>
										</div> -->
								</div>
								<div class="auth-footer">
										<button class="btn btn-success" id="loadVoucherRequest" type="button">Load</button>
								</div>
						</div>
					</form>	
					 <!-- login form end -->
					 <div class="content-box voucher_message">
						<!-- voucher message -->
					</div>
		  </div>
		  <!-- <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div> -->
		</div>
	  </div>
	</div>

		<!-- Modal -->
		<div style="margin-top: 72px;" class="modal fade" id="transfer" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-exchange" aria-hidden="true"></i> iPoint Transfer:</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				 <!-- login form -->
					<form id="form-signin">
						<div class="auth-body">
								<div class="auth-content">
										<div class="form-group">
												<label for="">Email Or Phone</label>
												<input class="form-control" placeholder="Transfer with your registered email or phone" name="username" type="text">
												<div class="validation-message" data-field="username"></div>
										</div>
										<div class="form-group">
												<label for="">Password</label>
												<input class="form-control" placeholder="Enter password" name="password" value="" type="password" >
												<div class="validation-message" data-field="password"></div>
										</div>
										<!-- <div class="form-group">
											<label for=""></label>
											<div class="g-recaptcha" data-sitekey="<?php echo RE_CAPTCHA_SITE_KEY?>"></div>
											<div class="validation-message" data-field="g-recaptcha"></div>
										</div> -->
								</div>
								<div class="auth-footer">
										<button class="btn btn-success" id="log-in" type="button">Submit</button>
								</div>
						</div>
					</form>	
					 <!-- login form end -->
					<div class="content-box balance_list">
						<!-- List User Wallet -->
					</div>	
		  </div>
		  <!-- <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div> -->
		</div>
	  </div>
	</div>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script type="text/javascript">
	// login click event
	//$('.form-control').closest('form').find("input[type=text],input[type=password]").val("");

	$('#log-in').on("click", function() {
		login();
	});
	// choose wallet transfer event
	$('body').on("click", '.walletInfo', function(e) {
		const id = $(this).data('id')
		console.log(id);
		$('.balance_list').html('');
		transferProcess(id);
	});
	$('body').on("click",'#transfer_process', transfer);
	$('body').on("click",'#transfer_cancle', getBalanceWallets);
// transfer form process
		$('body').on("click",'#transfer_btn', transfer_validate);

	$('body').on("change",'.wallet_id', function(e) {
			$('.recipient_wallet').html($('.wallet_id').find(':selected').data('walt'));
	});


	 $('body').on("click",'#loadVoucherRequest', function(evt){
        evt.preventDefault();
        $('.validation-message').html('');
		loadVoucherRequest();
		});
		
		function loadVoucherRequest() {
       $('#loadVoucherRequest').html("Loading....").attr('disabled', true);
       var obj = $('#loadVoucherForm').serialize();
       console.log(obj);
       var url = "<?php echo base_url('/transfer/loadIpinVoucher'); ?>";
       swal({   
            title: "Are you sure, You want to load this ipin voucher?",   
            text: "Note!!! Loading this iPin voucher is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Load",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if(data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "iPin voucher was successful loaded.",
                            type: "success"
                        })
                        $('#loadVoucherRequest').html("Load").attr('disabled', false);
												$('#loadVoucherForm')['0'].reset();
												$('#loadVoucherForm').hide();
												voucherComplete(data.message)
                    } else {
                        $('#loadVoucherRequest').html("Load").attr('disabled', false);
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

	// $("#form-signin").keypress(function(event) {
	// 	if (event.which == 13) {
	// 		login();
	// 	}
	// });
// login process
	function login() {
		//$('#log-in').html("Authenticating...").attr('disabled', true);
		var data = $('#form-signin').serialize();
		$('#form-signin').hide();
	//	$('.loader').show();
		loaders();
		console.log(data);
		$.post("<?php echo base_url('transfer/userAuthentication'); ?>", data).done(function(data) {
			//console.log(data);
			if (data.username == "success") {
			$('.loaders').hide();
				$('#form-signin').hide();
				getBalanceWallets();
			} else {
				$('.loaders').hide();
				$('.validation-message').html('');
				$('#form-signin').show();
				//$('#log-in').html("Submit").attr('disabled', false);
				$('.validation-message').each(function() {
					for (var key in data) {
						if ($(this).attr('data-field') == key) {
							$(this).html(data[key]);
						}
					}
				});
			}
		});
	}

	// get the list of user balance wallet
	function getBalanceWallets(){
		$.get("<?php echo base_url('transfer/user_balance_wallets'); ?>", function(data) {
			$('.balance_list').html(data);
		});
	}

//get the transfer form page
	function transferProcess(id){
			$('.loaders').show();
			loaders();
		$.get("<?php echo base_url('transfer/transferForm/'); ?>"+id, function(data) {
			$('.loaders').hide();
			$('.balance_list').html(data);
		});
	}
	//validate transfer input
	function transfer_validate() {
		//$('#transfer_btn').html("Process...").attr('disabled', true);
		var object = $('#form-transfer').serialize();
		$('.transfer_form').hide();
		//loaders();
		//console.log(object);
		$.post("<?php echo base_url('transfer/transferValidate'); ?>", object).done(function(data) {
			console.log('validate: ',data);
			if (data.value == "success") {
				//$('.loaders').hide();
				$('.recipient_contact').html($('.recipient_contact').val())
				$('.transfer_value').html($('.value').val())
				$('.transfer_form').hide();
		 		$('.transfer_detail').show();
						
			} else {
				//$('.loaders').hide();
				$('#transfer_btn').html("Transfer").attr('disabled', false);
				$('.validation-message').html('');
				$('.transfer_form').show();
				$('.validation-message').each(function() {
					for (var key in data) {
						if ($(this).attr('data-field') == key) {
							$(this).html(data[key]);
						}
					}
				});
			}
		});
	}
// transfer
var contact ='';
	function transfer(){	
	
		swal({   
				title: "Are you sure, You want to continue?",   
				text: "Note!!! this Transfer can't be irreversible",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				cancelButtonText: "Cancel",
				confirmButtonText: "Continue",
				closeOnConfirm: true 
			}, function() {
				$('.validation-message').html('');
				 contact = $('.recipient_contact').val();
				var obj = $('#form-transfer').serialize();
				$('.loaders').show();
				loaders();
				//$('#transfer_process').html("Process...").attr('disabled', true);
				console.log('transfer: ',obj);
				$.post("<?php echo base_url('transfer/transferWalletBalance'); ?>", obj).done(function(data) {
					console.log('Transfer: ',data)
					if (data.value == 'success') {
					// successful page here
						swal({   
								title: "Success",
								text: "Your transferred successfully",
								type: "success"
						})
						$('.loaders').hide();
						$('.transfer_detail').hide();
						transferComplete();
						
						$('#form-transfer,#form-signin')[0].reset();
					
					} else {
					
						$('.loaders').hide();
						$('.validation-message').html('');
						$('.transfer_detail').hide();
						transferError();
						$('.validation-message').each(function() {
							for (var key in data) {
								if ($(this).attr('data-field') == key) {
									$(this).html(data[key]);
									console.log(data[key]);
								}
							}
						});
					
					}
					
				});
			});
	}

	function transferComplete(){
		
		$('.balance_list').html('<div style="text-align: center;" class="complete">'+
								'<div class="auth-content">'+
										'<div class="form-group">'+
												'<h2> Transfer Successful</h2>'+
												'<label for="">iPoint transferred to '+contact+' was successful</label>'+
										'</div>'+
										'<div class="form-group">'+
											'<a title="Please login to check your wallet details" href="<?php echo base_url('auth/login'); ?>" class="btn btn-outline-secondary btn-xs" >Login</a>'+
										'</div>'+
								'</div>'+
						'</div>');
	}

	function voucherComplete(message){
		
		$('.voucher_message').html('<div style="text-align: center;" class="complete">'+
								'<div class="auth-content">'+
										'<div class="form-group">'+
												'<h2> Voucher Successful</h2>'+
												'<label for="">'+message+'</label>'+
										'</div>'+
										'<div class="form-group">'+
											'<a title="Please login to check your wallet details" href="<?php echo base_url('auth/login'); ?>" class="btn btn-outline-secondary btn-xs" >Login</a>'+
										'</div>'+
								'</div>'+
						'</div>');
	}

	function transferError(){
		$('.balance_list').html('<div style="text-align: center;" class="complete">'+
								'<div class="auth-content">'+
										'<div class="form-group">'+
												'<h2> Transfer Not Successful</h2>'+
												'<input class="value"  name="value" type="hidden">'+
												'<div class="validation-message" data-field="value"></div>'+
										'</div>'+
										'<div class="form-group">'+
											'<button class="btn btn-info" type="button" id="transfer_cancle">Try Again</button>'+
										'</div>'+
								'</div>'+
						'</div>');
	}

function loaders(){
		$('.balance_list').html('<div style="text-align: center;" class="loaders">'+
								'<div class="auth-content">'+
										'<div class="form-group">'+
											'<div style="display:block; margin: 0 auto" class="loader">'+
											'</div>'+
										'</div>'+
								'</div>'+
						'</div>');
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
    </body>
</html>