
<style>
		.small-font{
			font-size:18px !important;
		}
		.bullet-point {
			font-size: 20px !important;
			font-family: initial;
			font-weight: inherit;
		}
		.figure{
			font-size:23px;
		}
		.card,.normal{
			justify-content: normal !important;
		}
		.all,.subscriberFitter, .productFitter,
		.subscriberFitter,.productFitter,.date_range,.kyc,.state,.status{
			display:none;
		} 

		.card_extra{
			font-size: 24px !important;
    		/* font-weight: 800 !important; */
		}
		.label-success {
			background-color: #5cb85c;
		}
	</style>
	<div class="content with-top-banner">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title"></div>
			<select class="select-rounded pull-right">
				<!-- <option>Today</option>
				<option>7 Days</option>
				<option>14 Days</option>
				<option>Last Month</option> -->
			</select>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#947002" class="fa fa-rub"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer_decimals" data-from="0" data-to="<?= $ipoint->ipoints ?>"><?= $ipoint->ipoints ?></span>
							</div>
							<div class="card-subtitle">iPoints Sold, </div>
						</div>
					</div>

					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#947002" class="fa fa-ngn">&#x20A6;</i>
						<div class="clear">
							<div class="card-title">
								<span class="timer_decimals" data-from="0" data-to="<?= $ipoint->amount ?>"><?= $ipoint->amount ?></span>
							</div>
							<div class="card-subtitle">iPoints Sold In Naira </div>
						</div>
					</div>

					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #449d44" class="fa fa-users"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= $subscribers?>">
									<?= $subscribers?>
								</span>
							</div>
							<div class="card-subtitle">Subscriber Count</div>
						</div>
					</div>
					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#337ab7" class="fa fa-user"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= $merchants?>"><?= $merchants?></span>
							</div>
							<div class="card-subtitle">Merchant Count</div>
						</div>
					</div>
					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#ccc" class="fa fa-pencil-square-o"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?=$underwriters?>"><?=$underwriters?></span>
							</div>
							<div class="card-subtitle">Underwriter</div>
						</div>
					</div>
					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>

				</div>
				
			</div>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#947002" class="fa fa-money"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer_decimals" data-from="0" data-to="<?= (is_null($iSaving_charges))? 0 :$iSaving_charges->balance ?>">
								<?= (is_null($iSaving_charges))? 0 :$iSaving_charges->balance ?>
								</span>
							</div>
							<div class="card-subtitle">iSavings Charge</div>
						</div>
					</div>

					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #449d44" class="fa fa-money"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer_decimals" data-from="0" data-to="<?= (is_null($iInsurance_charges))?0 :$iInsurance_charges->balance ?>">
									<!-- 72 -->
									<?= (is_null($iInsurance_charges))? 0 :$iInsurance_charges->balance ?>
								</span>
							</div>
							<div class="card-subtitle">iInsurance Charge</div>
						</div>
					</div>
					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#337ab7" class="fa fa-ngn">&#x20A6;</i>
						<div class="clear">
							<div class="card-title">
								<span class="timer_decimals" data-from="0" data-to="<?= (is_null($sales_commission->balance))? 0: $sales_commission->balance?>">
									<?= (is_null($sales_commission->balance))? 0: $sales_commission->balance?>
								</span>
							</div>
							<div class="card-subtitle">iPoint Sales Commission</div>
						</div>
					</div>
					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#337ab7" class="fa fa-money"></i>
						<div class="clear">
							<div title="Underwriter Product Sales Commission" class="card-title">
								<h4>Underwriter Commission</h4>
								<span class="timer_decimals" data-from="0" data-to="<?= (is_null($underwriter_commission))?'0:00':$underwriter_commission->balance ?>">
								<?= (is_null($underwriter_commission))?'0:00':$underwriter_commission->balance ?>
								</span>
							</div>
							<div class="card-subtitle">Total <strong><?= (is_null($underwriter_commission))?'0:00':$underwriter_commission->users ?></strong> Underwriters</div>
						</div>
					</div>
					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div tittle="Click to view annual reports" class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#ccc" class="fa fa-money"></i>
						<div class="clear">
							<div title="System Annual Fee For Subscribers" class="card-title">
								<h4>(&#8381;) Annual Subscribtion</h4>
								<span class="timer_decimals" data-from="0" data-to="<?= $UserSubscription->paid_sumup?? 0 ?>"><?= $UserSubscription->paid_sumup?? 0 ?></span>
							</div>
							<div class="card-subtitle">Total <strong><?= $UserSubscription->users?></strong> Users Paid</div>
						</div>
					</div>
					<div class="card-menu">
						<ul>
							<li><a href="<?php echo base_url('fundamental/annual_fee')?>">click</a></li>
							<!-- <li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li> -->
						</ul>
					</div>

				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #ec971f;" class="fa fa-credit-card-alt"></i>
						<div class="clear">
							<div class="card-title">
								<h4>(&#8381;) iSavings Cash-Out</h4>
								<span class="timer_decimals" data-from="0" data-to="0">0</span>
							</div>
							<!-- <div class="card-title">
								<h4>Equivalent in (&#8358;) Naira</h4>
								<span class="timer" data-from="0" data-to="<?=$all_iSavings_naira?>"><?=$all_iSavings_naira?></span>
							</div> -->
							<div class="card-subtitle">Total <strong>0</strong> Users Cash-Out</div>
						</div>
						<!-- <div class="card-subtitle">
							<a  href="#" class="btn btn-warning">View CashOut</a>
						</div> -->
					</div>
					<div class="card-menu">
						<ul>
							<li><a href="<?php echo base_url('withdraw')?>">click</a></li>
							<!-- <li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li> -->
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-4">
					<div class="content-header">
						<i class="fa fa-newspaper-o"></i>
						<div class="content-header-title">Users Chart</div>
					</div>
					<div class="content-box">
						<div class="donut-chart-wrapper">
							<canvas width="120" height="120" id="donut-chart"></canvas>
							<div class="donut-chart-label">
								<div class="donut-chart-value">
									<?= number_format((int)$users, 0, '.', ',')?>
								</div>
								<div class="donut-chart-title">Total users</div>
							</div>
						</div>
						<div class="donut-chart-legend">
							<div class="legend-list">
								<div class="legend-bullet green"></div>
								<div class="legend-title">Subscibers</div>
							</div>
							<div class="legend-list">
								<div class="legend-bullet red"></div>
								<div class="legend-title">Merchants</div>
							</div>
							<div class="legend-list">
								<div class="legend-bullet yellow"></div>
								<div class="legend-title">Underwriter</div>
							</div>
							<!-- <div class="legend-list">
								<div class="legend-bullet blue"></div>
								<div class="legend-title">Agents</div>
							</div> -->
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="content-header">
						<i class="fa fa-newspaper-o"></i>
						<div class="content-header-title">Basic Products/Services</div>
					</div>
					<div class="content-box">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Product Name</th>
										<th class="text-center">Service Provider</th>
										<th class="text-center">Price</th>
										<th class="text-center">Allowable Tenure</th>
										<!-- <th class="text-center">Date</th> -->
									</tr>
								</thead>
								<tbody>
								<?php foreach ($products as $p){ ?>
									<tr>
										<td class="nowrap"><?= $p->product_name?></td>
										<td class="text-center"><?= (!empty($p->provider_name))? $p->provider_name :'N/A'?></td>
										<td class="text-center">&#x20A6; <?=number_format($p->price, 2, '.', ',') ?></td>
										<td class="text-center"><?= $p->allowable_tenure ?></td>
										<!-- <td class="text-center"> <?= date_format(date_create($p->updated_at),"Y-m-d"); ?></td> -->
									</tr>
								<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- visible and hide content -->
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Quick Analytics</div>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-4 card-wrapper">
					<div class="card card_extra">
						<i style="color: #449d44;" class="fa fa-users"></i>
						<div class="card-title">
							<span>Subscribers</span>
						</div>
					</div>
					<div class="card normal">
						<div class="clear">
							<div class=" bullet-point">
								New Users &nbsp;&nbsp; <span class="timer figure" data-from="0" data-to="<?= $new_users?>"><?= $new_users?></span>
							</div>
							<div class="bullet-point">
							Incompleted KYC &nbsp;&nbsp;<span class="timer figure" data-from="0" data-to="<?= $incompleted_kyc?>"><?= $incompleted_kyc?></span>
							</div>
							<div class="bullet-point">
								Total Users &nbsp;&nbsp; <span class="timer figure" data-from="0" data-to="<?= $subscribers;?>"><?= $subscribers;?></span>
							</div>
							<div class="card-subtitle">
									<botton  class="btn btn-success subscriberBotton"><i class="fa fa-eye-slash sub small-font"> Filter</i></botton>
							</div>
						</div>
					</div>

					<div class="card-menu">
						
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card card_extra">
						<i style="color: #337ab7" class="fa fa-briefcase"></i>
						<div class="card-title">
							<span>User's Wallet</span>
						</div>
					</div>
					<div class="card normal">
						<div class="clear">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th class="text-left"><h4>Wallets</h4></th>
											<th class="text-right"><h4>Users</h4></th>
											<th class="text-right"><h4>Balances</h4></th>
										</tr>
									</thead>
									<tbody id="groupUserByWalletData">
										
									</tbody>
								</table>
							</div>
							<div class="card-subtitle">
									<a href="<?php echo base_url('fundamental/overdraft')?>" class="btn btn-primary "><i class="fa fa-link small-font"> Click</i></a>
							</div>
						</div>
					</div>

					<div class="card-menu">
						
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card card_extra">
						<i style="color:#f0ad4e" class="fa fa-tags"></i>
						<div class="card-title">
							<span>Products</span>
						</div>
					</div>
					<div class="card normal">
						<div class="clear">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											
											<th class="text-left"><h4>Product</h4></th>
											<th class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th class="text-right"><h4>Users</h4></th>
										</tr>
									</thead>
									<tbody id="numOfSubProUserData">
									
									</tbody>
								</table>
							</div>
							<div class="card-subtitle">
								<botton  class="btn btn-warning productBotton"><i class="fa fa-eye-slash pro small-font"> Filter</i></botton>
							</div>
						</div>
					</div>

					<div class="card-menu">
					
					</div>
				</div>
			</div>
		</div>
		<div class="panel subscriberFitter">
			<h5>&nbsp;&nbsp;&nbsp;&nbsp; Subscribers Manager</h5>
			<div class="content-box">
				<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-search-plus" aria-hidden="true"></i> Filter Search</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form id="userForm">
												<div class="row">
													<div class="form-group col-sm-3">
														<input title="Enter Users Email or Phone" type="text" placeholder="Users Phone Or Email (Optional)" name="customerName"  class="form-control customerName">
														<input type="hidden" name="customerId" class="customerId">
														<div class="validation-message" data-field="customerId"></div>
													</div>
													<div class='form-group col-sm-3'>
														<select class="form-control fitter" name="fitter">
															<option value="">Choose User's Filter </option>
															<option value="date_range">Date Range</option>
															<option value="kyc">KYC</option>
															<option value="state">State</option>
															<option value="status">Status</option>
														</select>
														<div class="validation-message" data-field="fitter"></div> 
													</div>
													<div class='col-md-3 date_range all'>
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
													<div class='col-md-3 date_range all'>
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
													<div class='form-group kyc all col-sm-3'>
														<select class="form-control clear kyc_status" name="kyc_status">
															<option value="">KYC Status</option>
															<option value="complete">Completed KYC</option>
															<option value="none-complete">Uncompleted KYC</option>
														</select>
														<div class="validation-message" data-field="kyc_status"></div>
													</div>
											
													<div class='form-group state all col-sm-3'>
														<input placeholder="Start typing to select State" type='text' class="form-control clear state" />
														<input type="hidden" name="states"  id="states" />
														<div class="validation-message" data-field="states"></div> 
													</div>
													<div class='form-group status all col-sm-3'>
														<select class="form-control clear status" name="status">
															<option value="">Choose Active User</option>
															<option value="1">Active User</option>
															<option value="0">None Active User</option>
														</select>
														<div class="validation-message" data-field="status"></div> 
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<!-- <input type="hidden" name="hidden_start_date" value="<?php echo $start_date ?>" class="hidden_start_date">
														<input type="hidden" name="hidden_end_date" value="<?php echo $end_date ?>" class="hidden_end_date"> -->
														<button class="btn btn-success"  id="userSearch">Search</button>
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
										<h5><i class="fa fa-download" aria-hidden="true"></i>New Subscribers List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<a  href="<?php echo base_url('dashboard/userExportReport')?>" class="btn btn-success userExportExcel url">EXPORT TO EXCEL (.XLS)</a>
										<hr>
										<table id="userTable" class="display" cellspacing="0" width="100%">
											<thead id="userHeader">
												<tr>
													<th>Name</th>
													<th>Gender</th>
													<th>Contact</th>
													<th>Group</th>
													<th>Birthday</th>
													<th>Created Date</th>
													<th class="none">Residence Address</th>
													<th class="none">States</th>
													<th class="none">Local Goverment</th>
													<th class="none">Email</th>
													<th class="none">Next Of Kin</th>
													<th class="none">Next Of Kin Phone</th>
												</tr>
											</thead>
											<tbody id="userData">
											</tbody>
										</table>
										<!-- Paginate -->
										<div id='pagination'></div>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel productFitter">
			<h5>&nbsp;&nbsp;&nbsp;&nbsp;Insurance Product Subscriptions Manager</h5>
			<div class="content-box">
				<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-search-plus" aria-hidden="true"></i> Filter Search</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form id="productForm">
												<div class="row">
													<div class="form-group col-sm-3">
														<input title="Enter Users Email or Phone" type="text" placeholder="Users Phone Or Email (Optional)" name="customerName" class="form-control customerName">
														<input type="hidden" name="customerId" class="customerId">
														<div class="validation-message" data-field="customerId"></div>
													</div>
													<div class='form-group col-sm-3'>
														<select class="form-control product" name="product">
															<option value="">Choose Product/Services</option>
														</select>
														<div class="validation-message" data-field="product"></div> 
													</div>
													<div class='form-group col-sm-3'>
														<select class="form-control period" name="period">
															<option value="">Choose Cover Period</option>
														</select>
														<div class="validation-message" data-field="period"></div> 
													</div>
													<div class='form-group col-sm-3'>
														<select class="form-control filter logic_option" name="fitter">
															<option value="">Choose product's filter (Optional)</option>
															<option value="=">EqualTo (=)</option>
															<option value=">=">GreaterThan And EqualTo (>=)</option>
															<option value="<=">LessThan And EqualTo (<=)</option>
															<option value="!=">NotEqual (!=)</option>
														</select>
													<div class="validation-message" data-field="fitter"></div> 
												</div>
												<div class="form-group col-sm-3">
													<input title="Enter Value" type="text" placeholder="Enter Value (Optional)" name="value" class="form-control value">
													<div class="validation-message" data-field="value"></div>
												</div>
												<div class='col-md-3'>
													<div class="form-group">
														<div class='input-group date' id='start_services'>
															<input type='text' placeholder="Start Date" name="pro_start_date"  class="form-control pro_start_date" />
															<span class="input-group-addon">
																<span class="glyphicon glyphicon-calendar"></span>
															</span>
														</div>
													</div>
												</div>
												<div class='col-md-3'>
													<div class="form-group">
														<div class='input-group date' id='end_services'>
															<input type='text'  placeholder="End Date" name="pro_end_date"  class="form-control pro_end_date" />
															<span class="input-group-addon">
																<span class="glyphicon glyphicon-calendar"></span>
															</span>
														</div>
													</div>
												</div>
												<div class="form-group col-sm-3">
													<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
													<button class="btn btn-success"  id="productSearch">Search</button>
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
										<h5><i class="fa fa-download" aria-hidden="true"></i> Product/Service List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<a  href="<?php echo base_url('dashboard/serviesProductExportReport')?>" class="btn btn-success productExportExcel url">EXPORT TO EXCEL (.XLS)</a>
										<hr>
										<table id="productTable" class="display" cellspacing="0" width="100%">
											<thead id="productHeader">
												<tr>
													<th>Name</th>
													<th>Phone Number</th>
													<th>Product/Services</th>
													<th>Value</th>
													<th>Cover Period</th>
													<th>Status</th>
													<th>Purchase Date</th>
													<th>Expiring Date</th>
													<th class="none">Batch ID</th>
												</tr>
											</thead>
											<tbody id="productData">
											</tbody>
										</table>
										<!-- Paginate -->
										<div id='pagination-product'></div>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
	</div>
