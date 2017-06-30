<div class="box box-warning collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-folder-open"></i>

              <h3 class="box-title">Linked Documents</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                                    <?php $count = 0;
if(!empty($files))
    $count = count($files);
?>
                  <span data-toggle="tooltip" title="" class="badge bg-yellow-gradient" data-original-title="<?= $count; ?> Documents"><?= $count; ?></span>
                  
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
<?php if (!empty($files)): ?>

<?php else: ?>
<p>There are no files linked to this customer.</p>
<?php endif; ?>
    </div>
</div>