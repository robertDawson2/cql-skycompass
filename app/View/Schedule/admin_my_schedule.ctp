<style>
    .fc-event {
        cursor: pointer;
        margin-bottom: 3px;
        box-shadow: 1px 1px 5px #444;
    }
    .popup-text {
        padding: 5px;
        margin-left: 25px;
        margin-top: 3px;
        box-shadow: 2px 2px 8px #888;
        z-index: 99999999;
    }
    .popup-text p
    {
        margin-left: 10px;
        font-size: 90%;
    }

</style>
<div class='row'>
    <div class='col-md-12'>
<h1><?php if(!isset($userName)): ?>Your<?php else: echo $userName; endif;?> Schedule</h1>

<h3>Key</h3>
<p>
        <?php foreach($setColors as $i => $j):
            echo "<span style='color: white; font-weight: bold; border-radius: 5px; padding: 5px; background-color: " . $j . ";'>" . ucfirst($i) . "</span> &nbsp;";
        endforeach;
        ?>
        </p>
        <p style='text-align: right; padding: 4px 20px;'>
            <?php if(!$full) { ?>
            <a href="/admin/schedule/mySchedule/all" class="btn btn-info"><i class="fa fa-calendar"></i> Show Full Calendar Instead</a>
            <?php } else { ?>
            <a href="/admin/schedule/mySchedule" class="btn btn-info"><i class="fa fa-calendar"></i> Show My Calendar Only</a>
            <?php } ?>
        </p>

       

<div id='calendar'>
</div>
        
        <h1>Time Off Requests</h1>

<table class="table table-striped table-bordered table-hover approval-dataTable" id="users-table">
	<thead>
		<tr>
			<th>Start Date</th>
                        <th>End Date</th>
                        <th>Type</th>
                        <th>Result</th>
                        <th>Notes</th>
                        <th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($schedule as $time) { $entry = $time['ScheduleEntry']; ?>
               <?php if($entry['approved'] === "0") { ?> <tr class='disabled'>
                        
                       
			<td><?php echo date("m/d/y", strtotime($entry['start_date'])); ?></td>
                        <td><?php echo date("m/d/y", strtotime($entry['end_date'])); ?></td>
                        <td><?php echo ucwords(str_replace("_", " ", $entry['type'])); ?> </td>
                        <td style='color: red;'>Denied</td>
                        <td><?php echo $entry['notes']; ?></td>
                        <td></td>
                       
		</tr>
               <?php }
               else if ($entry['approved'] === "1") {
                   if($entry['type'] !== 'scheduling') { ?>
                
                <tr class='approved'>
                        
                       
			<td><?php echo date("m/d/y", strtotime($entry['start_date'])); ?></td>
                        <td><?php echo date("m/d/y", strtotime($entry['end_date'])); ?></td>
                        <td><?php echo ucwords(str_replace("_", " ", $entry['type'])); ?> </td>
                        <td style='color: green;'>Approved</td>
                        <td><?php echo $entry['notes']; ?></td>
                        <td><a href='/admin/schedule/cancelTORequest/<?= $entry['id']; ?>' style='color: red;'><i class='fa fa-remove'></i> Cancel</a></td>
		</tr>
                   <?php }} else {
                       if($entry['type'] !== 'scheduling') { ?> 
                <tr class='approved'>
                        
                       
			<td><?php echo date("m/d/y", strtotime($entry['start_date'])); ?></td>
                        <td><?php echo date("m/d/y", strtotime($entry['end_date'])); ?></td>
                        <td><?php echo ucwords(str_replace("_", " ", $entry['type'])); ?> </td>
                        <td style='color: yellow;'>Pending</td>
                        <td><?php echo $entry['notes']; ?></td>
                        <td><a onclick='return confirm("Are you sure you want to remove this request? This action CANNOT be undone.");' href='/admin/schedule/cancelTORequest/<?= $entry['id']; ?>' style='color: red;'><i class='fa fa-remove'></i> Cancel</a></td>
		</tr>
                       <?php } } ?>
	<?php } ?>
	</tbody>
</table>
        

        <?php

        $this->append('jquery-scripts'); ?>
        
$('#calendar').fullCalendar({
    weekends: true,
    defaultView: 'month',
    editable: false,   
    events: [
    <?php foreach($schedule as $request): if($request['ScheduleEntry']['approved'] === "0") continue; ?>
    
    <?php $color = "";
    if($request['ScheduleEntry']['approved'] === "1")
    {
    if($request['ScheduleEntry']['type'] != "scheduling"){
        $color = $setColors['timeoff'];}
    else {
       
        if(isset($request['Job']['ServiceArea']) && isset($request['Job']['ServiceArea']['Parent']))
    $color = $setColors[strtolower($request['Job']['ServiceArea']['Parent']['name'])];
        else {
            $color = $setColors['other'];
        }
    }
    
    } 
    else if($request['ScheduleEntry']['approved'] === "0")
        {
        $color = $setColors['unapproved'];
    }
    else
        {
        $color = $setColors['pending'];
    }
    
    ?>
    <?php if($request['ScheduleEntry']['type'] != 'scheduling'): ?>
    { title: "<?= ucwords(str_replace("_", " ", $request['ScheduleEntry']['type'])); ?>",
    <?php else: ?>
    { title: "<?= $request['Job']['name']; ?>:\n<?= isset($request['Job']['ServiceArea']['name']) ? $request['Job']['ServiceArea']['name'] : "" ; ?>\n<?= ucwords(str_replace("_", " ", $request['ScheduleEntry']['position'])); ?>",
    url: "/admin/jobs/dashboard/<?= $request['Job']['id']; ?>",
    <?php endif; ?>
            start: "<?= $request['ScheduleEntry']['start_date']; ?>",
            end: "<?= date('Y-m-d', strtotime($request['ScheduleEntry']['end_date'] . " +1 day")); ?>",
            color: "<?= $color; ?>",
            id: "<?= $request['ScheduleEntry']['id']; ?>"
            },
            <?php endforeach; ?>
            
            <?php
            if($full) {
            foreach($fullSchedule as $request): if($request['ScheduleEntry']['approved'] === "0") continue; ?>
    
    <?php $color = "";
    if($request['ScheduleEntry']['approved'] === "1")
    {
    
            $color = $setColors['employees'];
      
    
    } 
    else if($request['ScheduleEntry']['approved'] === "0")
        {
        $color = $setColors['unapproved'];
    }
    else
        {
        $color = $setColors['pending'];
    }
    
    ?>

    { title: "<?= $request['User']['first_name'] . " " . $request['User']['last_name']; ?>:\n<?= $request['Job']['Customer']['bill_city'] . ", " . $request['Job']['Customer']['bill_state']; ?>",
            start: "<?= $request['ScheduleEntry']['start_date']; ?>",
            end: "<?= date('Y-m-d', strtotime($request['ScheduleEntry']['end_date'] . " +1 day")); ?>",
            color: "<?= $color; ?>",
            className: "employees"
            },
            <?php endforeach; } ?>
    ],
    
    header: {center: 'basicWeek,month,year'}
    
            });
            

<?php $this->end(); ?>
