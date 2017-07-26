<?php $this->set('title_for_layout', 'View Submitted '
        . "Time Entries"); ?>

<h1>Submitted Time Entries</h1>
<?php if (!empty($timeEntries)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="users-table">
	<thead>
		<tr>
                    
			<th>Date</th>
                        <th>User</th>
                        <th>Duration</th>
			<th>Organization</th>
			<th>Item</th>
			<th>Class</th>
                        <th>Payroll Item</th>
                        <th>Notes</th>
                        
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($timeEntries as $time) {  $entry = $time['TimeEntry'];?>
                <tr>
                        
			<td><?php echo date("m/d/Y", strtotime($entry['txn_date'])); ?></td>
                        <td><?= $time['User']['first_name'] . " " . $time['User']['last_name']; ?></td>
                        <td><?= str_replace("PT", "", str_replace("H", " Hrs, ", str_replace("M"," Mins",$entry['duration']))); ?></td>
			<td><?php echo $time['Customer']['full_name']; ?></td>
                        <td><?php if(isset($time['Item']['full_name'])) { echo $time['Item']['full_name']; } ?></td>
			<td><?php echo $entry['class_name']; ?></td>
                        <td><?php echo $entry['payroll_item_name']; ?></td>
                        <td><?= $entry['notes']; ?></td>
                        
                       
                        
                        
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Entry', 'text' => 'delete this time entry?', 'action' => '/admin/users/delete/{id}')); ?>

<?php else: ?>
<p>There are no time entries for this user in your database.</p>
<?php endif; ?>
