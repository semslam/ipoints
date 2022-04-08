<div class="auth-wrapper">
    <form id="form-signin">
        <div class="auth-header">
            <div class="auth-title">UICI</div>
            <!--<div class="auth-subtitle">Simple and Clean Admin Template</div>-->
            <div class="auth-label">Login</div>
        </div>
        <div class="auth-body">
            <div class="auth-content">
                <div class="form-group">
                    <label for="">Email Or Phone</label>
                    <input class="form-control" placeholder="Login with your registered email or phone" name="username" type="text">
                    <div class="validation-message" data-field="username"></div>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input class="form-control" placeholder="Enter password" name="password" type="password" >
                    <div class="validation-message" data-field="password"></div>
                    <input type="hidden" name="" value="<?php echo $token ?>">
                </div>
            </div>
            <div class="auth-footer">
                <button class="btn btn-success" id="sign-in" type="button">Submit</button>
                <div class="pull-right auth-link">
                    <label class="check-label"><a href="<?php echo base_url() . 'auth/forgot_password'; ?>">Forgot Password?</a>
                    <div class="devider"></div>
                    
                </div>
            </div>
        </div>
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
		$.post("<?php echo base_url() . '/api/v1/oAuthProcess/tokens'; ?>", data).done(function(data) {
			if (data.status == "success") {
				window.location.replace("<?php echo base_url('/api/v1/oAuthProcess/tokens'); ?>");
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