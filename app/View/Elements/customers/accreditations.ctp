<div class="box box-danger collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-trophy"></i>

              <h3 class="box-title">Accreditations</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                  <?php $count = 0;
if(!empty($accreditations))
    $count = count($accreditations);
?>
                  <span data-toggle="tooltip" title="" class="badge bg-red" data-original-title="<?= $count; ?> Accreditation Entries"><?= $count; ?></span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
<?php if (!empty($accreditations)): ?>

<?php else: ?>
<p>There are no accreditations linked to this customer.</p>
<?php endif; ?>
    </div>
</div>