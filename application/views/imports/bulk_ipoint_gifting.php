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


		.blinking{
    	animation:blinkingText 0.8s infinite;
		}
		@keyframes blinkingText{
			0%{     color: #000;    }
			49%{    color: transparent; }
			50%{    color: transparent; }
			99%{    color:transparent;  }
			100%{   color: #000;    }
		}
    </style>
	<div class="content with-top-banner">
		<div class="panel">
			<div class="content-box">
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5><i class="fa fa-exclamation-triangle text-warning"></i> Note!!!  Bulk gifting can only process <b>100,000</b> records at once</h5>
								<div class="ibox-tools">
								</div>
							</div>
							<!-- <div class="ibox-title">
								<div class='form-group col-sm-8'>
									<p><i class="fa fa-question fa-lg" aria-hidden="true"></i>&nbsp;&nbsp; Please click on the selector to choose your gifting designated wallet ID &nbsp;&nbsp;<i class="fa fa-long-arrow-right fa-lg blinking" aria-hidden="true"></i></p> 
								</div>
								<div class="form-group col-sm-4">
									<select class="form-control wallet_" name="wallet">
										<option value="">Choose Wallet ID</option>
									</select>
								</div>
								<div class="ibox-tools">
								</div>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel subscriberFitter">
			<div class="content-box">
			<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Work in process <strong>( WIP )</strong> Bulk Ipoints Gifting In .XLSX Format</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form method="post" id="bulkGiftingForm" action="" enctype="multipart/form-data">
												<div class="row">
                                                        
                                                        <!-- <div class='form-group col-sm-3'>
                                                            <input placeholder="Enter request Id in 7 characters length eg. IBRAHIM" type='text' title="Enter request ID in 7 characters length  eg. IBRAHIM" name="request"  id="request"  class="form-control  request" />
                                                            <div class="validation-message requ" data-field="request"></div> 
                                                        </div> -->
														<div class='form-group col-sm-3'>
															<select class="form-control wallet" name='wallet' >
															<option value="">Choose Designate Wallet</option>
															</select>
															<div class="validation-message wall" data-field="wallet"></div>
                                                        </div>
														
                                                    </select>
                                                        
													 <div class="form-group col-sm-6">
                                                        <div style="position:relative;">
                                                            <a class='btn btn-primary' href='javascript:;'>
                                                            <i class="fa fa-file-text"></i> Choose .XLSX File...
                                                                <input type="file" class="input-file bulk_transfer_xlsx_file"  name="file"  required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  onchange='$("#upload-file-info").html($(this).val());'>
                                                            </a>
                                                            &nbsp;
                                                            <span class='label label-info' id="upload-file-info"></span>
                                                        </div>
													
													    <div class="validation-message" data-field="bulk_transfer_xlsx_file"></div>
												    </div>
													<div class="form-group col-sm-6 subscriber_response">
														<span class="updated-subscribers_count cunt"></span> <b>Records</b><br>
														<span class="updated-subscribers_title">Bulk gifting was successfully uploaded</span>
													</div>
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
													<div class="form-group submit col-sm-3">
														<input type="submit" class="btn btn-success" value="Upload">
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
						<!-- <div class="ibox-title">
								<div class='form-group col-sm-8'>
									<p><i class="fa fa-question fa-lg" aria-hidden="true"></i>&nbsp;&nbsp; Choose your gifting designated wallet key form the table list  &nbsp;&nbsp;<i class="fa fa-long-arrow-right fa-lg blinking" aria-hidden="true"></i></p> 
								</div>
								<div class="form-group col-sm-4">
									<table id="wallet" class="display nowrap" style="width:100%">
											<thead>
												<tr>
													<th>Wallet</th>
													<th>Key</th>
												</tr>
											</thead>
											<tbody class="wallet_">
											</tbody>
									</table>
								</div>
								<div class="ibox-tools">
								</div>
						</div> -->
						<div class="ibox-title">
							<h5>
								<i class="fa fa-download"></i> Bulk ipoint Gifting .XLSX Excel File Format Example
							</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<table id="bulkTransferTable" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>recipient_phone</th>
										<th>qty</th>
										<!-- <th>wallet_id</th> -->
									</tr>
								</thead>
								<tbody id="subscriptionData">
                                    <tr>
                                        <td>2347000000000</td>
                                        <td>20</td>
                                        <!-- <td>9</td> -->
                                    </tr>
                                    <tr>
                                        <td>2348000000000</td>
                                        <td>20</td>
                                        <!-- <td>9</td> -->
                                    </tr>
                                    <tr>
                                        <td>2349000000000</td>
                                        <td>20</td>
                                        <!-- <td>9</td> -->
                                    </tr>
                                    <tr>
                                        <td>2348100000000</td>
                                        <td>20</td>
                                        <!-- <td>9</td> -->
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
    // var wallet = $('#wallet')
    tableBulkTransfer.DataTable({
						'destroy':true,
						'responsive': true,
						"searching": false, //desable search bar
					    "bFilter": false,//desable search bar
                        'bInfo': false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
                        'paging': false,//Dont want paging 
                        'bPaginate': false,//Dont want paging 
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

	$('#bulkGiftingForm').on("submit", function(e){
		console.log('**********************', this);
		e.preventDefault()
		const mfile = $('.bulk_transfer_xlsx_file')[0].files[0];
		const mWallet = $('.wallet').val();
		var form = new FormData(this);
		$('.validation-message').html('');
		// if(mRequest.length < 7 || mRequest.length > 7){
		// 	$('.requ').html('Request id can\'t be greater than or less than 7 in length');return;
		// }
		if(!mWallet > 0){
			$('.wall').html('Wallet can\'t be empty');return;
		}
		form.append('file', mfile);
		form.append('wallet', mWallet);
		
		$.ajax({
                url:BASE_URL + "/import/bullk_gifting_process",
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
					//console.log(data.value)
					if(data.value == 'success'){
						$('.bulk_transfer_xlsx_file').val("")
						$('.subscriber_response').show()
						$('.cunt').html(data.results)
						$('.submit').html('<button class="btn btn-success"  disabled ><i class="fa fa-upload"></i> Upload Done</button>');
			
					}else{
						$('.submit').html('<button class="btn btn-danger"  disabled ><i class="fa fa-upload"></i> Upload</button>');
                        $('.validation-message').html('');
                        $('.validation-message').each(function() {
                            for (var key in data) {
                                if ($(this).attr('data-field') == key) {
                                    $(this).html(data[key]);
                                }
                            }
                        });
						$('.subscriber_response').show()
						$('.updated-subscribers_count').css({'font-size':'1.5em','color':'red'});
						$('.cunt').html(data.value +' : Updated row '+data.results)
						$('.updated-subscribers_title').html('Bulk point transfer wasn\'t successful.')
						
					}
				},
				error:function(data){
					$('.submit').html('<button class="btn btn-danger"  disabled ><i class="fa fa-upload"></i> Upload</button>');
                        $('.validation-message').html('');
                        $('.validation-message').each(function() {
                            for (var key in data) {
                                if ($(this).attr('data-field') == key) {
									console.log(data[key]);
                                    $(this).html(data[key]);
                                }
                            }
                        });
				}
            })

});


walletOption()
    function walletOption(){
		var url = BASE_URL + "/offline/walletList"
        $.get(url, function(data) {
           let wallets = data.wallets;
            console.log(wallets)
				for(x in wallets){
                    $(".wallet").append('<option value="'+wallets[x].id+'">'+wallets[x].name+'</option>');
                }
        });
     }

// function walletTable(data){
//          if(!IsEmptyOrUndefined(data)){
//            var walletData =''; 
//                       for(x in data){
// 						if(data[x].name == '<?= I_POINT?>'){
// 							continue;
// 						}
//                         walletData+='<tr>'+
//                                 '<td>'+(data[x].name ||'N/A')+'</td>'+
//                                 '<td>'+(data[x].id ||'N/A')+'</td>'+
// 							'</tr>';
							
// 							console.log(walletData);
//                       }
//                       $(".wallet_").html(walletData);
//                       wallet.DataTable({
// 						  'destroy':true,
// 						  'searching': false, //desable search bar
// 						  'bFilter': false,//desable search bar
// 						   'bInfo': false, //Dont display info e.g. "Showing 1 to 4 of 4 entries"
// 						  'paging': false,//Dont want paging 
// 						  "scrollY":        "80px",
//        					  "scrollCollapse": true,
						  
//                          });
//                   }else{
//                       $(".wallet_").html('<tr><td align="center" colspan="2">NO RECORD FOUND</td></tr>');
//                   }

//      }


//      function wallteOption(){
// 		var url = BASE_URL + "/offline/walletList"
// 		$(".wallet_").html('<tr><td align="center" colspan="2"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
//         $.get(url, function(data) {
// 			console.log(data.wallets)
// 				walletTable(data.wallets)
//         });
//      }


 		
	</script>
