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

	#total
    {
        text-align: center;
        font-weight:bold; 
        font-size: 125%;
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

<!--validating itme id and if valid then insert and return to homepage-->
<?php
$month = $year = "";
$updateerr = "";
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

	$month = mysqli_real_escape_string($conn, $_POST["month"]);
	$year = mysqli_real_escape_string($conn, $_POST["year"]);

	$sql = "SELECT income, expenditure, profit FROM cashbook WHERE month = '$month' AND year = '$year'";
	$result = $conn->query($sql);

	if ($result->num_rows == 0)
	{
		$updateerr = "No data recorded for this month!";
	}
	else 
	{
		$show = FALSE;
		$row = $result->fetch_assoc();
		echo "<p id=total>".$month." - ".$year."</p>";
		echo "<p class=title> Income      : Rs.".$row['income']."</p>";
		echo "<p class=title> Expenditure : Rs.".$row['expenditure']."</p>";
		echo "<p class=title> Profit      : Rs.".$row['profit']."</p>";
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
	<span class="error"> <?php echo $updateerr;?> </span>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<fieldset class="loginf">
		<br>
		Month :
		<select name="month" value="<?php echo $month;?>">
			<option> Jan </option>
			<option> Feb </option>
			<option> Mar </option>
			<option> Apr </option>
			<option> May </option>
			<option> Jun </option>
			<option> Jul </option>
			<option> Aug </option>
			<option> Sep </option>
			<option> Oct </option>
			<option> Nov </option>
			<option> Dec </option>
		</select>
		<br>
		<br>
		Year :
		<input type="number" name="year" required="true" value="<?php echo $year;?>">
		<br>
		<br> <br>
		<input id="button" type="submit" name="submit" value="Submit">
		<br>
	</fieldset>
</form>

<?php
}
?>

</body>
</html>