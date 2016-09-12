<!-- Modal -->
<div id="expenseModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-lg fa-clock-o"></i> Expense Entry</h4>
      </div>
      <div class="modal-body">
          <h4>How would you like to enter your expenses?</h4>
          
          <div class="row" style='margin: 10px; padding: 15px; border-radius: 5px; border: 1px solid #abdede;'>
              <div class="col-sm-6" style='border-right: 1px solid #dedede; margin-bottom: 15px;'>
                  <a href='/admin/expenses/add' class='btn btn-lg btn-info btn-block'><i class='fa fa-2x fa-edit'></i> Single Entry</a>
              </div>
     
                <div class="col-sm-6">
                  <a href='/admin/expenses/travelSheet' class='btn btn-lg btn-info btn-block'><i class='fa fa-2x fa-list-alt'></i> Travel Sheet</a>
              </div>
             
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>