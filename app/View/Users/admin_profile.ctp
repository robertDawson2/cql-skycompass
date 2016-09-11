<h2>User Profile</h2>
<hr>
<div class="row">
    <div class="col-md-6">
<h3>Basic Information</h3>
<p>
<strong><?= $user['User']['first_name']; ?> <?= $user['User']['middle_name']; ?> <?= $user['User']['last_name']; ?></strong>
<br />
<?= $user['User']['addr1']; ?>
<br />
<?php if(!empty($user['User']['addr2']))
        echo $user['User']['addr2'] . "<br />";
?>
<?= $user['User']['city'] . ", " . $user['User']['state'] . " " . $user['User']['zip']; ?>
</p>
<br>
<p>
    <strong>Phone:</strong> <?= $user['User']['phone']; ?><br />
    <strong>Mobile:</strong> <?= $user['User']['mobile']; ?><br />
    <strong>Email:</strong> <?= $user['User']['email']; ?><br />
    
</p>

    </div>
    <div class="col-md-6">
        <?php if(isset($user['EmployeeEarning']) && !empty($user['EmployeeEarning'])): ?>
        <h3>Payroll Information</h3>
        <table class="table table-bordered table-responsive table-striped">
            <thead>
                <tr>
                    <th>
                       Payroll Item 
                    </th>
                    <th>
                        Pay Rate
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($user['EmployeeEarning'] as $earning): ?>
                <tr>
                    <td><?= $earning['payroll_item_name']; ?></td>
                    <td>$ <?= $earning['rate']; ?></td>
                </tr>
                
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <h3>
            Sick Time
            
        </h3>
        <p>
            <strong>Hours Accrued: </strong><?php echo  showAsTime($user['User']['sick_hours_accrued']); ?><br />
            <strong>Hours Available: </strong><?php echo showAsTime($user['User']['sick_hours_available']); ?><br />
            <strong>Hours Used: </strong><?php echo showAsTime($user['User']['sick_hours_used']); ?><br />
        </p>
        
        <h3>
            Vacation Time
            
        </h3>
        <p>
            <strong>Hours Accrued: </strong><?php echo showAsTime($user['User']['vacation_hours_accrued']); ?><br />
            <strong>Hours Available: </strong><?php echo showAsTime($user['User']['vacation_hours_available']); ?><br />
            <strong>Hours Used: </strong><?php echo showAsTime($user['User']['vacation_hours_used']); ?><br />
        </p>
    </div>
</div>

<?php function showAsTime($time)
{
    if(!isset($time) || empty($time))
        return "000:00";
   
    $newTime = str_replace(array('PT','M0S'),"",$time);
    $newTime = str_replace('H',"|",$newTime);
    
     
    $timeArray = explode('|',$newTime);
   
    $newTime = sprintf("%03d", $timeArray[0]) . ":" . sprintf("%02d", $timeArray[1]);
    return $newTime;
}
?>