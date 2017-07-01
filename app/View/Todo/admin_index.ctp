<style>
    li > .completion-div {
        display: none;
        float: right;
        margin-right: 10px;
        color: green;
    }
    li.done > .completion-div {
        display: inline;
    }
     #FullAddForm .quick-todo
    {
        border-radius: 8px;
        background: #deefff;
        color: #242424;
        font-size: 11px;
    }
    </style>
    
   
<div class="row">
    <div class='col-md-3'>
        <ul class="nav nav-pills nav-stacked">
  <li class="active"><a data-toggle="pill" href="#uncategorized">Uncategorized</a></li>
  <li><a data-toggle="pill" href="#job-lists">Job Lists</a></li>

  
</ul>
       
         
    </div>
    
    <div class="col-md-9 tab-content">
        <div id="uncategorized" class="tab-pane fade in active">
<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list-ul"></i> To-Do List
            </h3>

        </div>
        
        <div class="box-body">
            <div class="box box-default">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-file-o"></i>

              <h4 class='box-title'>Add New Item</h4>
              <!-- tools box -->
              <div class="pull-right box-tools">
                                    
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
                <div class="box-body">
            <form id='FullAddForm' action='/admin/todo/ajaxFullAdd' method='post'>
              <div class='row' style='margin-bottom: 5px;'>
                  <div class='col-sm-12'>
              <textarea id='TodoDesc' name='data[Todo][description]' rows='2' class='form-control quick-todo' style='resize: none;'></textarea>
                  </div>
              </div>
                  <div class='row'>
                     
                          <div class='col-sm-4'>
                      <?= $this->Form->input('Todo.todo_type_id', array(
                          'id' => 'TodoType',
                          'label' => 'Category',
                          'default' => 0,
                          'options' => $todoTypes,
                          'class' => 'input form-control quick-todo')); ?>
                  </div>
                      
                  <div class='col-sm-4'>
                      <?= $this->Form->input('Todo.priority', array(
                          'id' => 'TodoPri',
                          'label' => 'Priority', 'default' => 3,
                          'options' => array(
                              1 => 'Highest',
                              2 => 'High',
                              3 => 'Normal',
                              4 => 'Low',
                              5 => 'Lowest'
                          ),
                          'class' => 'input form-control quick-todo')); ?>
                  </div>
                      <div class='col-sm-4'>
                      <?= $this->Form->input('Todo.due', array(
                          'type' => 'text',
                          'label' => 'Due Date/Time',
                          'class' => 'datetimepicker12 input form-control quick-todo')); ?>
                          
                  </div>
                  </div> 
                <div class='row'>
                    <div class='col-sm-7'>
                        <?= $this->Form->input('Todo.search', array(
                            'class' => 'full-search input form-control quick-todo',
                            'label' => 'Link Customer/Contact Info'
                        )); ?>
                       
                        <div id="custSearchResults" data-context='hidden' style="
           position: absolute; z-index: 9999999; 
           display: none;">
          <div class='box-tools'><a role='button'><i class='fa fa-close'></i></a></div>
          </div>
                    </div>
                    <div class='col-sm-1'>
                        <label></label>
                            <i id='approvedCheck' style='display: none; margin-top: 30px' class='fa fa-2x fa-check-circle green'></i>
                    </div>
                        <div class='col-sm-4'>
                      <?= $this->Form->input('Todo.reminder', array(
                          'type' => 'text',
                          'label' => 'Reminder Date/Time',
                          'class' => 'datetimepicker12 input form-control quick-todo')); ?>
                          
                  </div>
                        
                </div>
                <?= $this->Form->hidden('Todo.hidden_id'); ?>
                <?= $this->Form->hidden('Todo.contact'); ?>
                <?= $this->Form->hidden('Todo.customer'); ?>
                <div class='row'>
                    <div class='col-sm-6 col-sm-offset-3'>
                        <label></label>
                        <a id='btnFullAdd' data-context='add' role='button' class='btn btn-block btn-info'><i class='fa  fa-check-square-o'></i> <span class='btn-text'>Add</span></a>
                      </div>
                    <div class='col-sm-2 col-sm-offset-1'>
                        <label></label>
                        <a style='margin-top: 20px;' id='btnClear' onclick='resetForm();' role='button' class='btn btn-sm btn-default'><i class='fa  fa-remove'></i> <span class='btn-text'>Clear</span></a>
                      </div>
                </div>
                

        <?php $this->append('jquery-scripts'); ?>
            $('.datetimepicker12').datetimepicker({
                sideBySide: true,
                useCurrent: false,
                showClear: true
            });
            <?php $this->end(); ?>
   
   
                
                  
          </form>
                </div>
            </div>
          
          
          <h3 class="control-sidebar-heading"><i class="fa fa-check-square-o"></i> To-Do List Items</h3>
          <style>
              #fullTodoList, #fullJobList .todo-list.small {
                  background: #fff;
                  color: #424242;
                  border-top: 2px solid #484848;
                  border-radius: 8px;
              }
              #fullTodoList > li, #fullJobList .todo-list.small > li {
                  background: #fff;
                  color: #424242;
                  border-bottom: 1px solid #858585;
                  margin-bottom: 0;
              }
              #fullTodoList > li > .text, #fullJobList .todo-list.small > li > .text {
                  color: #424242;
              }
              #fullTodoList > li.done, #fullJobList .todo-list.small > li.done{
                  
                  background-color: #EFEFEF;
                  color: #9c9c9c;
              }
              #fullTodoList > li.done {
                  display: none;
              }
              #fullTodoList > li.done > .text, #fullJobList .todo-list.small > li.done > .text {
                  color: #484848;
                  font-style: italic;
              }
              #fullTodoList > li > .text.late
              {
                  color: red;
                  font-weight: 800;
              }
          </style>       
          <a style="margin-bottom: 2px; " data-toggle='hidden' role='button' id='showCompleted' class='btn btn-sm btn-success'><i class='fa fa-eye'></i> Show Completed</a>
<ul id='fullTodoList' class='todo-list uncategorized-todo-list small'>
          
                    
                    
                </ul>
            
        </div>
        
        
 </div>
            
            
        </div>
        

        
        <div class="tab-pane fade" id="job-lists">   
            <div class='row'>
                <div class='col-md-12'>
         <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list-alt"></i> Job-Based Lists
            </h3>

          
        </div>
           
        <!-- /.box-header -->
        <div class="box-body">
        <?php if(!empty($jobtasklists)) {
            echo "<div class='row' id='fullJobList'>";
                  foreach($jobtasklists as $tl) {
                      echo "<div class='col-md-6'>";
                      echo $this->element('jobtasklist', array('entry' => $tl));
                      echo "</div>";
                  }
                  echo "</div>";
          } ?>
        </div>
        
        
 </div>
                </div>
              </div>
        </div>
        
          
        
    </div>
</div>
  <?php $this->append('jquery-scripts'); ?>
	$.ajax('/admin/todo/ajaxGetList/true/500/true').done(function(data) {
     $("#fullTodoList").html(data);
});
	
<?php $this->end(); ?>  
<?php $this->append('scripts'); ?>
<script>
    
    //cust search
    function defineLink(clicked)
    {
        $("#TodoContact").val("");
        $("#TodoCustomer").val("");
        hideQSearch();
        $("#TodoSearch").val($(clicked).text());
        
        var field = "#Todo" + capitalizeFirstLetter($(clicked).data('context'));
        
        $(field).val($(clicked).data('id'));
        $("#approvedCheck").fadeIn('fast');
    }
    function undefineLink()
    {
        $("#TodoContact").val("");
        $("#TodoCustomer").val("");
        $("#approvedCheck").fadeOut('fast');
    }
    function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
    // global search
   function hideQSearch() {
      $("#TodoSearch").val(''); 
      $("#custSearchResults").fadeOut('fast');
                $("#custSearchResults").data('context', 'hidden');
   }
function searchB(force) {
    var existingString = $("#TodoSearch").val();
    if (!force && existingString.length < 3) 
    {
        $("#custSearchResults").hide();
                $("#custSearchResults").data('context', 'hidden');
            return; //wasn't enter, not > 2 char
        
    }
    $.ajax('/admin/CRM/ajaxSiteSearch/'+encodeURIComponent(existingString)+'/false/true/true/false/todo' ).done(function(data) {
            if(data === 'done'){
                $("#custSearchResults").hide();
                $("#custSearchResults").data('context', 'hidden');
            }
            else
            {
                if($("#custSearchResults").data('context') === 'hidden')
                {
                    $("#custSearchResults").slideDown();
                    $("#custSearchResults").data('context', 'visible');
                }
                $("#custSearchResults").html(data);
            }
                
        });
}
$("#TodoSearch").keydown(function(e) {
    if(e.keyCode == 13)
        e.preventDefault();
    undefineLink();
});
    $("#TodoSearch").keyup(function(e) {
        
        clearTimeout($.data(this, 'timer'));
    if (e.keyCode == 13){
        e.preventDefault();
        searchB(true);
    }
      
    else{
      $(this).data('timer', setTimeout(searchB, 500));
  }
        
    });
    
    $("#btnFullAdd").click(function(e) {
        $.post('/admin/todo/ajaxFullAdd',
    $("#FullAddForm").serialize()).done(function(data) {
     $("#fullTodoList").html(data);
     $.ajax('/admin/todo/ajaxGetList').done(function(data) {
     $("#quick-todo-list").html(data);
});
// Manipulate submit button for add and clear form
resetForm();
undefineLink();
});
    });
    
    function resetForm() {
        $("#FullAddForm input").val("");
$("#FullAddForm textarea").val("");
$("#TodoType").val("0");
$("#TodoPri").val('3');
$("#btnFullAdd").addClass('btn-info').removeClass('btn-warning').data('context', 'add');
$("#btnFullAdd .btn-text").text('Add');
$("#btnFullAdd > i").removeClass('fa-edit').addClass('fa-check-square-o');
    }
$("#showCompleted").click(function(e) {
    e.preventDefault();
    if($(this).data('toggle') === 'hidden')
    {
        $("#fullTodoList > li.done").slideDown('fast');
        $(this).data('toggle', 'visible');
        $(this).html("<i class='fa fa-eye-slash'></i> Hide Completed");
    }
    else
        {
        $("#fullTodoList > li.done").slideUp('fast');
        $(this).data('toggle', 'hidden');
        $(this).html("<i class='fa fa-eye'></i> Show Completed");
    }
});

function toggleFullDetails(id,clicked) {

    $.ajax('/admin/todo/ajaxGetFullDetails/'+id).done(function(data) {
        $('#todoFullDetails').find('.modal-body').html(data);
        $('#todoFullDetails').modal();
    });
}
function editRow(id) {
    
    //populate form with ajax return
    $.ajax({dataType: "json", 
        url: "/admin/todo/ajaxGetJsonDetails/"+id,
complete: function(data){
$response = JSON.parse(data.responseText);

$("#TodoDesc").val($response.Todo.description);
$("#TodoType").val($response.Todo.todo_type_id);
$("#TodoPri").val($response.Todo.priority);
if($response.Todo.due_date !== null)
    $("#TodoDue").val($response.Todo.due_date);
else
    $("#TodoDue").val("");

if($response.Todo.reminder_date !== null)
    $("#TodoReminder").val($response.Todo.reminder_date);
else
    $("#TodoReminder").val("");

$("#approvedCheck").hide();
$("#TodoSearch").val("");
if($response.Todo.contact_id !== null)
{
    $("#TodoSearch").val($response.Contact.first_name + " " + $response.Contact.last_name);
    $("#TodoContact").val($response.Todo.contact_id);
    $("#approvedCheck").show();
        }
else
{
    $("#TodoContact").val("");
}

if($response.Todo.customer_id !== null)
{
    $("#TodoSearch").val($response.Customer.name);
    $("#TodoCustomer").val($response.Todo.customer_id);
    $("#approvedCheck").show();
        }
else
{
    $("#TodoCustomer").val("");
}

$("#TodoHiddenId").val(id);

// Manipulate submit button for edit
$("#btnFullAdd").removeClass('btn-info').addClass('btn-warning').data('context', 'edit');
$("#btnFullAdd .btn-text").text('Edit');
$("#btnFullAdd > i").removeClass('fa-check-square-o').addClass('fa-edit');
}
});
}
</script>
<?php $this->end(); ?>

<?= $this->element('modals/todoFullDetails'); ?>


 <div style='clear: both;'></div>