<div class="tab-pane" id="control-sidebar-jobs-tab">
        
          <h3 class="control-sidebar-heading">Job Task Lists</h3>
          <hr>
          <?php if(!empty($jobtasklists)) {
                  foreach($jobtasklists as $tl) {
                      echo $this->element('jobtasklist', array('entry' => $tl));
                      echo "<hr>";
                  }
          } ?>
          
      </div>
      <!-- /.tab-pane -->