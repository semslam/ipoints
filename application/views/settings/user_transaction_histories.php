<style>

</style>
<div class="content with-top-banner">
        <div class="panel">
			<div class="row">
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#337ab7" class="fa fa-exchange"></i>
						<div class="clear">
							<div class="card-title">
								<h4 class="tran_type">Last TRXN ?: </h4>
								<span class="last_tran_amount">
								</span>
							</div>
                            <!-- Oct 23 2019 15:36 34 -->
							<div class="card-subtitle"><strong class="last_tran_date"></strong></div>
						</div>
					</div>
					<div class="card-menu">
						
					</div>
				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color:#ccc" class="fa fa-rub"></i>
						<div class="clear">
							<div class="card-title">
								<h4> Total Amount Credit</h4>
								<span class="total_credit"></span>
							</div>
							<div class="card-subtitle">Number Of Credit TRXN: <strong class="credit_number"></strong></div>
						</div>
					</div>
					<div class="card-menu">
					</div>

				</div>
				<div class="col-md-4 card-wrapper">
					<div class="card">
						<i style="color: #ec971f;" class="fa fa-rub"></i>
						<div class="clear">
							<div class="card-title">
								<h4>Total Amount Debit</h4>
								<span class=" total_debit"></span>
							</div>
							<div class="card-subtitle">Number Of Debit TRXN:  <strong class="debit_number"></strong></div>
						</div>
					</div>
					<div class="card-menu">
						
					</div>
				</div>
			</div>
	    </div>    
		<div class="panel">
            <div class="content-box">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>
                                    <i class="fa fa-search-plus" aria-hidden="true"></i> Transaction Search</h5>
                                <div class="ibox-tools">
                                </div>
                            </div>
                            <div class="ibox-content">
                                <form id="transactionForm">
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <input title="Enter Transaction tran_reference" type="text" placeholder="Enter Transaction reference" name="tran_reference" class="form-control tran_reference">
                                            <div class="validation-message" data-field="tran_reference"></div>
                                        </div>
                                        <!-- <div class="form-group col-sm-3">
                                            <input title="Enter Email" type="text" placeholder="Enter Email" name="user"
                                                class="form-control user">
                                            <div class="validation-message" data-field="user"></div>
                                        </div> -->
                                        <div class='form-group col-sm-3'>
                                            <select title="Choose Transaction Type" class="form-control transaction_type" placeholder="Choose Transaction Type" name="transaction_type">
                                                <option value="">Choose Transaction Type</option>
                                                <option value="credit">Credit</option>
                                                <option value="debit">Debit</option>
                                            </select>
                                            <div class="validation-message" data-field="transaction_type"></div>
                                        </div>
                                        <div class='form-group col-sm-3'>
                                            <select title="Choose Wallet" class="form-control wallet" placeholder="Choose Wallet" name="wallet_id">
                                                <option value="">Choose Wallet</option>
                                            </select>
                                            <div class="validation-message" data-field="wallet_id"></div>
                                        </div>
                                        <div class='col-md-3'>
                                            <div class="form-group">
                                                <div class='input-group date' id='start'>
                                                    <input type='text' placeholder="Start Date" name="start_date" class="form-control clear start_date" />
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                </div>
                                                <div class="validation-message" data-field="start_date"></div>
                                            </div>
                                        </div>
                                        <div class='col-md-3'>
                                            <div class="form-group">
                                                <div class='input-group date' id='end'>
                                                    <input type='text'  placeholder="End Date" name="end_date"  class="form-control clear end_date" />
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                </div>
                                                <div class="validation-message" data-field="end_date"></div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <button class="btn btn-success" id="transactionSearch">Search</button>
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
                                <h5>
                                    <i class="fa fa-download"></i> Trsnsaction Histories Queue</h5>
                                <div class="ibox-tools">
                                </div>
                            </div>
                            <div class="ibox-content">
                                
                                <table id="transactionTable" class="display" cellspacing="0" width="100%">
                                    <thead id="transactionHeader">
                                        <tr>
                                            <th>TRXN Type </th>
                                            <th>Wallet</th>
                                            <th>Value</th>
                                            <th>Sender</th>
                                            <th>Receiver</th>
                                            <th>Current Balance</th>
                                            <th>Tran Reference</th>
                                            <th>Tran Date</th>
                                            <th class="none">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactionData">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
    <script type="text/javascript" >

      var tableTransaction= $('#transactionTable')
      
      getTransactionHistories(0);
    // Handle click on "Expand All" button
    $('#btn-show-all-children').on('click', function () {
        // Expand row details
        table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
    });

    // Handle click on "Collapse All" button
    $('#btn-hide-all-children').on('click', function () {
        // Collapse row details
        table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
    });
    

    totalCreditTransactions()
    function totalCreditTransactions(){
        var url = BASE_URL + "/UserConfigSetting/totalCreditTransactions/"+<?php echo $user['userId'] ?>;
        $.get(url, function(data) {
           let credit = data.credits;
           let total_amount = Number(credit.total_amount);
           $('.total_credit').html((total_amount == null )?'0.00':total_amount.toFixed(2))
           $('.credit_number').html(credit.number)
        });
     }

     totalDebitTransactions()
    function totalDebitTransactions(){
		var url = BASE_URL + "/UserConfigSetting/totalDebitTransactions/"+<?php echo $user['userId'] ?>;
        $.get(url, function(data) {
           let debit = data.debits;
           let total_amount = Number(debit.total_amount);
           $('.total_debit').html((total_amount == null )?'0.00':total_amount.toFixed(2))
           $('.debit_number').html(debit.number)
        });
     }

     lastTransactions()
    function lastTransactions(){
		var url = BASE_URL + "/UserConfigSetting/lastTransactions/"+<?php echo $user['userId'] ?>;
        $.get(url, function(data) {
           let last = data.last_t;
           let amount = Number(last.amount);
           $('.last_tran_amount').html((amount == null )?'0.00':amount.toFixed(2))
           $('.last_tran_date').html(last.created_at)
           $('.tran_type').append(last.type)
        });
     }
     

    walletOption()
    function walletOption(){
		var url = BASE_URL + "/offline/walletList"
        $.get(url, function(data) {
           let wallets = data.wallets;
				for(x in wallets){
                    $(".wallet").append('<option value="'+wallets[x].id+'">'+wallets[x].name+'</option>');
                }
        });
     }

$('#transactionSearch').on("click", function(evt){
     evt.preventDefault();
     $('#transactionHeader,#pagination').hide();
     var pageno = $(this).attr('data-ci-pagination-page');
	pageno = (IsEmptyOrUndefined(pageno))? 0:pageno;
     if ($.fn.DataTable.isDataTable("#transactionTable")) {
         $("#transactionTable").DataTable().destroy();
     }
     getTransactionHistories(pageno);
 });     
$('#pagination').on('click','a',function(e){
    e.preventDefault(); 
    $('#transactionHeader,#pagination').hide()
    var pageno = $(this).attr('data-ci-pagination-page');
    if ($.fn.DataTable.isDataTable("#transactionTable")) {
         $("#transactionTable").DataTable().destroy();
     }
     getTransactionHistories(pageno);
});


	function transactionTable(data){
         if(!IsEmptyOrUndefined(data)){
             $('#transactionHeader').show();
           var transactionData =''; 
                      for(x in data){
                        transactionData+='<tr>'+
                                '<td>'+data[x].type+'</td>'+
                                '<td>'+data[x].wallet_name+'</td>'+
                                '<td>&#8381; '+data[x].value+'</td>'+
                                '<td>'+data[x].sender+'</td>'+
                                '<td>'+data[x].receiver+'</td>'+
                                '<td>&#8381; '+data[x].current_balance+'</td>'+
                                '<td> '+data[x].reference+'</td>'+
                                '<td> '+(data[x].created_at ||'00-00-0000')+'</td>'+
                                '<td> '+data[x].description +'</td>'+
                            '</tr>';
                      }
                      $("#transactionData").html(transactionData);
                      tableTransaction.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#transactionData").html('<tr><td align="center" colspan="9">NO RECORD FOUND</td></tr>');
				  }
	}

     function getTransactionHistories(pageno) {
         
         $('#transactionHeader,#pagination').hide()
         $('#transactionSearch').html("Searching...").attr('disabled', true);
         var obj = $('#transactionForm').serialize();
    
         $("#transactionData").html('<tr><td align="center" colspan="9"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         var url = BASE_URL + "/UserConfigSetting/getFitterTransaction/"+<?php echo $user['userId'] ?>+"/"+pageno
         $.post(url, obj).done(function(data) {
             try{
                 if (data.value == "success") {
                     $('#transactionSearch').html("Search").attr('disabled', false);
                     
                     $('#pagination').html(data.result.pagination);
                     transactionTable(data.result.transactions)
                 } else {
                     $('#transactionSearch').html("Search").attr('disabled', false);
                     $('.validation-message').html('');
                     $('.validation-message').each(function() {
                         for (var key in data) {
                             if ($(this).attr('data-field') == key) {
                                 $(this).html(data[key]);
                             }
                         }
                     });
                 }
              } catch(e){
                  console.log('Exp error: ',e)
              }
         });
     }			
						

     $('#start').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
     $('#end').datetimepicker({
         useCurrent: false, //Important! See issue #1075
         format: 'YYYY-MM-DD HH:mm:ss'
     });
     $("#start").on("dp.change", function (e) {
         $('#end').data("DateTimePicker").minDate(e.date);
     });
     $("#end").on("dp.change", function (e) {
         $('#start').data("DateTimePicker").maxDate(e.date);
     });	
    </script>
