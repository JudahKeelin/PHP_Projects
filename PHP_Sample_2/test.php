<?php

if(isset($_POST['inserttb'])){ //things to do, once the "submit" key is hit

	$id=$_POST['IDtb'];//get form value ID attribute
	$ln = $_POST['LNtb'];//get form value Last Name attribute
	$fn = $_POST['FNtb'];//get form values First Name attribute
	$city = $_POST['Citytb'];//get form value City attribute

	$servername = "localhost";// sql server machine name/IP (if your computer is the server too, then just keep it as "localhost"). 
	$username = "root";// mysql username
	$password = "";// sql password
	$dbname  = "db1";// database name

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = "INSERT INTO Persons VALUES ('$id', '$ln', '$fn', '$city')";//embed insert statement in PHP
	$result = $conn->query($sql);

	if($result) //if the insert into database was successful
	{
	echo "Records inserted successfully";
	}
}

?>


<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
ID : <input type="text" name="IDtb"/> 
Last Name : <input type="text" value="write Last Name here" name="LNtb"/>
First Name : <input type ="text" name ="FNtb"/>
City : <input type ="text" name ="Citytb"/>
<input type ="submit" value="Insert" name="inserttb"/>
</form>
