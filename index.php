<?php

include("header.php");

// Logout.
if ( isset($_GET['log']) && $_GET['log'] == 'out' )
{
	session_destroy();
	exiter("index");
}

// Login.
if ( isset($_POST['login']) )
{
	if ( isset($_SESSION['uid']) ) exiter("home");
	
	$username = handle_name($_POST['username']);
	
	if ( strlen($username) > 16 )
	{
		// $username returns error.
		echo $username;
	}
	else
	{
		$checkuser =
			mysqli_query(
				$conn,
				'SELECT char_id FROM game_users WHERE username = \''. $username .'\' AND pass_word = \''. md5($_POST['password']) .'\'' )
			or
				die( mysqli_error($conn) );
		
		if ( mysqli_num_rows($checkuser) != 1 )
		{
			echo "Invalid Username / Password Combination";
		}
		else
		{
			$uid = mysqli_fetch_assoc($checkuser);
			
			$_SESSION['uid'] = $uid['char_id'];
			
			exiter("home");
		}
	}
}

if ( ! isset($_SESSION['uid']) )
{
	?>
	
	<h1>Login</h1>
	
	<form action="index" method="POST">
		Username:
		<br />
		<input type="text" name="username" />
		<br />
		Password:
		<br />
		<input type="password" name="password" />
		<br />
		<br />
		<input type="submit" class="button1" name="login" value="Login" />
	</form>
	
	<a href="signup">Sign up</a>
	
	<hr>
	
	<?php
}

echo "<br />Hachi-maki";

include("footer.php");

?>