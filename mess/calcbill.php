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
		background-color: lightblue;
	}

</style>
</head>

<body>

<?php
$empid = $from = $to = "";
$emperr = $dateerr = $updateerr = "";
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

	$empid = mysqli_real_escape_string($conn, $_POST["empid"]);
	$from = mysqli_real_escape_string($conn, $_POST["sdate"]);
	$to = mysqli_real_escape_string($conn, $_POST["edate"]);

	$f = strtotime($from);
	$t = strtotime($to);

	//validating date
	if ($t < $f)			
	{
		$dateerr = "'End date' must be greater than 'Start date'";
	}




	//validating employee id
	$sql = "SELECT emp_id FROM diner";
	$result = $conn->query($sql);

	$flag = 0;

	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			if ($row['emp_id'] == $empid)
			{
				$flag = 1;
				break;
			}
		}
	}

	if ($flag == 0)
	{
		$emperr = "Employee ID doesn't exist";
	}






	//if both date and employee id are valid proceed
	if ($emperr == "" && $dateerr == "")
	{	
		//prevent the form from being displayed again
		$show = FALSE;	
		//first calculate extras
		$sql = "SELECT emp_id, item_name, _date, price FROM extras";
		$result= $conn->query($sql);

		$total = 0;

		echo "<p class=title> EXTRA ITEMS </p>";  
		echo '<table class="table"> <tr><th>Item Name</th> <th>Price(Rs)</th> <th>Purchase Date</th></tr>';
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				if ($row['emp_id'] == $empid)
				{
					$d = strtotime($row['_date']);

		            if ($d >= $f && $d <= $t)
		            {
		               echo "<tr> <td>".$row['item_name']."</td> <td>".$row['price']."</td> <td>". $row['_date']."</td> </tr>";
		               $total = $total + $row['price'];
		            }
				}
			}
		}
		echo '</table>';

		//then calculate guest coupons
		$sql = "SELECT emp_id, _date, type, cost FROM coupon";
		$result= $conn->query($sql);

		echo "<p class=title> GUEST COUPONS </p>";   
		echo '<table class="table"> <tr><th>Coupon Type</th> <th>Cost(Rs)</th> <th>Date</th></tr>';
		if (mysqli_num_rows($result) > 0) 
		{
		    while($row = mysqli_fetch_assoc($result)) 
		    {
		        if ($row['emp_id'] == $empid) 
		        {
		            $d = strtotime($row['_date']);

		            if ($d >= $f && $d <= $t)
		            {
		               echo "<tr> <td>".$row['type']."</td> <td>".$row['cost']."</td> <td>".$row['_date']."</td> </tr>";
		               $total = $total + $row['cost'];
		            }
		        }
		    }
		}
		echo '</table>';

		echo "<p id=total> TOTAL = Rs. ".$total."</p>";
	}

	$conn->close();
}
?>

<?php
if (!$show)
{
?>
<a href="operator.php"><center> Home Page </center></a>
<?php
}
?>

<?php
if ($show)
{
?>
<div class="title">
	<em> You are currently logged in as an Operator </em>
	<br>
	<br> <br> <br>
	<span class="error"> <?php echo $emperr;?> </span>
	<br>
	<span class="error"> <?php echo $dateerr;?> </span>
	<br>
	<span class="error"> <?php echo $updateerr;?> </span>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<fieldset class="loginf">
		<br>
		Employee ID :
		<input type="text" name="empid" required="true" value="<?php echo $empid;?>">
		<br>
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