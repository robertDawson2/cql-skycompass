
<div class="box box-success">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-graduation-cap"></i>
<?php $count = 0;
if(!empty($portalSubscriptions))
    $count = count($portalSubscriptions);
?>
              <h3 class="box-title">Portal Subscriptions</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                  <span data-toggle="tooltip" title="" class="badge bg-green" data-original-title="<?= $count; ?> Subscriptions"><?= $count; ?></span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
<?php if (!empty($portalSubscriptions)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="portal-table">
	<thead>
		<tr>

			<th>Access Type</th>
			<th>Start Date</th>
                        <th>End Date</th>
                        <th>Notes</th>
			
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($portalSubscriptions as $portal) { ?>
		<tr>
                    
			
                        <td><?= $portal['access_type']; ?></td>
			<td>
                            <?= date('m/d/Y', strtotime($portal['start_date'])); ?>
                            
                        </td>
                        <td>
                            <?= $portal['end_date'] !== null ? date('m/d/Y', strtotime($portal['end_date'])) : "" ; ?>
                        </td>
                        <td>
                            
                             <?= $portal['notes']; ?>
                            
                             
                              
                        </td>
                        
		
                </tr>
	<?php } ?>
	</tbody>
</table>

<?php else: ?>
<p>There are no portal subscriptions linked.</p>
<?php endif; ?>
    </div>
</div>