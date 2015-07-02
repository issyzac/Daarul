<?php
$file = $_REQUEST['file'];
// Set headers
     header("Cache-Control: public");
     header("Content-Description: File Transfer");
     header("Content-Disposition: attachment; filename=$file");
     header("Content-Type: text/sql");
     header("Content-Transfer-Encoding: binary");
    
     // Read the file from disk
     readfile("backup/".$file);
	 
?>