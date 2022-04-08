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
										<h5><i class="fa fa-bars" aria-hidden="true"></i> Create System Configuration Sitting Form</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form id="createSettingForm">
												<div class="row">
													<div class="form-group col-sm-4">
														<input title="Enter key value with know space eg my_key" type="text" placeholder="Key value with know space" name="meta_key" class="form-control meta_key">
														<div class="validation-message" data-field="meta_key"></div>
													</div>
                                                    <div class="form-group col-sm-4">
														<input title="Enter meta label" type="text" placeholder="Enter meta label" name="meta_label" class="form-control meta_label">
														<div class="validation-message" data-field="meta_label"></div>
													</div>
                                                    <div class="form-group col-sm-4">
														<select class="form-control meta_type" name="meta_type">
															<option value="">Choose Type Value</option>
															<option value="text">TEXT</option>
															<option value="number">NUMBER</option>
															<option value="textarea">TEXT AREA</option>
														</select>
														<div class="validation-message" data-field="meta_type"></div>
													</div>
                                                    <div class="form-group col-sm-12">
														<textarea title="Payment meta_description" name="meta_description"  placeholder="Enter meta description" rows="3" cols="100"></textarea>
														<div class="validation-message" data-field="meta_description"></div>
													</div>
                                                    <div class="form-group all textarea-value col-sm-12">
                                                        <label>Meta Value </lable>
                                                        <textarea class="form-control summertext meta_value_area" placeholder="Enter meta vale in html format" title="Enter meta value in html format"  name="meta_value_area"  rows="10" cols="100"></textarea>   
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


    $('body').on("click",'#createSettingSubmit',function(evt){
        evt.preventDefault();
        createSetting();
    });

    $('.meta_type').change(function() {
       const type = $('.meta_type').val()
        if(type == 'text') { 
           hideFormInputAndReset('.all');
           $('.text-value').show();
        }else if(type == 'number'){
           hideFormInputAndReset('.all');
           $('.number-value').show();		
        }else if(type == 'textarea'){
            hideFormInputAndReset('.all');
            $('.textarea-value').show();	
        }else{
            hideFormInputAndReset('.all');	
        }
   });

   function hideFormInputAndReset(mclass) {
	    $(mclass).hide().find('.meta_value_numb,.meta_value_text').val('');
        $(".summertext").summernote("code", '');
    }

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
                                if($(this).attr('data-field') == key) {
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
