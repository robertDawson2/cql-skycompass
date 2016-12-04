<!-- Modal -->
<div id="timesheetModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-lg fa-check-square-o"></i> View Full Timesheet</h4>
      </div>
      <div class="modal-body">
          <div id="timesheetViewBody">
              
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-success" id="approveTimesheetBillable">Approve All As Billable</button>
          <button type="button" class="btn btn-warning" id="approveTimesheetNotBillable">Approve All As Not Billable</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>