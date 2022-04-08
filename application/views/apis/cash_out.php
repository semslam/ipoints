<style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 450px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.price {
  color: grey;
  font-size: 22px;
}

.card a {
  border: none;
  outline: 0;
  padding: 12px;
  color: white;
  background-color: #28a745;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

.card a:hover {
  opacity: 0.7;
}
</style>
<div class="auth-wrapper">
    <form id="form-signin">
        <div class="auth-header">
            <div class="auth-title"></div>
            <!--<div class="auth-subtitle">Simple and Clean Admin Template</div>-->
            <div class="auth-label">Login To Make Cash-Out Request</div>
        </div>
        <div class="auth-body">
        <div class ="login_container">
        <strong style=" text-align: center;">&nbsp; &nbsp; Note!!! the minimum threshold for iSavings cash-out is &#x20A6; <?php echo $threshold ?></strong>
            <div class="auth-content">
                <div class="form-group">
                    <label for="">Bank Name</label>
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
                <div class="form-group">
                    <label for="">Account Number</label>
                    <input class="form-control" placeholder="Enter Account Number" name="account_number"  type="number">
                    <div class="validation-message" data-field="account_number"></div>
                </div>
                <div class="form-group">
                    <label for="">Amount</label>
                    <input title="Enter Amount" type="number" placeholder="Enter Amount" name="amount" min="1"
						class="form-control amount">
					<div class="validation-message" data-field="amount"></div>
                </div>
                <div class="form-group">
                    <input class="form-control" name="token_secret" value="<?php echo $token_secret; ?>" type="hidden">
                    <div class="validation-message" data-field="token_secret"></div>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input class="form-control" placeholder="Enter password" name="password" type="password" >
                    <div class="validation-message" data-field="password"></div>
                    <!-- <input type="hidden" name="" value="<?php echo $token ?>"> -->
                </div>
                <div class="form-group">
                    <label for=""></label>
					<div class="g-recaptcha" data-sitekey="<?php echo RE_CAPTCHA_SITE_KEY?>"></div>
					<div class="validation-message" data-field="g-recaptcha"></div>
                </div>
            </div>
            <div class="auth-footer">
                <button class="btn btn-success" id="sign-in" type="button">Submit</button>
                <div class="pull-right auth-link">
                    <label class="check-label"><a href="<?php echo base_url() . 'auth/forgot_password'; ?>" target="_blank">Forgot Password?</a>
                    <div class="devider"></div>
                    
                </div>
            </div>
        </div>
        <div class="result">
        </div>
       <div> 
    </form>
</div>
<script type="text/javascript">
	$('#sign-in').on("click", function() {
		login();
	});
	$("#form-signin").keypress(function(event) {
		if (event.which == 13) {
			login();
		}
	});

	function login() {
		$('#sign-in').html("Authenticating...").attr('disabled', true);
		var data = $('#form-signin').serialize();
		$.post("<?php echo base_url() . '/OpenUrlAuth/makeiSavingsCashOutRequest'; ?>", data).done(function(data) {
			if (data.status == "success") {
                $(".login_container").hide();
                $(".result").html(data.cash_out)
			} else {
				$('#sign-in').html("Login").attr('disabled', false);
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
</script>