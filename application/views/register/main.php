<style> .form-group.all{display:none;}</style>
<div class="auth-wrapper">
	<div class="auth-header">
		<div class="auth-title">iPoints Portal</div>
		<!--<div class="auth-subtitle">Simple and Clean Admin Template</div>-->
		<div class="auth-label">Register</div>
	</div>
	<div class="auth-body">
		<form id="form-action">
			<div class="auth-content">
				<div class="form-group">
					<label>Account Type</label>
					<!--Please do not change option value-->
					<select class="form-control" name='acct_type' id='acct_type'>
						<option value =''>Choose Account Type</option>
						<option value ='3'>Merchant</option>
						<option value='4'>Subscriber</option>
						<option value='5'>Underwriter</option>
						<option value='6'>Partner</option>
					</select>
					<div class="validation-message" data-field="acct_type"></div>
				</div>
				<div class="form-group subscriber all" id='fName'>
					<label>Full Name</label>
					<input name='fName' class="fName form-control" placeholder="Full Name" type="text">
					<div class="validation-message" data-field="fName"></div>
				</div>
				<div class="form-group subscriber all">
					<label>Gender</label>
					<!--Please do not change option value-->
					<select class="form-control" name='gender' id='gender'>
					<option value="">Choose Gender</option>
					<option value="male">Male</option>
					<option value="female">Female</option>
					</select>
					<div class="validation-message" data-field="gender"></div>
				</div>
				<div class="form-group merchant under_partner all" id='rc_number'>
					<label>RC Number</label>
					<input name='rc_number' class="rc_number form-control" placeholder="RC Number" type="text">
					<div class="validation-message" data-field="rc_number"></div>
				</div>
				<div class="form-group merchant under_partner all" id='biz_name'>
					<label>Business Name</label>
					<input name='biz_name' class="biz_name form-control" placeholder="Enter Business Name" type="text">
					<div class="validation-message" data-field="biz_name"></div>
				</div>
				<div class="form-group merchant all" id='industry'>
					<label>Industry</label>
					<input class="form-control indust" placeholder="Start typing to select" type="text">
					<input type="hidden" name="industries"  id="industries" />
					<div class="validation-message" data-field="industries"></div>
				</div>
				<div class="form-group subscriber all" id='dob'>
					<label>Date of Birth</label>
					<input name='dob' class="single-daterange form-control dob" placeholder="1993-12-23 " type="text" value="">
					<div class="validation-message" data-field="dob"></div>
				</div>
				<div class="form-group subscriber all" id='user_phone'>
					<label>Mobile Number</label>
					<input name='user_phone' class="user_phone form-control" placeholder="Enter Mobile" type="text">
					<div class="validation-message" data-field="user_phone"></div>
				</div>
				<div class="form-group subscriber merchant under_partner all" id='email'>
					<label>Email</label>
					<input name='email' class="email form-control" placeholder="Enter email" type="email">
					<div class="validation-message" data-field="email"></div>
				</div>
				<!-- <div class="form-group subscriber all" id='state'>
					<label>State</label>
					<input class="lga form-control" placeholder="Start typing to select" type="text">
					<input type="hidden" name="state"  id="state" />
					<div class="validation-message" data-field="state"></div>
				</div>
				<div class="form-group subscriber all" id='lga'>
					<label>Local Government of Residence</label>
					<input class="lga form-control" placeholder="Start typing to select" type="text">
					<input type="hidden" name="lga"  id="lg" />
					<div class="validation-message" data-field="lga"></div>
				</div> -->
				
				<div class="form-group subscriber all">
					<label>State</label>
					<!--Please do not change option value-->
					<select class="form-control" name='states' id='states'>
						<option value="">Choose State</option>
					</select>
					<div class="validation-message" data-field="states"></div>
				</div>
				<div class="form-group subscriber all">
					<label>Local Government of Residence</label>
					<!--Please do not change option value-->
					<select class="form-control" name='lga' id='lga'>
					</select>
					<div class="validation-message" data-field="lga"></div>
				</div>
				<div class="form-group subscriber all" id='nok_name'>
					<label>Name of Next of Kin</label>
					<input name='nok_name' class="nok_name form-control" placeholder="Enter Next of Kin" type="text">
					<div class="validation-message" data-field="nok_name"></div>
				</div>
				<div class="form-group subscriber all" id='nok_phone'>
					<label>Next of Kin Mobile Number</label>
					<input name='nok_phone' class="nok_phone form-control" placeholder="Next of Kin Mobile Number" type="text">
					<div class="validation-message" data-field="nok_phone"></div>
				</div>
				<div class="form-group merchant under_partner all" id='biz_address'>
					<label>Business Address</label>
					<input name='biz_address' class="biz_address form-control" placeholder="Enter business Address" type="text">
					<div class="validation-message" data-field="biz_address"></div>
				</div>
				<div class="form-group merchant all" id='referrer'>
					<label>Referrer</label>
					<input name='referrer' class="referrer form-control" placeholder="Enter Referrer Phone Number" type="text">
					<div class="validation-message" data-field="referrer"></div>
				</div>
				<!-- <div class="form-group">
                    <label for=""></label>
					<div class="g-recaptcha" data-sitekey="<?php echo RE_CAPTCHA_SITE_KEY?>"></div>
					<div class="validation-message" data-field="g-recaptcha"></div>
                </div> -->
				<div class="row" id='password'>
					<div class="col-sm-6">
						<div class="form-group all common">
							<label> Password</label>
							<input name='password' class="password form-control" placeholder="Password" type="password">
							<div class="validation-message" data-field="password"></div>
						</div>
					</div>
					<div class="col-sm-6" id='conf_password'>
						<div class="form-group all common">
							<label>Confirm Password</label>
							<input name='conf_password' class="conf_password form-control" placeholder="Re-Enter Password" type="password">
							<div class="validation-message" data-field="conf_password"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="auth-footer">
				<button class="btn btn-success action saved" title="save">Register Now!</button>
				<div class="pull-right auth-link">
					<a href="<?php echo base_url() . 'auth/login'; ?>">Already have an account?</a>
				</div>
				<hr />
				<div class="row">
					<div class="col-sm-12 text-center"><a href="<?php echo  base_url();?>">Back to Site</a></div>
				</div>
			</div>
		</form>	
	</div>
</div>
<script type="text/javascript">

     

/*hide some input fileds*/
$(document).ready(function() {




	$( "#states" )
            .change(function () {
			 var states = {state_id:$(this).val()};
			 console.log(states)
             post('<?php echo base_url() .'auth/local_govts'; ?>',{state_id:$(this).val()},function(data) {
                $("#lga").html('<option> Choose LGA... </option>')
              for (x in data) {
			
                  $("#lga").append('<option value="'+data[x].id+'">'+data[x].name+'</option>');
                }
            });
        }).change();

  getState();
  function getState() {          
    get('<?php echo base_url() .'auth/state'; ?>',function(data) {
		console.log(data)
      for (x in data) { 
		console.log(data[x].state_name);
        $("#states").append('<option value="'+data[x].state_id+'">'+data[x].state_name+'</option>');
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
	  


	  $(".lga").autocomplete({
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
              url: "<?php echo base_url() . 'auth/lga'; ?>",// domain url
              type:"POST",
              dataType: "json",
              data: { name: $('.lga').val() },
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
					$("#lg").val(key);
				}
      		});
    	},
	  });
	  
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
	  
	  $('.saved').click(function(e){
		 e.preventDefault();
		 $('.saved').html("Validating data, please wait...").attr('disabled', true);
		var formData = $('#form-action').serialize();
		//console.log(formData);
		var returnData;
		$.ajax({
	        url: "<?php echo base_url('auth/processor') ?>", async: false, type: 'POST', data: formData,
	        success: function(data, textStatus, jqXHR) {
				returnData = data;
	        }
	    });
        if (returnData.status != 'success') {
			//console.log(returnData);
			$('.saved').html("Register Now!").attr('disabled', false);
            $('.validation-message').html('');
            $('.validation-message').each(function() {
				console.log('error:: ',returnData);
                for (var key in returnData) {
                    if ($(this).attr('data-field') == key) {
                        $(this).html(returnData[key]);
                    }
                }
			});
        }else{
			window.location.replace("<?php echo base_url('auth/activate_form'); ?>");
		}
	});
	
	$('.form-group.all').hide(); //hide field on start
	
	$('#acct_type').change(function() {
 
		 var $index = $('#acct_type').index(this);
		const account_type = $('#acct_type').val()
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
    });
 });     

function hideFormInputAndReset(mclass) {
	$(mclass).hide().find('input,select').val('');
}

/*hide some input fileds*/
$(document).ready(function() {


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
	  


	  $(".lga").autocomplete({
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
              url: "<?php echo base_url() . 'auth/lga'; ?>",// domain url
              type:"POST",
              dataType: "json",
              data: { name: $('.lga').val() },
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
					$("#lg").val(key);
				}
      		});
    	},
	  });
	  
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
					console.log(key);
					$("#industries").val(key);
				}
      		});
    	},
	  });
	  
	//   $('.saved').click(function(e){
	// 	 e.preventDefault();
	// 	var formData = $('#form-action').serialize();
	// 	console.log(formData);
		
	// 	var returnData;
	// 	$('#saved').html("Validating data, please wait...").attr('disabled', true);	
	// 	$.ajax({
	//         url: "<?php echo base_url('auth/processor') ?>", async: false, type: 'POST', data: formData,
	//         success: function(data, textStatus, jqXHR) {
	// 			returnData = data;
	//         }
	//     });
    //     if (returnData.status != 'success') {
	// 		//console.log(returnData);
	// 		$('#saved').html("Register Now!").attr('disabled', false);
    //         $('.validation-message').html('');
    //         $('.validation-message').each(function() {
    //             for (var key in returnData) {
    //                 if ($(this).attr('data-field') == key) {
    //                     $(this).html(returnData[key]);
    //                 }
    //             }
	// 		});
    //     }else{
	// 		window.location.replace("<?php echo base_url('auth/activate_form'); ?>");
	// 	}
	// });

 });
</script>