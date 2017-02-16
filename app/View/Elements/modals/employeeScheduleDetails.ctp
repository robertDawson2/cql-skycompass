<!-- Modal -->
<div id="employeeScheduleDetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style='width: 95%;'>

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-lg fa-expand"></i> Employee Details - <em><span id="employeeName"></span></em></h4>
      </div>
      <div class="modal-body">
          
           <div class='container' style='width: 100%;'>
               
            <div class='row'>
            <div id="colAvailableEmployees" class='col-md-12'><h4>Scheduling Breakdown</h4>
                 
                   <table id='employees-tbl1' class='table table-responsive table-striped table-hover table-condensed' >
                               <thead><tr>
                                       <th>Customer</th>
                                       <th>Job</th>
                                       <th>Start</th>
                                       <th>End</th>
                                       <th>Position</th>
                                       <th>Approved?</th>
                                       <th>Notice</th>
                                       
                           </tr></thead>
                   </table>
            
            </div> 
                
                </div></div>
      </div>
      <div class="modal-footer">
         <a href="#" id="exportEmployeeData" data-id='0'  class="btn btn-warning"><i class='fa fa-download'></i> Export To Excel</a>
      </div>
    </div>

  </div>
</div>
<?php $this->append('scripts'); ?>

<?php $this->end(); ?>
