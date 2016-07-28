 <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Weekly Timesheet Entry Form</h3>

          
        </div>
        <!-- /.box-header -->
        <?php echo $this->Form->create('weekly'); ?>
        
        <div class="box-body">
            <div class="row">
                <div class="col-md-offset-8 col-md-4"><input name="data[TimeEntry][datepicker]" type="week" id="weeklyDatePicker"/></div>
            </div>
            <table class="table table-responsive table-hover table-condensed table-striped">
                <thead>
                <th>Details</th>
                
                <th>Notes</th>
                <th class="table-dates"></th>
                 <th class="table-dates"></th>
                  <th class="table-dates"></th>
                   <th class="table-dates"></th>
                    <th class="table-dates"></th>
                     <th class="table-dates"></th>
                      <th class="table-dates"></th>
                      <th>Total</th>
                </thead>
                <?php for($i=0;$i<10;$i++): ?>
                <tr id="input-row-<?= $i; ?>" data-id="<?= $i; ?>" <?php if($i>0){ echo "style='display: none;'"; } ?>>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                        <div class="form-group inline">
                            <label>Customer</label>
                        <select data-placeholder='Select a customer or job...' class="form-control customerList select2 validation" data-required='required' name='data[<?= $i; ?>][TimeEntry][customer_id]' style="width: 100%;">
                   <option></option>
                </select>
                        </div>
                            </div>
                            <div class="col-md-6">
                        <div class="form-group inline">
                            <label>Svc Item</label>
                        <select name='data[<?= $i; ?>][TimeEntry][item_id]' data-placeholder='Select a service item...' class="form-control serviceItemList select2 validation" data-required = "required" style="width: 100%;">
                   <option></option>
                </select>
                        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                   <div class="form-group inline">
                            <label>Payroll Item</label>
                        <select name='data[<?= $i; ?>][TimeEntry][payroll_item_id]' data-placeholder='Select a payroll item...'  class="form-control payrollItemList select2 validation" data-required = "required" style="width: 100%;">
                    <option></option>
                </select>
                   </div>
                            </div>
                            <div class="col-md-6">
                    <div class="form-group inline">
                            <label>Class</label>
                         <select name='data[<?= $i; ?>][TimeEntry][class_id]' data-placeholder='Select a class...'  class="form-control classItemList select2 validation" data-required = "required"  style="width: 100%;">
                    <option></option>
                </select> 
                    </div>
                        </div>
                        </div>
                    </td>
                    <td>
                        <input type="text"  name='data[<?= $i; ?>][TimeEntry][notes]'  class='form-control'  width='100%' />
                    </td>
                    <td>
                        <input type="text" name="data[<?= $i; ?>][TimeEntry][sunday]" class="form-control" maxlength="6" />
                    </td>
                    <td>
                        <input type="text" name="data[<?= $i; ?>][TimeEntry][monday]" class="form-control" maxlength="6" />
                    </td>
                    <td>
                        <input type="text" name="data[<?= $i; ?>][TimeEntry][tuesday]" class="form-control" maxlength="6" />
                    </td>
                    <td>
                        <input type="text" name="data[<?= $i; ?>][TimeEntry][wednesday]" class="form-control" maxlength="6" />
                    </td>
                    <td>
                        <input type="text" name="data[<?= $i; ?>][TimeEntry][thursday]" class="form-control" maxlength="6" />
                    </td>
                    <td>
                        <input type="text" name="data[<?= $i; ?>][TimeEntry][friday]" class="form-control" maxlength="6" />
                    </td>
                    <td>
                        <input type="text" name="data[<?= $i; ?>][TimeEntry][saturday]" class="form-control" maxlength="6" />
                    </td>
                    <td class="total-col" id='row-<?= $i; ?>'>
                        0.00
                    </td>
                </tr>
                
                <?php endfor; ?>
            </table>
                     
                     
                 
                      
              
              <style>
                  .main-item {font-weight: bold;}
                  .child-item {font-style: italic; font-size: 95%; padding-left: 25px;}
                  .form-control {font-size: 90%;}
                  .table-dates {
                      width: 60px;
                  }
              </style>
                
              
            <div class='row'>
                <div class='col-md-offset-10 col-md-2'>
                    <button type="submit" class='btn btn-primary'><i class='fa fa-arrow-cirle-right'></i> Submit Time</button>
                </div>
            </div>
        </div>
        
        <?php $this->Form->end(); ?>
 </div>

<?php $this->append('scripts'); ?>
<script>
   
        var d = new Date();
        var dateString = "" + (d.getMonth()+1) + "/" + d.getDate() + "/" + d.getFullYear();
        $("#weeklyDatePicker").val(dateString);
        makeChanges();
    
    $(".form-control").change(function() {
        $id = $(this).closest("tr").data('id');
        $id += 1;
        if($("#input-row-"+$id).css('display') === 'none')
            $("#input-row-"+$id).fadeIn();
    });
    
    $("#weeklyAdminWeeklyForm").submit(function(e) {
       $isValid = validateForm(); 
       
       if(!$isValid)
            e.preventDefault();
    });
    function validateForm() {
        // Validate Customer
        $return = true;
        $(".validation").each(function() {
             $(this).closest('.form-group').removeClass('has-error');
                    $(this).closest(".form-group").children('.help-block').remove();
            $value = $(this).val();
            if($(this).data('required') === 'required')
            {
                if($value === "")
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
    function populateLists() {
        $.ajax({
      
           url: "/admin/customers/ajaxGetCustomerList",
           dataType: "json",
           success: function(data) {
              $.each(data, function(index, value) {
                  $newAdd = "<option value='" + value.id + "' class='" + value.class + "'>";
                  $newAdd += value.name;
                  $newAdd += "</option>";
                  $(".customerList").append($newAdd);
              });
              $(".customerList").data('placeholder', "Select a customer or job...");
           }
   
    });
    $.ajax({
      
           url: "/admin/timeEntry/ajaxGetPayrollItemList",
           dataType: "json",
           success: function(data) {
              $.each(data, function(index, value) {
                  $newAdd = "<option value='" + value.id + "' class='" + value.class + "'>";
                  $newAdd += value.name;
                  $newAdd += "</option>";
                  $(".payrollItemList").append($newAdd);
              });
              $(".payrollItemList").data('placeholder', "Select a payroll item...");
           }
   
    });
    
    $.ajax({
      
           url: "/admin/timeEntry/ajaxGetClassList",
           dataType: "json",
           success: function(data) {
              $.each(data, function(index, value) {
                  $newAdd = "<option value='" + value.id + "' class='" + value.class + "'>";
                  $newAdd += value.name;
                  $newAdd += "</option>";
                  $(".classItemList").append($newAdd);
              });
              $(".classItemList").data('placeholder', "Select a class...");
           }
   
    });
    
    $.ajax({
      
           url: "/admin/timeEntry/ajaxGetServiceItemList",
           dataType: "json",
           success: function(data) {
              $.each(data, function(index, value) {
                  $newAdd = "<option value='" + value.id + "' class='" + value.class + "'>";
                  $newAdd += value.name;
                  $newAdd += "</option>";
                  $(".serviceItemList").append($newAdd);
              });
              $(".serviceItemList").data('placeholder', "Select a service item...");
           }
   
    });
    
    return true;
    }
    
         $answer = populateLists();
                 if($answer) {
        
            
            //Initialize Select2 Elements
    $(".select2").select2({
       
        templateResult: function (data) {    
    // We only really care if there is an element to pull classes from
    if (!data.element) {
      return data.text;
    }

    var $element = $(data.element);

    var $wrapper = $('<span></span>');
    $wrapper.addClass($element[0].className);

    $wrapper.text(data.text);

    return $wrapper;
  }
    });
            
           
    }
   
   
 
    
    
    
//Initialize the datePicker(I have taken format as mm-dd-yyyy, you can have your own)
$("#weeklyDatePicker").datepicker({
 autoclose: true,
    format: 'mm/dd/yy'
});

//Get the value of Start and End of Week
$('#weeklyDatePicker').on('change', function (e) {
   makeChanges();
   
});
function makeChanges() {
  value = new Date($("#weeklyDatePicker").val());
    date1 = (value.getDate() - value.getDay());
    date2 = date1+7;
    firstDate = value;
    lastDate = value;
    
    firstDate.setDate(date1);
   // lastDate.setDate(date2);
    daysofweek = ['Sat','Sun','Mon','Tue','Wed','Thu','Fri'];
    currDate=firstDate;
    $(".table-dates").each(function() {
        
       $(this).html(daysofweek[currDate.getDay()] + "<br />" + (currDate.getMonth()+1) + "/" + currDate.getDate());
       currDate.setDate(currDate.getDate()+1);
    });
   
     
}

    </script>
<?php $this->end(); ?>