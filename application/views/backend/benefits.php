<div class="content with-top-banner">
	<div class="panel">
		<div class="content-box">
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>
								<i class="fa fa-bars"></i> Product Benefit Form</h5>
							<div class="ibox-tools">
							</div>
						</div>
						<div class="ibox-content">
							<form class="productBenefitForm">
								<div class="row">
									<div class='form-group col-sm-3'>
										<select class="form-control product" name="product">
											<option value="">Choose Product Name</option>
										</select>
										<div class="validation-message" data-field="product"></div>
									</div>
									<div class="form-group col-sm-3">
										<input title="Enter Amount" type="number" placeholder="&#x20A6; Amount" name="amount" min="1" class="form-control amount">
										<div class="validation-message" data-field="amount"></div>
									</div>
									<div class="form-group col-sm-3">
										<input title="Enter Name" type="text" placeholder="Enter Name" name="name" class="form-control name">
										<div class="validation-message" data-field="name"></div>
									</div>
									<div class='form-group col-sm-3'>
										<select title="Enter Status" class="form-control pb-status" placeholder="Enter Status" name="pb-status">
											<option value="">Choose Status</option>
											<option value="1">Active</option>
											<option value="0">Inactive</option>
										</select>
										<div class="validation-message" data-field="pb-status"></div>
									</div>
									<div class="form-group col-sm-12">
										<textarea title="Product note" name="note" placeholder="Enter Benefit note" class="form-control note" rows="6" cols="100"></textarea>
										<div class="validation-message" data-field="note"></div>
									</div>
									<div class="form-group col-sm-3">
										<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
										<button class="btn btn-success productBenefitSubmit">Submit</button>
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
							<form id="fitterBenefitForm">
								<div class="row">
									<div class='form-group col-sm-3'>
										<select class="form-control product" name="product">
											<option value="">Choose Product Name</option>
										</select>
										<div class="validation-message" data-field="product"></div>
									</div>
									<div class="form-group col-sm-3">
										<input title="Enter Amount" type="number" placeholder="Enter Amount" name="amount" min="1"
										    class="form-control sc-amount">
										<div class="validation-message" data-field="amount"></div>
									</div>
									<div class='form-group col-sm-3'>
										<select title="Enter Status" class="form-control pb-status" placeholder="Enter Status" name="pb-status">
											<option value="">Choose Status</option>
											<option value="1">Active</option>
											<option value="0">Inactive</option>
										</select>
										<div class="validation-message" data-field="pb-status"></div>
									</div>
									
									<div class="form-group col-sm-3">
										<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
										<button class="btn btn-success" id="fitterBenefitSearch">Search</button>
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
							<table id="benefitTable" class="display" cellspacing="0" width="100%">
								<thead id="benefitHeader">
									<tr>
										<th>Product Name</th>
										<th>Amount</th>
										<th>Name</th>
										<th>Status</th>
										<th>Created Date</th>
										<th>Updated Date</th>
										<th>Action</th>
										<th class="none">Note</th>
									</tr>
								</thead>
								<tbody id="benefitData">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>