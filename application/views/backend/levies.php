<div class="content with-top-banner">
    <div class="panel">
			    <div class="content-box">
			        <div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Create Levies</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form class="createLeviesForm">
												<div class="row">
													<div class="form-group col-sm-6">
														<input title="Enter Levies Name Without Space E.g levies_example" type="text" placeholder="Enter Levies Name Without Space E.g levies_example" name="name" class="form-control name">
														<div class="validation-message" data-field="name"></div>
													</div>
                                                    <div class="form-group col-sm-6">
														<input title="Enter Levies Value" type="number" placeholder="Enter Levies Value" name="value"  class="form-control value">
														<div class="validation-message" data-field="value"></div>
													</div>
													<div class="form-group col-sm-12">
														<textarea title="Levies description" name="description"  placeholder="Enter Levies description" class="form-control description" rows="6" cols="100"></textarea>
														<div class="validation-message" data-field="description"></div>
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success createLeviesSubmit">Submit</button>
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
										<h5><i class="fa fa-download"></i>Levies List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<table id="leviesTable" class="display" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th>Name</th>
													<th>Value</th>
													<th >Created Date</th>
													<th>Action</th>
													<th class="none">Description</th>
												</tr>
											</thead>
											<tbody id="leviesData">
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

    var tableLevies = $('#leviesTable')
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


    $('body').on("click",'.createLeviesSubmit',function(evt){
        evt.preventDefault();
        createLevies(evt);
    });

    function leviesTable(data){
         if(!IsEmptyOrUndefined(data)){
             
           var leviesData =''; 
                      for(x in data){
                        leviesData+='<tr>'+
                                '<td>'+data[x].name+'</td>'+
                                '<td>'+data[x].value+'</td>'+
                                '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                                '<td> <a href="#" class="btn btn-primary slam-modal" data-modal="modal-'+data[x].id+'" >'+
                                '<i class="fa fa-pencil"></i> '+
                                    ' Edit'+
                                '</a>'+
                                '<div id="modal-'+data[x].id+'" class="modal">'+
                                        '<div class="modal-content m_small">'+
                                            '<div class="modal-header">'+
                                            '<h4 class="text-left">'+data[x].name+'</h4>'+
                                            '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                        '</div>'+
                                    '<div class="modal-body">'+
                                        '<form class="createLeviesForm">'+
                                            '<div class="row" style="margin: 50px;">'+
                                                '<div class="form-group 12">'+
                                                    '<label for="Name" class="col-sm-4 col-form-label">Levies Name</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input title="Enter livies name with space " type="text" placeholder="Enter levies name with space" value="'+data[x].name+'" name="name" class="form-control name">'+
                                                        '<div class="validation-message" data-field="name"></div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group 12">'+
                                                    '<label for="Wallet" class="col-sm-4 col-form-label">Levies Value</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input title="Enter Levies Value" type="number" placeholder="Enter Levies Name" value="'+data[x].value+'" name="value" class="form-control value">'+
                                                        '<div class="validation-message" data-field="value"></div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group 12">'+
                                                    '<div class="col-sm-6">'+
                                                        '<textarea title="Levies description" name="description"  placeholder="Enter Levies description" class="form-control description" rows="6" cols="50">"'+(data[x].description || '')+'"</textarea>'+
                                                        '<div class="validation-message" data-field="description"></div>'+ 
                                                    '</div>'+
                                                '</div>'+
                                                
                                                '<div class="form-group row">'+
                                                '<label for="" class="col-sm-4 col-form-label"></label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">'+
                                                        '<input type="hidden" name="id" class="id" value="'+data[x].id+'">'+
                                                        '<button class="btn btn-success createLeviesSubmit">Submit</button>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>'+
                                '</td>'+
                                '<td> '+(data[x].description ||'No Desacription')+'</td>'+
                            '</tr>';
                      }
                      $("#leviesData").html(leviesData);
                      tableLevies.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#leviesData").html('<tr><td align="center" colspan="6">NO RECORD FOUND</td></tr>');
                  }

     }

    function createLevies(evt) {
        $('.createLeviesSubmit').html("Processing...").attr('disabled', true);
        var obj;
            obj = $(evt.currentTarget).parents('form.createLeviesForm').serialize();
    
        var url = BASE_URL + "/levies/processLevies"
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {

                        title = "Created";
                        message ="The Levies has been saved successfully";
                        type = success;
                        const info = {title:title, message:message, type:type, position:topfullwidth};
                        
                        $('.createLeviesSubmit').html("Saved").attr('disabled', true);
                        if ($.fn.DataTable.isDataTable("#leviesTable")) {
                            $("#leviesTable").DataTable().destroy();
                        }
                        loadLevies();
                        toastNotification(info);
                    } else {
                        $('.createLeviesSubmit').html("Submit").attr('disabled', false);
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
    loadLevies();
    function loadLevies(){
         var url = BASE_URL + "/levies/loadLevies"
         $("#leviesData").html('<tr><td align="center" colspan="6"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.levies);
             leviesTable(data.levies)
         });
     }

    </script>