<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbnm = "pdg";

mysql_connect($host, $user, $pass) or die(mysql_error());
mysql_select_db($dbnm) or die(mysql_error());
?>
