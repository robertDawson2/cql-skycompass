<?php  $this->assign('title', 'Create New Task List Template'); ?>
<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary" style="position: fixed; width: 40%;">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Current Task Items</h3>

              <div class="box-tools pull-right">
                  <ul class="pagination pagination-sm inline">
                      
                </ul>
            
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="border-bottom: 2px solid #aeaeae;">
                <style>
                    #new-list {
                        min-height: 250px !important;
                    }
                    #new-list .tools {
                        opacity: 0;
                    }
                </style>
              <ul id='options-list' class="todo-list ui-sortable">
                  
                  <?php foreach($items as $item): ?>
                  
            
                <li data-id='<?= $item['TaskItem']['id']; ?>'>
                  <!-- drag handle -->
                      <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <!-- checkbox -->
<!--                  <input value="" type="checkbox">-->
                  <!-- todo text -->
                  <span class="text"><?= $item['TaskItem']['long_name']; ?></span>
                  <!-- Emphasis label -->
                  <span style="cursor: pointer;" onclick="showMore(this);" class="show-details pull-right label label-primary">More details <i class="fa fa-angle-double-down"></i> </span>
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                      <!-- Emphasis label -->
                  
                    <i class="fa fa-edit editable-item"></i>
                    <i class="fa fa-trash-o removeable-item" data-id='<?= $item['TaskItem']['id']; ?>'></i>
                  </div>
                  <div class="row more-details" style="padding-top: 10px; padding-bottom: 10px; display: none;">
                      
                      <div class="col-sm-6">
                          
                      <label>Activation Event</label>
                      <select class="input form-control" disabled>
                          <option value="<?= $item['ActivateEvent']['name']; ?>"><?= ucwords(str_replace("_", " ", $item['ActivateEvent']['name'])); ?></option>
                      </select>
                      <label>Completion Event</label>
                      <select class= "input form-control" disabled>
                          <option value="<?= $item['CompleteEvent']['name']; ?>"><?= ucwords(str_replace("_", " ", $item['CompleteEvent']['name'])); ?></option>
                      </select>
                      </div>
                      <div class="col-sm-6">
                          <label>Detailed Description</label>
                          <p>
                              <?= $item['TaskItem']['description']; ?>
                          </p>
                      </div>
                  </div>
                </li>
                <?php endforeach; ?>
                
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
                <div class="row">
                    <div class="col-sm-9">
                        <form id='newItemAdd' method='post'>
                        <div class="box-tools">
                    <div class="row">
                    <div class="col-sm-6">
                        <label>Full Item Name</label>
                  <input id="longName" name='data[TaskItem][long_name]' type="text" class="input form-control" placeholder="Enter Full Item Name Here...">
                    </div>
                        
                    <div class="col-sm-6">
                        <label>Shorthand Item Name</label>
                  <input id="shortName" name='data[TaskItem][short_name]' type="text" class="input form-control" placeholder="Enter Short Item Name Here...">
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-sm-6">
                        <label>Activation Action</label>
                  <select name='data[TaskItem][activate_event]' id="activate" class="input form-control">
                      <?php foreach($actions as $action): ?>
                      <option value="<?= $action['TaskListAction']['id']; ?>"><?= ucwords(str_replace("_", " ", $action['TaskListAction']['name'])); ?></option>
                     <?php endforeach; ?>
                  </select>
                    </div>
                    <div class="col-sm-6">
                       <label>Completion Action</label>
                  <select name='data[TaskItem][complete_event]' id="complete" class="input form-control">
                      <?php foreach($actions as $action): ?>
                      <option value="<?= $action['TaskListAction']['id']; ?>"><?= ucwords(str_replace("_", " ", $action['TaskListAction']['name'])); ?></option>
                     <?php endforeach; ?>
                  </select>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Description</label>
                            <textarea  name='data[TaskItem][description]' id="description" class="form-control"></textarea>
                        </div>
                    </div>
              </div>
                        </form>
                    </div>
                    <div class="col-sm-3">
                        <button type="button" id="addNew" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>
                    </div>
                </div>
              
            </div>
          </div>
    </div>
    <div class='col-sm-6'>
<div class="box box-primary">
    <form id='templateForm' method='POST' action='/admin/TaskListTemplates/create'>
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">New Task List</h3>

              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="box-tools" style="padding-bottom: 10px; border-bottom: 2px solid #aeaeae;">
                    <div class="row">
                    <div class="col-sm-12">
                        <label>Full List Name</label>
                  <input name='data[TaskListTemplate][name]' type="text" class="input form-control" placeholder="Enter Full List Name Here...">
                    </div>
                     
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Description</label>
                            <textarea name='data[TaskListTemplate][description]' class="input form-control">
                                
                            </textarea>
                        </div>
                    </div>
              </div>
              <ul id='new-list' class="todo-list ui-sortable">
                
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
                <input type='hidden' name='data[TaskListTemplate][items]' id='dataItemArray' />
              <button type="button" id='saveTemplate' class="btn btn-default pull-right"><i class="fa fa-floppy-o"></i> Save New Template</button>
            </div>
</form>
          </div>
    </div>
</div>

<?php $this->append('scripts'); ?>
<script>
    
    
    var listElement = $('#options-list');
var perPage = 5; 
var numItems = listElement.children().size();
var numPages = Math.ceil(numItems/perPage);
reconfigurePagination();
setDraggable();
    function reconfigurePagination()
    {
      $('.pagination').children().remove();
      $('.pagination').attr("curr",0);

var curr = 0;
while(numPages > curr){
  $('<li><a href="#" class="">'+(curr+1)+'</a></li>').appendTo('.pagination');
  curr++;
}

$('.pagination  li:first a').addClass('active');

listElement.children().css('display', 'none');
listElement.children().slice(0, perPage).css('display', 'block');
    }
    
    $(".todo-list").sortable({
    placeholder: "sort-highlight",
    handle: ".handle",
    forcePlaceholderSize: true,
    zIndex: 999999
  });
  function showMore(clicked) {
     
      $(clicked).parent().children(".more-details").slideToggle(300);
  }
  function setDraggable() {
  $("#options-list li").draggable({
      appendTo: "body",
      connectToSortable: "#new-list",
      //revert: true
     helper: function(e) {
    var original = $(e.target).hasClass("ui-draggable") ? $(e.target) :  $(e.target).closest(".ui-draggable");
    return original.clone().css({
      width: original.width(), // or outerWidth*
      height: "auto"
    });                
  }
      
      
  });
  }

$("#saveTemplate").click(function() {
    $("#templateForm").submit();
});
  $("#templateForm").submit(function(e){
      $newItems = [];
      $('#new-list > li').each(function() {
          $newItems.push($(this).data('id'));
      });
      $("#dataItemArray").val(JSON.stringify($newItems));
      
  });
  $("#new-list").droppable();
  
    /* The todo list plugin */
  $(".todo-list").todolist({
    onCheck: function (ele) {
      window.console.log("The element has been checked");
      return ele;
    },
    onUncheck: function (ele) {
      window.console.log("The element has been unchecked");
      return ele;
    }
  });
    $(".removeable-item").click(function(){
        $id = $(this).data('id');
        if(confirm('Are you sure you want to remove this item?'))
        {
            $.ajax({
                url: '/admin/taskListTemplates/ajaxRemoveItem/' + $id
            });
            $(this).parent().parent().remove();
        }
    })
    $('#addNew').click(function() {
        $.ajax({
            url: '/admin/taskListTemplates/addNewItem',
            method: "POST",
            data: $("#newItemAdd").serialize(),
            complete: function(data)
            {
                if(data.responseText !== "error") {
                    
                    addToList(data.responseText);
                    $("#newItemAdd")[0].reset();
        }
                else
                {
                    console.log(data.responseText);
                    alert("An error occurred with your form submission. Please Try again.");
                }
            }
        });
    });
    
$('.pagination li a').click(function(){
  var clickedPage = $(this).html().valueOf() - 1;
  goTo(clickedPage,perPage);
});

  function addToList(newId) {
      
    $lName = $("#longName").val();
    $sName = $("#shortName").val();
    $active = $( "#activate option:selected" ).text();
    $complete = $( "#complete option:selected" ).text();
    $desc = $("#description").val();
    
    $newItem = '<li data-id="' + newId + '" class="ui-draggable ui-draggable-handle">\n<!-- drag handle -->\n<span class="handle ui-sortable-handle">\n<i class="fa fa-ellipsis-v"></i>';
    $newItem += ' <i class="fa fa-ellipsis-v"></i></span>\n<span class="text" data-shortname="' + $sName + '">';
    $newItem += $lName + '</span><span style="cursor: pointer;" onclick="showMore(this);" class="show-details pull-right label label-primary">';
    $newItem += 'More details <i class="fa fa-angle-double-down"></i> </span>';
    $newItem += '<div class="tools">\n<i class="fa fa-edit editable-item"></i>\n<i class="fa fa-trash-o removeable-item"></i>\n';
    $newItem += '</div><div class="row more-details" style="padding-top: 10px; padding-bottom: 10px; display: none;">\n';
    $newItem += '<div class="col-sm-6"><label>Activation Event</label><select class="input form-control" disabled>\n';
    $newItem += '<option value="' + $active + '">' + $active + '</option></select>';
    $newItem += '<label>Completion Event</label><select class="input form-control" disabled>\n';
    $newItem += '<option value="' + $complete + '">' + $complete + '</option></select></div>';
    $newItem += '<div class="col-sm-6"><label>Detailed Description</label>\n<p>' + $desc + '</p></div></div></li>';
    
       console.log($newItem); 
        $("#options-list").append($newItem);
       // reconfigurePagination();
       /// goTo(numPages);
       setDraggable();
    
  }
  
function goTo(page){
  var startAt = page * perPage,
    endOn = startAt + perPage;

  listElement.children().css('display','none').slice(startAt, endOn).css('display','block');
  $('.pagination').attr("curr",page);
}
    </script>
<?php $this->end(); ?>