<style>
	.btn-link:hover { text-decoration:none; }
	.card-header .item-tag { line-height:22px; border: 2px solid; border-radius: 50%; width: 24px; height:24px; display:inline-block; margin-right:15px; font-weight:bold;}
</style>
<div class="accordion" id="accordionExample">

<?php $group = $this->session->userdata('active_user')?>
		<?php 
			$data ='';
			
			if(!empty($products)){
				$data .='<div class="card">';
				$data .='<div class="card-header" id="headingOne">';
					$data .='<h5 class="mb-0">';
							$data .='<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><span class="item-tag">1</span>';
								$data .='BUY A PRODUCT';
									$data .='</button>';
					$data .='</h5>';
				$data .='</div>';

				$data .='<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">';
					$data .='<div class="card-body">';
						$data .='<div class="form-group">';
								$data .='<label for=""> Choose product</label>';
									$data .='<select id="product-flag" class="form-control" name="product" required>';
										$data .='<option value="">-Select Product-</option>';
												 foreach ($products as $product) {
												 $cost = $product->price*floatval(IPOINTS_PER_UNIT_COST); 
												 //$data .='<option value="'.$product->id.'"> '.$product->product_name .'('.$product->price? $product->price.' iPoints = NGN '.$cost:'N/A )</option>';
												 $data .='<option value="'.$product->id.'"> '.$product->product_name .'('. $product->price.' iPoints = NGN '.$cost.' )</option>';
												  }
										$data .='</select>';
											$data .='<input name="prod_tenure" type="hidden" value="1">';
											$data .='<div class="validation-message" data-field="qty_product"></div>';
								$data .='</div>';
						$data .='</div>';
					$data .='</div>';
				$data .='</div>';
			}
				
			//var_dump('<pre>',$group->group_name);
			if(empty($group)){
				//$group == 'Subscriber'
				echo $data;
			}else if($group->group_name == SUBSCRIBER){
				echo $data;
			}
		?>
		
		<div class="card">
		<?php if (empty($group) || $group->group_name == SUBSCRIBER || $group->group_name == MERCHANT ) : ?>
				<div class="card-header" id="headingTwo">
						<h5 class="mb-0">
								<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><span class="item-tag">2</span>
										TOP-UP WALLET
								</button>
						</h5>
				</div>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
						<div class="card-body">
								<div class="form-group">
										<label for="wallet"> Choose wallet</label>
										<select class="form-control" name="wallet" required>
											<?php if (empty($group)) : ?>
												<option value="">-- Select Wallet To Top-Up --</option>
												<?php foreach ($wallets as $wallet) : ?>
													<option value="<?=$wallet->id?>"><?=$wallet->name?></option>
												<?php endforeach ?>
												<?php else: ?>
													<option value="">-- Select Wallet To Top-Up --</option>
													<?php foreach ($wallets as $wallet) : ?>
														<?php if ( $wallet->name === I_POINT && $group->group_name == MERCHANT ) : ?>
															<option value="<?=$wallet->id?>"><?=$wallet->name?></option>
															<?php break; ?>
														<?php elseif ( $wallet->name !== I_POINT && $group->group_name == SUBSCRIBER ): ?>
															<option value="<?=$wallet->id?>"><?=$wallet->name?></option>
														<?php endif; ?>
													<?php endforeach ?>
												<?php endif; ?>	
										</select>
										<div class="validation-message" data-field="wallet"></div>
								</div>
								<div class="form-group">
										<label for="topup_amount"> Enter quantity (iPoints)</label>
										<input class="form-control" name="topup_amount" placeholder="Enter Top-Up quantity" type="number" value="0" required>
										<div class="validation-message" data-field="quantity"></div>
								</div>
				</div>
		<?php else : ?>
		<div class="card-header" id="headingTwo">
						<h5 class="mb-0">
								<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><span class="item-tag">0</span>
										ONLY SUBSCRIBERS AND MERCHANTS ARE ALLOWED
								</button>
						</h5>
				</div>
		<?php endif; ?>
		</div>
</div>