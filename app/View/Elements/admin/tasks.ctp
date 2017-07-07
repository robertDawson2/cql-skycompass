<?php $this->start('taskalert-list'); ?>
<?php 
$newCount = 0;

foreach($taskalerts as $notification)
{
    if($notification['Notification']['seen'] == 0)
        echo "<li>";
    else
        echo "<li class='old'>";
    
    echo $this->element('notification-list/' . $notification['Notification']['context'], array('data' => $notification));
    if(!$notification['Notification']['seen'])
        $newCount += $notification['Notification']['count'];
    echo "</li>";
}
?>
<?php $this->end(); ?>
<style>
    .old {
        color: #aaa;
        border: 1px solid #ddd;
        background: #eee;
    }
    .old > a, .old > a > i {
        color: #aaa !important;
    }
    </style>
    <li class="dropdown notifications-menu">
            <a href="#" id="display-taskalerts" data-newcount="<?= $newCount; ?>" 
               data-userid="<?= $currentUser['id']; ?>" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <?php if($newCount > 0) { ?>
              <span class="label label-danger alerttasks notification-label"><?= $newCount; ?></span>
              <?php } ?>
            </a>
            <ul class="dropdown-menu">
                <?php if($newCount == 0) { ?>
              <li class="header">You have no notifications</li>
                <?php } ?>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <?= $this->fetch('taskalert-list'); ?>
                </ul>
              </li>
              <li class="footer"><a href="/admin/todo">View all</a></li>
            </ul>
          </li>
          
          
          <?php $this->append('scripts'); ?>
          <script>
              $("#display-taskalerts").click(function() {
                  
                  
                  if($(this).data('newcount') > 0)
                  {
 
                        $('.alerttasks.notification-label').fadeOut('fast');  
                          
                          
        }
              });
              
              
              </script>
          
          <?php $this->end(); ?>