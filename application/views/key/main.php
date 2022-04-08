<!-- Table -->
<section class="datagrid-panel">
	<div class="breadcrumb">
		<a href="">Home</a> 
		<a href="">API Key</a>
	</div>
	<div class="content">
		<div class="panel">
			<div class="content-header no-mg-top">
				<i class="fa fa-newspaper-o"></i>
				<div class="content-header-title">Api Keys</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="content-box">
						<div class="content-box-header">
							<div class="row">
								<div class="col-md-6">
									<button class="btn btn-primary" onclick="main_routes('create', '')"><i class="fa fa-pencil"></i> Add New Api Key</button>
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
    url           : "<?php echo base_url() . 'keys/data'; ?>",
    primaryField      : 'id', 
    rowNumber       : true,
    searchInputElement    : '#search', 
    searchFieldElement    : '#search-option', 
    pagingElement       : '#paging', 
    optionPagingElement   : '#option', 
    pageInfoElement     : '#info',
    columns         : [
          {field: 'name', title: 'Agent Name', editable: true, sortable: true, width: 250, align: 'left', search: true},
		  {field: 'api_key', title: 'Api Key', sortable: false, width: 300, align: 'center', search: false, 
            rowStyler: function(rowData, rowIndex) {
              return '<span class="badge badge-yellow">$' + rowData.ip_addresses + '</span>';
            }
          },
          {field: 'ignore_limits', title: 'Ignore Limits', sortable: false, width: 100, align: 'center', search: false, 
            rowStyler: function(rowData, rowIndex) {
              return '<span class="badge badge-yellow">$' + rowData.ignore_limits + '</span>';
            }
          },{field: 'level', title: 'Level', sortable: false, width: 100, align: 'center', search: false, 
            rowStyler: function(rowData, rowIndex) {
              return '<span class="badge badge-yellow">$' + rowData.level + '</span>';
            }
          },
          {field: 'ip_addresses', title: 'Ip Addresses', editable: true, sortable: true, width: 100, align: 'center', search: true},
          {field: 'menu', title: 'Menu', sortable: false, width: 200, align: 'center', search: false, 
            rowStyler: function(rowData, rowIndex) {
              return menu(rowData, rowIndex);
            }
          }
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

	function create_update_form(rowIndex) {
		$.post("<?php echo base_url() .'keys/form'; ?>", {index : rowIndex}).done(function(data) {
			$('.form-panel').html(data);
		});
	}

	function delete_action(rowIndex,level,id) {
		 console.log(level);
		 console.log(id);
		// console.log(rowData.level);
		// console.log(rowData.user_id);
		ti = 'Are you sure want to suspend this key?';
		mg = 'Suspended key can not be use';
		if(level == 0){
			ti = 'Are you sure, you want to activate this key?';
			mg = 'Activating this key can make it be use';
		 }
		swal({   
			title: ti,   
			text: mg,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			cancelButtonText: "Cancel",
			confirmButtonText: "Suspend",
			closeOnConfirm: true 
		}, function() {
			var row = datagrid.getRowData(rowIndex);
			$.post("<?php echo base_url() . 'keys/delete'; ?>", {id : id, level : level}).done(function(data) {
				datagrid.reload();
			});
		});
	}

	function main_routes(action, rowIndex) {
		$('.datagrid-panel').fadeOut();
		$('.loading-panel').fadeIn();

		if (action == 'create') {
			create_update_form(rowIndex);
		} else if (action == 'update') {
			create_update_form(rowIndex);
		}
	}

</script>