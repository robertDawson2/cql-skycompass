<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html lang="en">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>
      
    </title>

  </head>
  <body style="margin: 0; padding: 0; background: #fff;" bgcolor="#eee">


<p>
    <strong><?php echo $data['company']; ?>,</strong>
</p>
<p>
    This email will act as your receipt of a payment of <strong>$<?= $data['amount']; ?></strong> 
    <?php if(!empty($data['invoice_num'])) { ?> for invoice number <em><?php echo $data['invoice_num']; ?></em><?php } ?>.
    This payment was received on <em><?= date('m/d/Y'); ?></em>. Your transaction reference ID is <em>
    <?= $data['response']->transaction_id; ?></em>, and your authorization code is <em><?= $data['response']->authorization_code; ?></em>.
     
     
</p>
<p>
    Please keep this email receipt for your records.
</p>
<p>
    We appreciate your business!
</p>
<p>
    Best Regards,
    <br /><br />
    <em>Your CQL Team</em><br />
    <a href="https://c-q-l.org">https://c-q-l.org</a><br />
    
</p>

  </body>
 </html>