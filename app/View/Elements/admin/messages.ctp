<li class="dropdown messages-menu">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
<?php

$count = 0;
$this->start('message-contents');
if(empty($messages)): ?>
              <li class="header">You have no new messages</li>
              <?php else: ?>
              <li>
                
                <ul class="menu">
                    <?php $count = count($messages); foreach($messages as $message):
                        
                        
                        ?>
                    
                    
                  <li>
                    <a data-chatid="<?= $message['Chat']['id']; ?>" data-user= "<?= $message['User']['first_name'] . " " . $message['User']['last_name']; ?>" class="message-link" href="/admin/messages/ajaxView/<?= $message['Chat']['id']; ?>">
<!--                      <div class="pull-left">
                        <img src="/adminMenu/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>-->
                      <h4>
                         <?= $message['User']['first_name'] . " " . $message['User']['last_name']; ?>
                        <small><i class="fa fa-clock-o"></i> <?php $diff = time() - strtotime($message['Message']['created']); 
                                                                    if($diff < 60)
                                                                        echo $diff . " seconds ago";
                                                                    elseif($diff < 120)
                                                                        echo "1 minute ago";
                                                                    elseif($diff < 3600)
                                                                        echo (int) ($diff/60) . " minutes ago";
                                                                    elseif($diff < (3600*24))
                                                                        echo (int) ($diff/3600) . " hours ago";
                                                                    else
                                                                        echo (int) ($diff/(3600*24)) . " days ago";
                                                                            
                                                                            
                                                                     ?></small>
                      </h4>
                      <p><?= $message['Message']['message']; ?></p>
                    </a>
                  </li>
                  
                  <?php
                        endforeach;
                   
                   ?>
                  
                    
                  
                </ul>
              </li>
              <?php endif; ?>
              <?php $this->end(); ?>
              <?php if($count > 0){
                    ?>
                  
                   <span class="label label-success"><?= $count; ?></span>
                    <?php } ?>
            </a>
            <ul class="dropdown-menu">
                <?= $this->fetch('message-contents'); ?>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>