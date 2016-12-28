<style>
    .jobs-progress-section:hover {
        opacity: 0.9;
        background-color: #dfdfdf;
        cursor: pointer;
    }
</style>     
<div class="box box-widget">
         
            <div class="box-header with-border ui-sortable-handle">
              <h3 class="box-title">Job Progress</h3>
               <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <?php foreach($jobsProgress as $job): ?>
                <div class='form-group jobs-progress-section' data-id='<?= $job['jobId']; ?>'>
                <p><?= $job['jobName']; ?><br />
                <?php if(!empty($job['lastDone'])) { ?>
                    <small>Last Completed Item: <?= $job['lastDone']['TaskItem']['long_name'] . " (" .date('m/d/Y H:i', strtotime($job['lastDone']['completed'])) . ")"; ?></small> <br />
                    <small>Next Item In Queue: <?= $job['nextUp']['TaskItem']['long_name']; ?></small>
                <?php } ?>
                </p>
              <div class="progress progress">
                  
                <div class="progress-bar progress-bar-active progress-bar-striped progress-bar-green" role="progressbar" aria-valuenow="<?= $job['percentage']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $job['percentage']; ?>%">
                  <span class="sr-only"><?= $job['percentage']; ?>% Complete</span>
                </div>
              </div>
                </div>
                <hr>
                <?php endforeach; ?>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          
          <?php $this->append('scripts'); ?>
          <script>
           $(".jobs-progress-section").click(function() {
               window.location.href = "/admin/jobs/dashboard/" + $(this).data('id');
           });
          </script>
          <?php $this->end(); ?>