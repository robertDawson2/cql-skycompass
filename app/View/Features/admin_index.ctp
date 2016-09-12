<?php $this->set('title_for_layout', 'Manage Home Page Features'); ?>



<?php if (!empty($features)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="features-table">
	<thead>
		<tr>
			<th>Name</th>
			<th>Duration</th>
			<th>Start</th>
			<th>Stop</th>
			<th>Order</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($features as $feature) { ?>
		<tr>
			<td><?php echo $feature['Feature']['name']; ?></td>
			<td><?php echo $feature['Feature']['duration']; ?> seconds</td>
			<td><?php echo $feature['Feature']['begin_showing'] == '0000-00-00 00:00:00' ? 'N/A' : date('n/j/Y g:i A', strtotime($feature['Feature']['begin_showing'])); ?></td>
			<td><?php echo $feature['Feature']['stop_showing'] == '0000-00-00 00:00:00' ? 'Never' : date('n/j/Y g:i A', strtotime($feature['Feature']['stop_showing'])); ?></td>
			<td><?php echo $feature['Feature']['order_id']; ?></td>
			<td>
				<a role="button" class="btn btn-primary" href="/admin/features/edit/<?php echo $feature['Feature']['id']; ?>"><i class="fa fa-edit"></i> Edit</a>&nbsp;
				<a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $feature['Feature']['name']; ?>" data-object-id="<?php echo $feature['Feature']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Feature', 'text' => 'delete the <strong>{name}</strong> feature', 'action' => '/admin/features/delete/{id}')); ?>
<?php else: ?>
<p>There are no home page features.</p>
<?php endif; ?>

<a role="button" href="/admin/features/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New Feature</a>