
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
				</div>
			</div>
			<div class="panel">
			<div class="row">
				<div class="col-md-12">
					<div class="content-header">
						<i class="fa fa-newspaper-o"></i>
						<div class="content-header-title">Wallets Balance</div>
					</div>
					<div class="content-box">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<!-- <th><input type="checkbox"></th> -->
										<!-- <th class="text-center">Name</th> -->
										<th class="text-center">Product Name</th>
										<th class="text-center">Images</th>
										<th class="text-center">Status</th>
										<th class="text-right">Value</th>
										<th class="text-center">Date</th>
									</tr>
								</thead>
								<tbody>
								<?php if(!empty($userWalletsBalance)){ ?>
								<?php echo $status =''; ?>
								<?php foreach($userWalletsBalance as $userWallets){ ?>
									
									<tr>
										<td class="nowrap"><?= $userWallets->product_name; ?></td>
										<!-- <td class="nowrap"><?= $userWallets->name; ?></td> -->
										<td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/asparagus.jpg'; ?>"></td>
										<td class="text-center">
										<?php  $status =  ($userWallets->balance <= 0)?  'red': 'green'?>	
											<div class="status-pill <?php echo $status;?>"></div>
										</td>
										<td class="text-right"><?= $userWallets->balance; ?></td>
										<td class="text-right"><?= $userWallets->updated; ?></td>
										
									</tr>
								<?php }?>
								<?php }else{?>
									<td colspan="5"> NO Wallets Balance Found</td>
								<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-12">
					<div class="content-header">
						<i class="fa fa-newspaper-o"></i>
						<div class="content-header-title">Subscribe Services</div>
					</div>
					<div class="content-box">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<!-- <th><input type="checkbox"></th> -->
										<th class="text-center">Product Name</th>
										<th class="text-center">Value</th>
										<th class="text-center">Images</th>
										<th class="text-center">Status</th>
										<th class="text-right">Cover Period</th>
										<th class="text-center">Purchase Date</th>
										<th class="text-center">Expiring Date</th>
									</tr>
								</thead>
								<tbody>
								<?php if(!empty($userServices)){ ?>
									<?php echo $status =''; ?>
								<?php foreach($userServices as $userService){ ?>
									<tr>
										<td class="nowrap"><?= $userService->product_name; ?></td>
										<td class="nowrap"><?= $userService->value; ?></td>
										<td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/asparagus.jpg'; ?>"></td>
										<td class="text-center">
											<?php  $status =  ($userService->value <= 0)?  'red': 'green'?>	
											<div class="status-pill <?php echo $status;?>"></div>
										</td>
										<td class="text-right"><?= $userService->cover_period; ?> Months</td>
										<td class="text-right"><?= $userService->purchase_date; ?></td>
										<td class="text-right"><?= $userService->expiring_date; ?></td>
										
									</tr>
								<?php }?>
								<?php }else{?>
									<td colspan="5"> NO Subscribe Service Found</td>
								<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-12">
					<div class="content-header">
						<i class="fa fa-newspaper-o"></i>
						<div class="content-header-title">Transfers Wallet</div>
					</div>
					<div class="content-box">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<!-- <th><input type="checkbox"></th> -->
										<th class="text-center">User Name</th>
										<th class="text-center">Value</th>
										<th class="text-center">Current Balance</th>
										<th class="text-center">Images</th>
										<th class="text-center">Status</th>
										<th class="text-right">Cover Period</th>
										<th class="text-center">Purchase Date</th>
										<th class="text-center">Expiring Date</th>
									</tr>
								</thead>
								<tbody>
								<?php if(!empty($transfers)){ ?>
									<?php echo $status ='';?>
								<?php foreach($transfers as $transfer){ ?>
									<tr>
										<td class="nowrap"><?= $transfer->product_name; ?></td>
										<td class="nowrap"><?= $transfer->value; ?></td>
										<td class="nowrap"><?= $transfer->current_balance; ?></td>
										<td class="text-center"><img alt="pongo" class="image-table" src="<?php echo base_url() . 'assets/images/asparagus.jpg'; ?>"></td>
										<td class="text-center">
											<?php  $status =  ($transfer->current_balance <= 0)?  'red': 'green'?>	
											<div class="status-pill <?php echo $status;?>"></div>
										</td>
										<td class="text-right"><?= $transfer->reference; ?></td>
										<td class="text-right"><?= $transfer->type; ?></td>
										<td class="text-right"><?= $transfer->created_at; ?></td>
										
									</tr>
								<?php }?>
								<?php }else{?>
									<td colspan="5"> NO Transfers Wallet Found</td>
								<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
