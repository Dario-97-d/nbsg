<?php

require_once 'backend/backstart.php';

// Login.
if ( isset($_POST['login']) )
{
	if ( isset( $_uid ) ) exiter('char-home');
	
	$username = handle_name($_POST['username']);
	
	if ( strlen($username) > 16 )
	{
		// $username returns error.
		JS_add_message( $username );
	}
	else
	{
		$checkuser = sql_query(
			'SELECT char_id FROM game_users WHERE username = \''. $username .'\' AND pass_word = \''. md5($_POST['password']) .'\'' );
		
		if ( mysqli_num_rows($checkuser) != 1 )
		{
			JS_add_message('Invalid Username / Password combination');
		}
		else
		{
			$_SESSION['uid'] = mysqli_fetch_assoc( $checkuser )['char_id'];
			
			exiter('char-home');
		}
	}
}

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Login</h1>

<form method="POST">
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

<a href="user-register">Register</a>

<hr>
