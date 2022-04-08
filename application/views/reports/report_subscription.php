
<div class="content with-top-banner">
    <div class="panel">
        <div class="content-box">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>
                                <i class="fa fa-bars"></i> Create Report Subscription Form</h5>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        <div class="ibox-content">
                            <form class="createSubscriberForm">
                                <div class="row">
                                    <!-- <label>Subscriber Email</lable> -->
                                    <div class="form-group col-sm-3">
                                        <input title="Enter Subscriber Email" type="text" placeholder="Subscriber Email" name="customerName" class="form-control customerName">
                                        <input type="hidden" name="customerId" class="customerId">
                                        <div class="validation-message" data-field="customerId"></div>
                                    </div>
                                    <div class='form-group col-sm-3'>
                                        <!-- <label>Frequency</lable> -->
                                        <select class="form-control frequency" name="frequency">
                                            <option value="">Choose frequency</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                        <div class="validation-message" data-field="frequency"></div>
                                    </div>
                                    <div class='form-group col-sm-3'>
										<!-- <label>Report Action</lable> -->
										<select class="form-control report_type" name="report_type">
											<option value="">Choose Report Type</option>
												<?php
													foreach($types as $type){
													    echo '<option value="'.$type.'">'.$type.'</option>'; 
													} 
												?>			
										</select>
								        <div class="validation-message" data-field="report_type"></div>
									</div>
                                    <div class='form-group col-sm-3'>
                                        <!-- <label>Status</lable> -->
                                        <select title="Choose your Status" class="form-control rs-status" placeholder="Choose Report Status" name="rs-status">
                                            <option value="">Status IO</option>
                                            <option value="1">Subscribe</option>
                                            <option value="0">Unsubscribe</option>
                                        </select>
                                        <div class="validation-message" data-field="rs-status"></div>
                                    </div>
                                    <div class='form-group col-sm-3'>
                                        <!-- <label>Dispatcher Type</lable> -->
                                        <select title="Choose your Dispatcher Type" class="form-control dispatch" placeholder="Choose Dispatcher Type" name="dispatch">
                                            <option value="">Choose Dispatch Type</option>
                                            <option value="individual">Individual</option>
                                            <option value="group">Group</option>
                                            <option value="all">All</option>
                                        </select>
                                        <div class="validation-message" data-field="dispatch"></div>
                                    </div>
                                    <div class="form-group col-sm-3 all status">
                                        <!-- <label>All Email</lable> -->
                                        <input title="All Email" type="text" placeholder="All Email" name="send_to_all" class="form-control send_to_all">
                                        <div class="validation-message" data-field="send_to_all"></div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button class="btn btn-success subscriberSubmit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                                <i class="fa fa-search-plus" aria-hidden="true"></i>Filter Search</h5>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        <div class="ibox-content">
                            <form id="subscriberSearchForm">
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <input title="Enter Subscriber Email" type="text" placeholder="Subscriber Email" name="customerName" class="form-control customerName">
                                        <input type="hidden" name="customerId" class="customerId">
                                        <div class="validation-message" data-field="customerId"></div>
                                    </div>
                                    <div class='form-group col-sm-3'>
                                        <select class="form-control frequency" name="frequency">
                                            <option value="">Choose frequency</option>
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                        <div class="validation-message" data-field="frequency"></div>
                                    </div>
                                    <div class='form-group col-sm-3'>
										<!-- <label>Report Action</lable> -->
										<select class="form-control report_type" name="report_type">
											<option value="">Choose Report Type</option>
												<?php
													foreach($types as $type){
													    echo '<option value="'.$type.'">'.$type.'</option>'; 
													} 
												?>			
										</select>
								        <div class="validation-message" data-field="report_type"></div>
									</div>
                                    <div class='form-group col-sm-3'>
                                        <select title="Choose your Status" class="form-control rs-status" placeholder="Choose Report Status" name="rs-status">
                                            <option value="">Status IO</option>
                                            <option value="1">Subscribe</option>
                                            <option value="0">Unsubscribe</option>
                                        </select>
                                        <div class="validation-message" data-field="rs-status"></div>
                                    </div>
                                    <div class='form-group col-sm-3'>
                                        <select title="Choose your Dispatch Type" class="form-control dispatch" placeholder="Choose Dispatch Type" name="dispatch">
                                            <option value="">Choose Dispatch Type</option>
                                            <option value="individual">Individual</option>
                                            <option value="group">Group</option>
                                            <option value="all">All</option>
                                        </select>
                                        <div class="validation-message" data-field="dispatch"></div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button class="btn btn-success" id="subscriberSearch">Search</button>
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
                                <i class="fa fa-download"></i> Report Subscribers List</h5>
                            <div class="ibox-tools">
                            </div>
                        </div>
                        <div class="ibox-content">
                            <table id="subsciberTable" class="display" cellspacing="0" width="100%">
                                <thead id="subsciberHeader">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Frequency</th>
                                        <th>Report Type</th>
                                        <th>Dispatch Type</th>
                                        <th>Action</th>
                                        <th>Status IO</th>
                                        <th>Subscription</th>
                                        <th class="none">Author_By</th>
                                        <th class="none">Created</th>
                                        <th class="none">Updated</th>
                                    </tr>
                                </thead>
                                <tbody id="subsciberData">
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
    // $(document).ready(function (){
    var tableSubsciber = $('#subsciberTable')
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

    $('#subscriberSearch').on("click", function(evt){
    evt.preventDefault();
    if ($.fn.DataTable.isDataTable("#subsciberTable")) {
        $("#subsciberTable").DataTable().destroy();
    }
        getFitterSubscriber();
    });

    $('body').on("click",'.subscriberSubmit',function(evt){
        evt.preventDefault();
        createSubscriber(evt);
    });

    $('.dispatch').change(function() {
       const type = $('.dispatch').val()
        if(type == 'all') { 
           hideFormInputAndReset('.all');
           $('.status').show();
           
        }else {
           hideFormInputAndReset('.all');		
        }
   });

   function hideFormInputAndReset(mclass) {
	    $(mclass).hide().find('input,select').val('');
    }

    $("body").on("change",'.onoffswitch-checkbox',function (e) {
        const id = $(e.target).data('id')
        const biz = $(e.target).data('biz');
       $('#subsciberHeader').hide();
        var url = BASE_URL + "/reports/subcribeOrUnsubscribeReport"
        var status;
        if (this.checked) {
            status = 1;
        } else {
            status = 0;
        }
        var obj = {
            id: id,
            name:biz,
            status:status
        };
        
        console.log(obj);
        $.post(url, obj).done(function (data) {
            try {
                var title;
                var message;
                var type;
                if (data.value == "success") {

                    if(status == 1){
                        title = "Subscribe";
                        message ="The user has been subscribed for email report successfully";
                        type =success;
                        
                    }else{
                        title = "Unsubscribe";
                        message ="The user has been Unsubscribed for email report successfully";
                        type = warning;
                        console.log(title,message);
                    }
                    const info = {title:title, message:message, type:type, position:topfullwidth};
                    toastNotification(info);
                    if ($.fn.DataTable.isDataTable("#subsciberTable")) {
                        $("#subsciberTable").DataTable().destroy();
                    }
                    loadSubscribers();
                }else{
                    const info = {title:"Subscription Error", message:"The user was not subscribed successfully", type:error, position:topfullwidth};
                    toastNotification(info);
                }
            } catch (e) {
                console.log('Exp error: ', e)
            }
        });
        
    });


    function subscriberTable(data){
         if(!IsEmptyOrUndefined(data)){
            $('#subsciberHeader').show();
           var subscriberData =''; 
                      for(x in data){
                        subscriberData+='<tr>'+
                                '<td>'+data[x].user_name+'</td>'+
                                '<td>'+data[x].email+'</td>'+
                                '<td>'+data[x].frequency+'</td>'+
                                '<td>'+data[x].report_type+'</td>'+
                                '<td>'+data[x].dispatcher_type+'</td>'+
                                '<td> <a href="#" class="btn btn-primary slam-modal" data-modal="modal-'+data[x].id+'" >'+
                                '<i class="fa fa-pencil"></i> '+
                                    ' Edit'+
                                '</a>'+
                                '<div id="modal-'+data[x].id+'" class="modal">'+
                                        '<div class="modal-content m_small">'+
                                            '<div class="modal-header">'+
                                            '<h4 class="text-left">'+data[x].user_name+' Subscription</h4>'+
                                            '<span style="margin-top: -8%;" title="Close Modal" class="close">&times;</span>'+
                                        '</div>'+
                                    '<div class="modal-body">'+
                                        '<form class="createSubscriberForm">'+
                                            '<div class="row" style="margin: 50px;">'+
                                                '<div class="form-group row">'+
                                                    '<label for="Frequency" class="col-sm-4 col-form-label">Frequency</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<select class="form-control frequency" name="frequency">'+
                                                            '<option value="'+data[x].frequency+'">'+data[x].frequency+'</option>'+
															'<option value="daily">Daily</option>'+
															'<option value="weekly">Weekly</option>'+
															'<option value="monthly">Monthly</option>'+
															'<option value="yearly">Yearly</option>'+
														'</select>'+
														'<div class="validation-message" data-field="frequency"></div>'+ 
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                    '<label for="Report Type" class="col-sm-4 col-form-label">Report Type</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input title="Choose your Report Type" value="'+data[x].report_type+'" type="text" placeholder="Report Type" name="report_type" class="form-control report_type">'+
                                                        '<div class="validation-message" data-field="report_type"></div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                    '<label for="Subscribe Status" class="col-sm-4 col-form-label">Subscribe Status</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<select title="Choose your Status" class="form-control rs-status" placeholder="Choose Report Status" name="rs-status">'+
                                                            (data[x].status == 1 ? '<option value="1">Subscribe</option>' : '<option value="0">Unsubscriber</option>')+
															'<option value="1">Subscribe</option>'+
															'<option value="0">Unsubscriber</option>'+
														'</select>'+
														'<div class="validation-message" data-field="rs-status"></div>'+  
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                    '<label for="DispatcherType" class="col-sm-4 col-form-label">Dispatcher Type</label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<select title="Choose your Dispatcher Type" class="form-control dispatch" placeholder="Choose Dispatcher Type" name="dispatch">'+
															'<option value="'+data[x].dispatcher_type+'" selected>'+data[x].dispatcher_type+'</option>'+
															'<option value="individual">Individual</option>'+
															'<option value="group">Group</option>'+
															'<option value="all">All</option>'+
														'</select>'+
														'<div class="validation-message" data-field="dispatch"></div>'+  
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group row">'+
                                                '<label for="" class="col-sm-4 col-form-label"></label>'+
                                                    '<div class="col-sm-6">'+
                                                        '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">'+
                                                        '<input type="hidden" name="id" class="id" value="'+data[x].id+'">'+
                                                        '<button class="btn btn-success subscriberSubmit">Submit</button>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>'+
                                '</td>'+
                                '<td>'+(data[x].status == 1
                                    ?'<span class="pull-right claimed label-primary">Subscribe</span>'
                                    :'<span class="pull-right claimed label-danger">Unsubscribe</span>')+'</td>'+
                                    '<td>'+
                                        '<div class="switch">'+
                                            '<div class="onoffswitch">'+
                                                '<input type="checkbox" '+(data[x].status == 1 ? 'checked' : '')+' data-id="'+data[x].id+'"  data-biz="Ibrahim Olanrewaju" class="onoffswitch-checkbox" id="status_'+data[x].id+'">'+
                                                '<label class="onoffswitch-label" for="status_'+data[x].id+'">'+
                                                    '<span class="onoffswitch-inner"></span>'+
                                                    '<span class="onoffswitch-switch"></span>'+
                                                '</label>'+
                                            '</div>'+
                                        '</div>'+
                                    '</td>'+ 
                                
                                '<td> '+data[x].author_name +'</td>'+
                                '<td> '+(data[x].created_at ||'0000-00-00')+'</td>'+
                                '<td> '+(data[x].updated_at ||'0000-00-00')+'</td>'+
                            '</tr>';
                      }
                      $("#subsciberData").html(subscriberData);
                      tableSubsciber.DataTable({
                          'destroy':true,
                          'responsive': true
                         });
                  }else{
                      $("#subsciberData").html('<tr><td align="center" colspan="11">NO RECORD FOUND</td></tr>');
                  }

     }

    function createSubscriber(evt) {
        $('.subscriberSubmit').html("Processing...").attr('disabled', true);
        $('#subsciberHeader').hide();
        var obj;
            obj = $(evt.currentTarget).parents('form.createSubscriberForm').serialize();
        var url = BASE_URL + "/reports/createReportSubcriber"
            $.post(url, obj).done(function(data) {
                try{
                    if (data.value == "success") {

                        title = "Subscribed";
                        message ="The user has been subscribed for email report successfully";
                        type = success;
                        const info = {title:title, message:message, type:type, position:topfullwidth};
                        
                        $('.subscriberSubmit').html("Submit").attr('disabled', false);
                        if ($.fn.DataTable.isDataTable("#subsciberTable")) {
                            $("#subsciberTable").DataTable().destroy();
                        }
                        loadSubscribers();
                        toastNotification(info);
                    } else {
                        $('.subscriberSubmit').html("Submit").attr('disabled', false);
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
    loadSubscribers();
    function loadSubscribers(){
        $('#subsciberHeader').hide();
         var url = BASE_URL + "/reports/loadReportSubscriber"
         $("#subsciberData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
         $.get(url, function(data) {
             console.log(data.reportSubscriptions);
             subscriberTable(data.reportSubscriptions)
         });
     }

    function getFitterSubscriber() {
        $('#subscriberSearch').html("Searching...").attr('disabled', true);
        $('#subsciberHeader').hide(); 
        var obj = $('#subscriberSearchForm').serialize();
        console.log(obj);
        $("#subsciberData").html('<tr><td align="center" colspan="11"><i class="fa fa-spinner fa-spin fa-3x"></td></tr>');
        var url = BASE_URL + "/reports/fitterReportSubscriber"
        $.post(url, obj).done(function(data) {
            try{
                if (data.value == "success") {
                    $('#subscriberSearch').html("Search").attr('disabled', false);
                    subscriberTable(data.reportSubscriptions)

                } else {
                    $('#subscriberSearch').html("Search").attr('disabled', false);
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

    
    // }
    </script>