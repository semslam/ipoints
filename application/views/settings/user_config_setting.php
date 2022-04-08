<style>
.p-setting-img{
  width: 10%;
    height: 10%;
    margin-top: -35px;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    border-radius: 50%;
    border: 2px solid #A50;
}

.user-name-herder{
  font: 100 1.2em fantasy;
}

.u-pro-herder-bullet-pnt {
  font: 100 1.2em fantasy;
  text-decoration: underline;
  text-underline-position: under;
}

.p-3 {
    padding: 1rem!important;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}
.mt-3, .my-3 {
    margin-top: 1rem!important;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}
.rounded {
    border-radius: .25rem!important;
}
.bg-white {
    background-color: #fff!important;
}

.pb-2, .py-2 {
    padding-bottom: .5rem!important;
}

.mb-0, .my-0 {
    margin-bottom: 0!important;
}
.border-bottom {
    border-bottom: 1px solid #dee2e6!important;
}

.text-muted {
    color: #6c757d!important;
}
.pt-3, .py-3 {
    padding-top: 1rem!important;
}

.media {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: start;
    align-items: flex-start;
}



.bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.mr-2, .mx-2 {
    margin-right: .5rem!important;
}
.rounded {
    border-radius: .25rem!important;
}
svg {
    overflow: hidden;
    vertical-align: middle;
}




.lh-125 {
    line-height: 1.25;
}

.pb-3, .py-3 {
    padding-bottom: 1rem!important;
}
.mb-0, .my-0 {
    margin-bottom: 0!important;
}
.border-bottom {
    border-bottom: 1px solid #dee2e6!important;
}
.media-body {
    -ms-flex: 1;
    flex: 1;
}
.small, small {
    font-size: 80%;
    font-weight: 400;
}

.d-block {
    display: block!important;
}
.config-font-size {
    font-size: 1em !important;
}

		/* The customcheck */
    .customcheck {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default checkbox */
.customcheck input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    border: 2px solid #ccc;
    border-radius: 5px;
}

/* On mouse-over, add a grey background color */
.customcheck:hover input ~ .checkmark {

	border: 2px solid #398439;
}

/* When the checkbox is checked, add a blue background */
.customcheck input:checked ~ .checkmark {
    background-color: #398439;
    border-radius: 5px;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.customcheck input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.customcheck .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}



.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #5bc0de;
  border-right: 16px solid #449d44;
  border-bottom: 16px solid #ec971f;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>
<div class="breadcrumb">
	<a href="">Dashboard</a> 
	<a href="">Merchant Configuration</a>
</div>
<div class="content with-top-banner">
	<div class="panel">
		<div class="content-box">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
            <div class="ibox-title">
              <h5><i class="fa fa-cogs" aria-hidden="true"></i> User Configuration Sittings </h5>
              <div class="ibox-tools">
              </div>
            </div>
            <div class="ibox-content">
              <div class="jumbotron">
                <div class="container">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="user-top-profile">
                        <div class="user-image">
                          <div class="user-on"></div>
                          <img class="p-setting-img" alt="pongo" src="<?php echo base_url() . 'assets/images/user-ipoint.jpg'; ?>">
                        </div>
                        <div class="clear">
                          <div class="user-name-herder user-names"></div>
                          <div class="user-groups"></div>
                          <br>
                          <div class="user-join-date">Member since </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                        <div class="clear"><br><br></div>
                        <div class="clear">
                          <div class="u-pro-herder-bullet-pnt wallet-title">Wallet Balance</div>
                          <div id="user-wallet-list">
                            
                          </div>
                          <ul class="b-vertical-type"></ul>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="jumbotron">
                <div class="container">
                    <!-- Nav tabs -->
                  <ul class="nav nav-tabs">
                  <?php 
                      if($user['group'] == MERCHANT){
                        echo '
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#charges"> <i class="fa fa-percent"></i> Charges</a>
                        </li>';
                      }
                    ?>
                    <li class="nav-item">
                      <a class="nav-link" data-toggle="tab" href="#profile"><i class="fa fa-user" ></i> Profile</a>
                    </li>
                    <?php 
                      if($user['group'] == MERCHANT){
                        echo '
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#gifting"><i class="fa fa-plug"></i> Gifting Config</a>
                        </li>';
                      }
                    ?>
                    <?php 
                      if($user['group'] == MERCHANT || $user['group'] == SUBSCRIBER){
                        echo '
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#transaction"><i class="fa fa-retweet"></i> Transaction Log</a>
                        </li>';
                      }
                    ?>
                    
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    
                    <?php 
                      if($user['group'] == MERCHANT){
                        echo '
                        <div class="tab-pane container active" id="charges">
                          <div class="my-3 p-3 bg-white rounded shadow-sm">
                              <h6 class="border-bottom border-gray pb-2 mb-0">Charges Update</h6>
                              <div id="userConfigData"></div>
                              <small class="d-block text-right mt-3"><a href="#">All updates</a></small>
                          </div>
                        </div>
                        ';
                      }
                    ?>
                    <div class="tab-pane container fade" id="profile">
                      <div class="my-3 p-3 bg-white rounded shadow-sm">
                            <h6 class="border-bottom border-gray pb-2 mb-0">Update Profile</h6>
                              <?php 
                              if($user['on_session'] ==1){
                                $this->load->view('profile/update_profile');
                              }else{
                                $this->load->view('profile/view_profile');
                              }
                               ?>
                            <small class="d-block text-right mt-3"><a href="#">All updates</a></small>
                      </div>
                    </div>
                    <div class="tab-pane container fade" id="gifting">
                            <div class="my-3 p-3 bg-white rounded shadow-sm">
                                  <h6 class="border-bottom border-gray pb-2 mb-0"> Gifting Configuration</h6>
                                  <?php $this->load->view('settings/gifting_configuration') ?>
                                  <small class="d-block text-right mt-3"><a href="#">All updates</a></small>
                            </div>
                          </div>
                    
                    <div class="tab-pane container fade" id="transaction">
                      <div class="my-3 p-3 bg-white rounded shadow-sm">
                      <h6 class="border-bottom border-gray pb-2 mb-0">Transaction History</h6>
                        <?php 
                              if($user['group'] == MERCHANT || $user['group'] == SUBSCRIBER){
                                $this->load->view('settings/user_transaction_histories');
                              }
                        ?>
                            <small class="d-block text-right mt-3"><a href="#">All updates</a></small>
                      </div>
                    </div>
                  </div>
                  <!-- Tab pane end -->
                </div>
                <!-- container end -->
              </div>
              <!--  jumbotron end-->
            </div>
          </div>
        </div>
      </div>
		</div>
	</div>
</div>
<script type="text/javascript">

function userConfigTable(data){
         if(!IsEmptyOrUndefined(data)){
           var userConfigData =''; 
                    if("<?php echo $user['group']; ?>" == "<?php echo MERCHANT; ?>" && <?php echo $user['on_session']; ?> == 1){
                      for(x in data){//'+(data[x].status == "1"? 'checked':null)+'
                        userConfigData+='<div class="media text-muted pt-3">'+
                          '<div class="i-checks"><label class="customcheck"> <input '+(data[x].status == "1"? 'checked':null)+' type="checkbox" ><span class="checkmark"></span></label></div>'+
                          '<p class="media-body pb-3 mb-0 small config-font-size lh-125 border-bottom border-gray">'+
                            '<strong class="d-block">'+data[x].value+' &nbsp;&nbsp;'+data[x].value_type+'</strong>'+
                            '<strong class="d-block text-gray-dark">'+data[x].levies_name+'</strong> '+(data[x].description || 'No Description')+''+
                          '</p>'+
                        '</div>';
                      }
                    }else if('<?php echo $user['group']; ?>' != '<?php echo MERCHANT; ?>' && <?php echo $user['on_session']; ?> !=0){
                      // if not merchant display no charges
                      userConfigData+='<h1>No Charges......</h1>';
                    }else{
                      for(x in data){//'+(data[x].status == "1"? 'checked':null)+'
                        userConfigData+='<div class="media text-muted pt-3">'+
                          '<div class="i-checks"><label class="customcheck"> <input class="user-status" name="user-status" data-charge="'+data[x].id+'" data-user="'+data[x].userId+'" data-uici_levies_id="'+data[x].uici_levies_id+'" '+(data[x].status == "1"? 'checked':null)+' type="checkbox" ><span class="checkmark"></span></label></div>'+
                          '<p class="media-body pb-3 mb-0 small config-font-size lh-125 border-bottom border-gray">'+
                            '<strong class="d-block">'+data[x].value+' &nbsp;&nbsp;'+data[x].value_type+'</strong>'+
                            '<strong class="d-block text-gray-dark">'+data[x].levies_name+'</strong> '+(data[x].description || 'No Description')+''+
                          '</p>'+
                        '</div>';
                      }
                    }
                    $("#userConfigData").html(userConfigData);
                     
                  }else{
                      $("#userConfigData").html('');
                  }

     }

     userDetail();
    function userDetail(){
         var url = BASE_URL + "/UserConfigSetting/userDetails/<?php echo $user['userId']; ?>"
        
         $.get(url, function(data) {
             
             $('.user-names').html(data.userDetail.name);
             $('.user-groups').html(data.userDetail.group_name);
             $('.user-join-date').append(data.userDetail.created_at);
         });
     }

     userBalance();
    function userBalance(){
         var url = BASE_URL + "/UserConfigSetting/userBalance/<?php echo $user['userId']; ?>"
        
         $.get(url, function(data) {
             
             data = data.userBalance;
             if(!IsEmptyOrUndefined(data)){
                  var userWalletList ='';
                  for(x in data){//'+(data[x].status == "1"? 'checked':null)+'
                    userWalletList+='<div class="user-wallet"><strong>'+data[x].product_name+' : &#8381; '+data[x].balance+'</strong></div>';
                  }
                  $("#user-wallet-list").html(userWalletList); 
                  }else{
                      $("#user-wallet-list").html('<h5>No Wallet Found</h5>');
                  }
         });
     }

     useConfigSetting();
    function useConfigSetting(){
      $("#userConfigData").html('<div style="text-align: -webkit-center;"><div class="loader"></div></div>');
         var url = BASE_URL + "/UserConfigSetting/userSettingManager/<?php echo $user['userId']; ?>";
       
         $.get(url, function(data) {
             userConfigTable(data.userLevies)
         });
     }
     $('body').on("click",'.user-status', function(evt){
    evt.preventDefault();
   
    var checker = $(evt.target);
    const checkerSib = checker.siblings('input');
    checker.attr("checked", "checked")
    
    if (checker.is(':checked')) {checkerSib.removeAttr('checked')}
    else {checkerSib.attr("checked", "checked")}
    const charge = $(evt.target).data('charge');
   
    const user = $(evt.target).data('user');
    const uici_levies = $(evt.target).data('uici_levies_id');
    
    request ={
      isChecked : checker.is(':checked') ? "1" : "0",
      charge: charge,
      user: (user != null)? user : <?php echo $user['userId']; ?>,
      uici_levies: uici_levies
    }
    checker.toggle('checked')
    //return;
    
    updateUserCharge(evt,request);
});
     function updateUserCharge(evt,requests) {
        
         var url = BASE_URL + "/UserConfigSetting/updateAndCreateCharge"
        swal({   
            title: "Are you sure, you want to carry on this request?",   
            text: "Note!!! This action is reversible",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            cancelButtonText: "Cancel",
            confirmButtonText: "Continue",
            closeOnConfirm: true 
        }, function() {
            $.post(url, requests).done(function(data) {
                try{
                    if (data.value == "success") {
                        swal({   
                            title: "Success",
                            text: "Successfully updated",
                            type: "success"
                        })
                        $("#userConfigData").html('');
                        useConfigSetting();
                       
                    } else {
                        swal({   
                            title: "Failed",
                            text: "Your request wasn't successful.",
                            type: "error"
                        })
                        $('.approvedSearch').html("Process").attr('disabled', false);
                        $('.validation-message').html('');
                        $('.validation-message').each(function() {
                            for (var key in data) {
                                if ($(this).attr('data-field') == key) {
                                    $(this).html(data[key]);
                                }
                            }
                        });
                        if ($.fn.DataTable.isDataTable("#offlineTable")) {
                            $("#offlineTable").DataTable().destroy();
                        }
                        loadOfflinePayment();
                    }
                 } catch(e){
                     console.log('Exp error: ',e)
                 }
            });

        });
        
    } 

</script>
