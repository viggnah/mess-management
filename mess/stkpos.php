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

	#logout
	{
		border-radius: 10px;
		width: 100px;
		height: 40px;
		background: white;
		font-weight: bold;
		font-size: 110%;
	}

	.error
	{
		font-weight: bold;
		color: red;
	}

	.table
	{
		width: 50%;
		background-color: #eeeeee;
	}

	table, th, td
	{
		border: 1px solid black;
		margin: auto;
		margin-bottom: 3em;
	}

	th, td
	{
		padding: 4px;
		text-align: center;
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
	    die("Connection failed: ". $conn->connect_error);
	} 


	if (isset($_POST['choose']))
	{
		$itemid = mysqli_real_escape_string($conn, $_POST["itemid"]);

		$sql = "SELECT item_id, name, qty FROM stock WHERE item_id = '$itemid'";
		$result = $conn->query($sql);
		$show = FALSE;

		if ($result->num_rows == 0)
		{
			$show = TRUE;
			$itemerr = "Item ID is not valid!";
		}

		if ($itemerr == "")
		{
			while ($row = $result->fetch_assoc())
			{	
				echo "<br> <br>";
				echo "<p class='title'> ITEM ID : ".$itemid."</p>";
				echo "<p class='title'> ITEM ID : ".$row['name']."</p>";
				echo "<p class='title'> QUANTITY IN STOCK : ".$row['qty']."</p>";
			}
		}
	}

	if (isset($_POST['all']))
	{
		$sql = "SELECT item_id, name, qty FROM stock";
		$result = $conn->query($sql);
		$show = FALSE;

		echo "<br> <br> <br>";

		echo '<table class="table"> <tr><th>Item ID</th> <th>Item Name</th> <th>Quantity in Stock</th> </tr>';
		while ($row = $result->fetch_assoc())
		{
			echo "<tr> <td>".$row['item_id']."</td> <td>".$row['name']."</td> <td>".$row['qty']."</td> </tr>";
		}
		echo "</table>";
	}

	$conn->close();
}
?>


<?php
if (!$show)
{
?>
<br> <br>
<a href="manager.php"><center> Home Page </center></a>
<?php
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
	<input id="logout" type="submit" name="all" value="All Items">
	<br>
	<br> <br>
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<fieldset class="loginf">
		<br>
		Item ID :
		<input type="text" name="itemid" required="true" value="<?php echo $itemid;?>">
		<br>
		<br> <br>
		<input id="button" type="submit" name="choose" value="Submit">
		<br>
	</fieldset>
</form>

<?php
}
?>

</body>
</html>