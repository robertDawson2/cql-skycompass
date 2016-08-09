  <style>
      #uploadLabel {
          cursor: pointer;
          padding: 10px;
          color: white;
          font-weight: bold;
          background-color: green;
          border-radius: 5px;
          float: left;
      }
      #upload-photo
      {
          margin-left: -90px;
      }
      .hide-button {
          overflow: hidden;
          float: left;
      }
      
  </style>
  
  <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Expense Entry Form</h3>

          
        </div>
        <!-- /.box-header -->
  <!-- End Page Header --> 
  <!-- Start Content -->

<?php echo $this->Form->create('add', array('type' => 'file')); ?>
  <div class='box-body'>
	<fieldset>
            <div class="row">
              <div class="col-md-6">
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
                      
                      <label>Date</label>
                      <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name='data[BillItem][txn_date]' data-max='today' type="text" class="form-control pull-right validation" data-required='required' data-type='date' id="datepicker">
                  </div>
                      </div> 
                  <div class='form-group' style='padding-bottom: 10px;'>
                      
                      <label>Amount</label>
                     
                  <input name='data[BillItem][amount]' type="text" class="form-control pull-right validation" data-required='required' data-type='number' />
                  
                      </div> 
                 <div class='form-group'>
                     <div class='row' style='padding: 20px 0;'>
                         <div class='col-md-6'>
                     <label style='display: block;' id="uploadLabel" for="upload-photo"><i class="fa fa-2x fa-camera"></i> Choose Photo</label>
                        <div class="hide-button">
                       <input id="upload-photo" name="data[Image][image]" type="file" accept="image/*" capture="camera"> 
                        </div> 
                         </div>
                     
                     <div class='col-md-6'>
                     <img id='imagePreview' style='max-height: 120px; '/>
                     </div>
                     </div>
                 </div>
                  
              </div>
              
             
            <div class="col-md-6">
              <div class="form-group">
                <label>Item</label>
                <select name='data[BillItem][item_id]' id='serviceItemList' data-placeholder='Select a service item...' class="form-control select2 validation" data-required = "required" style="width: 100%;">
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
                <label>Description/Memo</label>
                <textarea  name='data[BillItem][description]'  class='form-control' rows='5' width='100%'></textarea>
              </div>
            </div> 
          </div>
		             
	
            <div class='row'>
                <div class='col-md-offset-10 col-md-2'>
                    <button type="submit" class='btn btn-primary'><i class='fa fa-arrow-cirle-right'></i> Submit Item</button>
                </div>
            </div>
	</fieldset>
    
  </div>
<?php echo $this->Form->end(); ?>
</div>
         <?php $this->append('scripts'); ?>
<script>
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
    
    $('#upload-photo').change(function() {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            document.getElementById("imagePreview").src = e.target.result;
        };
        
        reader.readAsDataURL(this.files[0]);
    });
    </script>
    <?php $this->end(); ?>