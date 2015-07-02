<script type="text/javascript" language="javascript">
	function validateData(){
		var name=document.forms['frmedit']['storename'].value.toString();
		var cont=document.forms['frmedit']['storecontact'].value.toString();
		if(name.length==0||cont.length==0){
			alert("Please fill all the required spaces");
			return false;
		}
		else{
			if(confirm("You are about to save Changes, Continue?")) return true;
			else return false;
		}
	}
	var request;
	function getRequest(){
		if(window.XMLHttpRequest){
			request=new XMLHttpRequest();
		}
		else{
			request=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	function getBranches(){
		var typeid=parseInt(document.getElementById('type').value);
		document.getElementById("bt").innerHTML="";
		document.getElementById("bt").innerHTML+='<option value="0">Main Branch</option>';
		if(typeid>2){
			getRequest();
			var url="stock/store/get_branch.php";
			request.onreadystatechange=processRequest;
			request.open("GET",url,true);
			request.send(null);
		}
	}
	function processRequest(){
		if(request.readyState==4){
			if(request.status==200){
				document.getElementById("bt").innerHTML=request.responseText;
			}else alert(request.statusRequest);
		}
	}
	function clearBT(){
		document.getElementById("bt").innerHTML="";
		getBranches();
	}
</script>
<?php
	$sid=$_GET['sid'];
	$sqlFetch="SELECT * FROM store WHERE storeid='{$sid}'";
	$result=mysql_query($sqlFetch);
	$row=mysql_fetch_array($result);
	$_SESSION['type']=$row['type'];
	$_SESSION['belongto']=$row['belongto'];
?>
<form action="?opt=stores" method="post" id="frmedit" name="frmedit" onsubmit="return validateData()" >
	<table>
		<tr><td>Store Name:</td><td><input type="text" id="storename" name="storename" value="<?php echo $row['storename']; ?>" /></td></tr>
		<tr><td>Contacts:</td><td><input type="text" id="storecontact" name="storecontact" value="<?php echo $row['contacts']; ?>" /></td></tr>
		<tr><td>Type:</td><td>
			<select id="type" name="type" onchange="clearBT()" >
				<?php
					$sql="SELECT * FROM store_type WHERE status='1'";
					$resultType=mysql_query($sql);
					while($rowType=mysql_fetch_array($resultType)){
						if($_SESSION['type']==$rowType['typeid']) $selected="selected='selected'";
						else $selected="";
						echo "<option value='{$rowType['typeid']}' ".$selected." >{$rowType['typename']}</option>";
					}
				?>
			</select>
		</td></tr>
		<tr><td>Belong to</td><td>
		<select id="bt" name="bt" onclick="getBranches()" >
			<?php
				$sql="SELECT * FROM store WHERE storeid='{$_SESSION['belongto']}'";
				$resultBt=mysql_query($sql);
				$rowBt=mysql_fetch_array($resultBt);
				echo "<option value='{$rowBt['storeid']}' >{$rowBt['storename']}</option>";
			?>
		</select>
	</td></tr>
		<input type="hidden" id="storeid" name="storeid" value="<?php echo $row['storeid']; ?>" />
		<tr><td colspan="2" align="right" ><input type="reset" id="clear" name="clear" value="Clear" /><input type="submit" id="saveedit" name="saveedit" value="Save" /></td></tr>
	</table>
</form>