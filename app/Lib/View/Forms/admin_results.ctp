<?php $this->set('title_for_layout', 'Form Submissions - ' . $form['Form']['title']); ?>

<?php $this->start('jquery-scripts'); ?>
	$('#users-table').dataTable({	
		"sPaginationType": "bootstrap"
		"order": [[ 3, "desc" ]]
	});
<?php $this->end(); ?>

<?php if (!empty($results)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="users-table">
	<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Phone Number</th>
			<th>Submitted</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($results as $result) { $result['FormResult']['data'] = unserialize($result['FormResult']['data']); ?>
		<tr>
			<td><?php echo isset($result['FormResult']['data']['name']) ? $result['FormResult']['data']['name'] : $result['FormResult']['data']['first_name'] . ' ' . $result['FormResult']['data']['last_name']; ?></td>
			<td><a href="mailto:<?php echo $result['FormResult']['data']['email']; ?>"><?php echo $result['FormResult']['data']['email']; ?></a></td>
			<td><?php echo $result['FormResult']['data']['phone_number']; ?></td>
			<td><?php echo date('n/j/Y g:i A', strtotime($result['FormResult']['created'])); ?></td>
			<td>
				<a role="button" class="btn btn-primary" href="/admin/forms/viewResult/<?php echo $result['FormResult']['id']; ?>"><i class="fa fa-bar-chart-o"></i> View Details</a>&nbsp;
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php else: ?>
<p>There are no submissions for this form.</p>
<?php endif; ?>