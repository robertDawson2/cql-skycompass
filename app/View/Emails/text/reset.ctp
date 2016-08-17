Your password reset for <?= $config['site.name']; ?> SkyCompass software has been received.
Please visit the URL below to complete your password reset.

<?= $config['site.url']; ?>/users/firstLogin/<?= $user['User']['reset_hash']; ?>/<?= $user['User']['id']; ?>

Best Regards,

<?= $config['site.name']; ?> SkyCompass Software Team