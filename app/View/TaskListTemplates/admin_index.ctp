<?php  $this->assign('title', 'View Templates'); ?>
<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Current Templates</h3>

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
                    
                </style>
              <ul id='options-list' class="todo-list ui-sortable">
                  
                  <?php foreach($templates as $item): ?>
                  
            
                <li data-id='<?= $item['TaskListTemplate']['id']; ?>'>
                  
                  <!-- checkbox -->
<!--                  <input value="" type="checkbox">-->
                  <!-- todo text -->
                  <span class="text"><?= $item['TaskListTemplate']['name']; ?></span>
                  <p><?= $item['TaskListTemplate']['description']; ?></p>
                  
                  
                </li>
                <?php endforeach; ?>
                
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
                <div class="row">
                    
                    <div class="col-sm-offset-8 col-sm-4">
                        <a href='/admin/taskListTemplates/create' class="btn btn-default pull-right"><i class="fa fa-plus"></i> Create New Template</a>
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

              <h3 class="box-title">Template Details</h3>

              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                
              <ul id='new-list' class="todo-list ui-sortable">
                
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
                <div class='pull-right'>
              <button style='display: none;' type="button" id='editTemplate' class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
              <button style='display: none;' type="button" id='removeTemplate' class="btn btn-danger"><i class="fa fa-remove"></i> Remove</button>
                </div>
            </div>
</form>
          </div>
    </div>
</div>
<style>
    .invert {
        color: #ccc !important;
background-color: #00004a !important;
    }
</style>
<?php $this->append('scripts'); ?>
<script>
    $("#options-list > li").click(function() {
       $("#options-list > li").removeClass('invert');
       $(this).addClass('invert');
       $("#editTemplate").show();
       $("#removeTemplate").show();
       loadDetails($(this).data('id'));
    });
    function loadDetails(id) {
        $.ajax('/admin/taskListTemplates/ajaxLoadCurrentTemplate/' + id).done(function(data) {

            console.log(JSON.parse(data));
            $newData = JSON.parse(data);
            $return = "<h4>" + $newData.TaskListTemplate.name + "</h4><p><em>" + $newData.TaskListTemplate.description + "</em></p><hr>";
            $return += "<ul id='new-list' class='todo-list'>";
            $($newData.TaskItem).each(function() {
               console.log(this); 
               $return += '<li data-id="' + this.id + '">\n' + 
                  '<input value="" type="checkbox">\n' +
                  '<span class="text">' + this.long_name + '</span>' + 
                  '<span style="cursor: pointer;" onclick="showMore(this);" class="show-details pull-right label label-primary">More details <i class="fa fa-angle-double-down"></i> </span>';
                $return += '<div class="row more-details" style="padding-top: 10px; padding-bottom: 10px; display: none;">';
                      
                   $return +=   '<div class="col-sm-6"><label>Activation Event</label>' +
                      '<select class="input form-control" disabled>' +
                          '<option value="' + this.ActivateEvent.name + '">' + this.ActivateEvent.name + '</option>';
                      $return += '</select>\n' +
                      '<label>Completion Event</label>' +
                      '<select class= "input form-control" disabled>' +
                          '<option>' + this.CompleteEvent.name + '</option>' + 
                      '</select>' +
                      '</div>' +
                      '<div class="col-sm-6">' + 
                          '<label>Detailed Description</label>' +
                          '<p>' + this.description + '</p></div></div></li>';
                   
            });
            $return += "</ul>";
            $("#new-list").html($return);
            
        });
    }
    var listElement = $('#options-list');
var perPage = 5; 
var numItems = listElement.children().size();
var numPages = Math.ceil(numItems/perPage);
reconfigurePagination();

$("#removeTemplate").click(function() {

if(confirm('Are you sure you want to remove this task list? \n\
This cannot be undone and will disconnect the task list from all active events! We do NOT recommend removing a task list'))
        {
            window.location.href = '/admin/taskListTemplates/delete/' + $(".invert").data('id');
        }
});

$("#editTemplate").click(function() {

if(confirm('Are you sure you want to edit this template? This could cause issues with in-progress jobs that use this template. \n\
We recommend creating a new similar template to use until all jobs using this template have ended.'))
        {
            window.location.href = '/admin/taskListTemplates/edit/' + $(".invert").data('id');
        }
});

function showMore(clicked) {
     
      $(clicked).parent().children(".more-details").slideToggle(300);
  }
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
    
    
$('.pagination li a').click(function(){
  var clickedPage = $(this).html().valueOf() - 1;
  goTo(clickedPage,perPage);
});

  
function goTo(page){
  var startAt = page * perPage,
    endOn = startAt + perPage;

  listElement.children().css('display','none').slice(startAt, endOn).css('display','block');
  $('.pagination').attr("curr",page);
}
    </script>
<?php $this->end(); ?>