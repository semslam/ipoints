<style>
.loader {
  border: 10px solid #f3f3f3;
  border-radius: 50%;
  border-top: 10px solid #f96318;
  width: 80px;
  height: 80px;
  -webkit-animation: spin 1s linear infinite; /* Safari */
  animation: spin 1s linear infinite;
} 

.addQuestion{
    background-color: #F7F8F4;
    width: 100%;
    color: #333333;
    height: 50px auto;
    margin: 0 auto;
    overflow: hidden;
    padding: 10px 0;
    align-items: center;
    justify-content: space-around;
    display: flex;
		float: none;
		border: 1px solid #500;
}

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
	<div class="content with-top-banner">
		<div class="panel">
			<div class="content-box">
			<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
                      <p>You can transfer ipoints from your wallet to reward loyal customers, staff, family and friends..</p>
										<div class="ibox-tools">
										</div>
									</div>
								</div>
						</div>
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="addQuestion">
			<div class="text-center row ">
						<div class="col-md-12 ">
						<!-- //col-md-offset-4 -->
							<div class="ibox-content balance_list">
								<form id="ipointTransfer">
									
										<!-- <div class="form-group col-md-6 ">
											<button class="btn btn-success"  id="sendTransfer">TRANSFER</button>
										</div> -->
									
								</form>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" >
	

	if('<?php echo $this->session->userdata('active_user')->group_name ?>' == '<?php echo MERCHANT?>'){
		transferProcess(<?php echo $ipoint_id ?>)
	}else{
		getBalanceWallets();
	}
	// choose wallet transfer event
	$('body').on("click", '.walletInfo', function(e) {
		const id = $(this).data('id')
		//console.log(id);
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
				text: "Note!!! this Transfer is irreversible",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#007bff",
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
								text: "Your transfer was successful",
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

	function confirmPassword(){
		$('.balance_list').html('<div style="text-align: center;" class="complete">'+
								'<div class="auth-content">'+
										'<div class="form-group">'+
											'<input title="Enter Point" type="text" placeholder="Enter User Password" name="password" class="form-control password" min="8" >'+
											'<div class="validation-message" data-field="password"></div>'+
										'</div>'+
										'<div class="form-group">'+
											'<button class="btn btn-dangar cancel_password" type="button" data-dismiss="modal">Cancel</button>'+
										'</div>'+
										'<div class="form-group">'+
											'<button class="btn btn-success process_password" type="button" data-dismiss="modal">Confirm</button>'+
										'</div>'+
								'</div>'+
						'</div>');
	}

	function transferComplete(){
		
		$('.balance_list').html('<div style="text-align: center;" class="complete">'+
								'<div class="auth-content">'+
										'<div class="form-group">'+
												'<h2> Transfer Successful</h2>'+
												'<label for="">point transferred to '+contact+' successfully</label>'+
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
