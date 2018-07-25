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

	#text
    {
        text-align: center;
    }

	#total
    {
        text-align: center;
        font-weight:bold; 
        font-size: 125%;
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
$from = $to = "";
$dateerr = $updateerr = "";
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

	$from = mysqli_real_escape_string($conn, $_POST["sdate"]);
	$to = mysqli_real_escape_string($conn, $_POST["edate"]);

	$f = strtotime($from);
	$t = strtotime($to);

	//validating date
	if ($t < $f)			
	{
		$dateerr = "'End date' must be greater than 'Start date'";
	}




	//if date is valid proceed
	if ($dateerr == "")
	{	
		//prevent the form from being displayed again
		$show = FALSE;	
		//first calculate extras
		$sql = "SELECT item_id, name FROM stock";
		$result= $conn->query($sql);

		$total = 0;

		echo "<p class=title> USAGE IN STORE </p>";  
		echo '<table class="table"> <tr> <th>Item ID</th> <th>Item Name</th> <th>Total Usage</th></tr>';
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$itemid = $row['item_id'];

				$res = $conn->query("SELECT item_id, qty FROM order_date WHERE _date >= '$from' AND _date <= '$to'");

		        if ($res->num_rows > 0)
		        {
		        	$qty = 0;
		            while ($r = $res->fetch_assoc()) 
		            {
		            	if ($r['item_id'] == $itemid)
		            	{
		            		$qty = $qty+$r['qty'];
		            	}
		            }

		            echo "<tr> <td>".$itemid."</td> <td>".$row['name']."</td> <td>".$qty."</td> </tr>";
				}
			}
		}
		echo '</table>';

		mysqli_data_seek($result, 0);	//reset result to beginning to reuse

		echo "<p class=title> TOTAL AMOUNT FOR PURCHASE </p>";   
		echo '<table class="table"> <tr><th>Item ID</th> <th>Item Name</th> <th>Cost(Rs)</th></tr>';
		if (mysqli_num_rows($result) > 0) 
		{
		    while($row = mysqli_fetch_assoc($result)) 
		    {
		        $itemid = $row['item_id'];

				$res = $conn->query("SELECT item_id, cost FROM purchase_date WHERE txn_id IN (SELECT txn_id FROM purchase WHERE _date >= '$from' AND _date <= '$to')");

		        if ($res->num_rows > 0)
		        {
		        	$total = 0;
		            while ($r = $res->fetch_assoc()) 
		            {
		            	if ($r['item_id'] == $itemid)
		            	{
		            		$total = $total+$r['cost'];
		            	}
		            }

		            echo "<tr> <td>".$itemid."</td> <td>".$row['name']."</td> <td>".$total."</td> </tr>";
				}
			}
		}
		echo '</table>';

	}

	$conn->close();
}
?>

<?php
if (!$show)
{
?>
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
	<span class="error"> <?php echo $dateerr;?> </span>
	<br>
	<span class="error"> <?php echo $updateerr;?> </span>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<fieldset class="loginf">
		<br>
		Start Date :
		<input type="date" name="sdate" required="true" value="<?php echo $from;?>">
		<br>
		<br> 
		End Date :
		<input type="date" name="edate" required="true" value="<?php echo $to;?>">
		<br>
		<br> <br>
		<input id="button" type="submit" name="submit" value="Submit">
		<br>
		<br>
	</fieldset>
</form>

<?php
}
?>

</body>
</html>