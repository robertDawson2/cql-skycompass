<!-- Modal -->
<div id="availableEmployeesModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style='width: 95%;'>

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-lg fa-clock-o"></i> Employee Scheduler - <em><span id="jobName"></span></em></h4>
      </div>
      <div class="modal-body">
          <style>.empList {min-height: 120px; width: 100%; background-color: white;
            box-shadow: 2px 2px 10px #ccc; border-radius: 5px; padding: 5px; margin: 5px;}
              .close {position:absolute; right: 15px;}
            </style>
           <div class='container' style='width: 100%;'>
               <h3>Customer Name: <em><span id='custName'></span></em> (<span id="custLoc"></span>)</h3>
            <div class='row'><div class='col-md-3'>
                    <h4>Team Leaders (<span id="teamLeadersNeeded">0</span> of <span id="teamLeadersCount">2</span>)</h4><div id='teamLeaders' class='empList'></div>
            <h4>Employees (<span id="employeesNeeded">0</span> of <span id="employeesCount">2</span>)</h4><div id='employees' class='empList'></div></div>
            <div class='col-md-6'><h4>Available Employees</h4>
                 
                   <table id='employees-tbl' class='table table-responsive table-striped table-hover table-condensed' >
                               <thead><tr>
                                       <th>First</th>
                                       <th>Last</th>
                                       <th>Location</th>
                                       <th>Abilities</th>
                                       <th>Notes</th>
                                       <th>Team Leader</th>
                                       <th>Employee</th>
                           </tr></thead>
                   </table></div> 
                    <div class='col-md-3'>Sorting stuff here</div></div></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php $this->append('jquery-scripts'); ?>

    var emptable =  $("#employees-tbl").DataTable({

        "ajax": "/ajax/jobs/scheduleEmployees",
        "columns": [
            {
                "data": "first"},
            {
             "data": "last" 
            },
            {"data": "location"},
            {"data": "abilities"},
            {"data": "notes"},
            {"data": "team-leader"},
            {"data": "employee"}
        ]
        
    })
    .on('order', function() {
        checkTLEcount();
    })
    .on('search', function() {
        checkTLEcount();
    })
    .on('page', function() {
        checkTLEcount();
    })
    .on('draw', function() {
        checkTLEcount();
    });

    
    
   <?php $this->end(); ?>