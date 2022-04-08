
$(document).ready(function () {

  $.fn.dataTable.ext.errMode = 'throw';
  try {
    let yadc = false,
      form = $("#reportForm"),
      tblConfig = {
        processing: true,
        serverSide: true,
        columns: [
          { data: 'sn', title: 'S/N' },
          { data: 'name', title: 'Full Name' },
          { data: 'birth_date', title: 'Birthdate' },
          { data: 'age', title: 'Age' },
          { data: 'mobile_number', title: 'Mobile Phone Number' },
          { data: 'gender', title: 'Gender' },
          { data: 'address', title: 'Address' },
          { data: 'benefits', title: 'Benefits' },
          { data: 'pstart', title: 'Start' },
          { data: 'pend', title: 'End' },
          { data: 'next_of_kin', title: 'Next of Kin' },
          { data: 'next_of_kin_phone', title: 'Next of Kin Phone' },
        ],
        // order: [
        //   [0, "asc"],
        //   [1, "asc"]
        // ],
        ajax: {
          url: form.attr('target'),
          type: 'POST'
        }
      };
    
    const setupServerProcessing = (data, destroy=false) => {
      if (data) {
        tblConfig.ajax.data = data
      }
      let oTable = $("#reportTable");
      if (destroy) {
        oTable.DataTable().destroy();
      }
      oTable.DataTable(tblConfig)
      if (yadc) {
        yadcf.init(oTable, [
          {
            column_number: 1,
            filter_type: "auto_complete"
          }
        ]);
      }
    }

    const events = {};

    events.filtersProps = () => {
      return {
        productId: $('select[name=productId]').val(),
        period: $('select[name=period]').val(),
        kycOnly: ($('input[name=validKycs]').prop('checked') == true) ? 1 : 0
      }
    }

    events.dtExportToExcel = evt => {
      evt.preventDefault();
      if (!events.filtersProps().kycOnly) {
        alert('You cannot subscribe subscribers who have not completed their kycs details');
        return false;
      }
      
      let mData = { exportTo: "excel" };

      const data = $.extend({}, mData, $("#reportTable")
            .DataTable()
            .ajax.params()),
        params = $.param(data),
        url = form.attr("target") + "?" + params;

      window.location.href = url;
    };

    events.onSearchFilterChange = (evt) => {
      setupServerProcessing(events.filtersProps(), true);
    }

    // event listeners
    $('.search-filter').on('change', events.onSearchFilterChange);
    $(".datatable-export#exportToExcel").on("click", events.dtExportToExcel);

    // self bootstrap
    setupServerProcessing(events.filtersProps());

  } catch (e) {
    console.log(e)
  }
});
