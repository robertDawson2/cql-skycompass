<?php $this->set('title_for_layout', 'Manage '
        . "Time Entries"); ?>

<h1>Time Entries for <small><em><?= $user['User']['first_name'] . " " . $user['User']['last_name']; ?></em></small></h1>
<?php if (!empty($user['TimeEntry'])): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="users-table">
	<thead>
		<tr>
                    <th></th>
			<th>Date</th>
                        <th>Duration</th>
			<th>Customer/Job</th>
			<th>Item</th>
			<th>Class</th>
                        <th>Payroll Item</th>
                        <th>Billable Status</th>
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($user['TimeEntry'] as $entry) { ?>
                <tr <?php if($entry['approved']) { ?> class='disabled' <?php } ?>>
                        <td><input style="width: 15px; height: 15px;" name="data[<?= $entry['id']; ?>][approved]" type="checkbox" <?php echo $entry['approved'] ? "checked disabled" : ""; ?> class="input form-control"/> </td>
			<td><?php echo date("m/d/Y", strtotime($entry['txn_date'])); ?></td>
                        <td><?= str_replace("PT", "", str_replace("H", " Hrs, ", str_replace("M"," Mins",$entry['duration']))); ?></td>
			<td><?php echo $entry['Customer']['full_name']; ?></td>
                        <td><?php if(isset($entry['Item']['full_name'])) { echo $entry['Item']['full_name']; } ?></td>
			<td><?php echo $entry['class_name']; ?></td>
                        <td><?php echo $entry['payroll_item_name']; ?></td>
                        <td><select <?php echo $entry['approved'] ? "disabled" : ""; ?> class="input form-control" selected='<?php echo $entry['billable_status']; ?>' name='data[<?= $entry['id']; ?>][billable_status]'><option value='Billable'>Billable</option><option value='NotBillable'>Not Billable</option><option value='HasBeenBilled'>Billed</option></select></td>
			
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete User', 'text' => 'delete the user record for <strong>{name}</strong>', 'action' => '/admin/users/delete/{id}')); ?>

<?php else: ?>
<p>There are no time entries for this user in your database.</p>
<?php endif; ?>
