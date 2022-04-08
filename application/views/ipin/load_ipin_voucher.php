<style>
	.label-success {
     background-color: #5cb85c;
}
</style>
<div class="content with-top-banner">
	<div class="panel">
		<div class="content-box">
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>
								<i class="fa fa-spinner" aria-hidden="true"></i> Load iPin Voucher</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<form id="loadVoucherForm">
								<div class="row">
                                   
                                    <div class="form-group col-sm-5">
										<input title="Enter iPin Voucher" type="text" placeholder="Enter iPin Voucher"  name="voucher"
										    class="form-control voucher">
										<div class="validation-message" data-field="voucher"></div>
									</div>
									<div class="form-group col-sm-3">
										<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
										<button class="btn btn-success" id="loadVoucherRequest">Load</button>
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

     $('body').on("click",'#loadVoucherRequest', function(evt){
        evt.preventDefault();
        $('.validation-message').html('');
		loadVoucherRequest();
    });
		
    function loadVoucherRequest() {
       $('#loadVoucherRequest').html("Loading....").attr('disabled', true);
       var obj = $('#loadVoucherForm').serialize();
       console.log(obj);
       //$("#withdrawerData").html('<tr><td align="center" colspan="10"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/ipingenerates/useIpinVoucher"
       swal({   
            title: "Are you sure, You want to load this ipin voucher?",   
            text: "Note!!! Loading this iPin voucher is irreversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "Cancel",
            confirmButtonText: "Load",
            closeOnConfirm: true 
        }, function() {
            $.post(url, obj).done(function(data) {
                try{
                    if(data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "iPin voucher was successful loaded.",
                            type: "success"
                        })
                        $('#loadVoucherRequest').html("Load").attr('disabled', false);
                        $('#loadVoucherForm')['0'].reset();
                    } else {
                        $('#loadVoucherRequest').html("Load").attr('disabled', false);
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

</script>