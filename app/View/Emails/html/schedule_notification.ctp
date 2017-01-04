<style>
    .entries {
        font-size: 90%;
    }
    .entries tr, .entries th
    {
        border-bottom: 1px solid #888;
        padding: 4px 8px;
        
    }
    .entries td {
        padding: 4px 8px;
    }
    .entries tr:odd {
        background-color: #cecece;
    }
    .btn-approve {
        padding: 5px 10px;
        background-color: #04519b;
        border: 1px solid #1562ac;
        border-radius: 8px;
        box-shadow: 2px 2px 8px #888;
        color: #dfdfdf;
        white-space: nowrap;
    }
    
</style>
<p>Hello <strong><?= $user['User']['first_name']; ?></strong>!:</p>
<p>The following are the most recent schedule changes made in the <?= $config['site.name']; ?> SkyCompass scheduling system. 
    Please review all listed changes, and either approve directly through this email, 
    or <a href="<?= $config['site.url']; ?>/admin">login</a> to approve or deny entries.</p>
<h4>Schedule</h4>
<table class='entries'>
    <tr>
        <th>Customer</th>
        <th>Job</th>
        <th>Dates</th>
        <th>Service Area</th>
        <th>Position</th>
       
        <th></th>
    </tr>
    <?php foreach($user['ScheduleEntry'] as $entry) { ?>
    <?php if(!$entry['email_alert_delivered'] && $entry['type'] === 'scheduling' && $entry['approved']===null)
    { ?>
    <tr>
        <td><?= $entry['Job']['company_name']; ?></td>
        <td><?= $entry['Job']['name']; ?></td>
        <td><?= date("m/d/y", strtotime($entry['start_date'])); ?> - <?= date("m/d/y", strtotime($entry['end_date'])); ?></td>
        <td><?= $serviceAreas[$entry['Job']['service_area_id']]; ?></td>
        <td><?= ucwords(str_replace("_", " ", $entry['position'])); ?></td>
       
        <td><a href="<?= $config['site.url']; ?>/schedule/autoApprove/<?= $user['User']['id']; ?>/<?= 
        $entry['id']; ?>" 
        class="btn-approve">Auto-Approve</a></td>
            
    </tr>
    <?php } } ?>
    
</table>
<p>Please approve or deny all entries quickly and efficiently in order to expedite the scheduling process.</p>
<p>Best Regards,
<br /><br />
<?= $config['site.name']; ?> SkyCompass Software Team</p>