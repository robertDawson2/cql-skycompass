
<?php $this->append('scripts'); ?>
<script>
    $(".iframe").fancybox();
    
    </script>

<?php $this->end(); ?>

<div class="box box-info collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-send"></i>

              <h3 class="box-title">Communications</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                   
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
        <div class="box-body">
            <h3>Internal Communications</h3>
            <table id='communicationsTable' class="table table-responsive table-striped table-hover table-condensed export-dataTable">
                <thead>
                    <tr>
                        <th class='show-on-export'>
                            Date
                        </th>
                        <th class='show-on-export'>
                            Context
                        </th>
                        <th class='show-on-export'>
                            Recipient Name
                        </th>
                        <th class='show-on-export'>
                            Recipient Email
                        </th>
                        <th class='show-on-export'>
                            Subject
                        </th>
                        <th class='show-on-export'>
                            Email Template
                        </th>
                        <th class='show-on-export'>
                            Result
                        </th>
                    </tr>
                    
                </thead>
                <tbody>
                    <?php foreach($internalCommunications as $comm): ?>
                    <tr>
                        <td>
                            <?= date('m/d/Y H:i', strtotime($comm['Communication']['created'])); ?>
                        </td>
                        <td>
                            <?= ucwords($comm['Communication']['context']); ?>
                        </td>
                        <td>
                            <?= $comm['Contact']['first_name'] . " " . $comm['Contact']['last_name']; ?>
                        </td>
                        <td>
                            <?= $comm['Contact']['email']; ?>
                        </td>
                        <td>
                            <?= $comm['Communication']['template_subject']; ?>
                        </td>
                        <td>
                            <a href='/admin/emailTemplates/preview/<?= $comm['Communication']['email_template_id']; ?>' class='iframe'>
                            <?= $comm['Communication']['template_name']; ?></a>
                        </td>
                        <td>
                            <?= ucwords($comm['Communication']['result']); ?>
                        </td>
                    </tr>
                    
                    <?php endforeach; ?>
                </tbody>
            </table>
           
        </div>
        
      
 </div>