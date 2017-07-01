<style>
     .quick-todo
    {
        border-radius: 8px;
        background: #444f54;
        color: #fefefe;
        font-size: 10px;
    }
    .info-div {
        float: right;
        padding-left: 10px;
        padding-right: 10px;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
    }
    .info-div .red, .red {
        color: red;
    }
    .green {
        color: green;
       
    }
    .orange {
        color: orange;
    }
    .yellow {
        color: gold;
    }
    .gray {
        color: gray;
    }
    .info-div a.green {
        margin-left: 5px;
        margin-right: 5px;
        transition: 200ms;
        color: lightgreen;
    }
    .info-div a.green:hover {
        color: greenyellow;
        text-shadow: 0 0 5px #green;
            
    }
    .info-div .lightblue {
        color: lightskyblue;
    }
    .info-div .yellow {
        color: gold;
    }
    .info-div > i {
        margin-right: 5px;
        margin-left: 5px;
    }
    .info-div .grey {
        color: #ccc;
        cursor: default;
    }
    li.done > .info-div {
        display: none;
    }
</style>
<div class="tab-pane active" id="control-sidebar-ordered-tab">
          <h3 class='control-sidebar-heading' style='margin-top: -5px;'><i class="fa fa-plus-square-o"></i> Quick Add Item</h3>
          <form id='QuickAddForm' action='/admin/todo/ajaxQuickAdd' method='post'>
              <div class='row' style='margin-bottom: 5px;'>
                  <div class='col-sm-12'>
              <textarea id='Todo.description' name='data[Todo][description]' rows='2' class='form-control quick-todo' style='resize: none;'></textarea>
                  </div>
              </div>
                  <div class='row'>
                     
                          <div class='col-sm-4'>
                      <?= $this->Form->input('Todo.todo_type_id', array(
                          'label' => false,
                          'default' => 0,
                          'options' => $todoTypes,
                          'class' => 'input form-control quick-todo')); ?>
                  </div>
                      
                  <div class='col-sm-4'>
                      <?= $this->Form->input('Todo.priority', array(
                          'label' => false, 'default' => 3,
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
                          <a id='btnQuickAdd' role='button' class='btn btn-block btn-info'><i class='fa  fa-check-square-o'></i> Add</a>
                      </div>
              </div>
                  
          </form>
          
          <hr>
          
          <h3 class="control-sidebar-heading"><i class="fa fa-check-square-o"></i> To-Do List Items</h3>
          
          
<ul id='quick-todo-list' class='todo-list uncategorized-todo-list small'>
          
                    
                    
                </ul>


<a href='/admin/todo' role='button' class='btn btn-block btn-success'><i class='fa fa-arrow-circle-o-right'></i> View Full List</a>
      </div>
      <!-- /.tab-pane -->
      
        <?php $this->append('scripts'); ?>
<script>

$("#btnQuickAdd").click(function(e) {
    e.preventDefault();
    $.post('/admin/todo/ajaxQuickAdd',
    $("#QuickAddForm").serialize()).done(function(data) {
     $("#quick-todo-list").html(data);
     
});
});

function toggleChecked(id, checkbox)
{
    var val = 0;
    if(checkbox.checked)
        val = 1;
    
    $.ajax('/admin/todo/ajaxChangeItemStatus/' + id + "/" + val).done(function() {
        if(val === 1)
            $(checkbox).parent().addClass('done');
        else
             $(checkbox).parent().removeClass('done');
    });
}
$(".uncategorized-todo-list").todolist({
    onCheck: function (ele) {
      $.ajax('/admin/todo/ajaxChangeItemStatus/' + $(this).data('id') + '/1');
      return ele;
    },
    onUncheck: function (ele) {
      $.ajax('/admin/todo/ajaxChangeItemStatus/' + $(this).data('id') + '/0');
      return ele;
    }
  });
</script>
<?php $this->end(); ?>