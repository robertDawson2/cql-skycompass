<style>
    .entries td, .entries th
    {
        border: 1px solid black;
        padding: 4px 8px;
        
    }
    
    
</style>
<p>This message has been generated to notify you of a time or expense denied in SkyCompass. The following have been denied by an administrator:</p>
<table class='entries'>
    <tr>
        <th>Organization</th>
        <th>Date</th>
        <th>Denial Message</th>
    </tr>
    <?php foreach($full as $entry) { ?>
    <tr>
        <td><?= $entry['customer']; ?></td>
        <td><?= $entry['date']; ?></td>
        <td><?= $entry['notice']; ?></td>
        
            
    </tr>
    <?php } ?>
    
</table>
<p>Best Regards,
<br /><br />
<?= $config['site.name']; ?> SkyCompass Software Team</p>