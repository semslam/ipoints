<?php $sub = sizeof(array_filter(array_column($users,'group_name'),function($v){return $v==='Subscriber';})) ?>
<?php $merchant = sizeof(array_filter(array_column($users,'group_name'),function($v){return $v==='Merchant';})) ?>
<?php $underwriter = sizeof(array_filter(array_column($users,'group_name'),function($v){return $v==='Underwriter';})) ?>
	<style>
		.small-font{
			font-size:18px !important;
		}
		.bullet-point {
			font-size: 23px !important;
			font-family: initial;
			font-weight: inherit;
		}
		.figure{
			font-size:23px;
		}
		.card,.normal{
			justify-content: normal !important;
		}
		.all,.subscriberFitter, .productFitter,
		.subscriberFitter,.productFitter,.date_range,.kyc,.state,.status{
			display:none;
		} 
		
	</style>
	<div class="content with-top-banner">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title"></div>
			<select class="select-rounded pull-right">
				<!-- <option>Today</option>
				<option>7 Days</option>
				<option>14 Days</option>
				<option>Last Month</option> -->
			</select>
		</div>
		<!-- <div class="panel">
			<div class="row">
				<div class="col-md-3 card-wrapper">
					<div class="card">
						<i class="fa fa-thumb-tack"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= $ipoint->ipoints?>"><?= $ipoints?></span>
							</div>
							<div class="card-subtitle">iPoints Sold</div>
						</div>
					</div>

					<div class="card-menu">
					
					</div>
				</div>
				<div class="col-md-3 card-wrapper">
					<div class="card">
						<i class="fa fa-users"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= $sub?>">
									
										<?= $sub?>
								</span>
							</div>
							<div class="card-subtitle">Subscriber Count</div>
						</div>
					</div>
					<div class="card-menu">
						
					</div>
				</div>
				<div class="col-md-3 card-wrapper">
					<div class="card">
						<i class="fa fa-user"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?= $merchant?>"><?= $merchant?></span>
							</div>
							<div class="card-subtitle">Merchant Count</div>
						</div>
					</div>
					<div class="card-menu">
					
					</div>
				</div>
				<div class="col-md-3 card-wrapper">
					<div class="card">
						<i class="fa fa-pencil-square-o"></i>
						<div class="clear">
							<div class="card-title">
								<span class="timer" data-from="0" data-to="<?=$underwriter?>"><?=$underwriter?></span>
							</div>
							<div class="card-subtitle">Underwriter</div>
						</div>
					</div>
					<div class="card-menu">
						
					</div>
				</div>
			</div>
		</div> -->
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
								<div class="donut-chart-value">
									<?= count($users);?>
								</div>
								<div class="donut-chart-title">Total users</div>
							</div>
						</div>
						<!-- <div class="donut-chart-legend">
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
								<div class="legend-title">Underwriter</div>
							</div>
							<div class="legend-list">
								<div class="legend-bullet blue"></div>
								<div class="legend-title">Agents</div>
							</div>
						</div> -->
					</div>
				</div>
				<div class="col-md-8">
					<div class="content-header">
						<i class="fa fa-newspaper-o"></i>
						<div class="content-header-title">Basic Products/Services</div>
					</div>
					<div class="content-box">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Product Name</th>
										<th class="text-center">Service Provider</th>
										<th class="text-right">Price</th>
										<!-- <th class="text-center">Date</th> -->
									</tr>
								</thead>
								<tbody>
								<?php foreach ($products as $p){ ?>
									<tr>
										<td class="nowrap"><?= $p->product_name?></td>
										<td class="text-center"><?= (!empty($p->provider_name))? $p->provider_name :'N/A'?></td>
										<td class="text-right">&#x20A6; <?=$p->price ?></td>
										<!-- <td class="text-center"> <?= date_format(date_create($p->updated_at),"Y-m-d"); ?></td> -->
									</tr>
								<?php }?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- visible and hide content -->
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">Quick Analytics</div>
			<select class="select-rounded pull-right">
				<!-- <option>Today</option>
				<option>7 Days</option>
				<option>14 Days</option>
				<option>Last Month</option> -->
			</select>
		</div>
		<div class="panel">
			<div class="row">
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i class="fa fa-users"></i>
						<div class="card-title">
							<span>Subscribers</span>
						</div>
					</div>
					<div class="card normal">
						<div class="clear">
							<div class=" bullet-point">
								New Users &nbsp;&nbsp; <span class="timer figure" data-from="0" data-to="<?= $newUsers->number?>"><?= $newUsers->number?></span>
							</div>
							<div class="bullet-point">
							Uncompleted KYC &nbsp;&nbsp;<span class="timer figure" data-from="0" data-to="<?= $notCompleteKyc->kyc_count?>"><?= $notCompleteKyc->kyc_count?></span>
							</div>
							<div class="bullet-point">
								Total Users &nbsp;&nbsp; <span class="timer figure" data-from="0" data-to="<?= $sub;?>"><?= $sub;?></span>
							</div>
							<div class="card-subtitle">
									<a href="<?php echo base_url('reports/subscriber_list'); ?>"  class="btn btn-primary"><i class="fa fa-external-link small-font"> Click</i></a>
							</div>
						</div>
					</div>

					<div class="card-menu">
						<!-- <ul>
							<li><a href="">Today</a></li>
							<li><a href="">7 Days</a></li>
							<li><a href="">14 Days</a></li>
							<li><a href="">Last Month</a></li>
						</ul> -->
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i class="fa fa-briefcase"></i>
						<div class="card-title">
							<span>User's Wallet</span>
						</div>
					</div>
					<div class="card normal">
						<div class="clear">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											
											<th class="text-left">Wallets</th>
											<th class="text-center">&nbsp;&nbsp;</th>
											<th class="text-right">Users</th>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($groupUserByWallet)){?>
											<?php foreach ($groupUserByWallet as $w){ ?>
												<tr>
													<td class="text-left"><?= $w->name?></td>
													<td class="text-center">&nbsp;&nbsp;</td>
													<td class="text-right"><?= $w->users?></td>
												</tr>
											<?php }?>
										<?php }else{?>
												<tr>
													<td class="text-center">No Wallet Found</td>
												</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
							<div class="card-subtitle">
							
							
									<a href="#"  class="btn btn-primary "><i class="fa fa-external-link small-font"> Click</i></a>
							</div>
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
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i class="fa fa-tags"></i>
						<div class="card-title">
							<span>Products</span>
						</div>
					</div>
					<div class="card normal">
						<div class="clear">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											
											<th class="text-left"><h4>Product</h4></th>
											<th class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th class="text-right"><h4>Users</h4></th>
										</tr>
									</thead>
									<tbody>
									<?php if(!empty($subscribeServices)){?>
										<?php foreach ($subscribeServices as $s){ ?>
											<tr>
												<td class="text-left"><h4> <?= $s->product_name?> </h4></td>
												<th class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
												<td class="text-right"><h4><?= $s->users?></h4></td>
											</tr>
										<?php }?>
									<?php }else{?>
											<tr>
												<td class="text-center">No Subscription Found</td>
											</tr>
									<?php } ?>	
									</tbody>
								</table>
							</div>
							<div class="card-subtitle">
								<a href="<?php echo base_url('reports/product_list'); ?>"  class="btn btn-primary"><i class="fa fa-external-link pro small-font"> Click</i></a>
							</div>
						</div>
					</div>

					<div class="card-menu">
						
					</div>
				</div>
			</div>
		</div>
	</div>
