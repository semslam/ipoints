<style>
.loader {
  border: 10px solid #f3f3f3;
  border-radius: 50%;
  border-top: 10px solid #1e7e34;
  width: 80px;
  height: 80px;
  -webkit-animation: spin 1s linear infinite; /* Safari */
  animation: spin 1s linear infinite;
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
</style>
	<!------------------------ aboutuici -------------------------------------------------->
	<div class="modal fade" id="about" tabindex="-1" role="dialog" aria-labelledby="aboutModalLabel" aria-hidden="true">
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
	<div class="modal fade" id="terms" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
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
	<div class="modal fade" id="privacy" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel" aria-hidden="true">
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
	<div class="modal fade" id="faq" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
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
	<div class="modal fade" id="stats" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Statistics:</h5>
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

		<!-- Modal -->
		<div class="modal fade" id="transfer" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Ipoint Transfer:</h5>
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
												<input class="form-control" placeholder="Enter password" name="password" type="password" >
												<div class="validation-message" data-field="password"></div>
										</div>
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
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<script type="text/javascript">
	// login click event
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
// transfer form process
		$('body').on("click",'#transfer_btn', transfer_validate);

	$('body').on("change",'.wallet_id', function(e) {
			$('.recipient_wallet').html($('.wallet_id').find(':selected').data('walt'));
	});
	$("#form-signin").keypress(function(event) {
		if (event.which == 13) {
			login();
		}
	});
// login process
	function login() {
		$('#log-in').html("Authenticating...").attr('disabled', true);
		//$('.auth-body').html('<div style="display:block; margin: 0 auto" class="loader"></div>');
		var data = $('#form-signin').serialize();
		console.log(data);
		$.post("<?php echo base_url('transfer/userAuthentication'); ?>", data).done(function(data) {
			//console.log(data);
			if (data.username == "success") {
			//	$('.auth-body').html('<div style="display:block; margin: 0 auto" class="loader"></div>').attr('disabled', false);
				$('#form-signin').hide();
				getBalanceWallets();
			} else {
				//$('.auth-body').html('<div style="display:block; margin: 0 auto" class="loader"></div>').attr('disabled', false);
				$('#log-in').html("Login").attr('disabled', false);
				$('.validation-message').html('');
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
		$.get("<?php echo base_url('transfer/transferForm/'); ?>"+id, function(data) {
			$('.balance_list').html(data);
		});
	}
	//validate transfer input
	function transfer_validate() {
		$('#transfer_btn').html("Process...").attr('disabled', true);

		var object = $('#form-transfer').serialize();
		//console.log(object);
		$.post("<?php echo base_url('transfer/transferValidate'); ?>", object).done(function(data) {
			//console.log(data);
			if (data.value == "success") {
			//	$('#form-transfer').hide();
			$('.transfer_value').html($('.value').val())
			// ()? transfer(object):getBalanceWallets();
			// 	if(){
					transfer(object);
			// 	}else{
			// 		getBalanceWallets();
			// 	}
						
			} else {
				$('#transfer_btn').html("Login").attr('disabled', false);
				$('.validation-message').html('');
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
	function transfer(object){
		if (click == 1) {
			alert('Button 1 was clicked process');
			console.log(object);
		}else {
			alert('Button 2 was clicked cancle');
		}
		// $.post("<?php echo base_url('transfer/transferWalletBalance'); ?>", object).done(function(data) {
		// 	//console.log(data);
		
		// 	if (data.value == "success") {
		// 		$('.transfer_form').hide();
		// 	$('.transfer_detail').show();
		// 		//getBalanceWallets();
		// 	} else {
		// 		$('#transfer').html("Login").attr('disabled', false);
		// 		$('.validation-message').html('');
		// 		$('.validation-message').each(function() {
		// 			for (var key in data) {
		// 				if ($(this).attr('data-field') == key) {
		// 					$(this).html(data[key]);
		// 				}
		// 			}
		// 		});
		// 	}
		// });
	}

	// 	function TransferProcess() {
	// 		$('#transfer').html("Processing...").attr('disabled', true);
	// 		var data = $('#form-transfer').serialize();
	// 		console.log(data);
	// 		$.post("<?php echo base_url('transfer/userAuthentication'); ?>", data).done(function(data) {
	// 			//console.log(data);
	// 			if (data.username == "success") {
	// 				$('#form-signin').hide();
	// 				getBalanceWallets();
	// 			} else {
	// 				$('#sign-in').html("Login").attr('disabled', false);
	// 				$('.validation-message').html('');
	// 				$('.validation-message').each(function() {
	// 					for (var key in data) {
	// 						if ($(this).attr('data-field') == key) {
	// 							$(this).html(data[key]);
	// 						}
	// 					}
	// 				});
	// 			}
	// 		});
	// }


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