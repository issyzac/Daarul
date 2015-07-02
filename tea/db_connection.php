<?php
$dbConnection = mysql_connect('localhost', 'root', 'root') or die(mysql_error());
$dbSelection = mysql_select_db('pmngdb', $dbConnection) or die(mysql_error());
?>