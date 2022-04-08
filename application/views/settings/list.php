<div class="content with-top-banner">
    <div class="panel">
        <div class="content-box">

            <div class="row">
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>
                                <i class="fa fa-download"></i> System Configuration Setting List</h5>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        <div class="ibox-content">
                            <table id="settingTable" class="display" cellspacing="0" width="100%">
                                <thead id="settingHeader">
                                    <tr>
                                        <th>Label</th>
                                        <th>Key</th>
                                        <th>Type</th>
                                        <th>Author By</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <th class="none">Value</th>
                                        <th class="none">Description</th>
                                    </tr>
                                </thead>
                                <tbody id="settingData">
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

   var tableSetting = $('#settingTable')
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


function settingsTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#settingHeader').show();
           var settingData =''; 
                      for(x in data){
                        settingData+='<tr>'+
                                '<td>'+(data[x].meta_label ||'N/A')+'</td>'+
                                '<td>'+data[x].meta_key+'</td>'+
                                '<td>'+data[x].meta_type+'</td>'+
                                '<td>'+(data[x].author_name ||'N/A')+'</td>'+
                                '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                                '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                                '<td> '+data[x].meta_value  +'</td>'+
                                '<td>'+(data[x].meta_description || 'No Description')+'</td>'+
                            '</tr>';
                      }
                      $("#settingData").html(settingData);
                      tableSetting.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#settingData").html('<tr><td align="center" colspan="8">NO RECORD FOUND</td></tr>');
                  }

     } 

     settingsManager();
    function settingsManager(){
         var url = BASE_URL + "/settings/listSettings"
         $('#settingHeader').hide();
         $("#settingData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.settings);
             settingsTable(data.settings)
         });
     }  
</script>