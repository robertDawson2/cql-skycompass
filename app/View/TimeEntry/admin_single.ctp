  

<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Single Time Entry Form</h3>

          
        </div>
        <!-- /.box-header -->
        <?php echo $this->Form->create('single'); ?>
        <div class="box-body">
          <div class="row">
              <div class="col-md-6">
                  <div class='form-group'>
                      <label>Organization</label>
                      <select id='customerList'  data-placeholder='Select an organization...' class="form-control select2 validation" data-required='required' name='data[TimeEntry][customer_id]' style="width: 100%;">
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
                      
                      <label>Date of Service</label>
                      <div class="input-group date">
                  <div class="input-group-addon" id="calendar-show">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name='data[TimeEntry][txn_date]' data-max='today' type="text" class="form-control pull-right validation" data-required='required' data-type='date' id="datepicker">
                  </div>
                      </div> 
                 
                      
                  <h4>Duration</h4>
                      <TABLE><tr><td width='50%' style='padding: 5px;'>
                                  
                                   <div class='form-group'>
                                       <label>Hours</label>
                      <input type="tel" name='data[TimeEntry][hours]' data-type="number" data-max="24"   class='form-control hour-spinner validation' data-required='required' default='0'>
                                   </div>
                              </td>
                              <td style='padding: 5px;'>
                                  
                                   <div class='form-group'>
                                       <label>Minutes</label>
                      <input  type="tel"  name='data[TimeEntry][minutes]' data-type='number' data-max="59" class='form-control minute-spinner validation' data-required='required' default='0'>
                                   </div>
                              </td>
                          </tr>
                      </table>
              </div>
              
             
            <div class="col-md-6">
              <div class="form-group">
                <label>Service Item</label>
                <select name='data[TimeEntry][item_id]' id='serviceItemList' data-placeholder='Select a service item...' class="form-control select2 validation" data-required = "required" style="width: 100%;">
                   <option></option>
                   <?php foreach($services as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
              </div>
                 <div class='form-group'>
                <label>Payroll Item</label>
                <select name='data[TimeEntry][payroll_item_id]'  id='payrollItemList' data-placeholder='Select a payroll item...'  class="form-control select2 validation" data-required = "required" style="width: 100%;">
                    <option></option>
                    <?php foreach($payrolls as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                </div>
                 <div class='form-group'>
                <label>Class</label>
                <select name='data[TimeEntry][class_id]'  id='classItemList' data-placeholder='Select a class...'  class="form-control select2 validation" data-required = "required"  style="width: 100%;">
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
                <label>Notes</label>
                <textarea  name='data[TimeEntry][notes]'  class='form-control' rows='5' width='100%'></textarea>
              </div>
            </div> 
          </div>
            <div class='row'>
                <div class='col-md-offset-10 col-md-2'>
                    <input id='submitButton' type="submit" class='btn btn-primary' value='Submit Time' />
                </div>
            </div>
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>


<?php $this->append('scripts'); ?>
<script>
   
   $("#calendar-show").click(function()
   {
       $("#datepicker").datepicker('show');
   });
    
    $("#singleAdminSingleForm").submit(function(e) {
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
                    
                    var max = null;
                    if($(this).data('max') !== undefined)
                    {
                        max = $(this).data('max');
                        if($value > max)
                {
                    $(this).closest('.form-group').addClass('has-error');
                    $(this).closest(".form-group").append('<span class="help-block">This field must be less than ' + max + '.</span>');
                    $return = false;
                }
                    }
                    }
            }
            
            
            
            
        });
        
        return $return;
    }
    
        
    
            
   
            //Initialize Select2 Elements
//    $(".select2").select2({
//       
//        templateResult: function (data) {    
//    // We only really care if there is an element to pull classes from
//    if (!data.element) {
//      return data.text;
//    }
//
//    var $element = $(data.element);
//
//    var $wrapper = $('<span></span>');
//    $wrapper.addClass($element[0].className);
//
//    $wrapper.text(data.text);
//
//    return $wrapper;
//  }
//    });
            
           
    
   
   
 
    
    
    
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });
    
    $(".minute-spinner").spinner({
        min: 0,
        max: 59
    }).val(0);
    $(".hour-spinner").spinner({
        min: 0,
        max: 300
    }).val(0);
</script>
<?php $this->end(); ?>