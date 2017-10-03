<?php $this->set('title_for_layout', 'Manage ' . $past . 
        "Jobs"); ?>

<?php if (!empty($jobs)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="jobs-table">
	<thead>
		<tr>
			<th>Name</th>
                        <th>Organization</th>
                        <th>Job Type</th>
                        <th>Start Date</th>
                        <th>Status</th>
			
                        <th>Balance</th>
                        
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($jobs as $cust) { ?>
		<tr>
			<td><?php echo $cust['Job']['name']; ?></td>
                        <td><a href="/admin/customers/view/<?= $cust['Customer']['id']; ?>">
                            <?php echo $cust['Customer']['full_name']; ?></a></td>
                        <td>
                            <?= isset($serviceArea[$cust['Job']['service_area_id']]) ? 
                            $serviceArea[$cust['Job']['service_area_id']] : ""; ?>
                        </td>
                        <td><?= $cust['Job']['start_date']; ?></td>
                        <td><?= $cust['Job']['job_status']; ?></td>
                        
			<td><?php echo $cust['Job']['total_balance']; ?></td>
            
			<td>
                            
				<a role="button" class="btn btn-primary" href="/admin/jobs/dashboard/<?php echo $cust['Job']['id']; ?>"><i class="fa fa-eye"></i> View</a>&nbsp;
				
                                <?php if($currentUser['pmArray']['jobs']['admin_edit']): ?>
				<a role="button" class="btn btn-primary" href="/admin/jobs/edit/<?php echo $cust['Job']['id']; ?>"><i class="fa fa-edit"></i> Edit</a>&nbsp;
				<?php endif; ?>
                                <a role="button" data-id="<?= $cust['Job']['id']; ?>"  class="btn btn-warning attendee-upload" data-toggle="modal" data-target="#uploadAttendeeList"><i class="fa fa-upload"></i> Upload Attendees</a> 
				<?php if($currentUser['pmArray']['jobs']['admin_delete']): ?><a role="button" class="btn btn-danger delete-object" data-toggle="modal" data-object-name="<?php echo $cust['Job']['full_name']; ?>" data-object-id="<?php echo $cust['Job']['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>&nbsp;<?php endif; ?>
                                
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Job', 'text' => 'delete the job record for <strong>{name}</strong>', 'action' => '/admin/jobs/delete/{id}')); ?>
<?php echo $this->element('modals/upload_attendee_list'); ?>
<?php else: ?>
<p>There are no <?= strtolower($past); ?>jobs in your database.</p>
<?php endif; ?>
<!--
<a role="button" href="/admin/users/create" class="btn btn-primary small"><i class="fa fa-plus"></i> Create New User</a>-->
<?php $this->append('scripts'); ?>
<script>
    $(".attendee-upload").click(function() {
        $("#attendeeJobId").val($(this).data('id'));
    });
    </script>
<?php $this->end(); ?>