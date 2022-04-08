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
										<form id="userForm">
												<div class="row">
													<div class="form-group col-sm-3">
														<input title="Enter Users Email or Phone" type="text" placeholder="Users Phone Or Email (Optional)" name="customerName" id="customerName" class="form-control ">
														<input type="hidden" name="customerId" id="customerId">
														<div class="validation-message" data-field="customerId"></div>
													</div>
													<div class='form-group col-sm-3'>
														<select class="form-control fitter" name="fitter">
															<option value="">Choose User's Filter</option>
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
										<h5><i class="fa fa-download" aria-hidden="true"></i> New Subscribers List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<a  href="<?php echo site_url('dashboard/userExportReport')?>" class="btn btn-success userExportExcel url">EXPORT TO EXCEL (.XLS)</a>
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