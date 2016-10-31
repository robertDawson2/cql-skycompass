<!-- Modal -->
<div id="expenseReportModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-lg fa-reply"></i> Expense Report</h4>
      </div>
      <div class="modal-body">
          <h4>Please choose start and end dates for your report.</h4>
          
          <div class="row" style='margin: 10px; padding: 15px; border-radius: 5px; border: 1px solid #abdede;'>
              <div class="col-sm-6" style='border-right: 1px solid #dedede; margin-bottom: 15px;'>
                  <input type='text' id='startDateReport' class='datepicker' />
              </div>
                <div class="col-sm-6">
                  <input type='text' id='endDateReport' class='datepicker' />
              </div>
          </div>
          <a class='btn btn-lg btn-success' href="#" id="expenseReportGenerate"><i class="fa fa-refresh"></i> Generate Report</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
