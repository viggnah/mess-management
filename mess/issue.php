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
		font-weight: bold;
		font-size: 120%;
		text-align: center;
	}

	.login
	{
		text-align: center;
	}

	.loginf
	{
		margin: auto;
		border: 3px solid gray;
		width: 35%;
		padding: 9px 35px;
		background: lightgrey;
		border-radius: 20px;
		box-shadow: 7px 7px 6px;
	}

	#button
	{
		border-radius: 10px;
		width: 170px;
		height: 40px;
		background: darkorange;
		font-weight: bold;
		font-size: 110%;
		box-shadow: 7px 7px 6px;
	}

	.table
	{
		width: 50%;
		background-color: #eeeeee;
	}

	.error
	{
		font-weight: bold;
		color: red;
		text-align: center;
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

function viewtable($conn, $date)
{
	$sql = "SELECT item_id,item_name,qty FROM order_date WHERE _date LIKE '$date'";
	$result = $conn->query($sql);

	echo "<p class=title>".$date." </p>";
	echo '<table class="table"> <tr><th>Item ID</th> <th>Item Name</th> <th>Quantity</th></tr>';
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			echo "<tr> <td>".$row['item_id']."</td> <td>".$row['item_name']."</td> <td>".$row['qty']."</td> </tr>";
		}
	}
	echo '</table>';
}

$show = TRUE;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$servername = "localhost";
	$username = "root";
	$password = "ssl";
	$db = "mess";

	$conn = new mysqli($servername, $username, $password, $db);

	if ($conn->connect_error)
	{
		die ("Connection failed: ".$conn->connect_error);
	}

	if (isset($_POST['view']))
	{
		$date = mysqli_real_escape_string($conn, $_POST["date"]);
		viewtable ($conn, "$date");
		$show = FALSE;
	}

	if (isset($_POST['remove']))
	{
		$show = FALSE;

		$date = mysqli_real_escape_string($conn, $_POST["date"]);

		$sql = "DELETE FROM order_to_kitchen WHERE _date LIKE '$date'";
		$result = $conn->query($sql);

		header("location:manager.php");
	}


	if (isset($_POST['issue']))
	{
		$f=0;
		$show = FALSE;

		$date = mysqli_real_escape_string($conn, $_POST["date"]);
		
		$sql = "SELECT item_id,qty FROM order_date WHERE _date LIKE '$date'";
		$result = $conn->query($sql);
		
		while ($row = $result->fetch_assoc()) 
		{
			$itemid = $row['item_id'];
			$qty = $row['qty'];
			$stqty = $conn->query("SELECT qty FROM stock WHERE item_id = '$itemid'");

			$r = $stqty->fetch_assoc();
			if (($r['qty']-$qty) < 0)
			{
				echo "<div class='title'><span class='error'> Item id ".$row['item_id']."'s quantity will fall below 0 in stock. </span></div>";
				$f=1;
				continue;
			}

			$conn->query("UPDATE stock SET qty = qty-'$qty' WHERE item_id = '$itemid'");
		}
		if ($f==0)
		{
			header("location:manager.php");
		}
		else
		{
			viewtable ($conn, "$date");
			$show = FALSE;
		}
	}

	$conn->close();
}

?>


<?php
if (!$show)
{
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<input id="button" type="submit" name="issue" value="Issue">
	&nbsp &nbsp
	<input id="button" type="submit" name="remove" value="Remove Request">
	<input type="hidden" name="date" value="<?php echo isset($_POST["date"])? $_POST["date"] : "";?>">
</form>
<br>
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
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<fieldset class="loginf">
		<br>
		Date:
		<input type="date" name="date" required="true">
		<br> 
		<br> <br>
		<input id="button" type="submit" name="view" value="Submit">
		<br>
		<br>
	</fieldset>
</form>

<?php
}
?>

</body>
</html>