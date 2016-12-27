  

<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> New Job Add <?php
            if(isset($customer)):
                echo " - " . $customer['Customer']['name'];
            endif; 
            ?>
            </h3>

          
        </div>
        <!-- /.box-header -->
        <?php echo $this->Form->create('add'); ?>
        <div class="box-body">
          <div class="row">
              <div class="col-md-6">
                  <div class='form-group'>
                      <label>Customer/Job</label>
                      <select id='customerList' <?php if(isset($customer)) { ?>disabled<?php } ?> data-placeholder='Select a customer or job...' class="form-control <?php if(!isset($customer)) { ?>select2<?php } ?> validation" data-required='required' name='data[Job][customer_id]' style="width: 100%;">
                   <?php if(!isset($customer)) { ?>
                          <option></option>
                   <?php } ?>
                   <?php foreach($customers as $p): ?>
                    <?php $selected = "";
                    if(isset($customer))
                        if($customer['Customer']['id'] == $p['id'])
                            $selected = "selected";
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                  </div> 
              </div>
              <div class="col-md-3">
                  <div class='form-group'>
                                       <label># People Served</label>
                      <input type="tel" name='data[Job][people_served_count]' data-type="number" data-max="600"   class='form-control validation' data-required='required' default='0'>
                                   </div>
              </div>
          </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Job Information</h4>
<div class='form-group'>
                      <label>Job Name</label>
                     <input type='text' name="data[Job][name]" class='form-control validation' data-type="text" data-required='required' placeholder='Insert Job Name...' />
                     
</div>
                    <div class="form-group">
                <label>Service Area</label>
                <select name='data[Job][service_area_id]' id='serviceItemList' data-placeholder='Select a service item...' class="form-control select2 validation" data-required = "required" style="width: 100%;">
                   <option></option>
                   <?php foreach($serviceAreas as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option class="main-item" value="<?= $p['ServiceArea']['id']; ?>" disabled="disabled" <?= $selected; ?>>
                        <?=$p['ServiceArea']['name']; ?>
                    </option>
                    <?php foreach($p['Children'] as $c): ?>
                     <option value = "<?= $c['id']; ?>" class="child-item" <?= $selected; ?>>
                        <?=$c['name']; ?>
                    </option>
                    <?php endforeach; ?>
                    
                    <?php endforeach; ?>
                </select>
              </div>
                  <div class='form-group'>
                      <label>Scheduler Task List Template</label>
                      <select id='taskList'  data-placeholder='Select a task list template...' class="form-control validation" data-required='required' name='data[Job][SchedulerTaskList]' style="width: 100%;">
      
                   <?php foreach($taskLists as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option value="<?= $p['TaskListTemplate']['id'];?>" <?= $selected; ?>>
                        <?=$p['TaskListTemplate']['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                      <label>Team Leader Task List Template</label>
                      <select id='taskList'  data-placeholder='Select a task list template...' class="form-control validation" data-required='required' name='data[Job][TeamLeaderTaskList]' style="width: 100%;">
      
                   <?php foreach($taskLists as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option value="<?= $p['TaskListTemplate']['id'];?>" <?= $selected; ?>>
                        <?=$p['TaskListTemplate']['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                      <label>Employee Task List Template</label>
                      <select id='taskList'  data-placeholder='Select a task list template...' class="form-control validation" data-required='required' name='data[Job][EmployeeTaskList]' style="width: 100%;">
      
                   <?php foreach($taskLists as $p): ?>
                    <?php $selected = "";
                    
                    ?>
                    <option value="<?= $p['TaskListTemplate']['id'];?>" <?= $selected; ?>>
                        <?=$p['TaskListTemplate']['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                  </div> 
                    <div class='form-group'>
                <label>Job Cost</label>
                <input name='data[Job][balance]'  data-placeholder='Enter cost with no dollar sign...'  class="form-control validation"  data-required='required' />
                    
                </div>
                 
                      
                  <h4>Employees</h4>
                      <TABLE><tr><td width='50%' style='padding: 5px;'>
                                  
                                   <div class='form-group'>
                                       <label># Team Leaders</label>
                      <input type="tel" name='data[Job][team_leader_count]' data-type="number" data-max="10"   class='form-control hour-spinner validation' data-required='required' default='0'>
                                   </div>
                              </td>
                              <td style='padding: 5px;'>
                                  
                                   <div class='form-group'>
                                       <label># Regular Employees</label>
                      <input  type="tel"  name='data[Job][employee_count]' data-type='number' data-max="10" class='form-control minute-spinner validation' data-required='required' default='0'>
                                   </div>
                              </td>
                          </tr>
                      </table>
              </div>
              
             
            <div class="col-md-6">
                <h4>Optional Fields</h4>
                 <div class='form-group'>
                <label>IDD or BH</label>
                <input name='data[Job][IDD_BH]'  data-placeholder='Enter IDD or BH here...'  class="form-control validation" style="width: 100%;" />
                    
                </div>
                 <div class='form-group'>
                <label>Engagement Fee Paid?</label>
                <select name='data[Job][eng_fee_paid]' class="form-control validation">
                    <option value='0'>NO</option>
                    <option value='1'>YES</option>
                </select>
                </div>
                 <div class='form-group'>
                <label>Notes</label>
                <textarea  name='data[Job][notes]'  class='form-control' rows='5' width='100%'></textarea>
              </div>
            </div> 
          </div>
            <div class='row'>
                <div class='col-md-offset-10 col-md-2'>
                    <input id='submitButton' type="submit" class='btn btn-primary' value='Create Job' />
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
    
    $("#addAdminAddForm").submit(function(e) {
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