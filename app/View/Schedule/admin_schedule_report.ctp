<h1>Scheduling Report</h1>
<div class="row">
    <div class="col-md-6">
        <h3>Please choose a date range</h3>
        <form id="populateTable" action="/ajax/schedule/reportByCompany" method="post">
            <div class="col-md-5">
                <label>Start Date</label>
                <input class="input form-control datepicker" type='date' id='startDate' required />
            </div>
            <div class="col-md-5">
                <label>End Date</label>
                <input class="input form-control datepicker" type='date' id='endDate' required />
            </div>
            <div class='col-md-2'>
                <label> </label>
                <input type='submit' class='btn btn-success' value='Update Table' />
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h2>Results</h2>
         <table id='scheduleTable' class='table table-responsive table-striped table-hover table-condensed' >
                               <thead><tr>
                                       <th>First Name</th>
                                       <th>Last Name</th>
                                       <th>Approvals</th>
                                       <th>Denials</th>
                                       <th>Pending</th>
                                       <th>Options</th>
                           </tr></thead>
                   </table>
        <a href="#" id="exportCompanyData" class="btn btn-warning"><i class='fa fa-download'></i> Export To Excel</a>
    </div>
</div>

<?= $this->element('modals/employeeScheduleDetails'); ?>
<?php $this->append('scripts'); ?>
<script>
    var specif;
    function viewEmployeeDetails(clicked) {
        
        $parentRow = $(clicked).parent().parent();
        $name = $parentRow.children('td')[0].innerText + " " + $parentRow.children('td')[1].innerText;
        $("#employeeName").text($name);
        $id = $(clicked).data('id');
        $("#exportEmployeeData").data('id', $id);
        console.log($("#exportEmployeeData").data('id'));
        
        var newUrl = "/ajax/schedule/reportByUser/" + $id + "/" + $("#startDate").val().replace(/\//g, "-") + "/" + $("#endDate").val().replace(/\//g, "-");
           // console.log(newUrl);
    specif.ajax.url(newUrl).load();
 $("#employeeScheduleDetailsModal").modal('show');
}
</script>
<?php $this->end(); ?>

<?php $this->append('jquery-scripts'); ?>

$("#exportCompanyData").click(function(e) {
$(this).attr('href', "/admin/schedule/exportByCompany/" + $("#startDate").val().replace(/\//g, "-") + "/" + $("#endDate").val().replace(/\//g, "-"));
});

$("#exportEmployeeData").click(function(e) {
$(this).attr('href', "/admin/schedule/exportByUser/" + $(this).data('id') + "/" + $("#startDate").val().replace(/\//g, "-") + "/" + $("#endDate").val().replace(/\//g, "-"));
});

$("#populateTable").submit(function(e) {

            e.preventDefault();
           
            // console.log(clickedEvent);
            var newUrl = "/ajax/schedule/reportByCompany/" + $("#startDate").val().replace(/\//g, "-") + "/" + $("#endDate").val().replace(/\//g, "-");
           // console.log(newUrl);
    emptable.ajax.url(newUrl).load();
            
        });
        
        var specific =  $("#employees-tbl1").DataTable({

        "columns": [
            {
                "data": "customer"},
            {
             "data": "job" 
            },
            {"data": "start"},
            {"data": "end"},
            {"data": "position"},
            {"data": "approved"},
            {"data": "notice"}

        ]
        
    });
    specif = specific;
    
    
    var emptable =  $("#scheduleTable").DataTable({

        "columns": [
            {
                "data": "first"},
            {
             "data": "last" 
            },
            {"data": "approvals"},
            {"data": "denials"},
            {"data": "pending"},
            {"data": "options"},

        ]
        
    });
<?php $this->end(); ?>