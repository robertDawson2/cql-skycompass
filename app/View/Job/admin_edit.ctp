  

<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Job Edit <?php
            if(isset($customer)):
                echo " - " . $customer['Customer']['name'];
            endif; 
            ?>
            </h3>

          
        </div>
        <!-- /.box-header -->
        <?php echo $this->Form->create('edit'); ?>
        <?= $this->Form->hidden('Job.id'); ?>
        <div class="box-body">
          <div class="row">
              <div class="col-md-6">
                  <div class='form-group'>
                      <label>Organization</label>
                      <select id='customerList' data-placeholder='Select a customer or job...' class="form-control select2 validation" data-required='required' name='data[Job][customer_id]' style="width: 100%;">
                  
                          <option></option>
                   
                   <?php foreach($customers as $p): ?>
                    <?php $selected = "";
                    
                        if($this->data['Customer']['id'] == $p['id'])
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
                      <input type="tel" value='<?= $this->data['Job']['people_served_count']; ?>' name='data[Job][people_served_count]' data-type="number" data-max="1000000"   class='form-control validation' default='0'>
                                   </div>
              </div>
          </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Job Information</h4>
<div class='form-group'>
    <div class='col-md-12' style='margin-bottom: 15px;'>
                     <label>Job Name</label>
                     <input type='text' value='<?= $this->data['Job']['name']; ?>'  name="data[Job][name]" class='form-control validation' data-type="text" data-required='required' placeholder='Insert Job Name...' />
                      </div>
                      
                      <div class='col-md-12'>
                     <a href="#" id='autofill' class="btn btn-sm btn-success"><i class='fa fa-star-half-full'></i> Autofill Address</a>
                      </div>
                     <?= $this->Form->input('Job.addr1', array(
                         'label' => 'Address',
                         'div' =>'col-md-12',
                         'class' => 'input form-control',
                         'type' => 'text',
                         'placeholder' => 'ie. 123 Main St.'
                     )); ?>
                      <?= $this->Form->input('Job.addr2', array(
                         'label' => false,
                          'type' => 'text',
                         'div' =>'col-md-12',
                         'class' => 'input form-control'
                     )); ?>
                     <?= $this->Form->input('Job.city', array(
                         'label' => 'City',
                         'div' =>'col-md-4',
                         'class' => 'input form-control',
                         'placeholder' => 'City'
                     )); ?>
                     <?= $this->Form->input('Job.state', array(
                         'label' => 'State',
                         'div' =>'col-md-4',
                         'class' => 'input form-control',
                         
                     )); ?>
                     <?= $this->Form->input('Job.zip', array(
                         'label' => 'Zip',
                         'div' =>'col-md-4',
                         'class' => 'input form-control',
                         'placeholder' => 'xxxxx-xxxx'
                     )); ?>
</div>
                      
                     
                    <div class="form-group">
                <label>Service Area</label>
                <select name='data[Job][service_area_id]' id='serviceItemList' data-placeholder='Select a service item...' class="form-control select2 validation" data-required = "required" style="width: 100%;">
                   <option></option>
                   <?php foreach($serviceAreas as $p): ?>
                    <?php $selected = "";
                    if($this->data['Job']['service_area_id'] == $p['ServiceArea']['id'])
                        $selected = "selected";
                    ?>
                    <option class="main-item" value="<?= $p['ServiceArea']['id']; ?>" disabled="disabled" <?= $selected; ?>>
                        <?=$p['ServiceArea']['name']; ?>
                    </option>
                    <?php foreach($p['Children'] as $c): 
                        if($this->data['Job']['service_area_id'] == $c['id'])
                        $selected = "selected";
                        ?>
                     <option value = "<?= $c['id']; ?>" class="child-item" <?= $selected; ?>>
                        <?=$c['name']; ?>
                    </option>
                    <?php endforeach; ?>
                    
                    <?php endforeach; ?>
                </select>
              </div>
                  
                    <div class='form-group'>
                <label>Job Cost</label>
                <input value='<?= $this->data['Job']['balance']; ?>' name='data[Job][balance]'  data-placeholder='Enter cost with no dollar sign...'  class="form-control validation"  />
                    
                </div>
                 
                      
                  <h4>Employees</h4>
                      <TABLE><tr><td width='50%' style='padding: 5px;'>
                                  
                                   <div class='form-group'>
                                       <label># Team Leaders</label>
                      <input value='<?= $this->data['Job']['team_leader_count']; ?>'  type="tel" name='data[Job][team_leader_count]' data-type="number" data-max="10"   class='form-control validation' data-required='required'>
                                   </div>
                              </td>
                              <td style='padding: 5px;'>
                                  
                                   <div class='form-group'>
                                       <label># Regular Employees</label>
                      <input value='<?= $this->data['Job']['employee_count']; ?>'   type="tel"  name='data[Job][employee_count]' data-type='number' data-max="10" class='form-control validation' data-required='required'>
                                   </div>
                              </td>
                          </tr>
                      </table>
              </div>
              
             
            <div class="col-md-6">
                <h4>Task Lists</h4>
                <div class='form-group'>
                      <label>Scheduler Task List Template</label>
                      <select id='taskList'  data-placeholder='Select a task list template...' class="form-control validation" data-required='required' name='data[Job][SchedulerTaskList]' style="width: 100%;">
      
                   <?php foreach($taskLists as $p): ?>
                    <?php $selected = "";
                    if($this->data['Job']['SchedulerTaskList'] == $p['TaskListTemplate']['id'])
                        $selected = "selected";
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
                    if($this->data['Job']['TeamLeaderTaskList'] == $p['TaskListTemplate']['id'])
                        $selected = "selected";
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
                    if($this->data['Job']['EmployeeTaskList'] == $p['TaskListTemplate']['id'])
                        $selected = "selected";
                    ?>
                    <option value="<?= $p['TaskListTemplate']['id'];?>" <?= $selected; ?>>
                        <?=$p['TaskListTemplate']['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                  </div> 
                <h4>Optional Fields</h4>
                 <div class='form-group'>
                <label>IDD or BH</label>
                <input value='<?= $this->data['Job']['IDD_BH']; ?>'  name='data[Job][IDD_BH]'  data-placeholder='Enter IDD or BH here...'  class="form-control validation" style="width: 100%;" />
                    
                </div>
                 <div class='form-group'>
                <label>Engagement Fee Paid?</label>
                <select name='data[Job][eng_fee_paid]' class="form-control validation">
                    <option <?= $this->data['Job']['eng_fee_paid'] === "1" ? "" : "selected"; ?> value='0'>NO</option>
                    <option <?= $this->data['Job']['eng_fee_paid'] === "0" ? "" : "selected"; ?> value='1'>YES</option>
                </select>
                </div>
                <div class='form-group'>
                <label>Training Upsell?</label>
                <select name='data[Job][training_upsell]' class="form-control validation">
                    <option <?= in_array($this->data['Job']['training_upsell'], ['0','1']) ? "" : "selected"; ?> value=''>N/A</option>
                    <option <?= $this->data['Job']['training_upsell'] === "0" ? "selected" : ""; ?> value='0'>NO</option>
                    <option <?= $this->data['Job']['training_upsell'] === "1" ? "selected" : ""; ?> value='1'>YES</option>
                </select>
                </div>
                 <div class='form-group'>
                <label>Notes</label>
                <textarea  name='data[Job][notes]'  class='form-control' rows='5' width='100%'><?= $this->data['Job']['notes']; ?></textarea>
              </div>
            </div> 
          </div>
            <div class='row'>
                <div class='col-md-offset-10 col-md-2'>
                    <input id='submitButton' type="submit" class='btn btn-primary' value='Update Job' />
                </div>
            </div>
        </div>
        
        <?php echo $this->Form->end(); ?>
 </div>

<?php $this->append('scripts'); ?>
<script>
   $("#autofill").click(function(e) {
      e.preventDefault();
      $address = "";
      $.ajax({
          'type': 'json',
          'url': '/customers/ajaxReturnAddress/' + $('#customerList').find(":selected").val()
            }).done(
              function(data) {
                  if(data !== 'false')
                  {
                      
                      $address = JSON.parse(data); 
                   $("#JobAddr1").val($address['bill_addr2']);
                   $("#JobAddr2").val("");
                   $("#JobCity").val($address['bill_city']);
                   $("#JobState").val($address['bill_state']);
                   $("#JobZip").val($address['bill_zip']);

                    }
                    else
                    {
                         $("#JobAddr1").val("");
                         $("#JobAddr2").val("");
                   $("#JobCity").val("");
                   $("#JobState").val(0);
                   $("#JobZip").val("");
                    }
              });
      
       
   });
   
   
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