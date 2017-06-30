<div class="row">
    <div class="col-sm-12">
        <style>.edit-box {
                display: inline;
            margin-left: 30px;
            }</style>
<!-- Customer -->
<div class="box">
            <div class="box-header">
                <i class="fa fa-eye-slash"></i>
                <h3 class="box-title"><em><strong><?= $customer['Customer']['name']; ?></strong> Quick Look</em></h3>
                <div class='edit-box'><a href='/admin/customers/edit/<?= $customer['Customer']['id']; ?>' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i> Edit</a></div>
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
                           <?= $customer['Customer']['bill_addr2'] . "<br />" . $customer['Customer']['bill_city'] . ", " . $customer['Customer']['bill_state'] . " " . $customer['Customer']['bill_zip']; ?> 
                        </p>
                    </div>
                    <div class='col-sm-3'>
                        <h4>Contact Info</h4>
                        <p>
                            <strong><?= $customer['Customer']['contact']; ?></strong> <br />
                            <?php if(!empty($customer['Customer']['email'])) { ?>
                            <i class='fa fa-envelope'></i> <?= $customer['Customer']['email']; ?><br />
                            <?php } if(!empty($customer['Customer']['phone'])) { ?>
                            <i class='fa fa-phone'></i> <?= $customer['Customer']['phone']; ?><br />
                            <?php } if(!empty($customer['Customer']['fax'])) { ?>
                            <i class='fa fa-fax'></i> <?= $customer['Customer']['fax']; ?>
                            <?php } ?>
                        </p>
                    </div>
                    <div class='col-sm-3'>
                        <h4>Balance Information</h4>
                        <p>
                            <strong>Total Balance: </strong>$<?= $customer['Customer']['total_balance']; ?>
                        </p>
                    </div>
                    <div class='col-sm-3'>
                        <h4>Notes</h4>
                        <p>
                            <?= $customer['Customer']['notes']; ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
    </div>
</div>
<div class ="row">
    <div class="col-sm-7">
<!-- JOBS -->
<div class="box collapsed-box">
            <div class="box-header">
                <i class="fa fa-cog"></i>
              <h3 class="box-title">Jobs</h3>
              <div class="box-tools pull-right">
                                    <?php $count = 0;
if(!empty($jobs))
    $count = count($jobs);
?>
                  <span data-toggle="tooltip" title="" class="badge bg-warning" data-original-title="<?= $count; ?> Jobs"><?= $count; ?></span>
                  
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                 <?php if(!empty($jobs)): ?>
              <table class="table table-condensed">
                <tbody><tr>
                        <th></th>
                  <th>Date</th>
                  <th>Name</th>
                  <th>Progress</th>
<!--                  <th style="width: 40px">Label</th>-->
                </tr>
               
                <?php foreach($jobs as $job): ?>
                <tr>
                    <td><a href='/admin/jobs/dashboard/<?= $job['Job']['id']; ?>' class='btn btn-sm btn-primary'><i class='fa fa-eye'></i> View</a></td>
                  <td><?php
                  if(isset($job['Job']['start_date']))
                    echo date('m/d/Y', strtotime($job['Job']['start_date'])). " - " . date('m/d/Y', strtotime($job['Job']['end_date'])); 
                  else
                      echo "<EM>Unscheduled</EM>"; ?></td>
                  <td><?= $job['Job']['name']; ?></td>
                  <td>
                      <?php $completed = 0; $total = 0;
                      
                      foreach($job['JobTaskList'] as $taskList): 
                          foreach($taskList['JobTaskListItem'] as $item):
                            if($item['completed'] !== null)
                                $completed++;
                            
                            $total++;
                          endforeach;
                      endforeach;
                      if($total > 0) {
                      $progress = number_format($completed/$total, 2) * 100;
                      }
                      else
                          $progress = 0;
                      ?>
                      
                    <div class="progress progress-sm progress-striped active" title="<?= $progress; ?>% Complete" >
                      <div class="progress-bar progress-bar-aqua" style="width: <?= $progress; ?>%"></div>
                    </div>
                  </td>
<!--                  <td><span class="badge bg-red">55%</span></td>-->
                </tr>
               <?php endforeach; ?>
              </tbody></table>
                <?php else: ?>
                <p><em>No scheduled jobs found for this customer.</em></p>
                <?php endif; ?>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
    
    <div class='col-sm-5'>
   <?= $this->element('quick_email'); ?>
    </div>
</div>
<div class='row'>
    <div class='col-sm-7'>
           <?php $contacts = array();
           if(isset($customer['Contact']))
            $contacts = $customer['Contact']; ?>
        <?= $this->element('customers/contacts', array('contacts' => $contacts)); ?>
    </div>
    <div class='col-sm-5'>
<?php $files = array();
           if(isset($customer['CustomerFile']))
            $files = $customer['CustomerFile']; ?>
        <?= $this->element('customers/files', array('files' => $files)); ?>
    </div>
</div>
<div class='row'>
    <div class='col-sm-7'>
           <?php $accreditations = array();
           if(isset($customer['CustomerAccreditation']))
            $accreditations = $customer['CustomerAccreditation']; ?>
        <?= $this->element('customers/accreditations', array('accreditations' => $accreditations)); ?>
    </div>
</div>

<?php $this->append('scripts'); ?>
<script>
$(".textarea").wysihtml5();
</script>
<?php $this->end(); ?>