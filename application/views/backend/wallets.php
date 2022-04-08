<div class="content with-top-banner">
    <div class="panel">
			    <div class="content-box">
			        <div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Create Wallet</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form class="createWalletForm">
												<div class="row">
													<div class="form-group col-sm-3">
														<input title="Enter Wallet Name" type="text" placeholder="Enter Wallet Name" name="wallet" class="form-control wallet">
														<div class="validation-message" data-field="wallet"></div>
													</div>
                                                    <div class='form-group col-sm-3'>
														<select class="form-control product" name="product">
															<option value="">Choose Product Name</option>
														</select>
														<div class="validation-message" data-field="product"></div> 
													</div>
													<div class='form-group col-sm-3'>
														<select class="form-control service_group" name="service_group">
															<option value="">Choose Service Group Name</option>
														</select>
														<div class="validation-message" data-field="service_group"></div> 
													</div>
                                                    <div class='form-group col-sm-3'>
														<select title="Enter Type" class="form-control type" placeholder="Type" name="type">
															<option value="">Type</option>
															<option value="product">Product</option>
															<option value="system">System</option>
															<option value="savings">Savings</option>
															<option value="commission">Commission</option>
														</select>
														<div class="validation-message" data-field="type"></div> 
													</div>
                                                    <div class='form-group col-sm-3'>
														<select title="Can User Inherit" class="form-control can_user_inherit" placeholder="Enter Can User Inherit" name="can_user_inherit">
															<option value="">Can User Inherit ?</option>
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<div class="validation-message" data-field="can_user_inherit"></div> 
													</div>
                                                    <div class='form-group col-sm-3'>
														<select title="Can Topup" class="form-control can_topup" placeholder="Enter Can Topup" name="can_topup">
															<option value="">Can Topup ?</option>
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<div class="validation-message" data-field="can_topup"></div> 
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success createWalletSearch">Submit</button>
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
										<h5><i class="fa fa-download"></i> Wallet List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<table id="walletTable" class="display" cellspacing="0" width="100%">
											<thead id="walletHeader">
												<tr>
													<th>Wallet Name</th>
													<th>Type</th>
													<th>User Inherit</th>
													<th>Can Topup</th>
													<th>Product</th>
													<th>Service Group Name</th>
													<th>Action</th>
													<th class="none">Created Date</th>
													<th class="none">Updated Date</th>
												</tr>
											</thead>
											<tbody id="walletData">
											</tbody>
										</table>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
	</div>