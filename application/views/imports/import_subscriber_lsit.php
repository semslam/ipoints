<style>
        .input-file{
            position:absolute;
            z-index:2;
            top:0;
            left:0;
            filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
            opacity:0;
            background-color:transparent;
            color:transparent;
        }
		.updated-subscribers_count{
			font-size: 4em;
			font-weight: 800;
			margin:auto;
			text-align: center;
			color: green;
			animation: blink 1s linear infinite;
		}

		.subscriber_response{
			display: none;
		}

		.updated-subscribers_title{
			font-size: 2em;
			font-weight: 600;
			font-family: -webkit-body;
		}
		@keyframes blink{
		0%{opacity: 0;}
		50%{opacity: .5;}
		100%{opacity: 1;}
		}
    </style>
	<div class="content with-top-banner">
		<div class="panel">
			<div class="content-box">
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h4>
									<i class="fa fa-exclamation-triangle text-warning"></i>
									Note!!!
								</h4>
								<h5> The import only process expired user's subscription, it accept a phone numbers in this format <strong>mobile_number</strong>, it can only process completed user KYC and the wallet should have sufficient balance to process the subscription.</h5>
								<div class="ibox-tools">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="content-box">
			<div class="row">
						<div class="col-lg-12"> 
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Expaired Product/Service Subscription In .XLSX Format, Only <strong>mobile_number</strong> excepted</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form method="post" id="csvfileForm" action="" enctype="multipart/form-data">
												<div class="row">
														<div class='form-group col-sm-3'>
                                                            <input placeholder="Enter Batch ID" type='text' name="batch_id"  id="batch_id"  class="form-control  batch_id" />
                                                            <div class="validation-message" data-field="batch_id"></div> 
                                                        </div>
                                                        <div class='form-group col-sm-3'>
                                                            <input placeholder="Enter Product Amount" type='text' name="amount"  id="amount"  class="form-control  amount" />
                                                            <div class="validation-message" data-field="reference"></div> 
                                                        </div>
                                                        <div class="form-group col-sm-3">
                                                            <select class="form-control product" name="product">
                                                                <option value="">Choose Product</option>
                                                            </select>
                                                            <div class="validation-message" data-field="product"></div>
													    </div>
                                                        <div class='col-md-3'>
                                                            <div class="form-group">
                                                                <div class='input-group date' id='start'>
                                                                    <input type='text' placeholder="Start Date" name="start_date" class="form-control clear start_date" />
                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                                <div class="validation-message" data-field="start_date"></div>
                                                            </div>
                                                        </div>
                                                        <div class='col-md-3'>
                                                            <div class="form-group">
                                                                <div class='input-group date' id='end'>
                                                                    <input type='text'  placeholder="End Date" name="end_date"  class="form-control clear end_date" />
                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                                <div class="validation-message" data-field="end_date"></div>
                                                            </div>
                                                        </div>
													 <div class="form-group col-sm-6">
                                                        <div style="position:relative;">
                                                            <a class='btn btn-primary' href='javascript:;'>
                                                            <i class="fa fa-file-text"></i> Choose .XLSX File...
                                                                <input type="file" class="input-file users_product_csv_file"  name="file"  required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  onchange='$("#upload-file-info").html($(this).val());'>
                                                            </a>
                                                            &nbsp;
                                                            <span class='label label-info' id="upload-file-info"></span>
                                                        </div>
													
													    <div class="validation-message" data-field="users_product_csv_file"></div>
												    </div>
													<div class="form-group col-sm-6 subscriber_response">
														<span class="updated-subscribers_count cunt"></span><br>
														<span class="updated-subscribers_title">Subscribers Successful Updated</span>
													</div>
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
													<div class="form-group submit col-sm-3">
														<button class="btn btn-success"  id="upload_file"><i class="fa fa-upload"></i> Upload</button>
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
								<i class="fa fa-download"></i> Subscription Result List</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<table id="subscriptionTable" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>N/S</th>
										<th>Mobile Number</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="subscriptionData">
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

	var tableSubscription = $('#subscriptionTable')
			
		// Handle click on "Expand All" button
		$('#btn-show-all-children').on('click', function(){
			// Expand row details
			table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
		});

		// Handle click on "Collapse All" button
		$('#btn-hide-all-children').on('click', function(){
			// Collapse row details
			table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
		});

	$('#upload_file').on("click", function(e){
		e.preventDefault()
		const mfile = $('.users_product_csv_file')[0].files[0];
		const mBatch_id = $('.batch_id').val();
		const mAmount = $('#amount').val();
		const mProduct = $('.product').val();
		const mStart_date = $('.start_date').val();
		const mEnd_date = $('.end_date').val();
		var form = new FormData(this);

		form.append('file', mfile);
		form.append('batch_id', mBatch_id);
		form.append('amount', mAmount);
		form.append('product', mProduct);
		form.append('start_date', mStart_date);
		form.append('end_date', mEnd_date);  
		
		$.ajax({
                url:BASE_URL + "/import/eligSubscriberImportCsv",
				method: 'POST',
				data: form,
				contentType:false,
				cache:false,
				processData:false,
				beforeSend:function(){
					$('.submit').html('<button class="btn btn-success"  disabled ><i class="fa fa-spinner fa-spin"></i> Uploading...</button>');
					$("#subscriptionData").html('<tr><td align="center" colspan="5"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
				},
				success:function(data){
					// $(this)[0].reset()
					//console.log(data.value)
					if(data.value == 'success'){
						if ($.fn.DataTable.isDataTable("#subscriptionTable")) {
							$("#subscriptionTable").DataTable().destroy();
						}
					
						subTable(data.subscribers);
						$('.users_csv_file').val("")
						$('.subscriber_response').show()
						$('.submit').html('<button class="btn btn-success"  disabled ><i class="fa fa-upload"></i> Upload Done</button>');
					//console.log('Success: ',data);
					}else{
						$('.subscriber_response').show()
						$('.updated-subscribers_count').css({'font-size':'1.5em','color':'red'});
						$('.cunt').html(data.value +' : Updated row '+data.results)
						$('.updated-subscribers_title').html('Subscriber Does Not Update Successful')
						$('.submit').html('<button class="btn btn-danger"  disabled ><i class="fa fa-upload"></i> Upload Error</button>');
						subTable(data.subscribers);
					}
				},
				error:function(data){
					console.log('Error: ',data);
					$('.submit').html('<button class="btn btn-success"  id="upload_file" ><i class="fa fa-upload"></i> Upload</button>');
				}
            })
            

             $('#start').datetimepicker({format: 'YYYY-MM-DD'});
            $('#end').datetimepicker({
                useCurrent: false, //Important! See issue #1075
                format: 'YYYY-MM-DD'
            });
            $("#start").on("dp.change", function (e) {
                $('#end').data("DateTimePicker").minDate(e.date);
            });
            $("#end").on("dp.change", function (e) {
                $('#start').data("DateTimePicker").maxDate(e.date);
			});

	

	function subTable(data){
        if(!IsEmptyOrUndefined(data)){
            
		  var subscriptionData ='';
		  count = 1 
                for(x in data){
					subscriptionData+='<tr>'+
							'<td>'+ count++ +'</td>'+
							'<td>'+data[x].mobile_number +'</td>'+
							'<td>'+(data[x].status == "Successful"
                            ?'<span class="pull-right claimed label-primary">Successful</span>'
                            :'<span class="pull-right claimed label-danger">Failed</span>')+'</td>'+
                   		'</tr>';     
                }
                     $("#subscriptionData").html(subscriptionData);
                     tableSubscription.DataTable({
                         'destroy':true,
                         'responsive': true
                        });
                 }else{
                     $("#subscriptionData").html('<tr><td align="center" colspan="5">NO RECORD WAS PROCESSED</td></tr>');
                 }
	}
	
	function IsEmptyOrUndefined(MyVar){ 
            return (
            (typeof MyVar== 'undefined')        //undefined
                        ||
            (MyVar == null)                     //null
                        ||
            (MyVar == false)  //!MyVariable     //false
                        ||
            (MyVar.length == 0)                 //empty
                        ||
            (MyVar == "")                       //empty
                        ||
            (!/[^\s]/.test(MyVar))              //empty
                        ||
            (/^\s*$/.test(MyVar))                //empty
            );
       }

});
 		
	</script>
