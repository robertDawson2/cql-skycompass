<?php $this->set('title_for_layout', 'Manage Forms'); ?>



<?php if (!empty($forms)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="users-table">
	<thead>
		<tr>
			<th>Title</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($forms as $form) { ?>
		<tr>
			<td><?php echo $form['Form']['title']; ?></td>
			<td>
				<a role="button" class="btn btn-primary" href="/admin/forms/results/<?php echo $form['Form']['id']; ?>"><i class="fa fa-bar-chart-o"></i> View Submissions</a>&nbsp;
				<a role="button" class="btn btn-primary" href="/admin/forms/export/<?php echo $form['Form']['id']; ?>" target="_blank"><i class="fa fa-file-o"></i> Export Submissions</a>&nbsp;
                <a role="button" class="btn btn-primary-red" href="/admin/forms/archive/<?php echo $form['Form']['id']; ?>"><i class="fa fa-trash-o"></i> Archive Submissions</a>&nbsp;
				<?php if (0): ?><a role="button" class="btn btn-primary" href="/admin/users/history/<?php echo $user['User']['id']; ?>"><i class="fa fa-bar-chart-o"></i> View Activity</a>&nbsp;
				<a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $user['User']['first_name']; ?> <?php echo $user['User']['last_name']; ?>" data-object-id="<?php echo $user['User']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a><?php endif; ?>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php if (0) echo $this->element('modals/delete', array('title' => 'Delete User', 'text' => 'delete the user record for <strong>{name}</strong>', 'action' => '/admin/users/delete/{id}')); ?>

<?php else: ?>
<p>There are no forms in your database.</p>
<?php endif; ?>

<?php if (0): ?><a role="button" href="/admin/users/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New User</a><?php endif; ?>