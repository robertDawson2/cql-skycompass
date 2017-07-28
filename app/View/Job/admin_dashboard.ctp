<?php $this->assign('title', $job['Job']['full_name'] . " Dashboard"); ?>
<style>
    .scheduleList p {
        font-size: 85%;
        margin: 0;
        padding: 5px 10px;
        min-height: 55px;
    }
    .scheduleList p i {
        margin-right: 10px;
    }
    .scheduleList p:nth-child(even) {
        background-color: #f8f8f8;
    }
    .scheduleList p:nth-child(odd) {
        background-color: #efefef;
    }
    
</style>
<div class="row">
    <div class="col-sm-12">
 
<!-- Job -->
<div class="box">
            <div class="box-header">
                <i class="fa fa-eye-slash"></i>
                <h3 class="box-title"><em><strong><?= $job['Job']['name']; ?></strong> Quick Look</em> 
                    <a href="/admin/jobs/edit/<?= $job['Job']['id']; ?>" class="btn btn-sm btn-info" style="margin-left: 15px;">
                        <i class="fa fa-edit"></i> Edit</a></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
                <div class="row">
                    <div class="col-sm-3">
                        <h4>Address</h4>
                        <p>
                           <?= $job['Job']['addr1'] . "<br />" .
                        $job['Job']['addr2'] . "<br />" .$job['Job']['city'] . ", " . $job['Job']['state'] . " " . $job['Job']['zip']; ?> 
                        </p>
                    </div>
                    <div class='col-sm-3'>
                        <h4>Contact Info</h4>
                        <p>
                            <strong><?= $job['Customer']['contact']; ?></strong> <br />
                            <?php if(!empty($job['Customer']['email'])) { ?>
                            <i class='fa fa-envelope'></i> <?= $job['Customer']['email']; ?><br />
                            <?php } if(!empty($job['Customer']['phone'])) { ?>
                            <i class='fa fa-phone'></i> <?= $job['Customer']['phone']; ?><br />
                            <?php } if(!empty($job['Customer']['fax'])) { ?>
                            <i class='fa fa-fax'></i> <?= $job['Customer']['fax']; ?>
                            <?php } ?>
                        </p>
                    </div>
                    <div class='col-sm-3'>
                        <h4>Job Information</h4>
                        <p>
                            <?php if($currentUser['is_scheduler'] || $currentUser['web_user_type'] === 'admin') {?>
                            <strong>Total Balance: </strong>$<?= $job['Job']['total_balance']; ?> <br />
                            
                            <?php $i=1; if($job['Job']['eng_fee_paid']) { $i=0; ?><i style='color: green;' class='fa fa-check green'></i> <?php }
                             else { ?><i style='color: red;' class='fa fa-remove'></i> <?php } ?>
                             Engagement Fee Paid? <br /><a href='/admin/jobs/changeEngagementFee/<?= $job['Job']['id'] . "/" . $i; ?>' class='btn btn-sm btn-success'>
                                 <i class='fa fa-edit'></i> Mark <?= $i === 1 ? "Paid" : "Unpaid"; ?></a><br />
                                 <?php } ?>
                            # People Served: <?= $job['Job']['people_served_count']; ?><br />
                            Team Leader Count: <?= $job['Job']['team_leader_count']; ?><br />
                            Employee Count: <?= $job['Job']['employee_count']; ?><br />
                            Service Area: <?= $job['ServiceArea']['name']; ?><br />
                            <?php if($job['Job']['training_upsell'] !== null): ?>
                             Training Upsell: <?php if($job['Job']['training_upsell'] === '1'): ?>
                             <i class='fa fa-lg fa-check-circle-o' style='color: green;'></i>
                             <?php else: ?>
                             <i class='fa fa-lg fa-remove' style='color: red;'></i>
                             <?php endif; ?><br />
                             <?php endif; ?>
                            <?php if(!empty($job['ScheduleEntry'])) { ?><i style='color: green;' class='fa fa-check green'></i> <?php }
                             else { ?><i style='color: red;' class='fa fa-remove red'></i> <?php } ?>
                            Scheduled?<br />
                            <?php if(!empty($job['ScheduleEntry'])) { ?>
                            <small><em><?= $job['Job']['start_date']; ?> - <?= $job['Job']['end_date']; ?></em></small><?php } ?>
                        </p>
                    </div>
                    <?php if(!empty($job['ScheduleEntry'])): ?>
                    <div class='col-sm-3 scheduleList'>
                        <h4>Scheduled Employees</h4>
                        
                            <?php foreach($job['ScheduleEntry'] as $entry): ?>
                            <p>
                                <strong><?= $entry['User']['first_name'] . " " . $entry['User']['last_name']; ?></strong>,
                                <em><?= ucwords(str_replace("_", " ", $entry['position'])); ?></em><br />
                                <?= !empty($entry['User']['phone']) ? "<i class='fa fa-phone'></i> " . $entry['User']['phone'] . "<br />" : ""; ?>
                                <?= !empty($entry['User']['email']) ? "<a href='mailto:". $entry['User']['email'] . "'><i class='fa fa-envelope'></i></a> " . $entry['User']['email'] . "<br />" : ""; ?>
                            </p>
                            <?php endforeach; ?>
                        
                    </div>
                    <?php endif; ?>
                    <div class='col-sm-3'>
                        <h4>Notes</h4>
                        <p>
                            <?php if(!empty($job['Job']['notes'])): ?>
                            <?= $job['Job']['notes']; ?>
                            <?php else: ?>
                            <em>No notes listed for this job.</em>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
    </div>
<div class='row'>
    <div class='col-sm-12'>
        <?= $this->element('job/attendees'); ?>
       
    </div>
</div>

<h3><?php if($isScheduler) { ?>Scheduler/Employee Task Lists <?php } else { ?>Your Task List<?php } ?></h3>
<div class='row'>
    <?php $count = 0; if(isset($job['JobTaskList']) && !empty($job['JobTaskList'])): foreach($job['JobTaskList'] as $list):
        if($list['type'] == 'scheduler' && $isScheduler): 

        ?>
    <div class='col-sm-4'>
        <div class="box">
            <div class="box-header">
                <i class='fa fa-cog'></i>
              <h3 class="box-title">Scheduler</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

                <ul class='todo-list job-todo-list'>
                    <?php 
                    $completed = $total = 0;
                    foreach($list['JobTaskListItem'] as $item): ?>
                    <li data-id='<?= $item['id']; ?>' 
                        <?php if(isset($item['completed']) && !empty($item['completed'])) { 
                            echo "class='done' title='completed: " . $item['completed'] . "'"; 
                            $completed++;
                        }
                        $total++; ?>>
                        <input value="" <?php if(isset($item['completed']) && !empty($item['completed'])) { echo "checked "; } ?>
                               type="checkbox">
                        <span class="text"><?= $item['TaskItem']['long_name']; ?></span>
                    </li>
                    <?php endforeach; 
                    $percentage = number_format((($completed/$total)*100),0);
                    $bar = '<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percentage.'%">
                  <span class="sr-only">' . $percentage . '% Complete</span>
                </div>'; 
                    ?> 
                </ul>
                <h4>Progress: </h4>
                <div class='progress progress-sm active' title='<?= $percentage; ?>% Completed'>
                <?= $bar; ?>
                </div>
               
            </div>
            <!-- /.box-body -->
          </div>
    </div>
    <?php $count++; endif;
    endforeach; endif; ?>

<?php if(!empty($job['ScheduleEntry'])): ?>

    <?php 
    foreach($job['ScheduleEntry'] as $entry):  
  
// deny denied entries
        if($entry['approved'] !== "0" && ($entry['user_id'] == $currentUser['id'] || $isScheduler)): 
        
        
        
        $count++;?>
    <div class="col-sm-4">
<!-- JOBS -->
<div class="box">
    
            <div class="box-header">
                <?= $entry['approved'] === '1' ? '<i style="color: green;" class="fa fa-lg fa-check"></i>' : '<i style="color: orange;" class="fa fa-lg fa-question-circle"></i>'; ?>
              <h3 class="box-title"><?= $entry['User']['first_name'] . " " . $entry['User']['last_name']; ?></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class='table table-condensed table-striped'>
                    <tr>
                        <td><strong>Position</strong></td>
                        <td><?= ucwords(str_replace("_", " ", $entry['position'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Approved?</strong></td>
                        <td><?= $entry['approved'] === '1' ? '<i style="color: green;" class="fa fa-lg fa-check"></i>' : '<i style="color: orange;" class="fa fa-lg fa-question-circle"></i>'; ?></td>
                        
                    </tr>
                    <tr>
                        <td><strong>Contact Email <i class='fa fa-envelope'></i></strong></td>
                        <td><?= $entry['User']['email']; ?></td>
                    </tr>
                </table>
                <hr>
                <!-- TASK LIST!! -->
                <h4>Task List</h4>
                
               <?php if(isset($entry['JobTaskList']) && !empty($entry['JobTaskList'])): ?>
                <ul class='job-todo-list todo-list'>
                    <?php 
                    $completed = $total = 0;
                    foreach($entry['JobTaskList']['JobTaskListItem'] as $item): ?>
                    <li data-id='<?= $item['id']; ?>' 
                        <?php if(isset($item['completed']) && !empty($item['completed'])) { 
                            echo "class='done' title='completed: " . $item['completed'] . "'"; 
                            $completed++;
                        }
                        $total++; ?>>
                        <input value="" <?php if(isset($item['completed']) && !empty($item['completed'])) { echo "checked "; } ?>
                               type="checkbox">
                        <span class="text"><?= $item['TaskItem']['long_name']; ?></span>
                    </li>
                    <?php endforeach; 
                    $percentage = number_format((($completed/$total)*100),0);
                    $bar = '<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percentage.'%">
                  <span class="sr-only">' . $percentage . '% Complete</span>
                </div>'; 
                    ?> 
                    
                </ul>
                <h4>Progress: </h4>
                <div class='progress progress-sm active' title='<?= $percentage; ?>% Completed'>
                <?= $bar; ?>
                </div>
                    <?php else:  ?>
                            <p><em>
                        No Task List Assigned!!! Contact an administrator.
                        </em></p>
                    
                    <?php endif; ?>
                </div>
               
            </div>
            <!-- /.box-body -->
          </div>

    <?php endif; endforeach; ?>

<?php else: ?>
    <div class='col-sm-4'>
        <p>
            <em>No employees scheduled yet!!</em>
        </p>
    </div>
  <?php  endif; ?>
</div>
<div class='row'>
    <div class='col-sm-6'>
   <?= $this->element('quick_email'); ?>
    </div>
</div>


