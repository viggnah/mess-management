<?php session_start(); ?>

<?php

if ($_SESSION['type'] != "operator")
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
		width: 250px;
		height: 50px;
		background: cyan;
		font-weight: bold;
		font-size: 110%;
		box-shadow: 7px 7px 6px;
	}

	body
	{
		background-color: lightblue;
	}

</style>
</head>

<body>

<div class="title">
	<em> You are currently logged in as an Operator </em>
	<br>
	<br> <br> <br>
</div>

<div class="options">
	<a href="red_leave.php"> <input id="button" type="button" name="red_leave" value="MESS REDUCTION / LEAVE"></a>
	<br> <br>
	<a href="calcbill.php"> <input id="button" type="button" name="calcbill" value="CALCULATE BILL"></a>
	<br> <br>
	<a href="pay.php"> <input id="button" type="button" name="pay" value="RECORD PAYMENT"></a>
	<br> <br>
	<a href="coupon.php"> <input id="button" type="button" name="guest" value="ISSUE GUEST COUPON"></a>
	<br> <br>
	<a href="preorder.php"> <input id="button" type="button" name="preorder" value="PREPARE ORDER"></a>
	<br> <br>
</div>

<br> <br> <br> <br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="login"> 
	<input id="logout" type="submit" name="logout" value="LOGOUT">
</form>

</body>
</html>