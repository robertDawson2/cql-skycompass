<?php $this->set('title_for_layout', 'Manage ' .
        "Organizations"); ?>


<?php if (!empty($customers)): ?>
<table class="table table-striped table-bordered table-hover export-dataTable" id="customers-table">
	<thead>
		<tr>
			<th class='show-on-export'>Name</th>
			
                        <th class='show-on-export'>Primary Contact</th>
			<th class='show-on-export'>Email Address</th>
			<th class='show-on-export'>Phone</th>
                        <th class='show-on-export'>Current Jobs</th>
                        <th class='show-on-export'>Balance</th>
                        <th class='show-on-export'>Notes</th>
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
                        <td><?= count($cust['Job']); ?></td>
			<td><?php echo $cust['Customer']['total_balance']; ?></td>
                        <td><?php echo $cust['Customer']['notes']; ?></td>
			<td>
                            <?php if($currentUser['pmArray']['customers']['admin_edit']): ?>
                            <a role="button" class="btn btn-primary" href="/admin/customers/view/<?php echo $cust['Customer']['id']; ?>"><i class="fa fa-eye"></i> View</a>&nbsp; 
				<a role="button" class="btn btn-primary" href="/admin/customers/edit/<?php echo $cust['Customer']['id']; ?>"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                                <a role="button" class="btn btn-success" href="/admin/jobs/add/<?php echo $cust['Customer']['id']; ?>"><i class="fa fa-plus"></i> New Job</a>&nbsp;
                                
				<?php endif; ?>
				<?php if($currentUser['pmArray']['customers']['admin_delete']): ?><a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $cust['Customer']['name']; ?>" data-object-id="<?php echo $cust['Customer']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>&nbsp;<?php endif; ?>
                                
                                
			</td>
		</tr
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Organization', 'text' => 'delete the organization record for <strong>{name}</strong>', 'action' => '/admin/customers/delete/{id}')); ?>

<?php else: ?>
<p>There are no organizations in your database.</p>
<?php endif; ?>
<!--
<a role="button" href="/admin/users/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New User</a>-->