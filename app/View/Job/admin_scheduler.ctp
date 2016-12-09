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
        z-index: 99999;
    }
</style>
<?php 
$setColors = [
        "training" => 'pink',
        "certification"=> 'lightblue',
        "accreditation"=> 'lightgreen',
        "other" => 'lightgray'
    ];
$pendingColors = [
        "training"=> 'purple',
        "certification" => 'darkblue',
        "accreditation" => 'darkred',
        "other" => 'black'
    ];
?>
<div class='row'>
    <div class='col-md-12'>
        Key:<p><strong>Need Employees: </strong>
        <?php foreach($pendingColors as $i => $j):
            echo "<span style='color: white; font-weight: bold; border-radius: 5px; padding: 5px; background-color: " . $j . ";'>" . ucfirst($i) . "</span> &nbsp;";
        endforeach;
        ?>
        </p>
        <p><strong>Fully Populated: </strong>
        <?php foreach($setColors as $i => $j):
            echo "<span style='color: white; font-weight: bold; border-radius: 5px; padding: 5px; background-color: " . $j . ";'>" . ucfirst($i) . "</span> &nbsp;";
        endforeach;
        ?>
        </p>
    </div>
</div>
<div class='row'>
    <div class='col-md-3'>
        <h3>Unscheduled Jobs</h3>
        <div id='external-events'>
<?php 

    
foreach($jobs['open'] as $job): ?>
            <?php if(isset($job['ServiceArea']['Parent']))
                $color = strtolower($job['ServiceArea']['Parent']['name']);
            else
                $color = 'other'; ?>
            <div class='fc-event' 
                 style='background-color: <?=  $pendingColors[$color]; ?>; border-color: <?= $pendingColors[$color]; ?>;' 
                 data-color='<?= $color; ?>' data-id='<?= $job['Job']['id']; ?>'>
                <strong><?= $job['Job']['company_name']; ?></strong>:<br />
                 <?= $job['Job']['name']; ?>  <br />
            <em><?= $job['Customer']['bill_city'] . ", " . $job['Customer']['bill_state']; ?></em><br />
            <small><?= $job['Job']['team_leader_count']; ?>X<?= $job['Job']['employee_count']; ?></small>
            
            </div>
			<?php endforeach; ?>
			<p>

			</p>
		</div>
    </div>
    <div class='col-md-9'>
<div id='calendar'>
</div>
    
</div>
   
</div>
<?= $this->element('modals/availableEmployees'); ?>
<?php $this->append('scripts'); ?>
<script>
var setColors = {
        training: 'pink',
        certification: 'lightblue',
        accreditation: 'lightgreen',
        other: 'lightgray'
    };
    var pendingColors = {
        training: 'purple',
        certification: 'darkblue',
        accreditation: 'darkred',
        other: 'black'
    };
    
    function removeTL(span)
    {
        $("#teamLeadersNeeded").text(parseInt($("#teamLeadersNeeded").text()) - 1);
        $removalId = $(span).parent().data('id');
        $(".addLeader").each(function() {
            if($(this).data('id') === $removalId)
              $(this).parent().parent().fadeIn();
        });
        $(span).parent().remove();
        
        checkTLEcount();
    }
    function removeE(span)
    {
        $("#employeesNeeded").text(parseInt($("#employeesNeeded").text()) - 1);
        $removalId = $(span).parent().data('id');
        $(".addEmployee").each(function() {
            if($(this).data('id') === $removalId)
              $(this).parent().parent().fadeIn();
        });
        $(span).parent().remove();
        
        checkTLEcount();
    }
    function teamLeaderAdd(active)
    {
        var userId = $(active).data('id');
        var row = $(active).parent().parent();

        $html = "<div data-id='" + userId + "'>" + row.children("td").eq(0).text() + " " + row.children("td").eq(1).text() + "<span onclick='removeTL(this);' class='close'><i class='fa fa-times-circle'></i></span></div>";
        $("#teamLeaders").append($html);
       row.fadeOut();
        $("#teamLeadersNeeded").text(parseInt($("#teamLeadersNeeded").text()) + 1);
        
        checkTLEcount();
        return false;
    }
    function employeeAdd(active)
    {
        var userId = $(active).data('id');
        var row = $(active).parent().parent();
        console.log(row.children("td").eq(0).text()); 
        $html = "<div data-id='" + userId + "'>" + row.children("td").eq(0).text() + " " + row.children("td").eq(1).text() + "<span onclick='removeE(this);' class='close'><i class='fa fa-times-circle'></i></span></div>";
        $("#employees").append($html);
        row.fadeOut();
        $("#employeesNeeded").text(parseInt($("#employeesNeeded").text()) + 1);
        
        checkTLEcount();
        return false;
    }
    function ajaxEmployeeAdd(jobId, employeeId, tl = false)
    {
        
    }
    function checkTLEcount()
    {
        $tl1 = parseInt($("#teamLeadersNeeded").text());
        $tl2 = parseInt($("#teamLeadersCount").text());
        $e1 = parseInt($("#employeesNeeded").text());
        $e2 = parseInt($("#employeesCount").text());
        
        if($tl2 > $tl1)
            $(".addLeader").show();
        else
            $(".addLeader").hide();
        
        
        if($e2 > $e1)
            $(".addEmployee").show();
        else
            $(".addEmployee").hide();
        
        
        
    }</script>
<?php $this->end(); ?>
<?php $this->append('jquery-scripts'); ?>

    
    $('#external-events .fc-event').each(function() {

			// store data so the calendar knows to render an event upon drop
			$(this).data('event', {
				title: $.trim($(this).text()), // use the element's text as the event title
                                color: pendingColors[$(this).data('color')],
                                id: $(this).data('id'),
				stick: true // maintain when user navigates (see docs on the renderEvent method)
			});

			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});

		});
                
    $('#calendar').fullCalendar({
    weekends: false,
    defaultView: 'basicWeek',
    editable: true,
    droppable: true,
    drop: function() {
        $(this).remove();
    },
    header: {center: 'basicWeek,month'},
    eventMouseover: function(calEvent, jsEvent, view) {
    $html = '<div class="popup-text" style="background-color: white; color: #333; width: 200px; height: 100px; position: absolute;">' +
       ' This is where details like assigned employees will show;</div>';
       $(this).append($html);
    },
    eventMouseout: function(calEvent, jsEvent, view) {
        $(this).children(".popup-text").remove();
    },
    eventClick: function(calEvent, jsEvent, view) {
        $.ajax({
            url: "/ajax/jobs/schedulerArray/" + calEvent.id,
            dataType: "json"
            
        }).done(function(data) {
            currentJob = calEvent.id;
            $("#jobName").text(data.job_name);
            $("#custName").text(data.cust_name);
            $("#custLoc").text(data.location);
            $("#teamLeadersCount").text(data.team_leaders_count);
            $("#employeesCount").text(data.employees_count);
            $(".empList div").remove();
            $("#teamLeadersNeeded").text(data.current_team_leaders.length);
            $("#employeesNeeded").text(data.current_employees.length);
            emptable.ajax.url("/ajax/jobs/scheduleEmployees/"+calEvent.id).load();
            
        $("#availableEmployeesModal").modal(
                {
                    width: "95%",
                    height: "95%",
                    show: true
                });
                
                });


    }
});


<?php $this->end(); ?>