<div class="content with-top-banner">
		<div class="panel">
			<div class="content-box">
			<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>
					<i class="fa fa-search-plus" aria-hidden="true"></i> Filter Search</h5>
				<div class="ibox-tools">
				</div>
			</div>
			<div class="ibox-content">
				<form id="fitterOverdraftForm">
					<div class="row">
						<div class="form-group col-sm-3">
							<input title="Enter User Email Or Phone" type="text" placeholder="User Email Or Phone" name="customerName" class="form-control customerName">
							<input type="hidden" name="customerId" class="customerId">
							<div class="validation-message" data-field="customerId"></div>
						</div>
						
						<div class='form-group col-sm-3'>
							<select title="Can Overdraft" class="form-control can_overdraft" placeholder="Enter Can Overdraft" name="can_overdraft">
								<option value="">Can Overdraft ?</option>
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
							<div class="validation-message" data-field="can_overdraft"></div>
						</div>
						<div class="form-group col-sm-3">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
							<button class="btn btn-success" id="overdraftSearch">Search</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
	
				<div class="row">
					<div class="col-md-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-download"></i>Merchants Overdraft Wallet Balance Manager</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<table id="overdraftTable" class="display" cellspacing="0" width="100%">
											<thead id="overdraftHeader">
												<tr>
													<th>User Name</th>
													<th>Wallet</th>
													<th>Balance</th>
													<th>Can overdraft</th>
													<th>Overdraft Limit</th>
													<th>Action</th>
													<th class="none">Group</th>
													<th class="none">Updated Date</th>
												</tr>
											</thead>
											<tbody id="overdraftData">
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

   var tableOverdraft = $('#overdraftTable')
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


	$('body').on("click",'.overdraftSubmit', function(evt){
		evt.preventDefault();
		setOverdraft(evt);
	});

		$('#overdraftSearch').on("click", function (evt) {
			evt.preventDefault();
			if ($.fn.DataTable.isDataTable("#overdraftTable")) {
				$("#overdraftTable").DataTable().destroy();
			}
			getFitterOverdraft()
		});
	

	function setOverdraft(evt) {
        $('.overdraftSubmit').html("Processing...").attr('disabled', true);
        $('#overdraftHeader').hide();
		var obj;
            obj = $(evt.currentTarget).parents('form.overdraftForm').serialize();
    
        console.log(obj);
        var url = BASE_URL + "/fundamental/setOverdraft"
       
        swal({   
            title: "Please check this info",   
            text: "Note!!! Saved this info can not be irreversible",
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
                            text: "Overdraft successfully saved",
                            type: "success"
                        })
						$('.overdraftSubmit').html("Saved").attr('disabled', true);
						if ($.fn.DataTable.isDataTable("#overdraftTable")) {
							$("#overdraftTable").DataTable().destroy();
						}
						overdraftManager();
                    } else {
                        $('.overdraftSubmit').html("Submit").attr('disabled', false);
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


function overdraftTable(data){
         if(!IsEmptyOrUndefined(data)){
            $('#overdraftHeader').show();
           var overdraftData =''; 
                      for(x in data){
                        overdraftData+='<tr>'+
                                '<td>'+(data[x].user_name ||'N/A')+'</td>'+
                                '<td>'+data[x].wallet_name+'</td>'+
								'<td>'+data[x].balance+'</td>'+
								'<td>'+(data[x].can_overdraft == 1
                                        ?'<span class="pull-right claimed label-primary">Yes</span>'
										:'<span class="pull-right claimed label-danger">No</span>')+'</td>'+
								'<td>'+(data[x].overdraft_limit ||'0')+'</td>'+
								'<td> <a href="#" class="btn btn-primary slam-modal" data-modal="modal-'+data[x].id+'" >'+
                                '<i class="fa fa-pencil"></i> '+
                                    ' Approve'+
                                '</a>'+
                                '<div id="modal-'+data[x].id+'" class="modal">'+
                                        '<div class="modal-content m_small">'+
                                            '<div class="modal-header">'+
                                            '<h4 class="text-left">User '+data[x].user_name+' ::  Wallet '+data[x].wallet_name+'</h4>'+
                                            '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                        '</div>'+
                                    '<div class="modal-body">'+
                                        '<form class="overdraftForm">'+
                                            '<div class="row" style="margin: 50px;">'+
                                                '<div class="form-group row">'+
                                                    '<label for="Wallet" class="col-sm-4 col-form-label">Overdraft Limit</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input title="Enter Overdraft Limit" type="number" placeholder="Enter Overdraft Limit" value="'+data[x].overdraft_limit+'" name="overdraft_limit" class="form-control overdraft_limit">'+
                                                        '<div class="validation-message" data-field="overdraft_limit"></div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                    '<label for="CanOverdraft" class="col-sm-4 col-form-label">Can Overdraft</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<select class="form-control can_overdraft" name="can_overdraft">'+
                                                            '<option value="'+data[x].can_overdraft+'">'+(data[x].can_overdraft == 1 ? 'Yes' : 'No')+'</option>'+
                                                            '<option value="1">Yes</option>'+
                                                            '<option value="0">No</option>'+
                                                        '</select>'+
                                                        '<div class="validation-message" data-field="can_overdraft"></div>'+ 
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                '<label for="" class="col-sm-4 col-form-label"></label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input type="hidden" name="id" class="id" value="'+data[x].id+'">'+
                                                        '<button class="btn btn-success overdraftSubmit">Submit</button>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>'+
								'</td>'+
								'<td> '+data[x].group_name +'</td>'+
								'<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                            '</tr>';
                      }
                      $("#overdraftData").html(overdraftData);
                      tableOverdraft.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#overdraftData").html('<tr><td align="center" colspan="8">NO RECORD FOUND</td></tr>');
                  }

	 } 
	 

	 function getFitterOverdraft() {
       $('#overdraftSearch').html("Searching...").attr('disabled', true);
       $('#overdraftHeader').hide();
       var obj = $('#fitterOverdraftForm').serialize();
       console.log(obj);
       $("#overdraftData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
       var url = BASE_URL + "/fundamental/overdraftFitterManager"
       $.post(url, obj).done(function(data) {
           try{
               if (data.value == "success") {
                   $('#overdraftSearch').html("Search").attr('disabled', false);
                   overdraftTable(data.userBalances)
               } else {
                   $('#overdraftSearch').html("Search").attr('disabled', false);
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

     overdraftManager();
    function overdraftManager(){
         var url = BASE_URL + "/fundamental/overdraftManager"
         $('#overdraftHeader').hide();
         $("#overdraftData").html('<tr><td align="center" colspan="8"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.userBalances);
             overdraftTable(data.userBalances)
         });
     }  
</script>