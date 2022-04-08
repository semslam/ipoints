<div class="row">
			<div class="col-md-6">
			
				<div class="content-box">
					<form id="form-action">
						<input type="text" name="group_id" class="group_id hidden" readonly>
						<div class="form-group subscriber all" id='name'>
							<label>Full Name</label>
							<input name='name' class="name form-control" type="text" placeholder="N/A" readonly>
							<div class="validation-message" data-field="name"></div>
						</div>
						<div class="form-group subscriber all " id='gender'>
							<label>Gender</label>
							<!--Please do not change option value-->
                            <input name='text' class="form-control gender" readonly placeholder="N/A"  type="text" >
							<div class="validation-message" data-field="gender"></div>
						</div>
						<div class="form-group subscriber all" id='mobile_number'>
							<label>Mobile Phone</label>
							<input name='text' class="form-control mobile_number" readonly placeholder="N/A"  type="text" >	
						</div>
						<div class="form-group subscriber all" id='email'>
							<label>Email</label>
							<input name='email' class="form-control email" readonly placeholder="N/A"  type="text" >
							<div class="validation-message" data-field="email"></div>
						</div>
						<div class="form-group merchant under_partner all" id='rc_number'>
							<label>RC Number</label>
							<input name='rc_number' class="rc_number form-control" readonly placeholder="N/A"  type="text">
							<div class="validation-message" data-field="rc_number"></div>
						</div>
						<div class="form-group merchant all" id='biz_name'>
							<label>Business Name</label>
							<input name='biz_name' class="biz_name form-control" readonly placeholder="N/A" type="text">
							<div class="validation-message" data-field="biz_name"></div>
						</div>
						<div class="form-group merchant all" id='industry'>
							<label>Industry</label>
							<input class="form-control indust" readonly placeholder="N/A"  type="text">
							<div class="validation-message" data-field="industries"></div>
						</div>
						<div class="form-group subscriber all" id='dob'>
							<label>Date of Birth</label>
							<input name='dob' class="single-daterange form-control dob" readonly placeholder="N/A" type="text" >
							<div class="validation-message" data-field="dob"></div>
						</div>
						
						<div class="form-group subscriber all" id='state'>
							<label>State</label>
							<!--Please do not change option value-->
                            <input name='states' class="states form-control" readonly placeholder="N/A"  type="text">
							
							<div class="validation-message" data-field="states"></div>
						</div>
						<div class="form-group subscriber all" id='lga'>
							<label>Local Government of Residence</label>
							<!--Please do not change option value-->
                            <input name='lga' class="lga form-control" readonly placeholder="N/A"  type="text">
							<div class="validation-message" data-field="lga"></div>
						</div>
						<div class="form-group subscriber all" id='nok_name'>
							<label>Name of Next of Kin</label>
							<input name='nok_name' class="nok_name form-control" readonly placeholder="N/A"  type="text">
							<div class="validation-message" data-field="nok_name"></div>
						</div>
						<div class="form-group subscriber all" id='nok_phone'>
							<label>Next of Kin Mobile Number</label>
							<input name='nok_phone' class="nok_phone form-control" readonly placeholder="N/A"  type="text">
							<div class="validation-message" data-field="nok_phone"></div>
						</div>
						<div class="form-group merchant under_partner all" id='biz_address'>
							<label>Business Address</label>
							<input name='biz_address' class="biz_address form-control" readonly placeholder="N/A"  type="text">
							<div class="validation-message" data-field="biz_address"></div>
						</div>
						<div class="form-group merchant all" id='referrer'>
							<label>Referrer</label>
							<input name='referrer' class="referrer form-control" readonly placeholder="N/A"  type="text">
							<div class="validation-message" data-field="referrer"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
        <script type="text/javascript">
	$(document).ready(function() {
		group(); userDetail();
	function group() {
 
		const account_type =  '<?php echo $user['group_id']; ?>'
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

    function userDetail(){
         var url = BASE_URL + "/UserConfigSetting/userDetails/<?php echo $user['userId']; ?>"
        
         $.get(url, function(data) {
            console.log('Preview User');
             console.log(data.userDetail); 
             //referrer biz_address nok_phone nok_name lga states dob indust biz_name rc_number email mobile_number gender  name
             $('.group_id').val(data.userDetail.group_id);
             $('.name').val(data.userDetail.name);
             $('.gender').val(data.userDetail.gender);
             $('.mobile_number').val(data.userDetail.mobile_number);
             $('.email').val(data.userDetail.email);
             $('.rc_number').val(data.userDetail.rc_number);
             $('.biz_name').val(data.userDetail.business_name);
             $('.indust').val(data.userDetail.industry_name);
             $('.dob').val(data.userDetail.birth_date);
             $('.states').val(data.userDetail.state_name);
             $('.lga').val(data.userDetail.local_govts_name);
             $('.nok_name').val(data.userDetail.next_of_kin);
             $('.nok_phone').val(data.userDetail.next_of_kin_phone);
             $('.biz_address').val(data.userDetail.address);
             $('.referrer').val(data.userDetail.referrer);
         });
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
			
             post('<?php echo base_url() .'auth/local_govts'; ?>',{state_id:$(this).val()},function(data) {
				const lgaElem = $(".lga");
				const lgaVal = lgaElem.val();
				lgaElem.html('<option> Choose LGA... </option>')
				let selected = false;
				
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
			
		for (x in data) { 
			
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