<div class="breadcrumb">
	<a href="">Settings</a> 
	<a href="">Client Manager</a>
</div>
<div class="content with-top-banner">
    <div class="panel">
        <div class="content-box">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>
                                <i class="fa fa-download"></i> Client User Manager</h5>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        <div class="ibox-content">
                            <table id="clientUserTable" class="display" cellspacing="0" width="100%">
                                <thead id="clientUserHeader">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Group</th>
                                        <th>Status IO</th>
                                        <th> Block $ Unblock</th>
                                        <th>Config Settings</th>
                                        <th>Created</th>
                                        <th class="none">Updated</th>
                                    </tr>
                                </thead>
                                <tbody id="clientUserData">
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
    var tableClientUser = $('#clientUserTable')
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


    $("body").on("change",'.onoffswitch-checkbox',function (e) {
        const id = $(e.target).data('id')
        const biz = $(e.target).data('biz');
        $('#clientUserHeader').hide();
        $("#clientUserData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
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
                    if ($.fn.DataTable.isDataTable("#clientUserTable")) {
                        $("#clientUserTable").DataTable().destroy();
                    }
                    loadClientUsers();
                }else{
                    const info = {title:"Blocking Error", message:"An error uncall", type:error, position:topfullwidth};
                    toastNotification(info);
                }
            } catch (e) {
                console.log('Exp error: ', e)
            }
        });
        
    });


    function clientUserTable(data){
         if(!IsEmptyOrUndefined(data)){
            $('#clientUserHeader').show();
           var clientUserData =''; 
                      for(x in data){
                        clientUserData+='<tr>'+
                                '<td>'+data[x].user_name+'</td>'+
                                '<td>'+data[x].email+'</td>'+
                                '<td>'+data[x].group_name+'</td>'+
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
                                '<td> <a href="'+BASE_URL+'/UserConfigSetting/config/'+data[x].id+'" class="pull-right claimed label-primary">Click</a></td>'+
                                '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                                '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                            '</tr>';
                      }
                      $("#clientUserData").html(clientUserData);
                      tableClientUser.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#clientUserData").html('<tr><td align="center" colspan="8">NO RECORD FOUND</td></tr>');
                  }

     }

    loadClientUsers();
    function loadClientUsers(){
        $('#clientUserHeader').hide();
         var url = BASE_URL + "/users/loadClientUsers"
         $("#clientUserData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.users);
             clientUserTable(data.users)
         });
     }


    </script>