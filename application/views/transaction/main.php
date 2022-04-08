<!-- Table -->
<section class="datagrid-panel">
	<div class="breadcrumb">Home <span class="breadcrumb-devider">/</span> Products <span class="breadcrumb-devider">/</span> Iphone 6</div>
	<div class="content">
		<div class="panel">
			<div class="content-header no-mg-top">
				<i class="fa fa-newspaper-o"></i>
				<div class="content-header-title">Transactions Page</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="content-box">
						<div class="content-box-header">
							<div class="row">
								<div class="col-md-12 form-inline justify-content-end">
									<select class="form-control mb-1 mr-sm-1 mb-sm-0" id="search-option"></select>
									<input class="form-control" id="search" placeholder="Search" type="text">
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered" id="datagrid"></table>
						</div>
						<div class="content-box-footer">
							<div class="row">
								<div class="col-md-2 form-inline">
									<select class="form-control" id="option"></select>
								</div>
								<div class="col-md-4 form-inline" id="info"></div>
								<div class="col-md-6">
									<ul class="pagination pull-right" id="paging"></ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	var datagrid = $("#datagrid").datagrid({
		url						: "<?php echo base_url() . 'transaction/data'; ?>",
		primaryField			: 'id', 
		rowNumber				: true,
		searchInputElement 		: '#search', 
		searchFieldElement 		: '#search-option', 
		pagingElement 			: '#paging', 
		optionPagingElement 	: '#option', 
		pageInfoElement 		: '#info',
		columns					: [
		{field: 'invoice_number', title: 'Invoice Number', editable: true, sortable: true, width: 250, align: 'left', search: true},
	    {field: 'total_price_formatted', title: 'Total Price', sortable: false, width: 450, align: 'right', search: false, 
	  		rowStyler: function(rowData, rowIndex) {
      			return '<span class="badge badge-yellow">$' + rowData.total_price + '</span>';
	        }
	    },
	    {field: 'menu', title: 'Menu', sortable: false, width: 200, align: 'center', search: false, 
	        rowStyler: function(rowData, rowIndex) {
	       		return menu(rowData, rowIndex);
	      	}
	    }
	],
	rowDetail				: {
		formatter : function(rowData, rowIndex) {
			return row_detail(rowData, rowIndex);
		},
		onExpandRow : function(rowData, rowIndex) {
			var datagrid_detail = $("#datagrid-" + rowIndex).datagrid({
				url						: "<?php echo base_url() . 'transaction/detail'; ?>",
				queryParams 			: { transaction_id : rowData.id },
				primaryField			: 'id',
				rowNumber				: true,
				columns					: [
					{field: 'product_name', title: 'Product Name', editable: true, sortable: true, width: 350, align: 'left', search: true},
	        		{field: 'price_formatted', title: 'Price', sortable: false, width: 200, align: 'right', search: false, 
	        			rowStyler: function(rowData, rowIndex) {
	        				return '<span class="badge badge-grey">$' + rowData.price + '</span>';
	       				}
	      			},
	        		{field: 'qty', title: 'QTY', editable: true, sortable: true, width: 80, align: 'center', search: true},
	        		{field: 'subtotal_price_formatted', title: 'Subtotal Price', sortable: false, width: 150, align: 'right', search: false, 
	        			rowStyler: function(rowData, rowIndex) {
	        				return '<span class="badge badge-grey">$' + rowData.subtotal_price + '</span>';
	        			}
	        		}
				]
			});

			datagrid_detail.run();
		}
	}
});

	datagrid.run();

	function menu(rowData, rowIndex) {
		return '<a href="' + '<?php echo base_url() . 'transaction/invoice'; ?>' + '/' + rowData.invoice_number + '"><i class="fa fa-pencil"></i> Print Invoice</a>'
	}

	function row_detail(rowData, rowIndex) {
		return  "<div class='table-responsive'>" +
		"<table class='table table-striped' id='datagrid-" + rowIndex + "'></table>" +
		"</div>";
	}

</script>