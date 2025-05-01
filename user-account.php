<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

$getdata = sql_query( 'SELECT * FROM game_users WHERE char_id = '. $_uid );

extract( $udata = mysqli_fetch_assoc($getdata) );

if ( isset($_POST['chemail']) )
{
	$pw = md5($_POST['pw']);
	
	if ( $pw != $pass_word )
	{
		echo "Wrong password";
	}
	else
	{
		$chemail = handle_email( $_POST['chemail'] );
		
		if ( strlen($chemail) > 48 )
		{
			// $chemail returns error.
			echo $chemail;
		}
		else
		{
			sql_query( 'UPDATE game_users SET email = \''. $chemail .'\' WHERE char_id = '. $_uid );
			
			echo "Email has been updated";
			
			$email = $chemail;
		}
	}
}
else if ( isset($_POST['newpw']) )
{
	$oldpw = md5($_POST['oldpw']);
	
	if ( $oldpw != $pass_word )
	{
		echo "Wrong password";
	}
	else
	{
		$newpw = md5($_POST['newpw']);
		
		$slpw = strlen($newpw);
		
		if ( $slpw < 8 || $slpw > 32 )
		{
			echo "Password must be 8-32 chars long";
		}
		else
		{
			sql_query( 'UPDATE game_users SET pass_word = \''. $newpw .'\' WHERE char_id = '. $_uid );
			
			echo "Password has been updated";
		}
	}
}

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Settings</h1>

<form method="POST">
	<br />New e-mail:
	<br /><input type="email" style="color: gray; width: 256px;" name="chemail" value="<?= $email ?>" maxlength="48" />
	<br />
	<br />Password:
	<br /><input type="password" name="pw" />
	<br />
	<br /><input type="submit" value="Change E-mail" />
	<br />
</form>

<form method="POST">
	<br />Old password:
	<br /><input type="password" name="oldpw" />
	<br />
	<br />New password:
	<br /><input type="password" name="newpw" />
	<br />
	<br /><input type="submit" value="Change Password" />
	<br />
</form>
