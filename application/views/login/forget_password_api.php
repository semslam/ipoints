<div class="auth-wrapper">
    <form id="form-signin">
        <div class="auth-header">
            <div class="auth-title">UICI</div>
            <!-- <div class="auth-subtitle">Simple and Clean Admin Template</div> -->
            <div class="auth-label">Forgot Password</div>
        </div>
        <div class="auth-body">
            <div class="auth-content">
                <div class="form-group">
                    <label for="">Email Or Phone</label>
                    <input class="form-control" placeholder="Enter email or phone" name="username" type="text">
					<div class="validation-message" data-field="username"></div>
                </div>
            </div>
            <div class="auth-footer">
                <button class="btn btn-success" id="sign-in" type="button">Reset</button>
			<div class="row">
				<div class="col-sm-12 text-center"></div>
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
		$.post("<?php echo base_url('auth/forgot_pass_process');?>", data).done(function(data) {
			if (data.status == "success") {
				window.location.replace("<?php echo base_url('auth/login'); ?>");
			} else {
				$('#sign-in').html("Reset").attr('disabled', false);
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