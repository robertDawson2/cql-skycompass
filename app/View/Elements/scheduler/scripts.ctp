<?php $this->append('scripts'); ?>
<script>
    var clickedEvent = null;
    var currentId = null;
    var calendarEvent = null;
    $("#submitInfo").click(function(e) {
        var a = JSON.stringify(scheduledEvents);
        e.preventDefault();
        $.ajax({dataType: "json",
            type: "POST",
        url: "/admin/jobs/checkSchedule",
        data: a,
        contentType: "application/json; charset=UTF-8",
        complete : function(data) {
          // console.log(data.responseText);
            $("#submitData").val(a);
            $("#submitForm").submit();
        },
        failure: function(errMsg) {
            alert(errMsg);
        }
    });
        
    });

var setColors = {
        training: 'black',
        certification: 'darkgreen',
        accreditation: 'saddlebrown',
        other: 'darkslategray'
    };
    var pendingColors = {
        training: 'blue',
        certification: 'blueviolet',
        accreditation: 'brown',
        other: 'cadetblue'
    };
    
    function updateColor() {
        // update color of the background based on employees chosen
        $curr = scheduledEvents[currentId].employees;
        $event = $("#calendar").fullCalendar('clientEvents', currentId)[0];
        $key = null;
        for (var key in setColors) {
            if(setColors.hasOwnProperty(key)) {
                if($event.color == setColors[key])
                    $key = key;
            }
        }
        for (var key in pendingColors) {
            if(pendingColors.hasOwnProperty(key)) {
                if($event.color == pendingColors[key])
                    $key = key;
            }
        }
       
        if($curr.employeeCount == $curr.needed)
        {
            $event.color = setColors[$key];
        $(clickedEvent).css({"background-color" : setColors[$key], "border-color" : setColors[$key]});
        }
        else
        {
            $event.color = pendingColors[$key];
        $(clickedEvent).css({"background-color" : pendingColors[$key], "border-color" : pendingColors[$key]});
        }
    }
    
    function updateQuickView($eventId) {
        // update the quick view with employee names
        $newText = "<h5>Team Leaders</h5>";
        $add = "<p>";
        $curr = scheduledEvents[$eventId].employees;
        for (var key in $curr.teamLeaders)
        {
            if($curr.teamLeaders.hasOwnProperty(key)) {
                $add += $curr.teamLeaders[key].name + "<br />";
            }
        }
        if($add == "<p>")
            $add += "<em>{none}</em>";
        $newText += $add + "</p><h5>Employees</h5>";
        $add = "<p>";
        
        for (var key in $curr.other)
        {
            if($curr.other.hasOwnProperty(key)) {
                $add += $curr.other[key].name + "<br />";
            }
        }
        
        if($add == "<p>")
            $add += "<em>{none}</em>";
        $newText += $add + "</p>";
        return ($newText);
    }
    
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
        
        
        
    }
    var scheduledEvents = {
        <?php foreach($jobs['set'] as $job): ?>
                <?php // break up job employees into team leaders and employees 
                $employees = array('team_leaders'=>array(), 'employees' => array());
                $count = 0;
                foreach($job['ScheduleEntry'] as $entry) {
                    if($entry['position'] == 'team_leader')
                        $employees['team_leaders'][$entry['user_id']] = $entry['User']['first_name'] . " " . $entry['User']['last_name'];
                    else
                        $employees['employees'][$entry['user_id']] = $entry['User']['first_name'] . " " . $entry['User']['last_name'];
                    
                    $count++;
                } ?>
                "<?= $job['Job']['id']; ?>" : {
                    start: "<?= date("Y-m-d", strtotime($job['Job']['start_date'])); ?>",
                    end: "<?= date("Y-m-d", strtotime($job['Job']['end_date'] . " +1 day")); ?>",
                    
                    employees: { "teamLeaders" : {
                        <?php foreach($employees['team_leaders'] as $i => $d): ?> 
                                "<?= $i; ?>": {
                                    name: "<?= $d; ?>" 
                                },
                                <?php endforeach; ?>
                            },
                                    "other" : {
                                         <?php foreach($employees['employees'] as $i => $d): ?> 
                                "<?= $i; ?>": {
                                    name: "<?= $d; ?>" 
                                },
                                <?php endforeach; ?>
                    },
                            needed: <?= ($job['Job']['team_leader_count'] + $job['Job']['employee_count']); ?>,
                    employeeCount: <?= $count; ?>
            }
                    
    },
                
                <?php endforeach; ?>
    };
 
    </script>
<?php $this->end(); ?>
<?php $this->append('jquery-scripts'); ?>


    
    var isEventOverDiv = function(x, y) {

            var external_events = $( '#external-events' );
            var offset = external_events.offset();
            offset.right = external_events.width() + offset.left;
            offset.bottom = external_events.height() + offset.top;

            
            // Compare
            if (x >= offset.left
                && y >= offset.top
                && x <= offset.right
                && y <= offset .bottom) { return true; }
            return false;

        }
    
    
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
    forceEventDuration: true,
    editable: true,
    droppable: true,
    events: [
    <?php foreach($jobs['set'] as $job): ?>
    <?php if(isset($job['ServiceArea']['Parent']))
                $color = strtolower($job['ServiceArea']['Parent']['name']);
            else
                $color = 'other'; ?>
    { title: "<?= $job['Job']['company_name']; ?>:\n<?= $job['Job']['name']; ?>\n<?php if(!empty($job['Job']['city'])): ?><?= $job['Job']['city'] . ", " . $job['Job']['state']; ?><?php else: ?><?= $job['Customer']['bill_city'] . ", " . $job['Customer']['bill_state']; ?><?php endif; ?>\n<?= $job['Job']['team_leader_count']; ?>X<?= $job['Job']['employee_count']; ?>",
            start: "<?= date("Y-m-d", strtotime($job['Job']['start_date'])); ?>",
            end: "<?= date("Y-m-d", strtotime($job['Job']['end_date'] . " +1 day")); ?>",
            color: "<?php
            if(($job['Job']['team_leader_count'] + $job['Job']['employee_count']) > count($job['ScheduleEntry']))
                echo $pendingColors[$color];
            else
                echo $setColors[$color];
            ?>",
            id: "<?= $job['Job']['id']; ?>"
            },
            <?php endforeach; ?>
    ],
    eventResize: function(event, delta, revertFunc) {
    id = event.id;
        
       
        if (scheduledEvents[id].employees.employeeCount > 0){
            if(!confirm("You have scheduled employees to this event. Moving it will unschedule all employees. Do you wish to continue?")) {
                revertFunc();
                }
            else
                {
                    var neededPrev = scheduledEvents[id].employees.needed;
                   scheduledEvents[id].end = event.end.format();
                   scheduledEvents[id].employees = {teamLeaders : {}, other: {}, employeeCount : 0, needed : neededPrev};
                   
                }
            }
        else
            {
                scheduledEvents[id].end = event.end.format();
            }
       

    },
    drop: function(date) {
        id = $(this).data('id');
        scheduledEvents[id] = 
         {start : date.format(),
          end : date.format(),
          employees : {teamLeaders : {}, other: {},
          employeeCount : 0,
                       needed : $(this).data('emp_count')},
          
        };
       
       
        $(this).remove();
    },
    eventDragStop: function( event, jsEvent, ui, view ) {
                
                if(isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                
                    $('#calendar').fullCalendar('removeEvents', event.id);
                    var el = $( "<div class='fc-event'>" ).appendTo( '#external-events' ).text( $.trim(event.title) );
                    el.draggable({
                      zIndex: 999,
                      revert: true, 
                      revertDuration: 0 
                    });
                    el.css({"background-color": event.color, "border-color": event.color});
                    el.data('id', event.id);
                    el.data('color',event.color);
                    el.data('emp_count', scheduledEvents[event.id].employees.needed);
                    el.data('event', { title: event.title, color: event.color, id :event.id, stick: true });
                    scheduledEvents[event.id] = null;
         
                }
            },
            nextDayThreshold: "00:00:00",
    header: {center: 'basicWeek,month,threeMonth'},
    views: {
        threeMonth: {
            type: 'basic',
            duration: { months: 3 },
            buttonText: '3 Months'
        }
    },
    eventDrop: function(event, delta, revertFunc) {
        id = event.id;
       
        if (scheduledEvents[id].employees.employeeCount > 0){
            if(!confirm("You have scheduled employees to this event. Moving it will unschedule all employees. Do you wish to continue?")) {
                revertFunc();
                
                }
            else
                {
                neededPrev = scheduledEvents[id].employees.needed;
                scheduledEvents[id].start = event.start.format();
                if(event.end != null)
                   scheduledEvents[id].end = event.end.format();
                else
                    scheduledEvents[id].end = event.start.format();
                    
                   scheduledEvents[id].employees = {teamLeaders : {}, other: {}, needed : neededPrev, employeeCount : 0};

                }
            }
            else
            {
                scheduledEvents[id].start = event.start.format();
                if(event.end == null)
                    scheduledEvents[id].end = event.start.format();
                else
                    scheduledEvents[id].end = event.end.format();
            }
       
    },
    eventMouseover: function(calEvent, jsEvent, view) {
    $newText = updateQuickView(calEvent.id);
    $html = '<div class="popup-text" style="z-index: 999999; background-color: white; color: #333; width: 250px; position: absolute;">' +
       $newText + '</div>';
       $(this).append($html);
    },
    eventMouseout: function(calEvent, jsEvent, view) {
        $(this).children(".popup-text").remove();
    },
    eventClick: function(calEvent, jsEvent, view) {
        calendarEvent = calEvent;
        clickedEvent = this;
        $.ajax({
            url: "/ajax/jobs/schedulerArray/" + calEvent.id ,
            dataType: "json"
            
        }).done(function(data) {
            currentJob = calEvent.id;
            currentId = currentJob;
            $("#jobName").text(data.job_name);
            $("#jobName").data('id', calEvent.id);
            $("#custName").text(data.cust_name);
            $("#custLoc").text(data.location);
            
            $("#teamLeadersCount").text(data.team_leaders_count);
            $("#employeesCount").text(data.employees_count);
            $(".empList div").remove();
            $("#teamLeadersNeeded").text(data.current_team_leaders.length);
            $("#employeesNeeded").text(data.current_employees.length);
            var url = "/ajax/jobs/scheduleEmployees/"+calEvent.id + "/start=" + calEvent.start + "&end=" + calEvent.end;
           console.log(url);
            emptable.ajax.url(url).load(function() {
            
            // Load already loaded employees
            var count = 0;
            for(var key in scheduledEvents[currentJob].employees.teamLeaders)
            {
                if (!scheduledEvents[currentJob].employees.teamLeaders.hasOwnProperty(key)) continue;

                var obj = scheduledEvents[currentJob].employees.teamLeaders[key];
                var newHTML = "<div data-id='" + key + "'>" + obj.name + '<span onclick="removeTL(this);" class="close"><i class="fa fa-times-circle"></i></span></div>';
                $("#teamLeaders").append(newHTML);
                
                // hide name in list
                $(".addLeader").each(function() {
                        if($(this).data('id') === key)
                        {
              $(this).parent().parent().hide();
              }
                });
                count++;
                
            }
            $("#teamLeadersNeeded").text(count);
            count = 0;
            for(var key in scheduledEvents[currentJob].employees.other)
            {
                if (!scheduledEvents[currentJob].employees.other.hasOwnProperty(key)) continue;

                var obj = scheduledEvents[currentJob].employees.other[key];
                var newHTML = "<div data-id='" + key + "'>" + obj.name + '<span onclick="removeE(this);" class="close"><i class="fa fa-times-circle"></i></span></div>';
                $("#employees").append(newHTML);
                
                // hide name in list
                $(".addEmployee").each(function() {
                        if($(this).data('id') === key)
                        {
              $(this).parent().parent().hide();
              }
                });
                 count++;
            }
            $("#employeesNeeded").text(count);
            checkTLEcount();
            });
            
            
            
            
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