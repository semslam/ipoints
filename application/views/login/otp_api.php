<div class="auth-wrapper">
    <form id="form-signin">
        <div class="auth-header">
            <div class="auth-title">UICI</div>
            <!-- <div class="auth-subtitle">Simple and Clean Admin Template</div> -->
            <div class="auth-label">OTP</div>
        </div>
        <div class="auth-body">
            <div class="auth-content">
                <div class="form-group">
                    <label for="">OTP</label>
                    <input class="form-control otp" placeholder="Enter your OTP" name="otp" type="text" >
					<div class="validation-message" data-field="otp"></div>
                </div>
            </div>
            <div class="auth-footer">
                <button class="btn btn-success" id="opt-conf" type="button">Confirm</button>
                <div class="pull-right auth-link">
                    <div class="devider"></div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
	$('#opt-conf').on("click", function() {
		login();
	});
	$("#form-signin").keypress(function(event) {
		if (event.which == 13) {
			login();
		}
	});

	function login() {
		$('#opt-conf').html("Authenticating...").attr('disabled', true);
		var data = $('#form-signin').serialize();
		$.post("<?php echo base_url('auth/activate'); ?>", data).done(function(data) {
			if (data.status == "success") {
				window.location.replace("<?php echo base_url(); ?>");
			} else {
				$('#opt-conf').html("Confirm").attr('disabled', false);
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