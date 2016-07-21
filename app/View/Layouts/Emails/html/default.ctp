<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html lang="en">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>
      <?php echo $title; ?>
    </title>
	<style type="text/css">
	a:hover { text-decoration: none !important; }
	.header h1 {color: #0eb6ce !important; font: bold 32px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 40px;}
	.header p {color: #c6c6c6; font: normal 12px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 18px;}

	.content h2 {color:#646464 !important; font-weight: bold; margin: 0; padding: 0; line-height: 26px; font-size: 18px; font-family: Helvetica, Arial, sans-serif;  }
	.content p {color:#444444; font-weight: normal; margin: 0; padding: 0; line-height: 20px; font-size: 12px;font-family: Helvetica, Arial, sans-serif;}
	table.data {margin: 0 20px 18px; }
	table.data th { font-weight: normal; margin: 0; padding: 4px 6px; border-bottom: 1px solid #ddd; line-height: 20px; font-size: 12px;font-family: Helvetica, Arial, sans-serif;}
	table.data td {background-color: #fff; color:#444444; font-weight: normal; margin: 0; padding: 4px 6px; border-bottom: 1px solid #ddd; line-height: 20px; font-size: 12px;font-family: Helvetica, Arial, sans-serif;}
	.content a {color: #0eb6ce; text-decoration: none;}
	.footer p {font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;}
	.footer a {color: #0eb6ce; text-decoration: none;}
	.content p {margin: 0 20px 18px;}
	</style>
  </head>
  <body style="margin: 0; padding: 0; background: #fff;" bgcolor="#eee">
  		<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding: 35px 0; background: #eee;">
		  <tr>
		  	<td align="center" style="margin: 0; padding: 0;">
			    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; background:#2a2a2a;" class="header">
					<tr>
					<td width="20" style="font-size: 0px;">&nbsp;</td>
			        <td width="600" align="left" style="padding: 18px 0;">
						<h1 style="color: #c7af6c !important; font: bold 32px Helvetica, Arial, sans-serif; margin: 0; padding: 0; line-height: 40px;"><singleline label="Title">CQL</singleline></h1>
						<p style="font-size: 14px; color: #ddd;"><multiline label="Description"><?php echo $description; ?></multiline></p>
			        </td>
			      </tr>
				</table><!-- header-->
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; background: #fff;" bgcolor="#fff">
			      	
					<tr>
			      <td width="600" valign="top" align="left" style="font-family: Helvetica, Arial, sans-serif; padding: 20px 0 0;" class="content">
					<?php echo $content_for_layout; ?>

					</td>
					
			      </tr>
				</table><!-- body -->
				<br /><table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; line-height: 10px;" class="footer">
				<tr>
			        <td align="center" style="padding: 5px 0 10px; font-size: 11px; color:#7d7a7a; margin: 0; line-height: 1.2;font-family: Helvetica, Arial, sans-serif;" valign="top">
						<p style="font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">Visit us online: <a href="http://www.net2sky.com">Net2Sky.com</a>.</p>
					</td>
			      </tr>
				</table><!-- footer-->
		  	</td>
		  	
		</tr>
    </table>
  </body>
</html>