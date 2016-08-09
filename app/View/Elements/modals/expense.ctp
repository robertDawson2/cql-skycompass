<!-- Modal -->
<div id="expenseModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-lg fa-list"></i> New Expense Sheet</h4>
      </div>
      <div class="modal-body">
          <h4>Choose the Customer/Job for which this bill applies to:</h4>
          
          <div class="row" style='margin: 10px; padding: 15px; border-radius: 5px; border: 1px solid #abdede;'>
              <div class="col-sm-12" style='margin-bottom: 15px;'>
                  <form method='post' name='FormExpenseSheet' action='/admin/expenses/chooseExpenseCustomer'>
                      <div class='form-group'>
                      <label>Customer/Job</label>
                      <select id='customerList'  data-placeholder='Select a customer or job...' class="form-control select2 validation" data-required='required' name='data[TimeEntry][customer_id]' style="width: 100%;">
                   <option></option>
                   <?php foreach($customerList as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                  </div> 
                      <div class='form-group'>
                          
                          <input type='submit' value='Create Expense Sheet' class='btn btn-success' />
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>