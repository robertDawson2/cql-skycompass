<?php $this->set('title_for_layout', 'Manage My '
        . "Time Entries"); ?>

<h1>Time Entries for <small><em><?= $user['first_name'] . " " . $user['last_name']; ?></em></small></h1>
<?php if (!empty($timeEntries)): ?>
<table class="table table-striped table-bordered table-hover dataTable" id="users-table">
	<thead>
		<tr>
                    
			<th>Date</th>
                        <th>Duration</th>
			<th>Customer/Job</th>
			<th>Item</th>
			<th>Class</th>
                        <th>Payroll Item</th>
                        <th>Approved?</th>
                        <th>Options</th>
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($timeEntries as $time) {  $entry = $time['TimeEntry'];?>
                <tr <?php if($entry['approved']) { ?> class='disabled' <?php } ?>>
                        
			<td><?php echo date("m/d/Y", strtotime($entry['txn_date'])); ?></td>
                        <td><?= str_replace("PT", "", str_replace("H", " Hrs, ", str_replace("M"," Mins",$entry['duration']))); ?></td>
			<td><?php echo $time['Customer']['full_name']; ?></td>
                        <td><?php if(isset($time['Item']['full_name'])) { echo $time['Item']['full_name']; } ?></td>
			<td><?php echo $entry['class_name']; ?></td>
                        <td><?php echo $entry['payroll_item_name']; ?></td>
                        <td><?php 
                        if($entry['approved'] === null)
                                      echo "";
                        elseif($entry['approved']==1) 
                                    echo "<i class='green fa fa-lg fa-check-circle-o'></i> Approved";
                                  elseif($entry['approved']==0)
                                     echo "<i class='red fa fa-lg fa-warning'></i> Denied";
                                  
                                      ?></td>
                       
                        <td>
                             <?php if(!$entry['approved']): ?>
                            <a class="btn btn-success" href="/admin/timeEntry/edit/<?= $entry['id']; ?>"><i class='fa fa-edit'></i> Edit</a>
                            <a class="btn btn-danger" href="/admin/timeEntry/delete/<?= $entry['id']; ?>"><i class='fa fa-remove'></i> Delete</a>
                            <?php endif; ?>
                        </td>
                        
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php echo $this->element('modals/delete', array('title' => 'Delete Entry', 'text' => 'delete this time entry?', 'action' => '/admin/users/delete/{id}')); ?>

<?php else: ?>
<p>There are no time entries for this user in your database.</p>
<?php endif; ?>
