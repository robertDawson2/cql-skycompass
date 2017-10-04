
<div class="box box-success">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-graduation-cap"></i>
<?php $count = 0;
if(!empty($trainings))
    $count = count($trainings);
?>
              <h3 class="box-title">Training Events</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                  <span data-toggle="tooltip" title="" class="badge bg-green" data-original-title="<?= $count; ?> Trainings"><?= $count; ?></span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
<?php if (!empty($trainings)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="trainings-table">
	<thead>
		<tr>

			<th>Name</th>
			<th>Type</th>
                        <th>Location</th>
                        <th>Attendees</th>
			<th>Upsell?</th>
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($trainings as $training) { ?>
		<tr>
                    
			<td><a role="button" class="btn btn-primary"
                               href="/admin/jobs/dashboard/<?php echo $training['Job']['id']; ?>">
                                <i class='fa fa-graduation-cap'></i> <?php echo $training['Job']['name']; 
                       ?></a></td>
                        <td><?= $training['ServiceArea']['name']; ?></td>
			<td>
                            <?= $training['Job']['addr1']; ?><br />
                            <?= !empty($training['Job']['addr2']) ? $training['Job']['addr2'] . "<br />" : ""; ?>
                            <?= $training['Job']['city'] . ", " . $training['Job']['state'] . " " . 
                                    $training['Job']['zip']; ?>
                        </td>
                        <td>
                            <?= $training['Job']['people_served_count']; ?>
                        </td>
                        <td>
                            <?php if($training['Job']['training_upsell'] !== null): ?>
                             <?php if($training['Job']['training_upsell'] === '1'): ?>
                             <i class='fa fa-2x fa-check-circle-o' style='color: green;'></i>
                             
                             <?php endif; ?>
                             <?php endif; ?>
                        </td>
                        
		
                </tr>
	<?php } ?>
	</tbody>
</table>

<?php else: ?>
<p>There are no training events linked.</p>
<?php endif; ?>
    </div>
</div>