
<div class="breadcrumb">
	<a href="">Home</a> 
	<a href="">Profile</a>
</div>
<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Profile</div>
		</div>
		<div class="row">
			<div class="col-md-6">
			
				<div class="content-box">
					<form id="form-action">
						<input type="text" name="id" class="hidden" value="<?php echo $active_user->id; ?>">
						<div class="form-group subscriber all" id='name'>
							<label>Full Name</label>
							<input name='name' class="name form-control" placeholder="Full Name" value="<?= $active_user->name; ?>" type="text">
							<div class="validation-message" data-field="name"></div>
						</div>
						<div class="form-group subscriber all " id='gender'>
							<label>Gender</label>
							<!--Please do not change option value-->
							<select class="form-control gender" name='gender'>
							<?php 
								if(empty($active_user->gender)){ echo '<option value="">Choose Gender</option>' ;
								}elseif($active_user->gender == 'male'){
									echo '<option value="'. $active_user->gender.'"> Male</option>';
								}else{
									echo '<option value="'. $active_user->gender.'"> Female</option>';
								}
							?>
							<option value="male">Male</option>
							<option value="female">Female</option>
							</select>
							<div class="validation-message" data-field="gender"></div>
						</div>
						<div class="form-group subscriber all" id='mobile_number'>
							<label>Mobile Phone</label>
							<input name='text' class="form-control" readonly  type="text" value="<?php echo $active_user->mobile_number; ?>">	
						</div>
						<div class="form-group subscriber all" id='email'>
							<label>Email</label>
							<input name='email' class="form-control email" placeholder="Email Address" type="text" value="<?php echo (empty($active_user->email))? '':$active_user->email; ?>">
							<div class="validation-message" data-field="email"></div>
						</div>
						<div class="form-group merchant under_partner all" id='rc_number'>
							<label>RC Number</label>
							<input name='rc_number' class="rc_number form-control" placeholder="RC Number" value='<?php echo $active_user->rc_number; ?>' type="text">
							<div class="validation-message" data-field="rc_number"></div>
						</div>
						<div class="form-group merchant all" id='biz_name'>
							<label>Business Name</label>
							<input name='biz_name' class="biz_name form-control" placeholder="Enter Business Name" value="<?php echo $active_user->business_name; ?>" type="text">
							<div class="validation-message" data-field="biz_name"></div>
						</div>
						<div class="form-group merchant all" id='industry'>
							<label>Industry</label>
							<input class="form-control indust" placeholder="Start typing to select" value="<?php echo (empty($active_user->industry_name))? '':$active_user->industry_name;?>" type="text">
							<input type="hidden" name="industries" value="<?php echo (empty($active_user->industry))?'':$active_user->industry;?>"  id="industries" />
							<div class="validation-message" data-field="industries"></div>
						</div>
						<div class="form-group subscriber all" id='dob'>
							<label>Date of Birth</label>
							<input name='dob' class="single-daterange form-control dob" placeholder="Date of birth" type="text" value="<?php echo $active_user->birth_date; ?>">
							<div class="validation-message" data-field="dob"></div>
						</div>
						
						<div class="form-group subscriber all" id='state'>
							<label>State</label>
							<!--Please do not change option value-->
							<select class="form-control states" name='states' >
									<?php 
										if(empty($active_user->state_name)){ 
											echo '<option value="">Choose State</option>' ;
										}else{
										echo '<option value="'. $active_user->states.'">'. $active_user->state_name.'</option>';
										}
									?>
								
							</select>
							<div class="validation-message" data-field="states"></div>
						</div>
						<div class="form-group subscriber all" id='lga'>
							<label>Local Government of Residence</label>
							<!--Please do not change option value-->
							<select class="form-control lga" name='lga' >
								<?php 
									if(empty($active_user->local_govts_name)){ echo '' ;
									}else{
									echo '<option value="'. $active_user->lga.'">'. $active_user->local_govts_name.'</option>';
									}
								?>
							</select>
							<div class="validation-message" data-field="lga"></div>
						</div>
						<div class="form-group subscriber all" id='nok_name'>
							<label>Name of Next of Kin</label>
							<input name='nok_name' class="nok_name form-control" placeholder="Enter Next of Kin" value="<?php echo $active_user->next_of_kin; ?>" type="text">
							<div class="validation-message" data-field="nok_name"></div>
						</div>
						<div class="form-group subscriber all" id='nok_phone'>
							<label>Next of Kin Mobile Number</label>
							<input name='nok_phone' class="nok_phone form-control" placeholder="Next of Kin Mobile Number" value="<?php echo $active_user->next_of_kin_phone; ?>" type="text">
							<div class="validation-message" data-field="nok_phone"></div>
						</div>
						<div class="form-group merchant under_partner all" id='biz_address'>
							<label>Business Address</label>
							<input name='biz_address' class="biz_address form-control" placeholder="Enter business Address" value="<?php echo $active_user->address; ?>" type="text">
							<div class="validation-message" data-field="biz_address"></div>
						</div>
						<div class="form-group merchant all" id='referrer'>
							<label>Referrer</label>
							<input name='referrer' class="referrer form-control" placeholder="Enter Referrer Phone Number" value="<?php echo $active_user->referrer; ?>" type="text">
							<div class="validation-message" data-field="referrer"></div>
						</div>
					</form>
					<div class="content-box-footer">
						<button type="button" class="btn btn-primary action" title="save" onclick="save()">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		group();
	function group() {
 
		const account_type = '<?php echo $active_user->group_id; ?>'
		if(account_type == '4') { 
			hideFormInputAndReset('.form-group.all');
			$('.form-group.subscriber,.form-group.common').show();
			
		} 
		else if(account_type == '3'){
			hideFormInputAndReset('.form-group.all');
			$('.form-group.merchant, .form-group.common').show();
		}
		else if(account_type == '5'){
			hideFormInputAndReset('.form-group.all');
			$('.form-group.under_partner,.form-group.common').show();
		}
		else if(account_type == '6'){
			hideFormInputAndReset('.form-group.all');
			$('.form-group.under_partner,.form-group.common').show();
		}
		else {
			hideFormInputAndReset('.form-group.all');		
		}
	};

	function hideFormInputAndReset(mclass) {
		$(mclass).hide();
	}

	function filter(data, term) {
      var objs =[];
      const flags = "gi";
      const regx = new RegExp(term, flags);
      for (x in data) {
        if (data[x].match(regx)) {
          value = data
          objs.push(data[x]); 
        }
      }
      	return objs;
    }
	  
	$( ".states" )
            .change(function () {
			 var states = {state_id:$(this).val()};
			 console.log(states)
             post('<?php echo base_url() .'auth/local_govts'; ?>',{state_id:$(this).val()},function(data) {
				const lgaElem = $(".lga");
				const lgaVal = lgaElem.val();
				lgaElem.html('<option> Choose LGA... </option>')
				let selected = false;
				console.log(lgaElem, lgaVal)
				for (x in data) {
					selected = lgaVal == data[x].id ? 'selected' : '';
					lgaElem.append('<option value="'+data[x].id+'" '+selected+'>'
						+data[x].name+
					'</option>');
				}

            });
        }).change();

	getState();
	function getState() {          
		get('<?php echo base_url() .'auth/state'; ?>',function(data) {
			console.log(data)
		for (x in data) { 
			console.log(data[x].state_name);
			$(".states").append('<option value="'+data[x].state_id+'">'+data[x].state_name+'</option>');
		}
		});         
	}

	function post( url,obj,callback){
    $.ajax({
      url: url,// domain url
      type:"POST",
      dataType: "json",
      data:obj,
      success: function (data) {
          callback(data);
      },
      error: function () {
        //alert("Error")
        callback([]);
      }
    });
    }

	function get( url,callback){
    $.ajax({
      url: url,// domain url
      type:"GET",
      dataType: "json",
      success: function (data) {
          callback(data);
      },
      error: function () {
        //alert("Error")
        callback([]);
      }
    });
    }
	  
	  $(".indust").autocomplete({
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
              url: "<?php echo base_url() . 'auth/industry'; ?>",// domain url
              type:"POST",
              dataType: "json",
              data: { name: $('.indust').val() },
				success: function (data) {
					console.log(data);
					const objs = filter(data, term);
					response(objs);
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
					console.log(key);
					$("#industries").val(key);
				}
      		});
    	},
	  });
	

	});
	function validate(formData) {
		var returnData;
		$('#form-action').disable([".action"]);
		$("button[title='save']").html("Validating data, please wait...");
		$.ajax({
			url: "<?php echo base_url() . 'profile/validate'; ?>", async: false, type: 'POST', data: formData,
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

	function save() {
		var formData = $('#form-action').serialize();
		if (validate(formData) == 'success') {
			swal({   
				title: "Please check your update",   
				text: "Note!!! This update is irreversible",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				cancelButtonText: "Cancel",
				confirmButtonText: "Continue",
				closeOnConfirm: true 
			}, function() {
				$('.validation-message').html('');
				$("button[title='save']").html("Saving Info, please wait...");
				$.post("<?php echo base_url() . 'profile/save'; ?>", formData).done(function(data) {
					swal({   
						title: "Success",
						text: "Your profile successfully updated",
						type: "success"
					})

					$('#form-action').enable([".action"]);
					$("button[title='save']").html("Save");
					$('.user-name').html($('[name="name"]').val())
				});
			});
		}
	}


</script>