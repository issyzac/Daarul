<?php
/**
 * This file contains the Backup_Database class wich performs
 * a partial or complete backup of any given MySQL database
 * @author Daniel López Azaña <http://www.daniloaz.com>
 * @version 1.0
 */
 
// Report all errors
//error_reporting(E_ALL);
/**
 * Define database parameters here
 */
if(isset($_POST['backup']))
{
	define("DB_USER", 'root');
	define("DB_PASSWORD", '');
	define("DB_NAME", 'pmngdb');
	define("DB_HOST", 'localhost');
	define("OUTPUT_DIR", 'C:/wamp/www/tea/configuration/backup');
	define("TABLES", '*');
	$backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$status = $backupDatabase->backupTables(TABLES, OUTPUT_DIR) ? 'OK' : 'KO';
}else if(isset($_POST['restore']))
{
	if($_FILES['file']['name'])
	{
	  if(!$_FILES['file']['error'])
	  {
		set_time_limit(5 * 60);
		  $handle = fopen($_FILES['file']['tmp_name'],'r');
		  $queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/",fread($handle,filesize($_FILES['file']['tmp_name']))); 
			foreach ($queries as $query){ 
				if (strlen(trim($query)) > 0) mysql_query($query);
			}
		  fclose($handle);
		echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />
Backup restored successfully</center>';
	  }
	  else
	  {
		echo "error applying the backup";
	  }
	}
}
/**
 * The Backup_Database class
 */
class Backup_Database {
    /**
     * Host where database is located
     */
    var $host = '';
 
    /**
     * Username used to connect to database
     */
    var $username = '';
 
    /**
     * Password used to connect to database
     */
    var $passwd = '';
 
    /**
     * Database to backup
     */
    var $dbName = '';
 
    /**
     * Database charset
     */
    var $charset = '';
 
    /**
     * Constructor initializes database
     */
    function Backup_Database($host, $username, $passwd, $dbName, $charset = 'utf8')
    {
        $this->host     = $host;
        $this->username = $username;
        $this->passwd   = $passwd;
        $this->dbName   = $dbName;
        $this->charset  = $charset;
 
        $this->initializeDatabase();
    }
 
    protected function initializeDatabase()
    {
        $conn = mysql_connect($this->host, $this->username, $this->passwd);
        mysql_select_db($this->dbName, $conn);
        if (! mysql_set_charset ($this->charset, $conn))
        {
            mysql_query('SET NAMES '.$this->charset);
        }
    }
 
    /**
     * Backup the whole database or just some tables
     * Use '*' for whole database or 'table1 table2 table3...'
     * @param string $tables
     */
    public function backupTables($tables = '*', $outputDir = '.')
    {
        try
        {
            /**
            * Tables to export
            */
            if($tables == '*')
            {
                $tables = array();
                $result = mysql_query('SHOW TABLES');
                while($row = mysql_fetch_row($result))
                {
                    $tables[] = $row[0];
                }
            }
            else
            {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }
 
            $sql = 'CREATE DATABASE IF NOT EXISTS '.$this->dbName.";\n\n";
            $sql .= 'USE '.$this->dbName.";\n\n";
 
            /**
            * Iterate tables
            */
            foreach($tables as $table)
            {
 
                $result = mysql_query('SELECT * FROM '.$table);
                $numFields = mysql_num_fields($result);
 
                $sql .= 'DROP TABLE IF EXISTS '.$table.';';
                $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
                $sql.= "\n\n".$row2[1].";\n\n";
 
                for ($i = 0; $i < $numFields; $i++)
                {
                    while($row = mysql_fetch_row($result))
                    {
                        $sql .= 'INSERT INTO '.$table.' VALUES(';
                        for($j=0; $j<$numFields; $j++)
                        {
                            $row[$j] = addslashes($row[$j]);
                            $row[$j] = str_replace("\n","\\n",$row[$j]);
                            if (isset($row[$j]))
                            {
                                $sql .= '"'.$row[$j].'"' ;
                            }
                            else
                            {
                                $sql.= '""';
                            }
 
                            if ($j < ($numFields-1))
                            {
                                $sql .= ',';
                            }
                        }
 
                        $sql.= ");\n";
                    }
                }
 
                $sql.="\n\n\n";
            }
        }
        catch (Exception $e)
        {
            var_dump($e->getMessage());
            return false;
        }
 
        return $this->saveFile($sql, $outputDir);
    }
 
    /**
     * Save SQL to file
     * @param string $sql
     */
	
	 
    protected function saveFile(&$sql, $outputDir = '.')
    {
        if (!$sql) return false;
 
        try
        {
			$filename = 'db-backup-'.$this->dbName.'-'.date("Ymd-His", time()).'.sql';
            $handle = fopen($outputDir."/".$filename,'w+');
            fwrite($handle, $sql);
            fclose($handle);
			echo '<center><img src="images/ok.png" alt="Ok..." style="width:100px" /> <br />
Backup created successfully<br />';
			echo "<a href='configuration/back_download.php?file={$filename}' target='blank'>Download File</a></center>";
        }
        catch (Exception $e)
        {
            var_dump($e->getMessage());
            return false;
        }
 
        return true;
    }
}
?>
<h3>Backup</h3>
<form method="post">
<input type="submit" name="backup" value="Click To Backup"/>
</form>
<h3>Restore</h3>
<script>
function load()
{
	document.getElementById("load").style.visibility = "visible";
	document.getElementById("frm").style.visibility = "hidden";
	return true;
}
</script>
<form action="" method="post" enctype="multipart/form-data" onsubmit="return load()">
	<center><div id="load" style="visibility:hidden;display:hidden"><img src="images/loading_bar.gif" alt="Loading"><br />Data is being backed up. Please wait....</div></center>
  <div id="frm">Your sql file: <input type="file" name="file" size="25" />
  <input type="submit" name="restore" value="Submit" /></div>
</form>