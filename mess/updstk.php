<?php session_start(); ?>

<?php

if (!isset($_SESSION['type']))
{
	header("location:index.php");
}
else if ($_SESSION['type'] == "operator")
{
	header("location:operator.php");
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
		background: darkorange;
		font-weight: bold;
		font-size: 110%;
		box-shadow: 7px 7px 6px;
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

	body
	{
		background-color: lightsalmon;
	}

</style>
</head>

<body>

<?php
$itemid = $qty = "";
$itemerr = $updateerr = $qtyerr = "";
$show = TRUE;

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
	$qty = mysqli_real_escape_string($conn, $_POST["qty"]);

	$sql = "SELECT item_id, qty FROM stock WHERE item_id = '$itemid'";
	$result = $conn->query($sql);

	if ($result->num_rows == 0)
	{
		$itemerr = "Item ID is not valid!";
	}

	if (($row['qty']+$qty) < 0)
	{
		$qtyerr = "Stock quantity will fall below 0";
	}
	
	if ($itemerr == "" && $qtyerr == "")
	{
		$sql = "UPDATE stock SET qty = qty+'$qty' WHERE item_id = '$itemid'";
		if ($conn->query($sql) === TRUE)
		{
			header("location:manager.php");
		}
		else
		{
			$updateerr = "Failed to update stock";
		}
	}

	$conn->close();
}
?>

<?php
if ($show)
{
?>
<div class="title">
	<em> You are currently logged in as a Manager </em>
	<br>
	<br> <br> <br>
	<span class="error"> <?php echo $itemerr;?> </span>
	<br>
	<span class="error"> <?php echo $updateerr;?> </span>
	<br>
	<span class="error"> <?php echo $qtyerr;?> </span>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<fieldset class="loginf">
		<br>
		Item ID :
		<input type="text" name="itemid" required="true" value="<?php echo $itemid;?>">
		<br>
		<br>
		Quantity :
		<input type="number" name="qty" required="true" value="<?php echo $qty;?>">
		<br>
		<br> <br>
		<input id="button" type="submit" name="submit" value="Update">
		<br>
	</fieldset>
</form>

<?php
}
?>

</body>
</html>