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

<!--validating employee id and dates and if both are valid then insert and return to homepage-->
<?php
$empid = $from = $to = $days = "";
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
	$from = mysqli_real_escape_string($conn, $_POST["from"]);
	$to = mysqli_real_escape_string($conn, $_POST["to"]);

	$f = strtotime($from);
	$t = strtotime($to);

	//validating date
	if ($t < $f)			
	{
		$dateerr = "'To date' must be greater than 'From date'";
	}
	else if (intval(($t-$f)/86400) < 1)
	{
		$dateerr = "Date Interval must be at least one day";
	}

	$sql = "SELECT emp_id FROM diner";
	$result = $conn->query($sql);

	//flag helps in validating empid
	$flag = 0;

	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			if ($row['emp_id'] == $empid)
			{
				$flag = 1;
			}

			if ($row['emp_id'] == $empid && $dateerr == "")
			{	
				//days is derived from both dates
				$days = intval(($t-$f)/86400);		
				$sql = "INSERT INTO red_leave VALUES ('$empid', '$from', '$to', '$days')";
				
				if ($conn->query($sql) === TRUE)
				{
					$show = FALSE;
					header("location:operator.php");
				}
				else
				{
					$updateerr = "Failed to record data";
				}
			}
		}
	}

	if ($flag == 0)
	{
		$emperr = "Employee ID doesn't exist";
	}


	$conn->close();
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
		From :
		<input type="date" name="from" required="true" value="<?php echo $from;?>">
		<br>
		<br>
		To :
		<input type="date" name="to" required="true" value="<?php echo $to;?>">
		<br>
		<br>
		<input id="button" type="submit" name="submit" value="Submit">
		<br>
	</fieldset>
</form>

<?php
}
?>

</body>
</html>