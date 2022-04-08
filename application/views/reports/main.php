<!-- Table -->
<section class="datagrid-panel">
	<div class="breadcrumb">
		<a href="">Home</a> 
		<a href="">Product/Service Subscription Report</a>
	</div>
	<div class="content">
		<div class="panel">
			<div class="content-header no-mg-top">
				<i class="fa fa-newspaper-o"></i>
				<div class="content-header-title">Product/Service Subscription Reports</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="content-box">
						<div class="content-box-header">
							<div class="row">
								<div class="col-md-12">
									<form id="reportForm" target="<?=base_url('reports/validSubscribers')?>" class="form">
										<div class="form-group">
											<label class="col-sm-1 col-form-label" for="productId">Billing Product: </label>
											<div class="col-sm-2">
												<select id="productId" name="productId" class="form-control search-filter">
													<option value="">--Select--</option>
													<?php foreach ($products as $product) :?>
														<option value="<?=$product->id?>" <?=($_GET['productId']??'') == $product->id ? 'selected' : null?>><?=$product->product_name?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-1 col-form-label" for="productId"> Period From: </label>
											<div class="col-sm-2">
												<select id="period" name="period" class="form-control search-filter">
													<option value="">--Select--</option>
													<?php foreach ($periods as $period) :?>
														<option value="<?=$period['value']?>" <?=(($_GET['period']??'') == $period['value']) ? 'selected' : null?>><?=$period['name']?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>
										<div class="form-check form-check-inline col-sm-2">
											<input id="validKycs" class="form-check-input search-filter" type="checkbox" name="validKycs" <?=($_GET['validKycs']??'') == 'on' ? 'checked':null?>>
											<label class="form-check-label" for="validKycs">
												&nbspValid KYCs
											</label>
										</div>
										<!-- <button class="btn btn-info mb-1">Apply Filter</button> -->
										<button id="exportToExcel" class="btn btn-success pull-right mb-2 datatable-export"><i class="fa fa-upload"></i> Subscribe</button>
									</form>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered" id="reportTable"></table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Form -->
<section class="form-panel"></section>

<script type="text/javascript" src="<?=base_url('assets/js/productSubscription.js')?>"></script>