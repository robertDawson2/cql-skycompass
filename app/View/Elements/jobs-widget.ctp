     <div class="box box-primary">
            <div class="box-header with-border">
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
                <p><?= $job['jobName']; ?><br />
                <?php if(!empty($job['lastDone'])) { ?>
                    <small>Last Completed Item: <?= $job['lastDone']['TaskItem']['long_name'] . " (" .date('m/d/Y H:i', strtotime($job['lastDone']['completed'])) . ")"; ?></small> <br />
                    <small>Next Item In Queue: <?= $job['nextUp']['TaskItem']['long_name']; ?></small>
                <?php } ?>
                </p>
              <div class="progress">
                  
                <div class="progress-bar progress-bar-striped progress-bar-green" role="progressbar" aria-valuenow="<?= $job['percentage']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $job['percentage']; ?>%">
                  <span class="sr-only"><?= $job['percentage']; ?>% Complete</span>
                </div>
              </div>
                <hr>
                <?php endforeach; ?>
              <div class="progress">
                <div class="progress-bar progress-bar-aqua" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                  <span class="sr-only">20% Complete</span>
                </div>
              </div>
              <div class="progress">
                <div class="progress-bar progress-bar-yellow" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                  <span class="sr-only">60% Complete (warning)</span>
                </div>
              </div>
              <div class="progress">
                <div class="progress-bar progress-bar-red" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                  <span class="sr-only">80% Complete</span>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->