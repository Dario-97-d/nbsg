<?php

require_once 'backend/backstart.php';

if ( isset( $_uid ) ) exiter('char-home');

$username = '';
$email = '';

if ( isset($_POST['user-register']) )
{
	$username = handle_name($_POST['username']);
	
	if ( strlen($username) > 16 )
	{
		// $username returns error.
		echo $username;
	}
	else if ( mysqli_num_rows( sql_query("SELECT char_id FROM game_users WHERE username = '$username'") ) > 0)
	{
		echo $username ." already in use";
	}
	else
	{
		$email = handle_email($_POST['email']);
		
		if ( strlen($email) > 48 )
		{
			// $email returns error.
			echo $email;
		}
		else if ( mysqli_num_rows( sql_query("SELECT char_id FROM game_users WHERE email = '$email'") ) > 0 )
		{
			echo $email ." already in use";
		}
		else
		{
			$password = $_POST['password'];
			$slpw = strlen($password);
			
			if ( $slpw < 8 || $slpw > 32 )
			{
				echo "Password must be 8-32 chars long";
			}
			else
			{
				$sql_register = sql_query('CALL sp_register_userchar(\''. $username .'\', \''. $email .'\', \''. md5($password) .'\')' );
				
				$_SESSION['uid'] = mysqli_fetch_assoc( $sql_register )['char_id'];
				
				exiter('clan-hall');
			}
		}
	}
}

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Register</h1>

<form method="POST">
	Username:
	<br /><input type="text" name="username" value="<?= $username ?>" />
	<br />
	Password:
	<br /><input type="password" name="password" />
	<br />
	E-mail:
	<br /><input type="email" name="email" value="<?= $email ?>" />
	<br />
	<br /><input type="submit" class="button1" name="user-register" value="Start" />
</form>

<a href="user-login">Login</a>

<hr>
