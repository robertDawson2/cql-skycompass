<?php $this->set('title_for_layout', 'Manage Events'); ?>

<?php $this->start('jquery-scripts'); ?>
	$('#events-table').dataTable({	
		"sPaginationType": "bootstrap"
	});
<?php $this->end(); ?>

<?php if (!empty($events)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="events-table">
	<thead>
		<tr>
			<th>Title</th>
			<th>Start Date/Time</th>
			<th>End Date/Time</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($events as $event) { ?>
		<tr>
			<td><?php echo $event['Event']['title']; ?></td>
			<td><?php echo $event['Event']['start_date'] == '0000-00-00 00:00:00' ? 'N/A' : date('n/j/Y g:i A', strtotime($event['Event']['start_date'])); ?></td>
			<td><?php echo $event['Event']['end_date'] == '0000-00-00 00:00:00' ? 'N/A' : date('n/j/Y g:i A', strtotime($event['Event']['end_date'])); ?></td>
			<td>
				<a role="button" class="btn btn-primary" href="/admin/events/edit/<?php echo $event['Event']['id']; ?>"><i class="fa fa-edit"></i> Edit</a>&nbsp;
				<a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $event['Event']['title']; ?>" data-object-id="<?php echo $event['Event']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Event', 'text' => 'delete the <strong>{name}</strong> event', 'action' => '/admin/events/delete/{id}')); ?>
<?php else: ?>
<p>There are no events in the database.</p>
<?php endif; ?>

<a role="button" href="/admin/events/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New Event</a>