<div class="auth-wrapper">
    <form id="form-signin">
        <div class="auth-header">
            <div class="auth-title">UICI</div>
            <!-- <div class="auth-subtitle">Simple and Clean Admin Template</div> -->
            <div class="auth-label">Regenerate OTP</div>
        </div>
        <div class="auth-body">
            <div class="auth-content">
                <div class="validation-message" data-field="usernamepassword"></div>
                <div class="form-group">
                    <label for="">Email Or Phone</label>
                    <input class="form-control" placeholder="Enter your registered email or phone" name="username" type="text">
                    <div class="validation-message" data-field="username"></div>
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input class="form-control" placeholder="Enter password" name="password" type="password" >
                    <div class="validation-message" data-field="password"></div>
                </div>
                <!-- <div class="form-group">
                    <label for=""></label>
					<div class="g-recaptcha" data-sitekey="<?php echo RE_CAPTCHA_SITE_KEY?>"></div>
					<div class="validation-message" data-field="g-recaptcha"></div>
                </div> -->
            </div>
            <div class="auth-footer">
                <button class="btn btn-success" id="regenotp-in" type="button">Generate</button>
                <!-- <div class="pull-right auth-link">
                    <label class="check-label"><input type="checkbox" name="keep_login" value="true"> Remember Me</label>
                    <div class="devider"></div>
                    <a href="<?php echo base_url('auth/forgot_password'); ?>">Forgot Password?</a>
                </div> -->
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
	$('#regenotp-in').on("click", function() {
		login();
	});
	$("#form-signin").keypress(function(event) {
		if (event.which == 13) {
			login();
		}
	});

	function login() {
		$('#regenotp-in').html("Authenticating...").attr('disabled', true);
		var data = $('#form-signin').serialize();
		$.post("<?php echo base_url('auth/otpregen') ; ?>", data).done(function(data) {
			if (data.status == "success") {
				window.location.replace("<?php echo base_url('auth/activate_form'); ?>");
			} else {
				$('#regenotp-in').html("Generate").attr('disabled', false);
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