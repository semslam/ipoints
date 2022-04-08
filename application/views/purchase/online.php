<style>
	.card-header { padding: .5em 1.25em; }
	.card-body { padding: 20px 15px 5px; }
</style>

<div class="breadcrumb">
	<a href="">Home</a> 
	<a href="">Buy iPoints</a>
</div>
<div class="content">
	<div class="panel">
		<div class="content-header no-mg-top">
			<i class="fa fa-newspaper-o"></i>
			<div class="content-header-title">PURCHASE</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="content-box">
					<div id="notifier"></div>
					<form id="submit-action">
						<?php $this->load->view('purchase/form') ?>
						<div class="content-box-footer">
							<button type="button" class="btn btn-default action" title="cancel" onclick="form_routes('cancel')">Cancel</button>
							<button type="button" class="btn btn-primary action" title="buyipoints" onclick="form_routes('buyipoints')">Make Payment</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?=FLW_JS_INLINE_SCRIPT?>"></script>
<script src="<?=base_url('assets/js/purchase.js')?>" type="text/javascript"></script>