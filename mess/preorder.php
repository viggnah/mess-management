<?php session_start(); ?>

<?php

if (!isset($_SESSION['type']))
{
	header("location:index.php");
}
else if ($_SESSION['type'] == "manager")
{
	header("location:manager.php");
}
else if ($_SESSION['type'] == "diner")
{
	header("location:diner.php");
}

?>



<!DOCTYPE html>
<html>
<head>

<style>
	.title
	{
		text-align: center;
		font-size: 120%; 
		font-weight: bold;
	}

	.login
	{
		text-align: center;
	}

	.loginf
	{
		border: 3px solid gray;
		margin: 0 auto;
		width: 35%;
		padding: 9px 35px; 
		background: lightgrey;
		border-radius: 20px;
		box-shadow: 7px 7px 6px;
	}

	#button
	{
		border-radius: 10px;
		width: 100px;
		height: 40px;
		background: cyan;
		font-weight: bold;
		font-size: 110%;
		box-shadow: 7px 7px 6px;
	}

	#add
	{
		width: 115px;
		height: 30px;
		background: white;
		font-size: 102%;
	}

	#left
	{
		text-align: left;
	}

	.error
	{
		font-weight: bold;
		color: red;
	}

	.txtw
	{
		width: 100px;
	}

	body
	{
		background-color: lightblue;
	}

</style>
</head>

<body>

<?php
$date =  "";
$itemerr = $updateerr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$servername = "localhost";
	$username = "root";
	$password = "ssl";
	$db = "mess";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $db);

	// Check connection
	if ($conn->connect_error) 
	{
	    die("Connection failed: " . $conn->connect_error);
	} 


	$itemid = mysqli_real_escape_string($conn, $_POST["itemid"]);
	$date = mysqli_real_escape_string($conn, $_POST["date"]);
	$qty = mysqli_real_escape_string($conn, $_POST["qty"]);

	$sql = "SELECT item_id FROM stock WHERE item_id = $itemid";
	$result = $conn->query($sql);

	if ($result->num_rows == 0)
	{
		$itemerr = "Item ID is not valid!";
	}
	else
	{
		$sql = "SELECT _date FROM order_to_kitchen WHERE _date LIKE '$date'";
		$result = $conn->query($sql);

		if ($result->num_rows == 0)
		{
			$conn->query("INSERT INTO order_to_kitchen VALUES ('$date')");
		}

		$sql = "SELECT item_id,name FROM stock WHERE item_id = '$itemid'";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc())
		{
			$name = $row['name'];
			$conn->query("INSERT INTO order_date VALUES ('$date', '$itemid', '$name' ,'$qty')");
			header("location:operator.php");
		}
	}

	$conn->close();
}
?>


<div class="title">
	<em> You are currently logged in as an Operator </em>
	<br>
	<br> <br>
	<span class="error"> <?php echo $itemerr;?> </span>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<fieldset class="loginf">
		<br>
		Date :
		<input type="date" name="date" required="true" value="<?php echo $date;?>">
		<br>
		<br> <br>
		Item ID:
		<input type="text" name="itemid" required="true" value="<?php echo $itemid;?>" class="txtw">
		&nbsp &nbsp
		Quantity :
		<input type="number" name="qty" required="true" value="<?php echo $qty;?>" class="txtw">
		<br>
		<br> <br>
		<input id="button" type="submit" name="submit" value="Submit">
		<br>
		<br>
	</fieldset>
</form>

</body>
</html>
