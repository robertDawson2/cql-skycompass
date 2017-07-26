<h5><?= $entry['Job']['full_name']; ?></h5>
                
                <ul class='job-todo-list todo-list small'>
                    <?php 
                    $completed = $total = 0;
                    foreach($entry['JobTaskListItem'] as $item): ?>
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
                <h4 class="control-sidebar-subheading">
                Progress
                <span class="label label-success pull-right"><?=$percentage; ?>%</span>
              </h4>
                <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: <?= $percentage; ?>%"></div>
              </div>