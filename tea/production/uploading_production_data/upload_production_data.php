<script type="text/javascript" language="javascript" >
	function confirmUpload(){
		var file=document.forms['uploadproduction']['productionfile'].value.toString();
		if(file.length!=0){
			if(confirm("You are about to upload a file, Continue?")) return true;
			else return false;
		}
		else{
			alert("You must select a file to upload");
			return false;
		}
	}
</script>
<fieldset><legend align="center" >Upload Production Data using Excel File</legend>
<a href="production/uploading_production_data/production_format.php" ><p align="center" >Download Sample Excel file to fill</p></a>
<form action="#" method="post" enctype="multipart/form-data" id="uploadproduction" name="uploadproduction" onsubmit="return confirmUpload()" >
	Choose Excel File to Upload:<input type="file" name="productionfile" id="productionfile" /><input type="submit" id="upload" name="upload" value="Upload" />
</form></fieldset>
<?php
	if(isset($_POST['upload'])){
		$exp=explode(".",$_FILES['productionfile']['name']);
		if(strtolower(end($exp))!="xls"&&strtolower(end($exp)!="xlsx")){
			echo "<span style='font-size:40px' >Invalid File Uploaded</span><br />";
		}
		else{
			$file="production/uploading_production_data/uploads/".$_SESSION['userid'].$_FILES['productionfile']['name'];
			move_uploaded_file($_FILES['productionfile']['tmp_name'],$file);
			include("production/uploading_production_data/insert_production_data.php");
			insert($file);
			unlink($file);
			echo "<span style='font-size:40px' >Data upload successful</span>";
		}
	}
?>