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
        <div style="min-height: 300px;" id='external-events'>
<?php 

    
foreach($jobs['open'] as $job): ?>
            <?php if(isset($job['ServiceArea']['Parent']))
                $color = strtolower($job['ServiceArea']['Parent']['name']);
            else
                $color = 'other'; ?>
            <div class='fc-event' 
                 style='background-color: <?=  $pendingColors[$color]; ?>; border-color: <?= $pendingColors[$color]; ?>;' 
                 data-color='<?= $color; ?>' data-id='<?= $job['Job']['id']; ?>' data-emp_count = '<?= 
                 ($job['Job']['team_leader_count'] + $job['Job']['employee_count']); ?>'>
                <strong><?= $job['Job']['company_name']; ?></strong>:<br />
                 <?= $job['Job']['name']; ?>  <br />
            <em><?= $job['Customer']['bill_city'] . ", " . $job['Customer']['bill_state']; ?></em><br />
            <small><?= $job['Job']['team_leader_count']; ?>X<?= $job['Job']['employee_count']; ?></small>
            
            </div>
			<?php endforeach; ?>
			<p>

			</p>
		</div>
        <div class='spacer30'>
        </div>
        <div>
            <a href='#' id='submitInfo' class='btn btn-default btn-lg btn-block'>Submit Changes</a>
        </div>
    </div>
    <div class='col-md-9'>
<div id='calendar'>
</div>
    
</div>
   
</div>
<div style='display: none;'>
    <form id='submitForm' method='post' action='/admin/jobs/schedule'>
        <input type='hidden' id='submitData' name='data' value='' />
    </form>
</div>
<?= $this->element('modals/availableEmployees'); ?>
<?= $this->element('scheduler/scripts'); ?>