<?php $this->set('title_for_layout', 'Manage ' .
        "Contacts"); ?>


<?php if (!empty($contacts)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="contacts-table">
	<thead>
		<tr>
			<th>Name</th>
			
                        <th>Title</th>
			<th>Email</th>
			<th>Phone(s)</th>
                        <th>Linked Customers</th>
                       
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($contacts as $cust) { ?>
		<tr>
			<td><?php echo $cust['Contact']['first_name']. " "; 
                       if(!empty($cust['Contact']['middle_name']))
                           echo $cust['Contact']['middle_name'] . " ";
                       echo $cust['Contact']['last_name']; ?></td>
			<td><?php echo $cust['Contact']['title']; ?></td>
                        <td><?= ucfirst($cust['Contact']['email']); ?></td>
			<td><?php if(!empty($cust['ContactPhone'])) {
                            foreach($cust['ContactPhone'] as $phone) { ?>
                            <em><?=ucwords($phone['type']); ?></em>: <?= $phone['phone']; ?>
                            <?php if(!empty($phone['ext']))
                                    echo " x " . $phone['ext']; ?>
                            <br />
                            <?php }  } ?>
                        </td>
                        <td>
                            <?php if(!empty($cust['Customer'])) {
                            foreach($cust['Customer'] as $customer) { 
                                if($customer['ContactCustomer']['archived'] === NULL): ?>
                            
                            <a href='/admin/customers/view/<?= $customer['id']; ?>'><?= $customer['name']; ?></a>
                            <br />
                            <?php endif; }  } ?>
                        </td>
			
			<td>
                            <?php if($currentUser['pmArray']['customers']['admin_edit']): ?>
                            <a role="button" class="btn btn-primary" href="/admin/contacts/view/<?php echo $cust['Contact']['id']; ?>"><i class="fa fa-eye"></i> View</a>&nbsp; 
				<a role="button" class="btn btn-primary" href="/admin/contacts/edit/<?php echo $cust['Contact']['id']; ?>"><i class="fa fa-edit"></i> Edit</a>&nbsp;
                                
                                
				<?php endif; ?>
				<?php if($currentUser['pmArray']['customers']['admin_delete']): ?><a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $cust['Contact']['first_name']; ?>" data-object-id="<?php echo $cust['Contact']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>&nbsp;<?php endif; ?>
                                
                                
			</td>
		</tr
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Contact', 'text' => 'delete the contact record for <strong>{name}</strong>', 'action' => '/admin/contacts/delete/{id}')); ?>

<?php else: ?>
<p>There are no contacts in your database.</p>
<?php endif; ?>
<!--
<a role="button" href="/admin/users/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New User</a>-->