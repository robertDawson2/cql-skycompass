<?php $this->set('title_for_layout', 'Manage ' .
        "Organizations"); ?>


<?php if (!empty($customers)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="customers-table">
	<thead>
		<tr>
			<th>Name</th>
			
                        <th>Primary Contact</th>
			<th>Email Address</th>
			<th>Phone</th>
                        <th>Balance</th>
                        <th>Notes</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($customers as $cust) { ?>
		<tr>
			<td><?php echo $cust['Customer']['full_name']; ?></td>
			<td><?php echo $cust['Customer']['contact']; ?></td>
                        <td><?= ucfirst($cust['Customer']['email']); ?></td>
			<td><?php echo $cust['Customer']['phone']; ?></td>
			<td><?php echo $cust['Customer']['total_balance']; ?></td>
                        <td><?php echo $cust['Customer']['notes']; ?></td>
			<td>
                            <?php if($currentUser['pmArray']['customers']['admin_edit']): ?>
				<a role="button" class="btn btn-primary" href="/admin/customers/edit/<?php echo $cust['Customer']['id']; ?>"><i class="fa fa-edit"></i> View/Edit</a>&nbsp;
                                
				<?php endif; ?>
				<?php if($currentUser['pmArray']['customers']['admin_delete']): ?><a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $user['User']['first_name']; ?> <?php echo $user['User']['last_name']; ?>" data-object-id="<?php echo $user['User']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>&nbsp;<?php endif; ?>
                                
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Organization', 'text' => 'delete the organization record for <strong>{name}</strong>', 'action' => '/admin/customers/delete/{id}')); ?>

<?php else: ?>
<p>There are no organizations in your database.</p>
<?php endif; ?>
<!--
<a role="button" href="/admin/users/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New User</a>-->