
<div class="content with-top-banner">
    <div class="panel">
        <div class="content-box">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>
                                <i class="fa fa-bars"></i> Create User</h5>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        <div class="ibox-content">
                            <form class="backEndUserForm">
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <input title="Enter User Name" type="text" placeholder="Enter user name" name="name" class="form-control name">
                                        <div class="validation-message" data-field="name"></div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <input title="Enter email" type="text" placeholder="Enter Email" name="email" class="form-control email">
                                        <div class="validation-message" data-field="email"></div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <input title="Enter Password" type="password" placeholder="Enter Password" name="password" class="form-control password">
                                        <div class="validation-message" data-field="password"></div>
                                    </div>
                                    <div class='form-group col-sm-3'>
                                        <select class="form-control gander" name="gender">
                                            <option value="">Choose Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <div class="validation-message" data-field="gender"></div>
                                    </div>
                                    <div class='form-group col-sm-3'>
                                        <select title="Choose Group" class="form-control group_id" placeholder="Choose Group" name="group_id">
                                            <option value="">Choose Group</option>
                                        </select>
                                        <div class="validation-message" data-field="group_id"></div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button class="btn btn-success backEndUserSubmit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel">
        <div class="content-box">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>
                                <i class="fa fa-download"></i> Backend User Manager</h5>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        <div class="ibox-content">
                            <table id="backendUserTable" class="display" cellspacing="0" width="100%">
                                <thead id="backendUserHeader">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Change Role</th>
                                        <th>Group</th>
                                        <th>Gender</th>
                                        <th>Status IO</th>
                                        <th> Block $ Unblock</th>
                                        <th class="none">Created</th>
                                        <th class="none">Updated</th>
                                    </tr>
                                </thead>
                                <tbody id="backendUserData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript" >
    // $(document).ready(function (){
    var tableBackendUser = $('#backendUserTable')
    // Handle click on "Expand All" button
    $('#btn-show-all-children').on('click', function () {
        // Expand row details
        table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    });

    // Handle click on "Collapse All" button
    $('#btn-hide-all-children').on('click', function () {
        // Collapse row details
        table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
    });

    // $('#subscriberSearch').on("click", function(evt){
    // evt.preventDefault();
    // if ($.fn.DataTable.isDataTable("#backendUserTable")) {
    //     $("#backendUserTable").DataTable().destroy();
    // }
    //     getFitterSubscriber();
    // });

    groups()
    function groups(){
        var url = BASE_URL + "/users/groups"
        $.get(url, function(data) {
            group = '';
            for(g in data.groups){
                if(data.groups[g].group_name != '<?php echo  MERCHANT ?>' && data.groups[g].group_name != '<?php echo UNDERWRITER ?>' &&  data.groups[g].group_name != '<?php echo SUBSCRIBER ?>'){
                    group +='<option value="'+data.groups[g].id+'">'+data.groups[g].group_name +'</option>';
                }
                //console.log(product)
            }
            $('.group_id').append(group)
        });
     }

    $('body').on("click",'.backEndUserSubmit',function(evt){
        evt.preventDefault();
        createBackendUser(evt);
    });

    $('.dispatch').change(function() {
       const type = $('.dispatch').val()
        if(type == 'all') { 
           hideFormInputAndReset('.all');
           $('.status').show();
           
        }else {
           hideFormInputAndReset('.all');		
        }
   });

   function hideFormInputAndReset(mclass) {
	    $(mclass).hide().find('input,select').val('');
    }


    $("body").on("change",'.group_id',function (e) {
        const id = $(e.target).data('id')
        const group_id = $(this).find(":selected").val()
        console.log('Group Id :...'+ group_id);
        console.log('User Id :...'+ id);
        $('#backendUserHeader').hide();
        $("#backendUserData").html('<tr><td align="center" colspan="9"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/users/changeUserRole"

        if(IsEmptyOrUndefined(group_id)){
            message ="The user role was successful change";
            const info = {title:"Error", message:message, type:error, position:topfullwidth};
            toastNotification(info);
            if ($.fn.DataTable.isDataTable("#backendUserTable")) {
                $("#backendUserTable").DataTable().destroy();
            }
            loadBackEndUsers();return;
        }
       
        var obj = {
            id: id,
            group_id:group_id
        };
        
        console.log(obj);
        $.post(url, obj).done(function (data) {
            try {
                var title;
                var message;
                var type;
                if (data.value == "success") {
                    title = "Success";
                    message ="The user role was successful change";
                    type =success;
                    const info = {title:title, message:message, type:type, position:topfullwidth};
                    toastNotification(info);
                    if ($.fn.DataTable.isDataTable("#backendUserTable")) {
                        $("#backendUserTable").DataTable().destroy();
                    }
                    loadBackEndUsers();
                }else{
                    const info = {title:"Error", message:data.value, type:error, position:topfullwidth};
                    toastNotification(info);
                    if ($.fn.DataTable.isDataTable("#backendUserTable")) {
                        $("#backendUserTable").DataTable().destroy();
                    }
                    loadBackEndUsers();
                }
            } catch (e) {
                console.log('Exp error: ', e)
            }
        });
        
    });

    $("body").on("change",'.onoffswitch-checkbox',function (e) {
        const id = $(e.target).data('id')
        const biz = $(e.target).data('biz');
        $('#backendUserHeader').hide();
        $("#backendUserData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/users/blockAndUnblockUser"
        var status;
        console.log('Check:...'+ this.checked);
        if (this.checked) {
            status = 1;
        } else {
            status = 0;
        }
        var obj = {
            id: id,
            name:biz,
            status:status
        };
        
        console.log(obj);
        $.post(url, obj).done(function (data) {
            try {
                var title;
                var message;
                var type;
                if (data.value == "success") {

                    if(status == 1){
                        title = "Unblock";
                        message ="The user has been unblock successfully";
                        type =success;
                        
                    }else{
                        title = "Block";
                        message ="The user has been block successfully";
                        type = warning;
                        console.log(title,message);
                    }
                    const info = {title:title, message:message, type:type, position:topfullwidth};
                    toastNotification(info);
                    if ($.fn.DataTable.isDataTable("#backendUserTable")) {
                        $("#backendUserTable").DataTable().destroy();
                    }
                    loadBackEndUsers();
                }else{
                    const info = {title:"Blocking Error", message:data.value, type:error, position:topfullwidth};
                    toastNotification(info);
                    if ($.fn.DataTable.isDataTable("#backendUserTable")) {
                        $("#backendUserTable").DataTable().destroy();
                    }
                    loadBackEndUsers();
                }
            } catch (e) {
                console.log('Exp error: ', e)
            }
        });
        
    });


    function backendUserTable(data){
         if(!IsEmptyOrUndefined(data)){
            $('#backendUserHeader').show();
           var backendUserData =''; 
                      for(x in data){
                        backendUserData+='<tr>'+
                                '<td>'+data[x].user_name+'</td>'+
                                '<td>'+data[x].email+'</td>'+
                                '<td>'+(data[x].is_system == 1 ?
                                '' : 
                                 '<div class="form-group col-sm-3">'+
                                        '<select title="Choose Group" class="form-control group_id" data-id="'+data[x].id+'" placeholder="Choose Group" name="group_id">'+
                                            '<option value="">Choose Group</option>'+
                                        '</select>'+
                                        '<div class="validation-message" data-field="group_id"></div>'+
                                    '</div>')+
                                '</td>'+
                                '<td>'+data[x].group_name+'</td>'+
                                '<td>'+(data[x].gander|| 'N/A')+'</td>'+
                                '<td>'+(data[x].status == 1
                                    ?'<span class="pull-right claimed label-primary">Active</span>'
                                    :'<span class="pull-right claimed label-danger">Block</span>')+'</td>'+
                                '<td>'+
                                    '<div class="switch">'+
                                        '<div class="onoffswitch">'+
                                            '<input type="checkbox" '+(data[x].status == 1 ? 'checked' : '')+' data-id="'+data[x].id+'"  data-biz="'+data[x].user_name+'" class="onoffswitch-checkbox" id="status_'+data[x].id+'">'+
                                            '<label class="onoffswitch-label" for="status_'+data[x].id+'">'+
                                                '<span class="onoffswitch-inner"></span>'+
                                                '<span class="onoffswitch-switch"></span>'+
                                            '</label>'+
                                        '</div>'+
                                    '</div>'+
                                '</td>'+ 
                                '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                                '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                            '</tr>';
                      }
                      $("#backendUserData").html(backendUserData);
                      tableBackendUser.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#backendUserData").html('<tr><td align="center" colspan="9">NO RECORD FOUND</td></tr>');
                  }

     }

    function createBackendUser(evt) {
        $('.backEndUserSubmit').html("Processing...").attr('disabled', true);
        $('#backendUserHeader').hide();
        var obj;
            obj = $(evt.currentTarget).parents('form.backEndUserForm').serialize();
        var url = BASE_URL + "/users/processor"
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {

                        title = "Created";
                        message ="The user has been created successfully";
                        type = success;
                        const info = {title:title, message:message, type:type, position:topfullwidth};
                        
                        $('.backEndUserSubmit').html("Created").attr('disabled', true);
                        if ($.fn.DataTable.isDataTable("#backendUserTable")) {
                            $("#backendUserTable").DataTable().destroy();
                        }
                        loadBackEndUsers();
                        toastNotification(info);
                    } else {
                        $('.backEndUserSubmit').html("Submit").attr('disabled', false);
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
    loadBackEndUsers();
    function loadBackEndUsers(){
        $('#backendUserHeader').hide();
         var url = BASE_URL + "/users/loadBackEndUsers"
         $("#backendUserData").html('<tr><td align="center" colspan="9"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.users);
             backendUserTable(data.users)
         });
     }

    // function getFitterSubscriber() {
    //     $('#subscriberSearch').html("Searching...").attr('disabled', true);
    //     var obj = $('#subscriberSearchForm').serialize();
    //     console.log(obj);
    //     $("#offlineUserData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
    //     var url = BASE_URL + "/reports/fitterReportSubscriber"
    //     $.post(url, obj).done(function(data) {
    //         try{
    //             if (data.value == "success") {
    //                 $('#subscriberSearch').html("Search").attr('disabled', false);
    //                 subscriberTable(data.reportSubscriptions)

    //             } else {
    //                 $('#subscriberSearch').html("Search").attr('disabled', false);
    //                 $('.validation-message').html('');
    //                 $('.validation-message').each(function() {
    //                     for (var key in data) {
    //                         if ($(this).attr('data-field') == key) {
    //                             $(this).html(data[key]);
    //                         }
    //                     }
    //                 });
    //             }
    //          } catch(e){
    //              console.log('Exp error: ',e)
    //          }
    //     });
    // }

    
    // }
    </script>