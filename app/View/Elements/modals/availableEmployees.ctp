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
            <div class='row'><div class='col-md-2'>
                    <h4>Team Leaders (<span id="teamLeadersNeeded">0</span> of <span id="teamLeadersCount">2</span>)</h4><div id='teamLeaders' class='empList'></div>
                    <h4>Employees (<span id="employeesNeeded">0</span> of <span id="employeesCount">2</span>)</h4><div id='employees' class='empList'></div>
                        
                    <div>
                     <button type='button' class='btn btn-info' id='saveEmployees'>Save Changes</button>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
                </div>
            <div id="colAvailableEmployees" class='col-md-8'><h4>Available Employees</h4>
                 
                   <table id='employees-tbl' class='table table-responsive table-striped table-hover table-condensed' >
                               <thead><tr>
                                       <th>First</th>
                                       <th>Last</th>
                                       <th>Loc</th>
                                       <th>Dist</th>
                                       <th>Score</th>
                                       <th>Abilities</th>
                                       <th>Notes</th>
                                       <th>Team Leader</th>
                                       <th>Employee</th>
                           </tr></thead>
                   </table>
            <a style='padding: 20px 2px; position: absolute; top: 33%; right: 0; margin-right: -10px;' href='#' id='show-hide-refine' class='btn btn-default'><i class='fa fa-lg fa-arrow-circle-right'></i></a>
            </div> 
                
                <div id='colRefineResults' class='col-md-2'>
                    
                    <form id="searchForm" action="/admin/jobs/ajaxRefineEmployeeData" method="post">
                    <h2>Refine Results</h2>
                    <p><label for="refine-distance">Distance From Job</label><select name="distance" class="input form-control" id="refine-distance">
                            <option value="999999">All</option>
                            <option value="10">10 mi.</option>
                            <option value="25">25 mi.</option>
                            <option value="50">50 mi.</option>
                            <option value="100">100 mi.</option>
                            <option value="200">200 mi.</option>
                            
                        </select></p>
                        
                            <label>Abilities Needed</label>
                        <div class='scrollPane' style='max-height: 300px;
overflow-y: scroll;
border: 2px solid #aaa;
border-radius: 10px;
padding: 5px 10px;'>
                            <?php foreach($abilities as $i => $ability): ?>
                            <input class='ability-checkbox' type="checkbox" name="abilities-<?= $i; ?>" /> <?= $ability; ?>
                            <br />
                            <?php endforeach; ?>
                        </div>
                            <p>
                                <input id='checkAll' type='checkbox' /> Select All Abilities
                            </p>
                            <p>
                                <input type='submit' class='btn btn-success' value='Apply' />
                            </p>
                    </form>
                </div></div></div>
      </div>
      <div class="modal-footer">
         
      </div>
    </div>

  </div>
</div>
<?php $this->append('scripts'); ?>

<?php $this->end(); ?>
<?php $this->append('jquery-scripts'); ?>

    var currentJob = null;
    $("#checkAll").change(function () {
    $(".ability-checkbox").prop('checked', $(this).prop("checked"));
});
    $("#show-hide-refine").on('click', function(e) {
    e.preventDefault();
    if(!$(this).hasClass('hide-me')) {
    $("#colRefineResults").fadeOut(300, function() {
        
        $("#colAvailableEmployees").addClass('col-md-10');
        $("#show-hide-refine").addClass('hide-me');
        $("#colAvailableEmployees").removeClass('col-md-8');
        $("#show-hide-refine > i").removeClass('fa-arrow-circle-right');
        $("#show-hide-refine > i").addClass('fa-arrow-circle-left');
        
    });
    }
    else
    {
    
    $("#colAvailableEmployees").addClass('col-md-8');
        $("#show-hide-refine").removeClass('hide-me');
        $("#colAvailableEmployees").removeClass('col-md-10');
        $("#show-hide-refine > i").addClass('fa-arrow-circle-right');
        $("#show-hide-refine > i").removeClass('fa-arrow-circle-left');
        $("#colRefineResults").fadeIn(300);
    }
    
    });
    $("#saveEmployees").on('click', function() {
        var id = $("#jobName").data('id');
        var count = 0;
        neededPrev = scheduledEvents[id].employees.needed;
               
                   scheduledEvents[id].employees = {teamLeaders : {}, other: {}, needed : neededPrev, employeeCount : 0};

        
        
        $("#teamLeaders > div").each(function() {
        count++;
        
            scheduledEvents[id].employees.teamLeaders[$(this).data('id')] = {name: $(this).text()}
        });
        $("#employees > div").each(function() {
        count++;
            scheduledEvents[id].employees.other[$(this).data('id')] = {name: $(this).text()}
        });
        scheduledEvents[id].employees.employeeCount = count;
        updateColor();
      
     //   updateQuickView();
     $("#availableEmployeesModal").modal('hide');
    });
    $("#searchForm").submit(function(e) {
            e.preventDefault();
            var formData = $('#searchForm').serialize();
            console.log(clickedEvent);
            var newUrl = "/ajax/jobs/scheduleEmployees/" + currentJob + "/start=" + calendarEvent.start + "&end=" + calendarEvent.end + "&" + formData;
            console.log(newUrl);
    emptable.ajax.url(newUrl).load();
            
        });
    
    
    var emptable =  $("#employees-tbl").DataTable({

        //"ajax": "/ajax/jobs/scheduleEmployees",
        "columns": [
            {
                "data": "first"},
            {
             "data": "last" 
            },
            {"data": "location"},
            {"data": "distance"},
            {"data": "score"},
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