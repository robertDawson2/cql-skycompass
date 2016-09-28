   <style>
  .custom-combobox {
    position: relative;
    display: inline-block;
    width: 100%;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 2px 5px;
    border: 1px solid #cfcfcf;
    background: #efefef;
    cursor: pointer;
    color: #888;
    font-weight: bold;
    
    right: 0;
    
    
  }
  .custom-combobox-input {
    margin: 0;
    padding: 5px 10px;
  
  }
  </style>
 
<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Single Time Edit Form</h3>

          
        </div>
        <!-- /.box-header -->
        <?php echo $this->Form->create('edit'); ?>
        <input type="hidden" name='data[TimeEntry][id]' value='<?= $data['TimeEntry']['id']; ?>' />
        <input type="hidden" name='data[TimeEntry][user_id]' value='<?= $data['TimeEntry']['user_id']; ?>' />
        <div class="box-body">
          <div class="row">
              <div class="col-md-6">
                  <div class='form-group'>
                      <label>Customer/Job</label>
                      <select id='customerList'  data-placeholder='Select a customer or job...' class="form-control select2 validation" data-required='required' name='data[TimeEntry][customer_id]' style="width: 100%;">
                   <option></option>
                   <?php foreach($customers as $p): ?>
                    <?php $selected = "";
                    if($p['id'] === $this->data['TimeEntry']['customer_id'])
                        $selected = "selected";
                    
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
                  <input value='<?= $data['TimeEntry']['txn_date']; ?>' name='data[TimeEntry][txn_date]' data-max='today' type="text" class="form-control pull-right validation" data-required='required' data-type='date' id="datepicker">
                  </div>
                      </div> 
                 
                      
                  <h4>Duration</h4>
                      <TABLE><tr><td width='50%' style='padding: 5px;'>
                            <?php 
                            
                            $timeArray = str_replace("PT","", str_replace("M","",$this->data['TimeEntry']['duration']));
                            $timeArray = explode("H", $timeArray);
                            
                            
                            ?>
                                   <div class='form-group'>
                                       <label>Hours</label>
                      <input type="tel" name='data[TimeEntry][hours]' data-max="24" max="24" default='<?= $timeArray[0]; ?>' data-type="number"  class='form-control hour-spinner validation' data-required='required'>
                                   </div>
                              </td>
                              <td style='padding: 5px;'>
                                  
                                   <div class='form-group'>
                                       <label>Minutes</label>
                      <input  type="tel"  name='data[TimeEntry][minutes]' data-max="59" max="59" default='<?= $timeArray[1]; ?>' data-type='number'  class='form-control minute-spinner validation' data-required='required'>
                                   </div>
                              </td>
                          </tr>
                      </table>
              </div>
              
              <style>
                  .main-item {font-weight: bold;}
                  .child-item {font-style: italic; font-size: 95%; padding-left: 25px;}
              </style>
            <div class="col-md-6">
              <div class="form-group">
                <label>Service Item</label>
                <select name='data[TimeEntry][item_id]' id='serviceItemList' data-placeholder='Select a service item...' class="form-control select2 validation" data-required = "required" style="width: 100%;">
                   <option></option>
                   <?php foreach($services as $p): ?>
                    <?php $selected = "";
                    if($p['id'] === $this->data['TimeEntry']['item_id'])
                        $selected = "selected";
                    
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
                    if($p['id'] === $this->data['TimeEntry']['payroll_item_id'])
                        $selected = "selected";
                    
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
                    if($p['id'] === $this->data['TimeEntry']['class_id'])
                        $selected = "selected";
                    
                    ?>
                    <option class="<?=$p['class']; ?>" value="<?=$p['id'];?>" <?= $selected; ?>>
                        <?=$p['name']; ?>
                    </option>
                    
                    <?php endforeach; ?>
                </select>
                </div>
                 <div class='form-group'>
                <label>Notes</label>
                <textarea  name='data[TimeEntry][notes]'  class='form-control' rows='5' width='100%'><?= $data['TimeEntry']['notes']; ?></textarea>
              </div>
            </div> 
          </div>
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
    
    $("#calendar-show").click(function()
   {
       $("#datepicker").datepicker('show');
   });
    
//    $editArray = {customer_id : '<?=$data['TimeEntry']['customer_id']; ?>',
//        item_id : '<?=$data['TimeEntry']['item_id']; ?>',
//        payroll_id : '<?=$data['TimeEntry']['payroll_item_id']; ?>',
//        class_id : '<?=$data['TimeEntry']['class_id']; ?>',
//       customer_name : '<?=$data['Customer']['full_name']; ?>',
//        item_name : '<?=$data['Item']['full_name']; ?>',
//        payroll_name : '<?=$data['TimeEntry']['payroll_item_name']; ?>',
//        class_name : '<?=$data['TimeEntry']['class_name']; ?>',
//       };
       
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
//    function populateLists() {
//        $.ajax({
//      
//           url: "/admin/customers/ajaxGetCustomerList",
//           dataType: "json",
//           success: function(data) {
//              $.each(data, function(index, value) {
//                  $newAdd = "<option value='" + value.id + "' class='" + value.class + "'";
//                  if(value.id === $editArray.customer_id)
//                      $newAdd += " selected = 'selected' ";
//                  $newAdd += ">";
//                  $newAdd += value.name;
//                  $newAdd += "</option>";
//                  $("#customerList").append($newAdd);
//              });
//              $("#customerList").data('placeholder', "Select a customer or job...");
//           }
//   
//    });
////    $.ajax({
////      
////           url: "/admin/timeEntry/ajaxGetPayrollItemList",
////           dataType: "json",
////           success: function(data) {
////              $.each(data, function(index, value) {
////                  $newAdd = "<option value='" + value.id + "' class='" + value.class + "'";
////                  if(value.id === $editArray.payroll_id)
////                      $newAdd += " selected = 'selected' ";
////                  $newAdd += ">";
////                  $newAdd += value.name;
////                  $newAdd += "</option>";
////                  $("#payrollItemList").append($newAdd);
////              });
////              $("#payrollItemList").data('placeholder', "Select a payroll item...");
////           }
////   
////    });
//    
//    $.ajax({
//      
//           url: "/admin/timeEntry/ajaxGetClassList",
//           dataType: "json",
//           success: function(data) {
//              $.each(data, function(index, value) {
//                  $newAdd = "<option value='" + value.id + "' class='" + value.class + "'";
//                  if(value.id === '<?= $this->data['TimeEntry']['class_id']; ?>')
//                      $newAdd += " selected";
//                  $newAdd += ">";
//                  console.log($newAdd);
//                  $newAdd += value.name;
//                  $newAdd += "</option>";
//                  $("#classItemList").append($newAdd);
//              });
//              $("#classItemList").data('placeholder', "Select a class...");
//           }
//   
//    });
//    
//    $.ajax({
//      
//           url: "/admin/timeEntry/ajaxGetServiceItemList",
//           dataType: "json",
//           success: function(data) {
//              $.each(data, function(index, value) {
//                  $newAdd = "<option value='" + value.id + "' class='" + value.class + "'";
//                  if(value.id === $editArray.item_id)
//                      $newAdd += " selected = 'selected' ";
//                  $newAdd += ">";
//                  $newAdd += value.name;
//                  $newAdd += "</option>";
//                  $("#serviceItemList").append($newAdd);
//              });
//              
//           }
//   
//    });
//    
//    return true;
//    }
//    
//         $answer = populateLists();
//                 if($answer) {
        
        
    
     $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
          
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value.trim() )
          .attr( "title", "" )
          .addClass( "custom-combobox-input form-control" )
          .tooltip({
            classes: {
              "ui-tooltip": "ui-state-highlight"
            }
          })
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          });
                  
            this.input
                    .autocomplete( "instance" )._renderItem = function( ul, item ) {
                       
              $class = item.option.attributes.class.value;
      
            return $( "<li value='"+ item.value + "' class='" + $class +"' >" + item.label + "</li>")
        .appendTo( ul );
    };
          
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
  
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .html('<i class="fa fa-2x fa-angle-down"></i>')
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .on( "mousedown", function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .on( "click", function() {
            input.trigger( "focus" );
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text.trim(),
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
 
    $( ".select2" ).combobox();
            
           
  //  }
   
   
 
    
    
    
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });
    
    $(".minute-spinner").spinner({
        min: 0,
        max: 59

    }).val(<?= $timeArray[1]; ?>);

    $(".hour-spinner").spinner({
        min: 0,
        max: 300
    }).val(<?= $timeArray[0]; ?>);
    </script>
<?php $this->end(); ?>