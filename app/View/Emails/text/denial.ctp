This message has been generated to notify you of a time or expense denied in SkyCompass. 
The following have been denied by an administrator:

<?php foreach($full as $entry) { ?>

    CUSTOMER: <?= $entry['customer']; ?>
    DATE: <?= $entry['date']; ?>
    MESSAGE: <?= $entry['notice']; ?>
    -------------------------------------
    <?php } ?>
    

Best Regards,

<?= $config['site.name']; ?> SkyCompass Software Team