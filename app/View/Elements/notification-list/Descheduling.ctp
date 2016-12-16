
                    <a href="<?= $data['Notification']['href']; ?>">
                      <i class="fa fa-remove text-red"></i> 
                      <?= str_replace("%i",$data['Notification']['count'], $data['Notification']['message']); ?>
                    </a>
                 