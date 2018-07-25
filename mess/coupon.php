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
		background-color: lightblue;
	}

</style>
</head>

<body>

<!--validating employee id then insert and return to homepage-->
<?php
$empid = $cost = $type = $date =  "";
$emperr = $updateerr = "";

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
	$type = mysqli_real_escape_string($conn, $_POST["type"]);
	$date = mysqli_real_escape_string($conn, $_POST["date"]);
	$cost = mysqli_real_escape_string($conn, $_POST["cost"]);

	$sql = "SELECT emp_id FROM diner";
	$result = $conn->query($sql);

	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			if ($row['emp_id'] == $empid)
			{	
				$sql = "INSERT INTO coupon (emp_id, _date, type, cost) VALUES ('$empid', '$date', '$type', '$cost')";
				
				if ($conn->query($sql) === TRUE)
				{
					header("location:operator.php");
				}
				else
				{
					$updateerr = "Failed to record data";
				}
			}
		}
	}

	$emperr = "Employee ID is not valid!";

	$conn->close();
}
?>


<div class="title">
	<em> You are currently logged in as an Operator </em>
	<br>
	<br> <br> <br>
	<span class="error"> <?php echo $emperr;?> </span>
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
		Type of Coupon :
		<br>
		<input type="radio" value="breakfast" name="type" checked="checked"><em> Breakfast </em><br>
		<input type="radio" value="lunch" name="type"><em> Lunch </em><br>
		<input type="radio" value="dinner" name="type"><em> Dinner </em>
		<br>
		<br>
		Cost :
		<input type="number" name="cost" required="true" value="<?php echo $cost;?>">
		<br>
		<br>
		Date :
		<input type="date" name="date" required="true" value="<?php echo $date;?>">
		<br>
		<br>
		<input id="button" type="submit" name="submit" value="Submit">
		<br>
		<br>
	</fieldset>
</form>


</body>
</html>