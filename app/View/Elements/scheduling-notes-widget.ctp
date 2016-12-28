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
                <hr>
                <h4>Upcoming Jobs</h4>
                <?php if(!empty($upcoming)): 
                    foreach($upcoming as $job): ?>
                <div class='col-md-12 help-block' style='border-bottom: 1px solid #cfcfcf';>
                    <?php if($job['ScheduleEntry']['type'] == 'scheduling'): ?>
                    <a href='/admin/jobs/dashboard/<?= $job['Job']['id']; ?>'><?= $job['Job']['full_name']; ?></a>
                    <?php else: ?>
                    <strong><em><?= ucwords(str_replace("_", " ", $job['ScheduleEntry']['type'])); ?></em></strong>
                    <?php endif; ?>
                    <p>
                        <?= date("l, F j, Y", strtotime($job['ScheduleEntry']['start_date'])); ?> - 
                        <?= date("l, F j, Y", strtotime($job['ScheduleEntry']['end_date'])); ?>
                    </p>
                </div>
                <?php endforeach; ?>
                <p><a class="btn btn-primary btn-sm" href="/admin/schedule/mySchedule"><i class="fa fa-calendar-o"></i> View Full Schedule</a></p>
                
                 <?php       else: ?>
                <p><em>No upcoming jobs scheduled.</em></p>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <a target="_BLANK" href="https://support.office.com/en-us/article/View-and-subscribe-to-Internet-Calendars-f6248506-e144-4508-b658-c838b6067597#bm2"><i class="fa fa-question-circle"></i> How do I use this?</a>
                </div>
                <h4>Your personal URL for calendar linking:</h4>
                <p>
                    <em><a href="<?= $config['site.url']; ?>/schedule/mySchedule/<?= $updatedUser['User']['id']; ?>"><?= $config['site.url']; ?>/schedule/mySchedule/<?= $updatedUser['User']['id']; ?></a></em>
                </p>
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