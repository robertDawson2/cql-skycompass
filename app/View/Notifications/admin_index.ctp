<?php 

$newCount = 0;

foreach($allNotifications as $notification)
{
    
    if(!$notification['Notification']['seen'])
        $newCount += $notification['Notification']['count'];
 
}


?>
<div class="row">
        <div class="col-md-3">          
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Notification Types</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><i class="fa fa-inbox"></i> All
                  <span class="label label-primary pull-right"><?= $newCount; ?></span></a></li>
<!--                <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
                <li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a>
                </li>
                <li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>-->
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
<!--          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Labels</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
              </ul>
            </div>
             /.box-body 
          </div>-->
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">All Notifications</h3>

              
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped notifications-dataTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                  <tbody>
                      <?php foreach($allNotifications as $notification):?>
                      
                  <tr <?php $notification['Notification']['seen'] ? "class='old'" : ""; ?>>
                    
                    <td class="mailbox-star"><a href="#">
                            <?php if(!$notification['Notification']['seen']) { ?>
                            <i class="fa fa-circle text-green"></i>
                            <?php } else { ?>
                            <i class="fa fa-circle-o text-gray"></i>
                            <?php } ?>
                        </a></td>
                    <td class="mailbox-name"><a href="<?= $notification['Notification']['href']; ?>"<strong><?= $notification['Notification']['title']; ?></strong></td>
                    <td class="mailbox-subject"
                       <?php if($notification['Notification']['seen']) { echo " style='color: #aeaeae;'";} ?> >
                        <em><?= str_replace("%i",$notification['Notification']['count'], $notification['Notification']['message']);; ?></em>
                    </td>
                    
                    <td class="mailbox-date" <?php if($notification['Notification']['seen']) { echo " style='color: #aeaeae;'";} ?> ><?= $notification['elapsed']; ?></td>
                  </tr>

                  <?php endforeach; ?>
                  
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
