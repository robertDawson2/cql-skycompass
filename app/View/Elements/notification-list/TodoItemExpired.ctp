
                    <a href="<?= $data['Notification']['href']; ?>">
                      <i class="fa fa-bell gold"></i> 
                      <?= str_replace("%i",$data['Notification']['count'], $data['Notification']['message']); ?>
                    </a>
         