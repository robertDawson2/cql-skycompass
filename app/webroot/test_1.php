<?php
$conn = mysql_connect("localhost", "root", "password");
mysql_select_db("cql") or die(mysql_error());

$sql = "SELECT mime,file FROM bill_item_images WHERE id=4";
$result = mysql_query("$sql") or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysql_error());
$row = mysql_fetch_array($result);
header("Content-Type: " . $row["mime"]);
echo ($row["file"]);

mysql_close($conn);
