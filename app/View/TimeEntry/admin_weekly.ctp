 <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Weekly Timesheet Entry Form</h3>

          
        </div>
     
     <style>
         .custom-combobox {
    position: relative;
    display: inline-block;
    width: 65%;
    float: right;
    margin-bottom: 2px;
  }
     </style>
  
        <!-- /.box-header -->
        <?php echo $this->Form->create('weekly'); ?>
        
        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-offset-8 col-md-4"><input name="data[TimeEntry][datepicker]" class="validation" id="weeklyDatePicker" data-type="date" data-max="today" /></div>
            </div>
            <table class="table table-responsive table-hover table-striped">
                <thead>
                <th style='min-width: 300px;'>Details</th>
                
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
                
                <?php $showrow = true; for($i=0;$i<5;$i++): ?>
                
                <tr id="input-row-<?= $i; ?>" data-id="<?= $i; ?>" <?php if(!$showrow) { echo "style='display: none;'"; } else { if(!isset($startingEntries[$i])) { echo "class='nextrow'"; } } ?>>
                    <?php if(!isset($startingEntries[$i]))
                        $showrow=false;
                    ?>
                    <td style="max-width: 250px !important;">
                        <div class="row">
                            <div class="col-md-12">
                        <div class="form-group inline">
                            <label>Customer</label>
                        <select data-placeholder='Select a customer or job...' class="short-box customerList select2 validation" data-required='required' name='data[<?= $i; ?>][TimeEntry][customer_id]' style="width: 100%;">
                   <option></option>
                   <?php foreach($customers as $p): ?>
                    <?php $selected = "";
                    if(!empty($startingEntries) && isset($startingEntries[$i]) && $startingEntries[$i][0]['TimeEntry']['customer_id'] === $p['id'])                        
                    {
                        $selected = 'selected="selected"';

                    }
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                        </div>
                            </div>
                            <div class="col-md-12">
                        <div class="form-group inline">
                            <label>Svc Item</label>
                        <select name='data[<?= $i; ?>][TimeEntry][item_id]' data-placeholder='Select a service item...' class="short-box serviceItemList select2 validation" data-required = "required" style="width: 100%;">
                   <option></option>
                   <?php foreach($services as $p): ?>
                    <?php $selected = "";
                    if(!empty($startingEntries) && isset($startingEntries[$i]) && $startingEntries[$i][0]['TimeEntry']['item_id'] === $p['id'])                        
                    {
                        $selected = 'selected="selected"';

                    }
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                        </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                   <div class="form-group inline">
                            <label>Payroll Item</label>
                        <select name='data[<?= $i; ?>][TimeEntry][payroll_item_id]' data-placeholder='Select a payroll item...'  class="short-box payrollItemList select2 validation" data-required = "required" style="width: 100%;">
                    <option></option>
                    <?php foreach($payrolls as $p): ?>
                    <?php $selected = "";
                    if(!empty($startingEntries) && isset($startingEntries[$i]) && $startingEntries[$i][0]['TimeEntry']['payroll_item_id'] === $p['id'])                        
                    {
                        $selected = 'selected="selected"';

                    }
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                   </div>
                            </div>
                            <div class="col-md-12">
                    <div class="form-group inline">
                            <label style="display: inline;">Class</label>
                         <select name='data[<?= $i; ?>][TimeEntry][class_id]' data-placeholder='Select a class...'  class="short-box classItemList select2 validation" data-required = "required"  style="width: 100%;">
                    <option></option>
                    <?php foreach($classes as $p): ?>
                    <?php $selected = "";
                    if(!empty($startingEntries) && isset($startingEntries[$i]) && $startingEntries[$i][0]['TimeEntry']['class_id'] === $p['id'])                        
                    {
                        $selected = 'selected="selected"';

                    }
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select> 
                    </div>
                        </div>
                        </div>
                    </td>
                    <td>
                        <textarea  name='data[<?= $i; ?>][TimeEntry][notes]'  class='form-control' style="min-width: 150px; min-height: 140px;" width='100%'></textarea>
                    </td>
                    <td>
                        <input <?= isset($startingEntries[$i]['Dates']['sunday']) ? 'value="'.$startingEntries[$i]['Dates']['sunday'].'" disabled ' : ''; ?> data-id="<?= $i; ?>" type="text" name="data[<?= $i; ?>][TimeEntry][sunday]" class="form-control hour-counter hour-counter-<?= $i; ?>" maxlength="6" />
                    </td>
                    <td>
                        <input <?= isset($startingEntries[$i]['Dates']['monday']) ? 'value="'.$startingEntries[$i]['Dates']['monday'].'" disabled ' : ''; ?> data-id="<?= $i; ?>" type="text" name="data[<?= $i; ?>][TimeEntry][monday]" class="form-control hour-counter hour-counter-<?= $i; ?>" maxlength="6" />
                    </td>
                    <td>
                        <input <?= isset($startingEntries[$i]['Dates']['tuesday']) ? 'value="'.$startingEntries[$i]['Dates']['tuesday'].'" disabled ' : ''; ?> data-id="<?= $i; ?>" type="text" name="data[<?= $i; ?>][TimeEntry][tuesday]" class="form-control hour-counter hour-counter-<?= $i; ?>" maxlength="6" />
                    </td>
                    <td>
                        <input <?= isset($startingEntries[$i]['Dates']['wednesday']) ? 'value="'.$startingEntries[$i]['Dates']['wednesday'].'" disabled ' : ''; ?> data-id="<?= $i; ?>" type="text" name="data[<?= $i; ?>][TimeEntry][wednesday]" class="form-control hour-counter hour-counter-<?= $i; ?>" maxlength="6" />
                    </td>
                    <td>
                        <input <?= isset($startingEntries[$i]['Dates']['thursday']) ? 'value="'.$startingEntries[$i]['Dates']['thursday'].'" disabled ' : ''; ?>data-id="<?= $i; ?>" type="text" name="data[<?= $i; ?>][TimeEntry][thursday]" class="form-control hour-counter hour-counter-<?= $i; ?>" maxlength="6" />
                    </td>
                    <td>
                        <input <?= isset($startingEntries[$i]['Dates']['friday']) ? 'value="'.$startingEntries[$i]['Dates']['friday'].'" disabled ' : ''; ?>data-id="<?= $i; ?>" type="text" name="data[<?= $i; ?>][TimeEntry][friday]" class="form-control hour-counter hour-counter-<?= $i; ?>" maxlength="6" />
                    </td>
                    <td>
                        <input <?= isset($startingEntries[$i]['Dates']['saturday']) ? 'value="'.$startingEntries[$i]['Dates']['saturday'].'" disabled ' : ''; ?>data-id="<?= $i; ?>" type="text" name="data[<?= $i; ?>][TimeEntry][saturday]" class="form-control hour-counter hour-counter-<?= $i; ?>" maxlength="6" />
                    </td>
                    <td class="total-col" id='total-row-<?= $i; ?>'>
                        <?= isset($startingEntries[$i]['Dates']['total']) ? number_format($startingEntries[$i]['Dates']['total'], 2) : '0.00'; ?>
                        
                    </td>
                    <td style='width: 6px;'>
                <?php if($i > 0): ?>
                        <div class="box-tools pull-right" style='margin-top: -10px; padding-left: 8px;'>
                    <button class="btn btn-box-tool btn-remove-row" type='button'>x</button>
                </div>
                        <?php endif; ?>
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
        <?php if(!empty($dateString)): ?>
            var dateString = "<?php $d = explode("-", $dateString); echo $d[1] . "/" . $d[2] . "/" . $d[0]; ?>";
            <?php else: ?>
        var dateString = "" + (d.getMonth()+1) + "/" + d.getDate() + "/" + d.getFullYear();
        
        <?php endif; ?>
        $("#weeklyDatePicker").val(dateString);
        makeChanges();
  
  $(".btn-remove-row").click(function() {
            $(this).closest("tr").fadeOut();
        });
    $(".hour-counter").change(function() {
        $id = $(this).data('id');
        $total = 0.00;

        $(".hour-counter-"+$id).each(function() {
           
            if($(this).val() !== '')
                $total += parseFloat($(this).val());
            else
                $(this).val(0);
        });

        $("#total-row-" + $id).text($total.toFixed(2));
    });
    $(".form-control").change(function() {
        $id = $(this).closest("tr").data('id');
        $id += 1;
        if($("#input-row-"+$id).css('display') === 'none')
        {
            $("#input-row-"+$id).fadeIn();
            $("#input-row-"+$id).addClass('nextrow');
            $("#input-row-"+($id-1)).removeClass('nextrow');
        }
    });
    
    $("#weeklyAdminWeeklyForm").submit(function(e) {
       $isValid = validateForm(); 
       
       if(!$isValid)
            e.preventDefault();
    });
    function validateForm() {
        // Validate Customer
        $return = true;
        if($("#input-row-0").hasClass('nextrow')){
                return false;
            }
        $(".validation").each(function() {
            
                
            
            if($(this).closest("tr").css('display') !== 'none' && !$(this).closest("tr").hasClass('nextrow')) {
             $(this).closest('.form-group').removeClass('has-error');
                    $(this).closest(".form-group").children('.help-block').remove();
            $value = $(this).val();
            if($(this).data('required') === 'required')
            {
                if($value === "")
                {
                    $(this).closest('.form-group').addClass('has-error');
                    $(this).closest(".form-group").append('<span class="help-block">This field is required.</span>');
                    console.log("Required field error - " + $(this).val());
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
                    console.log("Date field error - " + $(this).val());
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
                    console.log("Number field error - " + $(this).val());
                }
                else
                {
                    $(this).closest('.form-group').removeClass('has-error');
                    $(this).closest(".form-group").children('.help-block').remove();
                    }
            }
            
            
            } // END IF FOR VISIBLE ROW
            else
            {
            $(this).closest("tr").remove();
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
    
         
   
   
 
    
    
    
//Initialize the datePicker(I have taken format as mm-dd-yyyy, you can have your own)
$("#weeklyDatePicker").datepicker({
 autoclose: true,
    format: 'mm/dd/yyyy',
    maxDate: new Date()
});

//Get the value of Start and End of Week
$('#weeklyDatePicker').on('change', function (e) {
   // makeChanges();
   value = new Date($("#weeklyDatePicker").val());
   n = value.getMonth() + 1;
            month = (n < 10) ? ("0" + n) : n;
            n = value.getDate();
            date = (n < 10) ? ("0" + n) : n;
            fulldate = value.getFullYear() + "-"  + month + "-" + date;
            window.location = "/admin/timeEntry/weekly/" + fulldate;
            
   
   
});
function emptyForm() {
    $(".hour-counter").val("");
    $(".hour-counter").prop('disabled', false);
    $(".select2").select2('val', '');
    }
function makeChanges() {
  value = new Date($("#weeklyDatePicker").val());
    date1 = (value.getDate() - value.getDay());
    date2 = date1+7;
    firstDate = value;
    lastDate = value;
            
    firstDate.setDate(date1);
   // lastDate.setDate(date2);
    daysofweek = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    currDate=firstDate;
    $(".table-dates").each(function() {
        
       $(this).html(daysofweek[currDate.getDay()] + "<br />" + (currDate.getMonth()+1) + "/" + currDate.getDate());
       currDate.setDate(currDate.getDate()+1);
       
    });
   
     
}

    </script>
<?php $this->end(); ?>