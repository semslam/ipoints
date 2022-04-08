
	<style>
		
		.all,.date_range,.status,.reference,.settled,#date_approved, #date_settled{
			display:none;
		}



.offline-pay-unique{
	text-align: center;
    font-size: medium;
    font-weight: 700;
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

		.claimed{
                font-family: 'Open Sans';
                font-size: 11px;
                font-weight: 600;
                padding: 3px 8px;
                text-shadow: none;
				color: #ffffff;
            }

			.label-danger {
				background-color: #ed5565;
			}
			.label-primary {
				background-color: #1ab394;
			}
			.label-success {
				background-color: #1c84c6;
			}
			.label-warning {
				background-color: #f8ac59;
			}

	/* The Modal (background) */
		.modal {
			display: none; /* Hidden by default */
			position: fixed; /* Stay in place */
			z-index: 1; /* Sit on top */
			padding-top: 100px; /* Location of the box */
			left: 0;
			top: 0;
			width: 100%; /* Full width */
			height: 100%; /* Full height */
			overflow: auto; /* Enable scroll if needed */
			background-color: rgb(0,0,0); /* Fallback color */
			background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
		}

		.m_bigger{
			width: 100%;
		}

		.m_big{
			width: 80%;
		}

		.m_small{
			width: 40%;
		}

		/* Modal Content */
		.modal-content {
			position: relative;
			background-color: #fefefe;
			margin: auto;
			padding: 0;
			border: 1px solid #888;
			box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
			-webkit-animation-name: animatetop;
			-webkit-animation-duration: 0.4s;
			animation-name: animatetop;
			animation-duration: 0.4s
		}

		/* Add Animation */
		@-webkit-keyframes animatetop {
			from {top:-300px; opacity:0} 
			to {top:0; opacity:1}
		}

		@keyframes animatetop {
			from {top:-300px; opacity:0}
			to {top:0; opacity:1}
		}

		/* The Close Button */
		.close {
			color: white;
			float: right;
			font-size: 28px;
			font-weight: bold;
			margin-right: -44%;
		}

		.close:hover,
		.close:focus {
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}

		.modal-header {
			padding: 2px 16px;
			background-color: #5cb85c;
			color: white;
		}

		.modal-body {padding: 2px 16px;}

		.modal-footer {
			padding: 2px 16px;
			background-color: #5cb85c;
			color: white;
		}

		.col-center-block {
			float: none;
			display: block;
			margin-left: auto;
			margin-right: auto;
		}		
	</style>
	<script>
	const iPoint_unit = <?= $ipoint_unit ?> 
	</script>
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
				<form id="offlineUserForm">
					<div class="row">
						<!-- <div class="form-group col-sm-3">
														<input title="Enter Users Email or Phone" type="text" placeholder="Users Phone Or Email (Optional)" name="customerName" id="customerName" class="form-control ">
														<input type="hidden" name="customerId" id="customerId">
														<div class="validation-message" data-field="customerId"></div>
													</div> -->
						<div class='form-group col-sm-4'>
							<select class="form-control fitter" name="fitter">
								<option value="">Choose Filter</option>
								<option value="date_range">Date Range</option>
								<option value="reference">Reference</option>
								<!-- <option value="wallets">Wallet</option> -->
								<!-- <option value="products">Product</option> -->
								<option value="status-s">Purchase Status</option>
								<option value="processor">Payment Processor</option>
							</select>
							<div class="validation-message" data-field="fitter"></div>
						</div>
						<div class='col-md-4 date_range all'>
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
						<div class='col-md-4 date_range all'>
							<div class="form-group">
								<div class='input-group date' id='end'>
									<input type='text' placeholder="End Date" name="end_date" class="form-control clear end_date" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<div class="validation-message" data-field="end_date"></div>
							</div>
						</div>
						<div class='form-group reference all col-sm-4'>
							<input placeholder="Enter Payment Reference" type='text' name="reference" id="reference" class="form-control clear reference"
							/>
							<div class="validation-message" data-field="reference"></div>
						</div>
						<div class="form-group col-sm-4 all wallets">
							<select class="form-control wallet" name="wallet">
								<option value="">Choose Wallet</option>
							</select>
							<div class="validation-message" data-field="wallet"></div>
						</div>
						<div class="form-group col-sm-4 all products">
							<select class="form-control product" name="product">
								<option value="">Choose Product</option>
							</select>
							<div class="validation-message" data-field="product"></div>
						</div>
						<div class='form-group col-sm-4 all status-s'>
							<select class="form-control purchase_status" name="purchase_status">
								<option value="">Choose Purchase Status</option>
								<option value="1">Approved</option>
								<option value="0">Unapproved</option>
							</select>
							<div class="validation-message" data-field="purchase_status"></div>
						</div>
						<div class='form-group col-sm-4 all processor'>
							<select class="form-control payment_processor" name="payment_processor">
								<option value="">Choose Payment Processor</option>
							</select>
							<div class="validation-message" data-field="payment_processor"></div>
						</div>

						<div class="form-group col-sm-3">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
							<button class="btn btn-success" id="offlineUserSearch">Search</button>

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
										<h5><i class="fa fa-download" aria-hidden="true"></i> Offline Payment Manager</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<!-- <a  href="<?php echo site_url('dashboard/userExportReport')?>" class="btn btn-success userExportExcel url">EXPORT TO EXCEL (.XLS)</a> -->
										<hr>
										<table id="offlineUserTable" class="display" cellspacing="0" width="100%">
											<thead id="offlineUserHeader">
												<tr>
													<th>Feference</th>
													<th>Quantity</th>
													<th>Amount</th>
													<th>Status</th>
													<th>Payment Processor</th>
													<th>Wallet</th>
													<!-- <th>Product</th> -->
													<th class="none">Created Date</th>
													<th class="none">Updated Date</th>
													<th class="none">Request_by</th>
													<th class="none">Description</th>
												</tr>
											</thead>
											<tbody id="offlineUserData">
											</tbody>
										</table>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
	</div>
