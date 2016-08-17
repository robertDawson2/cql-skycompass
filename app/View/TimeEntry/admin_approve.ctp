<?php $this->set('title_for_layout', 'Manage Unapproved '
        . "Time Entries"); ?>

<h1>Pending Time Entries</h1>
<?php if (!empty($entries)): ?>
<?php echo $this->Form->create('approve'); ?>
<table class="table table-striped table-bordered table-hover approval-dataTable" id="users-table">
	<thead>
		<tr>
                    <th></th>
                    <th>Employee</th>
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
	<?php foreach ($entries as $time) { $entry = $time['TimeEntry']; ?>
                <tr <?php if($entry['approved']) { ?> class='disabled' <?php } ?>>
                        <td><input style="width: 15px; height: 15px;" name="data[entries][<?= $entry['id']; ?>][approved]" type="checkbox" <?php echo $entry['approved'] ? "checked disabled" : ""; ?> class="input form-control checkbox" /> </td>
                        <td><?= $time['User']['first_name'] . " " . $time['User']['last_name'];?></td>
			<td><?php echo date("m/d/Y", strtotime($entry['txn_date'])); ?></td>
                        <td><?= str_replace("PT", "", str_replace("H", " Hrs, ", str_replace("M"," Mins",$entry['duration']))); ?></td>
			<td><?php echo $time['Customer']['full_name']; ?></td>
                        <td><?php if(isset($time['Item']['full_name'])) { echo $time['Item']['full_name']; } ?></td>
			<td><?php echo $entry['class_name']; ?></td>
                        <td><?php echo $entry['payroll_item_name']; ?></td>
                        <td><select <?php echo $entry['approved'] ? "disabled" : ""; ?> class="input form-control" selected='<?php echo $entry['billable_status']; ?>' name='data[entries][<?= $entry['id']; ?>][billable_status]'><option value='Billable'>Billable</option><option value='NotBillable'>Not Billable</option><option value='HasBeenBilled'>Billed</option></select></td>
			
		</tr>
	<?php } ?>
	</tbody>
</table>
<input type="hidden" id="approveDeny" name="data[TimeEntry][approveDeny]" value="" />
<?php echo $this->form->end(); ?>
<div class="row">
    <h3>Options:</h3>
    <div class="col-md-2"><a class="btn btn-block btn-default" href="#" id="btn-check-uncheck"><i class="fa fa-check-square-o"></i> Select/Deselect All</a></div>
    <div class="col-md-2"><a class="btn btn-block btn-info" href="#" id="btn-approve"><i class="fa fa-check"></i> Approve Selected</a></div>
    <div class="col-md-2"><a class="btn btn-block btn-danger" href="#" id="btn-deny"><i class="fa fa-remove"></i> Deny Selected</a></div>
</div>
<?php echo $this->element('modals/delete', array('title' => 'Delete User', 'text' => 'delete the user record for <strong>{name}</strong>', 'action' => '/admin/users/delete/{id}')); ?>

<?php else: ?>
<p>There are no pending time entries in your database.</p>
<?php endif; ?>

<?php $this->append('scripts'); ?>
<script>
var checked = false;
$("#btn-check-uncheck").click(function(e) {
    e.preventDefault();
    if(checked)
    {
        $(".checkbox").each(function(){
           $(this).prop("checked", false); 
        });
        checked = false;
    }
    else
    {
        
        $(".checkbox").each(function(){
           $(this).prop("checked", true); 
        });
        checked = true;
    }
});

$("#btn-approve").click(function(e) {
    e.preventDefault();
    $("#approveDeny").val('approve');
    $("#approveAdminApproveForm").submit();
});

$("#btn-deny").click(function(e) {
    e.preventDefault();
    $("#approveDeny").val('deny');
    $("#approveAdminApproveForm").submit();
});
</script>

<?php $this->end(); ?>