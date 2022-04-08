<style>
	.card-header { padding: .5em 1.25em; }
	.card-body { padding: 20px 15px 5px; }
    .content-box-footer { padding-top: 15px; }
</style>

<div class="auth-wrapper">
    <div class="auth-header">
        <div class="auth-title"></div>
        <div class="auth-label">Quick Purchase</div>
    </div>
    <div class="auth-body">
        <div class="auth-content">
            <div id="notifier"></div>
            <form id="submit-action">
                <div class="form-group">
                    <label for=""> Enter Email or Phone</label>
                    <input id="emailphone" class="form-control" name="emailorphone" required>
                    <div class="validation-message" data-field="qty_product"></div>
                </div>
                <?php $this->load->view('purchase/form') ?>
                <div class="content-box-footer">
                    <button type="button" class="btn btn-primary action" title="buyipoints" onclick="form_routes('buyipoints')">Make Payment</button>
                    <a class="btn action pull-right" title="Go Home" href="<?=base_url()?>">Go Home</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?=FLW_JS_INLINE_SCRIPT?>"></script>
<script src="<?=base_url('assets/js/purchase.js')?>" type="text/javascript"></script>