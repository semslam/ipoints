
<div class="breadcrumb">
	<a href="">Home</a> 
	<a href="">API Key Form</a>
</div>
<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">API Key Form</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<form id="form-action">
						<input type="text" name="id" class="hidden">
						<div class="form-group">
							<label for=""> Agent Name</label>
							<input id="user" class="form-control"  placeholder="Agent Name" type="text">
							<input type="hidden" name="user_id"  id="user_key" />
							<div class="validation-message" data-field="user_id"></div>
						</div>
						<div class="form-group">
							<label for=""> Level</label>
							<select class="form-control" name="level">
								<?php for($add = 0; $add <= 10; $add++){ ?>
									<option value="<?php echo $add ?>"><?php echo $add ?></option>
								<?php }?>	
							</select>
							<div class="validation-message" data-field="level"></div>
						</div>
						<div class="form-group">
							<label for=""> Ip Address</label>
							<input class="form-control" name="ip_address" placeholder="127.0.0.1" type="text">
							<div class="validation-message" data-field="ip_address"></div>
						</div>
						<div class="form-group">
							<label for="">Ignore Limit</label>
							<input class="form-control" name="ignore_limits" placeholder="level 0 or 1000"  min="0" max="1000" pattern="[0-1000]*" type="number">
							<div class="validation-message" data-field="ignore_limits"></div>
						</div>
					</form>
					<div class="content-box-footer">
						<button type="button" class="btn btn-primary action" title="cancel" onclick="form_routes('cancel')">Cancel</button>
						<button type="button" class="btn btn-primary action" title="save" onclick="form_routes('save')">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	
	var onLoad = (function() {

		function filter(data, term) {
      var users =[];
      const flags = "gi";
      const regx = new RegExp(term, flags);
      for (x in data) {
        if (data[x].match(regx)) {
          value = data
          users.push(data[x]); 
        }
      }
      	return users;
    }
	

    $("#user").autocomplete({
		width: 300,
		max: 10,
		minLength: 2,
		autoFocus: true,
		cacheLength: 0,
		scroll: true,
		highlight: false,
    	source:  function (request, response) {
      		const term = request.term || '*'
          $.ajax({
              url: "<?php echo base_url() . 'user/search'; ?>",// domain url
              type:"POST",
              dataType: "json",
              data: { name: $('#user').val() },
				success: function (data) {
					const users = filter(data, term);
					response(users);
				},
				error: function () {
					response([]);
				}
        	});
      	},
    	select: function (event, ui) {
          lable = ui.item.value;
          $.each(value, function(key, value){
				if(lable == value) {
					$("#user_key").val(key);
				}
      		});
    	},
  	});



		var index = "<?php echo $index; ?>";

		var uploader = $('.picker-uploader').uploader({
			upload_url: '<?php echo base_url() . 'uploader/upload'; ?>',
			file_picker_url: '<?php echo base_url() . 'uploader/files'; ?>',
			input_name: 'images',
			maximum_total_files: 4,
			maximum_file_size: 50009000,
			file_types_allowed: ['image/jpeg', 'image/png', 'image/vnd.adobe.photoshop'],
			on_error: function(err) {
				swal({
					title: "Upload Failed",
					text: err.messages,
					type: "warning"
				})
			}
		})
		
		if (index != '') {
			datagrid.formLoad('#form-action', index);
			uploader.set_files(datagrid.getRowData(index).images)
		}

		$('.loading-panel').hide();
		$('.form-panel').show();


    
	})();

	function validate(formData) {
		var returnData;
		$('#form-action').disable([".action"]);
		$("button[title='save']").html("Validating data, please wait...");
		$.ajax({
	        url: "<?php echo base_url() . 'keys/validate'; ?>", async: false, type: 'POST', data: formData,
	        success: function(data, textStatus, jqXHR) {
				returnData = data;
	        }
	    });

		$('#form-action').enable([".action"]);
		$("button[title='save']").html("Save changes");
        if (returnData != 'success') {
        	$('#form-action').enable([".action"]);
			$("button[title='save']").html("Save changes");
            $('.validation-message').html('');
            $('.validation-message').each(function() {
                for (var key in returnData) {
                    if ($(this).attr('data-field') == key) {
                        $(this).html(returnData[key]);
                    }
                }
            });
        } else {
		    return 'success';	
        }
	}

	function save(formData) {
		$("button[title='save']").html("Saving data, please wait...");
		$.post("<?php echo base_url() . 'keys/action'; ?>", formData).done(function(data) {
        	$('.datagrid-panel').fadeIn();
			$('.form-panel').fadeOut();
			datagrid.reload();
        });
	}

	function cancel() {
		$('.datagrid-panel').fadeIn();
		$('.form-panel').fadeOut();
	}

	function form_routes(action) {
		if (action == 'save') {
			var formData = $('#form-action').serialize();
			if (validate(formData) == 'success') {
				swal({   
					title: "Please check your data",   
					text: "Saved data can not be restored",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					cancelButtonText: "Cancel",
					confirmButtonText: "Save",
					closeOnConfirm: true 
				}, function() {
					save(formData);
				});
			}
		} else {
			cancel();
		}
	}

</script>