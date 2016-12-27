<?php $this->assign('title', $job['Job']['full_name'] . " Dashboard"); ?>
<div class="row">
    <div class="col-sm-12">
 
<!-- Job -->
<div class="box">
            <div class="box-header">
                <i class="fa fa-eye-slash"></i>
                <h3 class="box-title"><em><strong><?= $job['Job']['name']; ?></strong> Quick Look</em></h3>
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
                           <?= $job['Customer']['bill_addr1'] . "<br />" .
                        $job['Customer']['bill_addr2'] . "<br />" .$job['Customer']['bill_city'] . ", " . $job['Customer']['bill_state'] . " " . $job['Customer']['bill_zip']; ?> 
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
                            <strong>Total Balance: </strong>$<?= $job['Job']['total_balance']; ?> <br />
                            
                            <?php if($job['Job']['eng_fee_paid']) { ?><i style='color: green;' class='fa fa-check green'></i> <?php }
                             else { ?><i style='color: red;' class='fa fa-remove'></i> <?php } ?>
                            Engagement Fee Paid?<br />
                            # People Served: <?= $job['Job']['people_served_count']; ?><br />
                            Team Leader Count: <?= $job['Job']['team_leader_count']; ?><br />
                            Employee Count: <?= $job['Job']['employee_count']; ?><br />
                            Service Area: <?= $job['ServiceArea']['name']; ?><br />
                            <?php if(!empty($job['ScheduleEntry'])) { ?><i style='color: green;' class='fa fa-check green'></i> <?php }
                             else { ?><i style='color: red;' class='fa fa-remove red'></i> <?php } ?>
                            Scheduled?<br />
                            <?php if(!empty($job['ScheduleEntry'])) { ?>
                            <small><em><?= $job['Job']['start_date']; ?> - <?= $job['Job']['end_date']; ?></em><small><?php } ?>
                        </p>
                    </div>
                    <div class='col-sm-3'>
                        <h4>Notes</h4>
                        <p>
                            <?= $job['Job']['notes']; ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
    </div>
</div>

<h3><?php if($isScheduler) { ?>Scheduler/Employee Task Lists <?php } else { ?>Your Task List<?php } ?></h3>
<div class='row'>
    <?php $count = 0; foreach($job['JobTaskList'] as $list):
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

                <ul class='todo-list'>
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
    endforeach; ?>

<?php if(!empty($job['ScheduleEntry'])): ?>

    <?php 
    foreach($job['ScheduleEntry'] as $entry):  
  
// deny denied entries
        if($entry['approved'] !== "0" && ($entry['user_id'] == $currentUser['id'] || $isScheduler)): 
        
        
        if($count > 0 && $count%3 == 0)
            echo "</div><div class='row'>"; 
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
                
               
                <ul class='todo-list'>
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
               
            </div>
            <!-- /.box-body -->
          </div>
    </div>
    <?php endif; endforeach; ?>

<?php else: ?>
    <div class='col-sm-4'>
        <p>
            <em>No employees scheduled yet!!</em>
        </p>
    </div>
  <?php  endif; ?>

    <div class='col-sm-6'>
   <?= $this->element('quick_email'); ?>
    </div>
</div>


<?php $this->append('scripts'); ?>
<script>
$(".textarea").wysihtml5();

$(".todo-list").todolist({
    onCheck: function (ele) {
      $.ajax('/admin/jobTaskListItems/ajaxChangeItemStatus/' + $(this).data('id') + '/1');
      return ele;
    },
    onUncheck: function (ele) {
      $.ajax('/admin/jobTaskListItems/ajaxChangeItemStatus/' + $(this).data('id') + '/0');
      return ele;
    }
  });
</script>
<?php $this->end(); ?>