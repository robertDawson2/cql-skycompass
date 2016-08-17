<p>Hi <?= $user['User']['first_name']; ?>!</p>
<p>Your password reset for <?= $config['site.name']; ?> SkyCompass software has been received.
    
    Please visit the URL below to complete your password reset.</p>
<p><a href="<?= $config['site.url']; ?>/users/firstLogin/<?= $user['User']['reset_hash']; ?>/<?= $user['User']['id']; ?>"><?= $config['site.url']; ?>/users/firstLogin/<?= $user['User']['reset_hash']; ?>/<?= $user['User']['id']; ?></a></p>

<p>Best Regards,
<br /><br />
<?= $config['site.name']; ?> SkyCompass Software Team</p>