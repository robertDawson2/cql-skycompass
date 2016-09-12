<div id="total-total" style='float: right; position:fixed; right: 25px; padding: 10px; background: rgba(255,255,255,0.9); font-size: 120%;'>
    <strong>Total Due: $0.00</strong>
</div>
<h3>General Information</h3>
<?php echo $this->Form->create('add', array('type' => 'file')); ?>
  <div class='box-body'>
	<fieldset>
           
            <div class="row">
              <div class="col-md-4">
                  <div class='form-group'>
                      <label>Customer/Job</label>
                      <select id='customerList'  data-placeholder='Select a customer or job...' class="form-control select2 validation" data-required='required' name='data[BillItem][customer_id]' style="width: 100%;">
                   <option></option>
                   <?php foreach($customers as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                  </div> 
                  <div class='form-group'>
                      
                      <label>Departure Date/Time</label>
                      <div class="input-group date">
                  <div class="input-group-addon" id="calendar-show">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name='data[BillItem][depart_date]' data-max='today' type="text" class="form-control pull-right validation" data-required='required' data-type='date' id="datepicker">
                  </div>
                      </div> 
              </div>
                <div class='col-md-4'>

                 <div class='form-group'>
                <label>Class</label>
                <select name='data[BillItem][class_id]'  id='classItemList' data-placeholder='Select a class...'  class="form-control select2 validation" data-required = "required"  style="width: 100%;">
                    <option></option>
                    <?php foreach($classes as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                </div>
                <div class='form-group'>
                      
                      <label>Return Date/Time</label>
                      <div class="input-group date">
                  <div class="input-group-addon" id="calendar-show">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name='data[BillItem][return_date]' data-max='today' type="text" class="form-control pull-right validation" data-required='required' data-type='date' id="datepicker">
                  </div>
                      </div> 
                </div> 
                <div class='col-md-4'> 
                    <div class='form-group' style='padding-bottom: 10px;'>
                      
                      <label>Destination (City, State)</label>
                     
                  <input name='data[BillItem][dest]' type="text" class="form-control pull-right validation" data-required='required' />
                  
                      </div> 
                 
                 <div class='form-group' style='margin-top: 44px;'>
                <label>Trip Notes</label>
                <textarea  name='data[BillItem][description]'  class='form-control' rows='5' width='100%'></textarea>
              </div>
            </div> 
          </div>
		             
	
            
	</fieldset>

  </div>
<?php echo $this->Form->end(); ?>
<hr>
<h3>Corporate Card Expenses</h3>
<div id="total-corporate" style='float: right;padding: 10px;'>
    <strong>Total: $0.00</strong>
</div>
<form id='addToCorp' action='#' method='post' enctype="multipart/form-data">

<table id='corporate-expense-table' class='table table-bordered table-responsive table-striped'>
    <thead>
        <tr>
    <th>Expense Type</th>
    <th>Date</th>
    <th>Amount</th>
    <th>Note</th>
    <th>Receipt?</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
    <tfoot style="background: white;">
<tr id='submit-row'>
            <td>
    <select name='type'>
        <option value='Hotel'>Hotel</option>
        <option value='Air'>Airfare</option>
        <option value='Car'>Rental Car</option>
        <option value='Parking'>Parking</option>
        <option value='Taxi'>Taxi</option>
        <option value='Other'>Other</option>
    </select>
            </td>
            <td>
                <div class="form-group">
    <input type='text' name='date' class="datepicker corp-validation" data-type="date" data-max="today" data-required ="required" />
                </div>
            </td><td>
                <div class="form-group">
    <input name='amount' type='text' data-type="number" data-required="required" class="corp-validation" />
                </div>
            </td><td>
    <textarea name='note'></textarea>
            </td><td>
    <input type='file' name='receipt' id="corporate-file-upload" />
            </td>
            <td>
    
    <input type='submit' class='btn btn-info' value='Add' />
            </td>
    </tr>

    </tfoot>
</table>
</form>
<hr>
<h3>Out Of Pocket Expenses</h3>

<ul class="nav nav-tabs">
	<li class="active"><a href="#meals" data-toggle="tab">Meals</a></li>
	<li><a href="#trans" data-toggle="tab">Transportation</a></li>
        <li><a href="#other" data-toggle="tab">Other</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="meals">
        <div class='row'>
            <div class='col-md-10 col-md-offset-1'>
        <div class='description'>
        <strong>Itemization Of Meals (Quarters)</strong> - CQL reimburses meals per diem at $40 
        (except for New York City and Chicago at $50). Receipts are not necessary. Travel time is recorded in Quarter Days, 
        with 1 Quarter reimbursed for breakfast, 1 Quarter for lunch, and 2 Quarters for dinner. Travelers are paid according 
        to the meals for which they are eligible. Check the appropriate meals below.
        </div>
        </div>
    </div>
        <div class='row'>
            <div class='col-md-12'>
                <div style="float: right;
padding: 10px;
width: 250px;
border: 1px solid #dedede;
border-radius: 10px;
font-style: italic;
text-align: right;">
                    <input type="checkbox" id="checkForNYC" /> Check this box if you were visiting Chicago or New York City
                </div>
                <table class='table table-responsive table-striped table-condensed'>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                MON
                            </th>
                            <th>
                                TUE
                            </th>
                            <th>
                                WED
                            </th>
                            <th>
                                THU
                            </th>
                            <th>
                                FRI
                            </th>
                            <th>
                                SAT
                            </th>
                            <th>
                                SUN
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Breakfast</strong>
                            </td>
                            <?php for($i=0; $i<7; $i++): ?>
                            <td>
                            <input data-type="1" class="breakfast meal" type='checkbox' name='[<?=$i; ?>][breakfast]' />
                            </td>
                            <?php endfor; ?>
                        </tr>
                        <tr>
                            <td>
                                <strong>Lunch</strong>
                            </td>
                            <?php for($i=0; $i<7; $i++): ?>
                            <td>
                            <input data-type="1" class="meal" type='checkbox' name='[<?=$i; ?>][lunch]' />
                            </td>
                            <?php endfor; ?>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    Dinner
                                </strong>
                            </td>
                            <?php for($i=0; $i<7; $i++): ?>
                            <td>
                            <input data-type="2" class="meal" type='checkbox' name='[<?=$i; ?>][dinner]' />
                            </td>
                            <?php endfor; ?>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan='7'></td>
                            
                            <td id="total-meals"><strong>Total: $0.00</strong></td>
                    </tfoot>
                </table>
            </div>
        </div>
        
    </div>
    <div class="tab-pane" id="trans">
        <div id="total-trans" style='float: right;padding: 10px;'>
    <strong>Total: $0.00</strong>
</div>
        <form id='transForm' action='#' method='post'>

<table id='transportation-expense-table' class='table table-bordered table-responsive table-striped'>
    <thead>
        <tr>
    <th>From</th>
    <th>To</th>
    <th>Date</th>
    <th>Car/Taxi</th>
    <th>Mileage</th>
    <th>Receipt?</th>
    <th>Cost</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
    <tfoot>
<tr id='submit-row'>
            <td>
                <div class='form-group'>
    <input type='text' name='trans-from' class='trans-validation' data-required='required' />
                </div>
            </td>
            <td>
                <div class='form-group'>
    <input type='text' name='trans-to' class='trans-validation' data-required='required' />
                </div>
            </td><td>
                <div class='form-group'>
    <input name='trans-date' type='text' data-type='date' data-max='today' data-required='required' class="datepicker trans-validation" style='width: 100px;'/>
                </div>
            </td><td>
    <select name='trans-taxi-car'>
        <option value='Car'>Car</option>
        <option value='Taxi'>Taxi</option>

    </select>
            </td><td>
                <div class='form-group'>
                <input type='text' name='trans-mileage' data-type='number' data-min='0' data-required='required' style='width: 100px;' class='trans-validation' />
                </div>
                
            </td>
            <td>
                <input type="file" name="trans-receipt" id='trans-file-upload' style='width: 150px;'/>
            </td>
            <td>
    
    <input type='submit' class='btn btn-info' value='Add' />
            </td>
    </tr>

    </tfoot>
</table>
</form>
    </div>
    
    <div class="tab-pane" id="other">
        <div id="total-other" style='float: right;padding: 10px;'>
    <strong>Total: $0.00</strong>
</div>
        <form id='addToOther' action='#' method='post'>

<table id='other-expense-table' class='table table-bordered table-responsive table-striped'>
    <thead>
        <tr>
    <th>Date</th>
    <th>Amount</th>
    <th>Description</th>
    <th>Receipt?</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
    <tfoot>
<tr id='submit-row'>
            
            <td>
                <div class='form-group'>
    <input type='text' name='other-date' class="datepicker other-validation" data-type='date' data-max='today' data-required='required' />
                </div>
            </td><td>
    <div class='form-group'>
                <input name='other-amount' type='text' class='other-validation' data-type='number' data-min='0' data-required='required' />
    </div>
            </td><td>
                <div class='form-group'>
    <textarea name='other-note' class='other-validation' data-required='required'></textarea>
                </div>
            </td><td>
    <input type='file' name='other-receipt' id="other-file-upload" />
            </td>
            <td>
    
    <input type='submit' class='btn btn-info' value='Add' />
            </td>
    </tr>

    </tfoot>
</table>
</form>
    </div>
    
</div>

<h3>Submission Disclaimer</h3>
<div class="form-group" style="padding: 10px; background: rgba(255,255,255,0.4); margin-bottom: 10px;">
<input class='validation' data-required='required' type='checkbox' id='disclaimer-accept' /> &nbsp;<strong><em>I hereby certify that this report is a true and accurate representation of expenses incurred.</em></strong>
</div>

<p>
    <a href='#' id='submit-all' class='btn btn-info btn-lg'><i class="fa fa-arrow-circle-right"></i> Submit</a>
</p>

<style>
    .remove {
        color: red;
    }
    .remove:hover {
        color: pink;
    }
    .tab-pane {
        background-color: white;
        padding: 20px;
        border: 1px solid #dedede;
        border-radius: 4px;
    }
    </style>
    


<?php $this->append('scripts'); ?>
<script>
    
    var total = {
        corporate: 0.00,
        meals: 0.00,
        trans: 0.00,
        other: 0.00,
        tot: 0.00
    };
    var mileage = <?= $mileage; ?>;
    var quarter = 10.00;
    var rowCounter = 0;
                var formData = new FormData();
    
    function updateTotals(category, value)
    {

        total[category] += parseFloat(value);
        
        if(category != 'corporate')
        total['tot'] += parseFloat(value);
     //   console.log(total);
        
        var field = "#total-" + category;

        $(field).html("<strong>Total: $" + total[category].toFixed(2) + "</strong>");
        
        
        $("#total-total").html("<strong>Total Due: $" + total['tot'].toFixed(2) + "</strong>");
    }
    
    $(".meal").change(function() {
        var whichWay = 1;
    if(!this.checked) {
        whichWay = -1;
}
            updateTotals('meals', (whichWay * quarter * parseInt($(this).data('type'))));
        //Do stuff
    
});

$("#submit-all").click(function(e) {
    e.preventDefault();
      $("#addAdminTravelSheetForm").submit();
      
    } );
$("#checkForNYC").change(function() {
    if(this.checked){
        quarter = 12.50;
        total['tot'] -= total['meals'];
        $quarters = total['meals']/10;
        total['meals'] = 0.00;
        updateTotals('meals', (quarter * $quarters));
    }
    else
    {
        quarter = 10.00;
        total['tot'] -= total['meals'];
        $quarters = total['meals']/12.50;
        total['meals'] = 0.00;
        updateTotals('meals', (quarter * $quarters));
    }
});


$("#addAdminTravelSheetForm").submit(function(e) {
    
   $isValid = validateForm(".validation"); 
       
       if(!$isValid)
            e.preventDefault();
        
        $(".meal").each(function() {
            if($(this).is(':checked'))
            {
              $html = "<input type='hidden' name='data[meals]" + $(this).attr('name') + "' value='1' />";
              $("#addAdminTravelSheetForm").append($html);
        }
    });
    
    if($("#checkForNYC").is(':checked'))
    {
        $html = "<input type='hidden' name='data[meals][nyc]' value='1' />";
              $("#addAdminTravelSheetForm").append($html);
          }
          else
          {
            $html = "<input type='hidden' name='data[meals][nyc]' value='0' />";
              $("#addAdminTravelSheetForm").append($html);  
              }
            
            
//            for (var [key, value] of formData.entries()) { 
//                console.log(key, value);}
                
           // upload the receipt images to the server
           $.ajax({
               url: "/admin/expenses/ajaxUploadReceipts",
               contentType: false,
               data: formData,
               processData: false,
               cache: false,
               type: 'POST',
               success: function(data) {
               console.log(data);
    }
               });
            


        
        
    });
    
    
    $("#addToCorp").submit(function(e) {
        // Validation
        var valid = validateForm(".corp-validation");
        
        
        
        if(valid)
        {
            var fileSelect = document.getElementById('corporate-file-upload');
            //console.log(fileSelect.files[0]);
            var fileName = "";
            
            if(fileSelect.files[0] != null)
                fileName = new Date().getTime() + "-" + fileSelect.files[0].name;
            // update corporate total
            updateTotals('corporate', parseFloat($('[name="amount"]').val()).toFixed(2));
            $html = "<tr data-remove='rowCounter-" + rowCounter + "'><td>";
            $html += $('[name="type"]').val();
            $html += "</td><td>";
            $html += $('[name="date"]').val();
             $html += "</td><td>$";
            $html += parseFloat($('[name="amount"]').val()).toFixed(2);
             $html += "</td><td>";
            $html += $('[name="note"]').val();
            $html += "</td><td>";
            

            if(($('[name="receipt"]').val() != ""))
                $html += "<i class='fa fa-lg fa-check-circle' style='color: green;'></i>";
            
            var amount = parseFloat($('[name="amount"]').val()).toFixed(2);
            $html += "</td><td><a data-ival='" + amount + "' onclick='removeRow(this, \"corporate\"); return false;' class='remove' href='#'><i class='fa fa-ban'></i></a></td></tr>";
            
            $("#corporate-expense-table tbody").append($html);
            
            $html = "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[corporate][" + rowCounter + "][type] value='" + $('[name="type"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[corporate][" + rowCounter + "][date] value='" + $('[name="date"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[corporate][" + rowCounter + "][amount] value='" + parseFloat($('[name="amount"]').val()).toFixed(2) + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[corporate][" + rowCounter + "][note] value='" + $('[name="note"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[corporate][" + rowCounter + "][receipt] value='" + fileName + "' />";
            

            if(fileName != "")
                formData.append('files[]', fileSelect.files[0],fileName);
            
            
            rowCounter++;
            $("#addAdminTravelSheetForm").append($html);
           
//            for (var [key, value] of formData.entries()) { 
//                console.log(key, value);}
            $(this)[0].reset();
            
        }
        
        
        e.preventDefault();
    });
    
    $("#transForm").submit(function(e) {
        // Validation
        var valid = validateForm('.trans-validation');
        
        
        
        if(valid)
        {
            
            var amount = parseFloat($('[name="trans-mileage"]').val()) * mileage;

            amount = parseFloat(amount).toFixed(2);
             var fileSelect = document.getElementById('trans-file-upload');
            //console.log(fileSelect.files[0]);
            var fileName = "";
            
            if(fileSelect.files[0] != null)
                fileName = new Date().getTime() + "-" + fileSelect.files[0].name;
            
            updateTotals('trans', amount);
            $html = "<tr data-remove='rowCounter-" + rowCounter + "'><td>";
            $html += $('[name="trans-from"]').val();
            $html += "</td><td>";
            $html += $('[name="trans-to"]').val();
             $html += "</td><td>";
            $html += $('[name="trans-date"]').val();
             $html += "</td><td>";
            $html += $('[name="trans-taxi-car"]').val();
            $html += "</td><td>";
            $html += $('[name="trans-mileage"]').val();
            $html += "</td><td>";
           
            if(($('[name="trans-receipt"]').val() != ""))
                $html += "<i class='fa fa-lg fa-check-circle' style='color: green;'></i>";
            
           
            $html += "</td><td><a data-ival='" + amount + "' onclick='removeRow(this, \"trans\"); return false;' class='remove' href='#'><i class='fa fa-ban'></i></a></td></tr>";
            
            $("#transportation-expense-table tbody").append($html);
            
            $html = "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[trans][" + rowCounter + "][from] value='" + $('[name="trans-from"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[trans][" + rowCounter + "][to] value='" + $('[name="trans-to"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[trans][" + rowCounter + "][date] value='" + $('[name="trans-date"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[trans][" + rowCounter + "][taxi-car] value='" + $('[name="trans-taxi-car"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[trans][" + rowCounter + "][amount] value='" + amount + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[trans][" + rowCounter + "][mileage] value='" + $('[name="trans-mileage"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[trans][" + rowCounter + "][receipt] value='" + fileName + "' />";
            

            if(fileName != "")
                formData.append('files[]', fileSelect.files[0],fileName);
            
            
            rowCounter++;
            $("#addAdminTravelSheetForm").append($html);
            
            $(this)[0].reset();
            
        }
        
        
        e.preventDefault();
    });
    
    $("#addToOther").submit(function(e) {
        // Validation
        var valid = validateForm('.other-validation');
        
        
        
        if(valid)
        {
            var fileSelect = document.getElementById('other-file-upload');
            //console.log(fileSelect.files[0]);
           var fileName = "";
            
            if(fileSelect.files[0] != null)
                fileName = new Date().getTime() + "-" + fileSelect.files[0].name;
            
              updateTotals('other', parseFloat($('[name="other-amount"]').val()).toFixed(2));
            $html = "<tr data-remove='rowCounter-" + rowCounter + "'><td>";
            
            $html += $('[name="other-date"]').val();
             $html += "</td><td>$";
            $html += parseFloat($('[name="other-amount"]').val()).toFixed(2);
             $html += "</td><td>";
            $html += $('[name="other-note"]').val();
            $html += "</td><td>";
           
            if(($('[name="other-receipt"]').val() != ""))
                $html += "<i class='fa fa-lg fa-check-circle' style='color: green;'></i>";
            
            $html += "</td><td><a data-ival='" + parseFloat($('[name="other-amount"]').val()).toFixed(2) +"' onclick='removeRow(this, \"other\"); return false;' class='remove' href='#'><i class='fa fa-ban'></i></a></td></tr>";
            
            $("#other-expense-table tbody").append($html);
            
            
            $html = "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[other][" + rowCounter + "][date] value='" + $('[name="other-date"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[other][" + rowCounter + "][amount] value='" + parseFloat($('[name="other-amount"]').val()).toFixed(2) + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[other][" + rowCounter + "][note] value='" + $('[name="other-note"]').val() + "' />";
            $html += "<input class='rowCounter-" + rowCounter + "' type='hidden' name=data[other][" + rowCounter + "][receipt] value='" + fileName + "' />";
            

            if(fileName != "")
                formData.append('files[]', fileSelect.files[0],fileName);
            
            
            rowCounter++;
            $("#addAdminTravelSheetForm").append($html);
           
            $(this)[0].reset();
            
        }
        
        
        e.preventDefault();
    });
    
    function removeRow(row, category)
    {
       var removeVal = parseFloat($(row).data('ival')) * -1;
       classRemoval = $(row).parent().parent().data('remove');
       console.log(classRemoval);
        updateTotals(category, removeVal);
        $(row).parent().parent().fadeOut(500, function() {
       $(this).remove();    
       $("." + classRemoval).remove();
    });
        
    }
    
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true
    });
    
    
    
    
    
    
    
    
    function validateForm($validationClass) {
        // Validate Customer
        $return = true;
        $($validationClass).each(function() {
             $(this).closest('.form-group').removeClass('has-error');
                    $(this).closest(".form-group").children('.help-block').remove();
            $value = $(this).val();
            if($(this).data('required') === 'required')
            {
                if($value === "" || ($(this).attr('type') == 'checkbox' && !$(this).is(":checked")))
                {
                    $(this).closest('.form-group').addClass('has-error');
                    $(this).closest(".form-group").append('<span class="help-block">This field is required.</span>');
                    $return = false;
                }
                else
                {
                    $(this).closest('.form-group').removeClass('has-error');
                    $(this).closest(".form-group").children('.help-block').remove();
                    }
            }
            
            if($(this).data('type') === 'date')
            {
                if(!Date.parse($value))
                {
                    $(this).closest('.form-group').addClass('has-error');
                    $(this).closest(".form-group").append('<span class="help-block">This field must be a date.</span>');
                    $return = false;
                }
                else
                {
                    if($(this).data('max') === 'today')
                    {
                        if($(this).datepicker('getDate') > new Date())
                        {
                            $(this).closest('.form-group').addClass('has-error');
                            $(this).closest(".form-group").append('<span class="help-block">You may not select a date in the future.</span>');
                            $return = false;
                        }
                    else
                    {
                         $(this).closest('.form-group').removeClass('has-error');
                         $(this).closest(".form-group").children('.help-block').remove();
                    }
                    }
                }
            }
            
            if($(this).data('type') === 'number')
            {
                if(!$.isNumeric($value))
                {
                    $(this).closest('.form-group').addClass('has-error');
                    $(this).closest(".form-group").append('<span class="help-block">This field must be a number.</span>');
                    $return = false;
                }
                else
                {
                    $(this).closest('.form-group').removeClass('has-error');
                    $(this).closest(".form-group").children('.help-block').remove();
                    }
            }
            
            
            
            
        });
        return $return;
    }
    
    </script>

<?php $this->end(); ?>