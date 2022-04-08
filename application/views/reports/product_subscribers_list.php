<style>
	.label-success {
     background-color: #5cb85c;
}

</style>
<div class="panel">
			<div class="content-box">
				<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-search-plus" aria-hidden="true"></i> Fitter Search</h5>
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
														<select class="form-control fitter logic_option" name="fitter">
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
										<h5><i class="fa fa-download" aria-hidden="true"></i> Product/Service Subscription Queue</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<a  href="<?php echo site_url('dashboard/serviesProductExportReport')?>" class="btn btn-success productExportExcel url">EXPORT TO EXCEL (.XLS)</a>
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