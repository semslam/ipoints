
<div class="content with-top-banner">

		<div class="panel">
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
										<form id="annualForm">
												<div class="row">
													<div class="form-group col-sm-3">
														<input title="Enter Users Email or Phone" type="text" placeholder="Users Phone Or Email (Optional)" name="customerName" class="form-control customerName">
														<input type="hidden" name="customerId" class="customerId">
														<div class="validation-message" data-field="customerId"></div>
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
													<div class='form-group col-sm-3'>
														<input placeholder="Enter Payment Reference" type='text' name="txn_reference"  id="txn_reference"  class="form-control txn_reference" />
														<div class="validation-message" data-field="txn_reference"></div> 
													</div>
                                                    <div class='form-group col-sm-3'>
														<input placeholder="&#x20A6; Paid" type='text' name="total_paid"  id="total_paid"  class="form-control total_paid" />
														<div class="validation-message" data-field="total_paid"></div> 
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success"  id="annualSearch">Search</button>
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
										<h5><i class="fa fa-download"></i> Annual Fee Payment List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<a  href="<?php echo site_url('fundamental/annualExportReport')?>" class="btn btn-success annualExportReport url">EXPORT TO EXCEL (.XLS)</a>
										<hr>
										<table id="annualTable" class="display" cellspacing="0" width="100%">
											<thead id="annualHeader">
												<tr>
													<th>Name</th>
													<th>Contact</th>
													<th>Feference</th>
													<th>Cost</th>
													<th>Tatal Paid</th>
													<th>Created Date</th>
													<th>Updated Date</th>
													<th class="none">Active</th>
													<th class="none">Latest</th>
													<th class="none">Complete</th>
												</tr>
											</thead>
											<tbody id="annualData">
											</tbody>
										</table>
										<!-- Paginate -->
										<div id='pagination-annual'></div>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
		<!-- start modal -->
		<!-- <div id="myModal" class="modal">
			<div class="modal-content m_small">
				<div class="modal-header">
				<h4 class="text-left">Process Offline Payment</h4>
				<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>
				</div>
			<div class="modal-body">
				<form id="approvedForm">
												<div class="row">
													<div class='col-md-6 col-center-block'>
														<div class="form-group">
															<input type="text" name="id" class="id">
															
															<div class="i-checks"><label class="customcheck"> <input class="approved" name="approved" type="checkbox" value="0"> <i></i> Approved <span class="checkmark"></span></label></div>
															<div class="validation-message" data-field="approved"></div>
															<div class='input-group date' id='date_approved'>
																<input type='text'  placeholder="Approved Date" name="approved_date"  class="form-control approved_date" />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="validation-message" data-field="approved_date"></div>
															<label></label>
															<div class="i-checks"><label  class="customcheck"> <input class="is_settled" name="is_settled" type="checkbox" value="0"> <i></i>Is Settled ?<span class="checkmark"></span></label></div>
															<div class="validation-message" data-field="is_settled"></div>
															<div class='input-group date' id='date_settled'>
																<input type='text'  placeholder="Settled Date" name="settled_date"  class="form-control settled_date" />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="validation-message" data-field="settled_date"></div>
															<label></label>
															<select class="form-control i-status" name="i-status">
																<option value="">Status Action</option>
																<option value="approved">Approved</option>
																<option value="canceled">Canceled</option>
															</select>
															<div class="validation-message" data-field="i-status"></div>
															<label></label>
															<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
															<button class="btn btn-success"  id="approvedSearch">Process</button>
														</div>
													</div>
											</div>
										</form>
			</div>
			 <div class="modal-footer">
			<h5 class="text-left">Modal Footer</h5>
			</div>
		</div> -->
		<!-- end modal -->
	</div>
