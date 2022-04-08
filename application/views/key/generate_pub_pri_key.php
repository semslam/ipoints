<div class="content with-top-banner">
        <div class="panel">
			    <div class="content-box">
			        <div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Generate API Keys</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form class="generateKeyForm">
												<div class="row">
                                                    <div class='form-group col-sm-12'>
                                                        <label class="control-label col-sm-2" for="user">Users:</label>
                                                        <div class="col-sm-7">
                                                            <select title="Choose API User" class="form-control user_id" placeholder="Choose User API" name="user_id">
                                                                <option value="">Choose API User ?</option>
                                                            </select>
                                                            <div class="validation-message user_mesg" data-field="user_id"></div> 
                                                        </div>
													</div>
                                                    <div class="form-group col-sm-12">
                                                        <label class="control-label col-sm-2" for="public_key">Public Key:</label>
                                                        <div class="col-sm-7">
                                                            <p type="text" class="form-control public" id="public_key" placeholder="Public Key"></p>
                                                            <div class="validation-message" data-field="public"></div>
                                                        </div>
                                                        <div class="col-sm-2">
														    <input type="butten" class="btn btn-info " onclick="copyToClipboard('#public_key')" value="Copy Key">
													    </div>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label class="control-label col-sm-2" for="private_key">Private Key:</label>
                                                        <div class="col-sm-7"> 
                                                            <p type="text" class="form-control private" id="private_key" placeholder="Public Key"></p>
                                                        </div>
                                                        <div class="col-sm-2">
														    <input type="butten" class="btn btn-info private_key" onclick="copyToClipboard('#private_key')" value="Copy Key" >
													    </div>
                                                    </div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success generateKeys">Generate</button>
													</div>
											</div>
										</form>
									</div>
								</div>
						</div>
				    </div>
			    </div>
		</div>
</div>
<script type="text/javascript" >


function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    loadApiUser()
    function loadApiUser(){
        var url = BASE_URL + "/apiKeys/apiUsers"
        $.get(url, function(data) {
            apiUser = '';
            for(p in data.apiusers){
                apiUser +='<option value="'+data.apiusers[p].id+'">'+data.apiusers[p].user_name+' Group ('+data.apiusers[p].group_name+')</option>';
            }
            $('.user_id').append(apiUser)
        });
    }

	$('body').on("change",'.user_id', function(evt){
		evt.preventDefault();
		getKeys();
    });
    
    $('body').on("click",'.generateKeys', function(evt){
		evt.preventDefault();
		generateKey();
    });
    
    // $('a.public_key').click(function(){
    //     $(this).siblings('input.public').select();      
    //     document.execCommand("copy");
    // });

   


	 function generateKey() {
        $('.generateKeys').html("Generating...").attr('disabled', true);
        var obj = $('.generateKeyForm').serialize();
       
         var url = BASE_URL + "/apiKeys/generateAPIKey"
         $('.user_mesg,.public, .private').html('');
       
        swal({   
            title: "Are you sure of generating API keys?",   
            text: "Note!!! This generating keys is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Save",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Generate was successful",
                            type: "success"
                        })
                        $('.generateKeys').html("Generated").attr('disabled', true);
                        console.log(data.keys)
                        $('.private').html(data.keys['private']);
                        $('.public').html(data.keys['public']);
                        $('.public ,.private').css('border', '1px solid #5cb85c !important');
                    } else {
                        $('.generateKeys').html("Generate").attr('disabled', false);
                        $('.validation-message').html('');
                        $('.validation-message').each(function() {
                            for (var key in data) {
                                if ($(this).attr('data-field') == key) {
                                    $(this).html(data[key]);
                                }
                            }
                        });
                    }
                 } catch(e){
                     console.log('Exp error: ',e)
                 }
            });

        });
        
	}
	
	function getKeys() {
       var obj = $('.generateKeyForm').serialize();
       console.log(obj);
       var url = BASE_URL + "/apiKeys/getAPIKey"
       $('.user_mesg,.public,.private').html('');
       
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   
                   $('.private').html(data.keys['private']);
                   $('.public').html(data.keys['public']);
                   $('.public ,.private').css('border', '1px solid #5cb85c !important');
               } else {
                   $('.validation-message').html('');
                   $('.validation-message').each(function() {
                       for (var key in data) {
                           if ($(this).attr('data-field') == key) {
                               $(this).html(data[key]);
                           }
                       }
                   });
               }
            } catch(e){
                console.log('Exp error: ',e)
            }
       });
   }
     
</script>