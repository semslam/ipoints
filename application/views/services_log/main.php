<!-- Table -->
<section class="datagrid-panel">
	<div class="breadcrumb">
		<a href="">Home</a> 
		<a href="">Services Log</a>
	</div>
	<div class="content">
		<div class="panel">
			<div class="content-header no-mg-top">
				<i class="fa fa-newspaper-o"></i>
				<div class="content-header-title">Services Log</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="content-box">
						<div class="content-box-header">
							<div class="row">
								<div class="col-md-6">
								</div>
								<div class="col-md-6 form-inline justify-content-end">
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
								<div class="col-md-3 form-inline">
									<select class="form-control" id="option"></select>
								</div>
								<div class="col-md-3 form-inline" id="info"></div>
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

<!-- Form -->
<section class="form-panel"></section>

<script type="text/javascript">
	var datagrid = $("#datagrid").datagrid({
    url           : "<?php echo base_url() . 'serviceslog/data'; ?>",
    primaryField      : 'id', 
    rowNumber       : true,
    searchInputElement    : '#search', 
    searchFieldElement    : '#search-option', 
    pagingElement       : '#paging', 
    optionPagingElement   : '#option', 
    pageInfoElement     : '#info',
    columns         : [
          {field: 'name', title: 'Name', editable: true, sortable: true, width: 250, align: 'left', search: true},
		  {field: 'product_name', title: 'Product Name', sortable: false, width: 250, align: 'center', search: false, 
            rowStyler: function(rowData, rowIndex) {
              return '<span class="badge badge-yellow">$' + rowData.product_name + '</span>';
            }
          },
          {field: 'value', title: 'Value', sortable: false, width: 100, align: 'center', search: false, 
            rowStyler: function(rowData, rowIndex) {
              return '<span class="badge badge-yellow">$' + rowData.value + '</span>';
            }
          },
          {field: 'cover_period', title: 'Cover Period', editable: true, sortable: true, width: 100, align: 'center', search: true},
          {field: 'purchase_date', title: 'Purchase Date', editable: true, sortable: true, width: 100, align: 'center', search: true},
          {field: 'expiring_date', title: 'Expiring Date', editable: true, sortable: true, width: 100, align: 'center', search: true}
          
      ]
  });
	datagrid.run();

	function menu(rowData, rowIndex) {
		 console.log(rowData);
		// console.log(rowData.level);
		// console.log(rowData.user_id);
		var menu = '<a href="javascript:;" onclick="main_routes(\'update\', ' + rowIndex + ')"><i class="fa fa-pencil"></i> Edit</a> ' +
		'<a href="javascript:;" onclick="delete_action(' + rowIndex + ','+rowData.level+','+rowData.id+')"><i class="fa fa-trash-o"></i> Delete</a>'
		return menu;
	}

	
</script>