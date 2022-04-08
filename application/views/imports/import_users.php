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
		<div class="panel subscriberFitter">
			<div class="content-box">
			<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Upload Subscriber KYC Details In .XLSX Format</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form method="post" id="csvfileForm" enctype="multipart/form-data">
												<div class="row">
													 <div class="form-group col-sm-6">
                                                     <div style="position:relative;">
                                                        <a class='btn btn-primary' href='javascript:;'>
                                                        <i class="fa fa-file-text"></i> Choose .XLSX File...
                                                            <input type="file" class="input-file users_csv_file"  name="file"  required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  onchange='$("#upload-file-info").html($(this).val());'>
                                                        </a>
                                                        &nbsp;
                                                        <span class='label label-info' id="upload-file-info"></span>
                                                    </div>
													
													  <div class="validation-message" data-field="users_csv_file"></div>
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
									<i class="fa fa-download"></i> User Subscribers KYC .XLSX Excel File Format Example
								</h5>
								<div class="ibox-tools">
								</div>
							</div>
							<div class="ibox-content">
								<table id="bulkTransferTable" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>name</th>
											<th>birth_date</th>
											<th>mobile_number</th>
											<th>gender</th>
											<th>address</th>
											<th>next_of_kin</th>
											<th>next_of_kin_phone</th>
										</tr>
									</thead>
									<tbody id="subscriptionData">
										<tr>
											<td>Ibrahim Olanrewaju</td>
											<td>1993-23-12</td>
											<td>2348094227050</td>
											<td>male</td>
											<td>3, Vincent Street Lane Lagos Island</td>
											<td>Sahula Ashipa</td>
											<td>2348123400000</td>
										</tr>
										<tr>
											<td>David Olakunle</td>
											<td>1983-20-02</td>
											<td>2348000000000</td>
											<td>male</td>
											<td>21, John Street Lagos Island</td>
											<td>Peter Olakunle</td>
											<td>2348332200000</td>
										</tr>
										<tr>
											<td>Margret Ogbo</td>
											<td>1996-02-02</td>
											<td>2349000000000</td>
											<td>female</td>
											<td>43, Olanrewaju Street Lagos Island</td>
											<td>Joe Ogbo</td>
											<td>2348000001111</td>
										</tr>
										<tr>
											<td>Adenike Alawumi</td>
											<td>1979-20-12</td>
											<td>2348100000000</td>
											<td>female</td>
											<td>43, Olanrewaju Street Lagos Island</td>
											<td>Tunde Alawumi</td>
											<td>2348000002222</td>
										</tr>
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

	 var tableBulkTransfer = $('#bulkTransferTable')
    tableBulkTransfer.DataTable({
                         'destroy':true,
                         'responsive': true
                        });
			
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
		var form = new FormData();
		const mfile = $('.users_csv_file')[0].files[0];

		form.append('file', mfile)
		
		console.log(form)
		$.ajax({
				url:BASE_URL + "/import/importCsv",
				method: 'POST',
				data: form,
				contentType:false,
				cache:false,
				processData:false,
				beforeSend:function(){
					$('.submit').html('<button class="btn btn-success"  disabled ><i class="fa fa-spinner fa-spin"></i> Uploading...</button>');
				},
				success:function(data){
					// $(this)[0].reset()
					console.log(data.value)
					if(data.value == 'success'){
						$('.users_csv_file').val("")
						$('.subscriber_response').show()
						$('.cunt').html(data.results)
						$('.submit').html('<button class="btn btn-success"  disabled ><i class="fa fa-upload"></i> Upload Done</button>');
					console.log('Success: ',data);
					}else{
						$('.subscriber_response').show()
						$('.updated-subscribers_count').css({'font-size':'1.5em','color':'red'});
						$('.cunt').html(data.value +' : Updated row '+data.results)
						$('.updated-subscribers_title').html('Subscriber Does Not Update Successful')
						$('.submit').html('<button class="btn btn-danger"  disabled ><i class="fa fa-upload"></i> Upload Error</button>');
					}
				},
				error:function(data){
					console.log('Error: ',data);
					$('.submit').html('<button class="btn btn-success"  id="upload_file" ><i class="fa fa-upload"></i> Upload</button>');
				}
			})

	});
 		
	</script>
