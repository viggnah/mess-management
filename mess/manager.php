<?php session_start(); ?>

<?php

if ($_SESSION['type'] != "manager")
{
	header("location:index.php");
}

if (isset($_POST['logout']))
{
	session_unset();
	session_destroy();
	header("location:index.php");
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

	.options
	{
		text-align: center;
	}

	.login
	{
		text-align: center;
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

	#button
	{
		border-radius: 10px;
		width: 205px;
		height: 45px;
		background: darkorange;
		font-weight: bold;
		font-size: 100%;
		box-shadow: 7px 7px 6px;
	}

	body
	{
		background-color: lightsalmon;
	}

</style>
</head>

<body>

<div class="title">
	<em> You are currently logged in as a Manager </em>
	<br>
	<br> <br> <br>
</div>

<div class="options">
	<a href="issue.php"> <input id="button" type="button" value="ISSUE TO KITCHEN"></a>
	<br> <br>
	<a href="orderit.php"> <input id="button" type="button" value="ORDER ITEMS"></a>
	<br> <br>
	<a href="updstk.php"> <input id="button" type="button" value="UPDATE STOCK"></a>
	<br> <br>
	<a href="stkpos.php"> <input id="button" type="button" value="STOCK POSITION"></a>
	<br> <br>
	<a href="cash.php"> <input id="button" type="button" value="FINANCIAL DETAILS"></a>
	<br> <br>
	<a href="stats.php"> <input id="button" type="button" value="ITEM STATISTICS"></a>
	<br> <br>
</div>

<br> <br> <br> <br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="login"> 
	<input id="logout" type="submit" name="logout" value="LOGOUT">
</form>

</body>
</html>