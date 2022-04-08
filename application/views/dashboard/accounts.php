
	<div class="content with-top-banner">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Quick Stats</div>
			<select class="select-rounded pull-right">
				<option>Today</option>
				<option>7 Days</option>
				<option>14 Days</option>
				<option>Last Month</option>
			</select>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-3 card-wrapper">
					<div class="card">
						<i class="fa fa-newspaper-o"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="1121">1,121</span>
							</div>
							<div class="card-subtitle">iPoints Sold</div>
						</div>
					</div>
					<div class="card-menu">
						<ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-3 card-wrapper">
					<div class="card">
						<i class="fa fa-signal"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="72">72</span>
							</div>
							<div class="card-subtitle">Subscriber Count</div>
						</div>
					</div>
					<div class="card-menu">
						<ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-3 card-wrapper">
					<div class="card">
						<i class="fa fa-map-marker"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="24">24</span>
							</div>
							<div class="card-subtitle">Merchant Count</div>
						</div>
					</div>
					<div class="card-menu">
						<ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-3 card-wrapper">
					<div class="card">
						<i class="fa fa-suitcase"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="8">808</span>
							</div>
							<div class="card-subtitle">Development Partners</div>
						</div>
					</div>
					<div class="card-menu">
						<ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
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
								<div class="donut-chart-value">330</div>
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
								<div class="legend-title">Development Partners</div>
							</div>
							<div class="legend-list">
								<div class="legend-bullet blue"></div>
								<div class="legend-title">Agents</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="content-header">
						<i class="fa fa-newspaper-o"></i>
						<div class="content-header-title">iInsurance Basic Producrs</div>
					</div>
					<div class="content-box">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th><input type="checkbox"></th>
										<th>Product Name</th>
										<th class="text-center">Images</th>
										<th class="text-center">Status</th>
										<th class="text-right">Order Total</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><input type="checkbox"></th>
										<td class="nowrap">iLife</td>
										<td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/asparagus.jpg'; ?>"></td>
										<td class="text-center">
											<div class="status-pill green"></div>
										</td>
										<td class="text-right">=N=150.00</td>
										<td class="text-center"><i class="fa fa-pencil"></i> <i class="fa fa-trash"></i></td>
									</tr>
									<tr>
										<th><input type="checkbox"></th>
										<td class="nowrap">iHealth</td>
										<td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/belts.jpg'; ?>"></td>
										<td class="text-center">
											<div class="status-pill red"></div>
										</td>
										<td class="text-right">=N=150.00</td>
										<td class="text-center"><i class="fa fa-pencil"></i> <i class="fa fa-trash"></i></td>
									</tr>
									<tr>
										<th><input type="checkbox"></th>
										<td class="nowrap">iPension</td>
										<td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/belts.jpg'; ?>"></td>
										<td class="text-center">
											<div class="status-pill red"></div>
										</td>
										<td class="text-right">=N=150.00</td>
										<td class="text-center"><i class="fa fa-pencil"></i> <i class="fa fa-trash"></i></td>
									</tr>
									<tr>
										<th><input type="checkbox"></th>
										<td class="nowrap">iSavings</td>
										<td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/belts.jpg'; ?>"></td>
										<td class="text-center">
											<div class="status-pill red"></div>
										</td>
										<td class="text-right">=N=150.00</td>
										<td class="text-center"><i class="fa fa-pencil"></i> <i class="fa fa-trash"></i></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
