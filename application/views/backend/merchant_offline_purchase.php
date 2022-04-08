	<script>
	const iPoint_unit = <?= $ipoint_unit ?> 
	</script>
	<div class="content with-top-banner">
		<div class="panel subscriberFitter">
			<div class="content-box">
			<div class="row">
						<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5><i class="fa fa-bars" ></i> Offline Payment Verification</h5>
										<div class="ibox-tools">
										</div>
									</div>
									<div class="ibox-content">
										<form id="createPaymentForm">
												<div class="row">
													<div class="form-group col-sm-4">
														<label for="reference">Payment Reference</label>
														<input title="Enter Value" type="text" placeholder="Enter Payment Reference" name="reference" class="form-control i-reference">
														<div class="validation-message" data-field="reference"></div>
													</div>
													<div class="form-group col-sm-3">
														<label for="amount">Amount In Naira</label>
														<input title="Enter Amount Paid" type="text" placeholder="&#x20A6; Amount " name="amount" class="form-control amount" min="1" readonly >
														<div class="validation-message" data-field="amount"></div>
													</div>
													<div class="form-group col-sm-3">
														<label for="ipoint">Number Of ipoint</label>
														<input title="Payment reference" type="number" placeholder="Enter iPoint quantity" name="ipoint" class="form-control ipoint">
														<div class="validation-message" data-field="ipoint"></div>
													</div>
													<div class="form-group col-sm-12">
													<label for="description">Payment Description</label>
														<textarea title="Payment description" name="description"  placeholder="Enter payment description" class="form-control description" rows="5" cols="100"></textarea>
														<div class="validation-message" data-field="description"></div>
													</div>
													<div class="form-group col-sm-3">
														<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
														<button class="btn btn-success"  id="createPaymentSearch">Submit</button>
													</div>
											</div>
										</form>
									</div>
								</div>
						</div>
				</div>
			</div>
		</div>
	</div>
