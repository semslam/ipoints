<div class="breadcrumb">
	<a href="">Home</a> 
	<a href="">Change Password</a>
</div>
<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Change Password</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<form id="form-action">
						<div class="form-group">
							<label for=""> Old Password</label>
							<input class="form-control old_password" name="old_password" placeholder="Old Password" type="password">
							<div class="validation-message" data-field="old_password"></div>
						</div>
						<div class="form-group">
							<label for=""> New Password</label>
							<input class="form-control new_password" name="new_password" placeholder="New Password" type="password">
							<div class="validation-message" data-field="new_password"></div>
						</div>
						<div class="form-group">
							<label for=""> Confirm Password</label>
							<input class="form-control conf_password" name="conf_password" placeholder="Confirm Password" type="password">
							<div class="validation-message" data-field="conf_password"></div>
						</div>
						<div class="content-box-footer">
							<button type="button" class="btn btn-primary action" title="save" onclick="save()">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	var onLoad = (function() {
		$('.action').click(function(){
			save();
		})
	})

	// function validate(formData) {
	// 	var returnData;
	// 	$('#form-action').disable([".action"]);
	// 	$("button[title='save']").html("Validating data, please wait...");
	// 	$.ajax({
	// 		url: "<?php echo base_url() . 'change_password/validate'; ?>", async: false, type: 'POST', data: formData,
	// 		success: function(data, textStatus, jqXHR) {
	// 			returnData = data;
	// 		}
	// 	});

	// 	$('#form-action').enable([".action"]);
	// 	$("button[title='save']").html("Save changes");
	// 	if (returnData != 'success') {
	// 		$('#form-action').enable([".action"]);
	// 		$("button[title='save']").html("Save changes");
	// 		$('.validation-message').html('');
	// 		$('.validation-message').each(function() {
	// 			for (var key in returnData) {
	// 				if ($(this).attr('data-field') == key) {
	// 					$(this).html(returnData[key]);
	// 				}
	// 			}
	// 		});
	// 	} else {
	// 		return 'success';	
	// 	}
	// }

	function save() {
		var formData = $('#form-action').serialize();
			swal({   
				title: "Are you sure of changing your password?",   
				text: "Note!!! This change is irreversible",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				cancelButtonText: "Cancel",
				confirmButtonText: "Continue",
				closeOnConfirm: true 
			}, function() {
				$('.validation-message').html('');
				$("button[title='save']").html("Saving data, please wait...");
				$.post("<?php echo base_url() . 'Change_Password/save'; ?>", formData).done(function(data) {
					console.log(data.status)
					if (data.status != 'success') {
						$('#form-action').enable([".action"]);
						$("button[title='save']").html("Save");
						$('.validation-message').html('');
						$('.validation-message').each(function() {
							for (var key in data) {
								if ($(this).attr('data-field') == key) {
									$(this).html(data[key]);
								}
							}
						});
					} else {

						swal({   
						title: "Success",
						text: "Your password successfully changed",
						type: "success"
					})

					$('#form-action').enable([".action"]);
					$("button[title='save']").html("Save");
					$('#form-action')[0].reset();
					}
					
				});
			});
	}

</script>