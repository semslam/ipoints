    <div class="content with-top-banner">
    <div class="panel">
			    <div class="content-box">
			        <div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Create Product Services</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form class="createProductForm">
												<div class="row">
													<div class="form-group col-sm-3">
														<input title="Enter Product Name" type="text" placeholder="Enter Product Name" name="product_name" class="form-control product_name">
														<div class="validation-message" data-field="product_name"></div>
													</div>
                                                    <div class="form-group col-sm-3">
														<input title="Enter Price" type="number" placeholder="&#x20A6; Price" name="price" min="1" class="form-control price">
														<div class="validation-message" data-field="price"></div>
													</div>
                                                    <div class='form-group col-sm-3'>
														<select title="Enter Insurance Product" class="form-control insurance_prod" placeholder="Insurance Product" name="insurance_prod">
															<option value="">Is Insurance Product ?</option>
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<div class="validation-message" data-field="insurance_prod"></div> 
													</div>
                                                    <div class='form-group col-sm-3'>
														<select title="base_product" class="form-control base_product" placeholder="Enter Base Product" name="base_product">
															<option value="">Is Base Product ?</option>
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<div class="validation-message" data-field="base_product"></div>
													</div>
                                                    <div class='form-group col-sm-3'>
														<select title="Is Group Product" class="form-control group_prod" placeholder="Enter Is Group Product" name="group_prod">
															<option value="">Is Group Product ?</option>
															<option value="1">Yes</option>
															<option value="0">No</option>
														</select>
														<div class="validation-message" data-field="group_prod"></div> 
													</div>
													<div class='form-group col-sm-3'>
														<select title="product charge commission" class="form-control charge_commission" placeholder="Enter Product Charge Commission" name="charge_commission">
															<option value="">Charge Commission ?</option>
														</select>
														<div class="validation-message" data-field="charge_commission"></div>
													</div>
													<div class='form-group col-sm-3'>
														<select title="product commission wallet" class="form-control commission_wallet" placeholder="Enter Product Commission Wallet" name="commission_wallet">
															<option value="">Commission Wallet ?</option>
														</select>
														<div class="validation-message" data-field="commission_wallet"></div>
													</div>
                                                    <div class="form-group col-sm-3">
														<input title="Payment Allowable Tenure" type="number" placeholder="Enter Allowable Tenure" name="allowable_tenure" min="1" max="12" class="form-control allowable_tenure">
														<div class="validation-message" data-field="allowable_tenure"></div>
													</div>
                                                    <div class="form-group col-sm-3">
														<input title="Enter Provider Email" type="text" placeholder="Provider Email" name="customerName" class="form-control customerName">
														<input type="hidden" name="customerId" class="customerId">
														<div class="validation-message" data-field="customerId"></div>
													</div>
													<div class="form-group col-sm-12">
														<textarea title="Product description" name="description"  placeholder="Enter Product description" class="form-control description" rows="6" cols="100"></textarea>
														<div class="validation-message" data-field="description"></div>
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success productSubmit">Submit</button>
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
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>
					<i class="fa fa-search-plus" aria-hidden="true"></i> Filter Search</h5>
				<div class="ibox-tools">
				</div>
			</div>
			<div class="ibox-content">
				<form id="fitterProductForm">
					<div class="row">
						<div class="form-group col-sm-3">
							<input title="Enter Provider Email" type="text" placeholder="Provider Email" name="customerName" class="form-control customerName">
							<input type="hidden" name="customerId" class="customerId">
							<div class="validation-message" data-field="customerId"></div>
						</div>
						<div class='form-group col-sm-3'>
							<select class="form-control product" name="product">
								<option value="">Choose Product Name</option>
							</select>
							<div class="validation-message" data-field="product"></div>
						</div>
						<div class='form-group col-sm-3'>
							<select title="Is Insurance Product" class="form-control insurance_prod" placeholder="Enter Is Group Product" name="insurance_prod">
								<option value="">Is Insurance Product ?</option>
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
							<div class="validation-message" data-field="insurance_prod"></div>
						</div>
						<div class="form-group col-sm-3">
							<input title="Payment Allowable Tenure" type="number" placeholder="Enter Allowable Tenure" name="allowable_tenure" min="1"
							    max="12" class="form-control allowable_tenure">
							<div class="validation-message" data-field="allowable_tenure"></div>
						</div>
						<div class="form-group col-sm-3">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
							<button class="btn btn-success" id="fitterProductSearch">Search</button>
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
										<h5><i class="fa fa-download"></i> Product Services List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<table id="productTable" class="display" cellspacing="0" width="100%">
											<thead id="productHeader">
												<tr>
													<th>Product Name</th>
													<th>Price</th>
													<th>Provider Name</th>
													<th>Insurance Product</th>
													<th>Base Product</th>
													<th>Allowable Tenure</th>
													<th>Charge Commission</th>
													<th>Action</th>
													<th class="none">Product Group</th>
													<th class="none">Created Date</th>
													<th class="none">Updated Date</th>
												</tr>
											</thead>
											<tbody id="productData">
											</tbody>
										</table>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
	</div>