
<div class="content with-top-banner">

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
                                <form id="annualForm">
                                        <div class="row">
                                
                                            <div class='form-group col-sm-3'>
                                                <input placeholder="Enter Payment Reference" type='text' name="txn_reference"  id="txn_reference"  class="form-control txn_reference" />
                                                <div class="validation-message" data-field="txn_reference"></div> 
                                            </div>
                                            <div class='form-group col-sm-3'>
                                                <input placeholder="&#x20A6; Paid" type='text' name="total_paid"  id="total_paid"  class="form-control total_paid" />
                                                <div class="validation-message" data-field="total_paid"></div> 
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                <button class="btn btn-success"  id="annualSearch">Search</button>
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
                                <h5><i class="fa fa-download"></i> Annual Fee Payment List</h5>
                                <div class="ibox-tools">
                                </div>
                            </div>
                            <div class="ibox-content">
                                <a  href="<?php echo site_url('fundamental/EligSubscriberExportReport')?>" class="btn btn-success EligSubscriberExportReport url">EXPORT TO EXCEL (.XLS)</a>
                                <hr>
                                <table id="eligSubscriberTable" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Gender</th>
                                            <th>Product</th>
                                            <th>Annual fee</th>
                                            <th>Balance</th>
                                            <th class="none">Next Of Kin</th>
                                            <th class="none">Next Of Kin Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody id="annualData">
                                    </tbody>
                                </table>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</div>

</div>
