<?php $this->set('title_for_layout', 'Manage Unapproved '
        . "Schedulings"); ?>

<h1>Pending Schedule</h1>
<?php if (!empty($entries)): ?>
<?php echo $this->Form->create('approveMySchedule'); ?>
<table class="table table-striped table-bordered table-hover approval-dataTable" id="users-table">
	<thead>
		<tr>
                    <th></th>
                    <th>Job</th>
                    <th>Type</th>
                    <th>Position</th>
			<th>Start Date</th>
                        <th>End Date</th>
                        
                        <th>Notes</th>
                        <th>Options</th>
                        
                        
			
		</tr>
	</thead>
	<tbody>
	<?php foreach ($entries as $time) { $entry = $time['ScheduleEntry']; ?>
                <tr <?php if($entry['approved']) { ?> class='disabled' <?php } ?>>
                        <td><input style="width: 15px; height: 15px;" name="data[ScheduleEntry][<?= $entry['id']; ?>][approved]" type="checkbox" <?php echo $entry['approved'] ? "checked disabled" : ""; ?> class="input form-control checkbox" /> </td>
                        <td><?= $time['Job']['full_name'] ;?></td>
                        <td><?= $time['Job']['ServiceArea']['name'] ;?></td>
                        <td><?= ucwords(str_replace("_", " ", $entry['position'])); ?></td>
			<td><?php echo date("m/d/y", strtotime($entry['start_date'])); ?></td>
                        <td><?php echo date("m/d/y", strtotime($entry['end_date'])); ?></td>
                        
                        <td><?php echo $entry['notes']; ?></td>
                        <td>
                        <a class="btn btn-warning denial-notice" href="#">
                                <i class="fa fa-plus-circle"></i> Add Denial Message</a>
                        <input type="hidden" name='data[ScheduleEntry][<?= $entry['id']; ?>][denial_message]' value='' class='denial-msg' /></td>
                       
			
		</tr>
	<?php } ?>
	</tbody>
</table>
<input type="hidden" id="approveDeny" name="data[ScheduleEntry][approveDeny]" value="" />
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

<h1>Approved/Unapproved Entries</h1>
<div id='calendar'>
</div>

<?php $this->append('jquery-scripts'); ?>
$('#calendar').fullCalendar({
    weekends: true,
    defaultView: 'month',
    editable: false,   
    events: [
    <?php foreach($approved as $request): ?>
    
    { title: "<?= $request['User']['first_name'] . " "  . $request['User']['last_name']; ?>:\n<?= ucwords(str_replace("_", " ", $request['ScheduleEntry']['type'])); ?>",
            start: "<?= $request['ScheduleEntry']['start_date']; ?>",
            end: "<?= date("Y-m-d", strtotime($request['ScheduleEntry']['end_date'] . " +1 day")); ?>",
            color: "<?= $request['ScheduleEntry']['approved'] ? 'lightgreen' : 'lightgray'; ?>",
            id: "<?= $request['ScheduleEntry']['id']; ?>"
            },
            <?php endforeach; ?>
    ],
    
    header: {center: 'basicWeek,month,year'}
    
            });
            

<?php $this->end(); ?>
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
    $("#approveMyScheduleAdminApproveMyScheduleForm").submit();
});

$("#btn-deny").click(function(e) {
    e.preventDefault();
    $("#approveDeny").val('deny');
    $("#approveMyScheduleAdminApproveMyScheduleForm").submit();
});

$(".denial-notice").click(function(e) {
    e.preventDefault();
    $j = prompt("Enter denial message below, or cancel to exit.");
    if($j != null)
    {
        $(this).parent().children(".denial-msg").val($j);
        $(this).parent().children(".denial-msg").attr('type', 'text');
    }
});
</script>

<?php $this->end(); ?>