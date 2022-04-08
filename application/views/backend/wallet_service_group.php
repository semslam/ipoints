<div class="content with-top-banner">
        <div class="panel">
			    <div class="content-box">
			        <div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Create Wallet Service Group</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form class="createServiceGroupForm">
												<div class="row">
													<div class="form-group col-sm-3">
														<input title="Enter Service Name" type="text" placeholder="Enter Service Group Name" name="name" class="form-control name">
														<div class="validation-message" data-field="name"></div>
													</div>
                                                    <div class="form-group col-sm-12">
														<textarea title="Product description" name="description"  placeholder="Enter Product description" class="form-control description" rows="6" cols="100"></textarea>
														<div class="validation-message" data-field="description"></div>
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success createServiceGroupSubmit">Submit</button>
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
										<h5><i class="fa fa-download"></i> Wallet Service Group List</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<table id="serviceGroupTable" class="display" cellspacing="0" width="100%">
											<thead id="serviceGroupHeader">
												<tr>
													<th>Service Group Name</th>
													<th>Action</th>
													<th>Created Date</th>
													<th>Updated Date</th>
													<th class="none">Description</th>
												</tr>
											</thead>
											<tbody id="serviceGroupData">
											</tbody>
										</table>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
</div>