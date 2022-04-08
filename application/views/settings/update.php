    <style>
        .text-value,.textarea-value,.number-value,.all{
            display:none;
        }
    </style>
    <div class="content with-top-banner">
		<div class="panel">
			<div class="content-box">
			<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" aria-hidden="true"></i> Update System Configuration Sitting Form</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form id="createSettingForm">
												<div class="row">
                                                    <div class="form-group col-sm-4">
														<select class="form-control settings_type" name="settings_type">
															<option value="">Choose Settings Meta Type</option>
														</select>
														<div class="validation-message" data-field="settings_type"></div>
													</div>
													<div class="form-group col-sm-4">
														<input title="Enter key value with know space eg my_key" type="text" placeholder="Key value with know space" name="meta_key" class="form-control meta_key">
														<div class="validation-message" data-field="key_meta"></div>
													</div>
                                                    <div class="form-group col-sm-4">
														<input title="Enter meta label" type="text" placeholder="Enter meta label" name="meta_label" class="form-control meta_label">
														<div class="validation-message" data-field="meta_label"></div>
													</div>
                                                    <div class="form-group col-sm-12">
														<textarea title="Payment meta_description" name="meta_description" class="meta_description"  placeholder="Enter meta description" rows="3" cols="100"></textarea>
														<div class="validation-message" data-field="meta_description"></div>
													</div>
                                                    <div class="form-group all textarea-value col-sm-12">
                                                        <label>Meta Value </lable>
                                                        <textarea class="form-control summertext meta_value_area"  placeholder="Enter meta vale in html format" title="Enter meta value in html format"  name="meta_value_area"  rows="10" cols="100"></textarea>   
                                                        <div class="validation-message" data-field="meta_value_area"></div>
                                                    </div>
                                                    <div class="form-group all text-value col-sm-4">
                                                        <input title="Enter meta text value" type="text" placeholder="Enter meta text value" name="meta_value_text" class="form-control meta_value_text">
                                                        <div class="validation-message" data-field="meta_value_text"></div>
                                                    </div>
                                                    <div class="form-group all number-value col-sm-4">
                                                        <input title="Enter meta numeric value" type="number" placeholder="Enter meta numeric value" name="meta_value_numb" class="form-control meta_value_numb">
                                                        <div class="validation-message" data-field="meta_value_numb"></div>
                                                    </div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="id" value="" class="id">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success"  id="createSettingSubmit">Submit</button>
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

var $placeholder = $('.placeholder');
$(".summertext").summernote({
  height: 300,
  codemirror: {
      mode: 'text/html',
      htmlMode: true,
      lineNumbers: true,
      theme: 'monokai'
  },
  callbacks: {
      onInit: function() {
          $placeholder.show();
      },
      onFocus: function() {
          $placeholder.hide();
      },
      onBlur: function() {
          var $self = $(this);
          setTimeout(function() {
              if ($self.summernote('isEmpty') && !$self.summernote('codeview.isActivated')) {
                  $placeholder.show();
              }
          }, 300);
      }
  }
});


    $("body").on("change",'.settings_type',function (e) {
        const id = $('.settings_type').val()
        var url = BASE_URL + "/settings/editSettings"
        $("#createSettingForm")[0].reset();
       
        var obj = {
            id: id,
        };
        
        console.log(obj);
//when was threenity was intro dus in christainity

        $.post(url, obj).done(function (data) {
            try {
                var title;
                var message;
                var type;
                if (data.value == "success") {
                    console.log(data.setting.meta_key);
                    $('.id').val(data.setting.id);
                    $('.meta_key').val(data.setting.meta_key);
                    $('.meta_label').val(data.setting.meta_label);
                    $('.meta_type').val(data.setting.meta_type);
                    $('.meta_description').val(data.setting.meta_description);
                    if(data.setting.meta_type == 'text'){
                        $('.textarea-value,.number-value').hide();
                        $('.text-value').show();
                        $('.meta_value_text').val(data.setting.meta_value);
                    }else if(data.setting.meta_type == 'number'){
                        $('.text-value,.textarea-value').hide();
                        $('.number-value').show();
                        $('.meta_value_numb').val(data.setting.meta_value);
                    }else if(data.setting.meta_type == 'textarea'){
                        $('.text-value,.number-value').hide();
                        $('.textarea-value').show();
                        $(".summertext").summernote("code", data.setting.meta_value);
                       
                    }
                   
                }else{
                    // const info = {title:"Subscription Error", message:"The user was not subscribed successfully", type:error, position:topfullwidth};
                    // toastNotification(info);
                }
            } catch (e) {
                console.log('Exp error: ', e)
            }
        });
        
    });


    $('body').on("click",'#createSettingSubmit',function(evt){
        evt.preventDefault();
        createSetting();
    });  

    function createSetting() {
        $('#createSettingSubmit').html("Processing...").attr('disabled', true);
        var obj;
            obj = $('#createSettingForm').serialize();
        var url = BASE_URL + "/settings/create"
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {
                        title = "Configuration";
                        message ="The settings was successfully saved";
                        type = success;
                        const info = {title:title, message:message, type:type, position:topfullwidth};
                        toastNotification(info);
                        $("#createSettingForm")[0].reset();
                        $(".summertext").summernote("code", '');
                        $('#createSettingSubmit').html("Saved").attr('disabled', true);
                    } else {
                        $('#createSettingSubmit').html("Submit").attr('disabled', false);
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

    configureSettings()
     function configureSettings(){
        var url = BASE_URL + "/settings/listSettings"
        $.get(url, function(data) {
            settings = '';
            for(s in data.settings){
                settings +='<option value="'+data.settings[s].id+'">'+(data.settings[s].meta_label +' ( '+data.settings[s].meta_key+' ) ')+'</option>';
                console.log(settings)
            }
            $('.settings_type').append(settings)
        });
     }
    </script>
