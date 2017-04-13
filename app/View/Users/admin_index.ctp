<?php $this->set('title_for_layout', 'Manage '
        . ucfirst($type) . "s"); ?>


<?php if (!empty($users)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="users-table">
	<thead>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
                        <th>Type</th>
			<th>Email Address</th>
			<th>Last Logged In</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($users as $user) { ?>
		<tr>
			<td><?php echo $user['User']['first_name']; ?></td>
			<td><?php echo $user['User']['last_name']; ?></td>
                        <td><?= ucfirst($user['User']['web_user_type']); ?></td>
			<td><?php echo $user['User']['email']; ?></td>
			<td><?php echo $user['User']['last_login'] == '0000-00-00 00:00:00' ? 'Never' : date('F j, Y g:i a', strtotime($user['User']['last_login'])); ?></td>
			<td>
				<a role="button" class="btn btn-primary" href="/admin/users/edit/<?php echo $user['User']['id']; ?>"><i class="fa fa-edit"></i> Edit</a>&nbsp;
				<a role="button" class="btn btn-success" href="/admin/users/timeEntries/<?php echo $user['User']['id']; ?>"><i class="fa fa-clock-o"></i> View Time</a>&nbsp;
                                <a role="button" class="btn btn-success" href="/admin/expenses/userExpenses/<?php echo $user['User']['vendor_id']; ?>"><i class="fa fa-money"></i> View Expenses</a>&nbsp;
				<?php if(0): ?><a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $user['User']['first_name']; ?> <?php echo $user['User']['last_name']; ?>" data-object-id="<?php echo $user['User']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>&nbsp;<?php endif; ?>
                                <p style="padding-top: 5px;">  <a role="button" class="btn btn-info" href="/admin/users/sendWelcomeEmail/<?php echo $user['User']['id']; ?>"><i class="fa fa-envelope-o"></i> Welcome Email</a>&nbsp;
                                <a role="button" class="btn btn-warning" href="/admin/schedule/userSchedule/<?php echo $user['User']['id']; ?>"><i class="fa fa-calendar-o"></i>View Schedule</a>&nbsp;
                                </p>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete User', 'text' => 'delete the user record for <strong>{name}</strong>', 'action' => '/admin/users/delete/{id}')); ?>

<?php else: ?>
<p>There are no users in your database.</p>
<?php endif; ?>

<a role="button" href="/admin/users/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New User</a>