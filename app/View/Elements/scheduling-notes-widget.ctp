<div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Scheduling Info Quick Edit</h3>
              
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form id="notesForm" role="form">
                <!-- text input -->
                

                <!-- textarea -->
                <div class="form-group">
                  <label>Current Scheduling Notes</label>
                  <textarea name="notes" id="employeeNotesBox" class="form-control" rows="3" placeholder="Enter notes here..."><?= $updatedUser['User']['scheduling_employee_notes']; ?></textarea>
                  <span style="display: none;" class="help-block"><i class="fa fa-check"></i> Saved</span>
                </div>
                <div class="form-group">
                    <a id='submitNotes' href="#" class='btn btn-success'>Update</a>
                </div>

              </form>
            </div>
            <!-- /.box-body -->
          </div>

<?php $this->append("scripts"); ?>
<script>
    $("#submitNotes").click(function(e) {
        e.preventDefault();
        
        $.post('/admin/users/ajaxUpdateSchedulingNotes',$("#notesForm").serialize(), function(data)
        {
            if(data == "success")
            {
                $("#employeeNotesBox").parent().removeClass('has-error');
                $("#employeeNotesBox").parent().addClass('has-success');
                $("#employeeNotesBox").parent().children('.help-block').html("<i class='fa fa-check'></i> Saved");
                $("#employeeNotesBox").parent().children('.help-block').fadeIn();
            }
            else
            {
                $("#employeeNotesBox").parent().removeClass('has-success');
                $("#employeeNotesBox").parent().addClass('has-error');
                $("#employeeNotesBox").parent().children('.help-block').html("<i class='fa fa-remove'></i> Error");
                $("#employeeNotesBox").parent().children('.help-block').fadeIn();
            }
        })
        
    });
    
    </script>

<?php $this->end();