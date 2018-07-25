<?php session_start(); ?>

<?php

if (!(isset($_SESSION['type'])))
{}
else if ($_SESSION['type'] == "operator")
{
	header("location:operator.php");
}
else if ($_SESSION['type'] == "manager")
{
	header("location:manager.php");
}
else if ($_SESSION['type'] == "diner")
{
	header("location:diner.php");
}


$uname = $pwd = $type = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$servername = "localhost";
	$username = "root";
	$password = "ssl";
	$db = "mess";

	$conn = new mysqli ($servername, $username, $password, $db);

	if ($conn->connect_error)
	{
		die("Connection failed: ". $conn->connect_error);
	}

	
	$uname = mysqli_real_escape_string($conn, $_POST["username"]);
	$pwd = mysqli_real_escape_string($conn, $_POST["password"]);
	$type = mysqli_real_escape_string($conn, $_POST["login_type"]);


	if ($type == 'manager')
	{
		$sql = "SELECT uname,pwd FROM manager";
		$result= $conn->query($sql);
	}
	else if ($type == 'operator')
	{
		$sql = "SELECT uname,pwd FROM operator";
		$result= $conn->query($sql);
	}
	else
	{
	    $sql = "SELECT uname,pwd FROM diner";
	    $result= $conn->query($sql);
	}

	if (mysqli_num_rows($result) > 0) 
	{
	    while($row = mysqli_fetch_assoc($result)) 
	    {
	    	if ($row['uname'] == $uname && $row['pwd'] == $pwd && $type == 'operator') 
	    	{
	    		$_SESSION['type'] = "operator";
	    		header("location:operator.php");
	    	}

	    	if ($row['uname'] == $uname && $row['pwd'] == $pwd && $type == 'manager') 
	    	{
	    		$_SESSION['type'] = "manager";
	    		header("location:manager.php");
	    	}

	        if ($row['uname'] == $uname && $row['pwd'] == $pwd && $type == 'diner') 
	        {
	        	$_SESSION['type'] = "diner";
	            header("location:diner.php");
	        }

	    }

	}

	$error = "Invalid username or password!";

	$conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
	<title> Welcome </title>

<style>
	.title
	{
		text-align: center;
	}

	.login
	{
		text-align: center;
	}

	.loginf
	{
		border: 3px solid brown;
		margin: 0 auto;
		width: 35%;
		padding: 9px 35px; 
		background: lightyellow;
		border-radius: 20px;
		box-shadow: 7px 7px 6px;
	}

	#button
	{
		border-radius: 10px;
		width: 80px;
		height: 40px;
		background: lavender;
		font-weight: bold;
		font-size: 110%;
	}

	.error
	{
		font-weight: bold;
		color: red;
		font-size: 105%;
	}

	body
	{
		background-color: lightyellow;
	}

</style>
</head>

<body>


<div class="title">
	<h1> Food Court Login </h1>
	<br>
	<em style="font-size: 120%"> Welcome to the login page </em>
	<br>
	<br> <br> <br>
	<span class="error"> <?php echo $error;?> </span>
	<br>
</div>


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login">
	<fieldset class="loginf">
		<br>
		Username :
		<input type="text" name="username" required="true" value="<?php echo $uname;?>">
		<br>
		<br>
		Password :
		<input type="password" name="password" required="true">
		<br>
		<br> <br>
		<input type="radio" value="diner" name="login_type" checked="checked"> Diner
		<input type="radio" value="manager" name="login_type" > Manager
		<input type="radio" value="operator" name="login_type" > Operator
		<br>
		<br> <br>
		<input id="button" type="submit" name="submit" value="Log-in">
		<br>
	</fieldset>
</form>

</body>
</html>