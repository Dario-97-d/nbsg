<?php

require_once 'backend.php';

if ( ! isset( $_uid ) ) exiter('index');

$player = '';
$msg_text = '';

if ( isset($_GET['to']) )
{
	$player = $_GET['to'];
	
	if ( strlen( $perr = handle_name($_GET['to']) ) > 16 )
	{
		$msg_text = $perr;
	}
}

if ( isset($_POST['sendpm']) )
{
	$player = $_POST['receiver_username'];
	$receiver_username = handle_name($player);
	$msg_text = $_POST['msg-text'];
	
	if ( strlen($receiver_username) > 16 )
	{
		// $receiver_username returns error.
		echo $receiver_username;
	}
	else if ( mysqli_num_rows( sql_query( $conn, "SELECT char_id FROM game_users WHERE username = '$receiver_username'" ) ) != 1)
	{
		echo $receiver_username ." not found";
	}
	else
	{
		$slpmt = strlen($msg_text);
		
		if ( $slpmt < 1 || $slpmt > 800)
		{
			// 800 ?
			echo "Number of chars in text must be 1-800";
		}
		else
		{
			$user = mysqli_fetch_assoc( sql_query( $conn, "SELECT username FROM game_users WHERE char_id = $_uid" ) );
			
			sql_prepstate(
				$conn,
				"INSERT INTO mail (sender_username, receiver_username, msg_text, seen)
				VALUES ('". $user['username'] ."', '". $receiver_username ."', ?, 0)",
				"s", $msg_text);
			
			echo "PM sent";
			
			exiter('pmsent');
		}
	}
}

?>

<?php require_once 'header.php'; ?>

<h1>Private Message</h1>

<h2>
	<a href="mailbox">Mailbox</a> || <a href="sendpm">Send pm</a> || <a href="pmsent">PMs sent</a>
</h2>

<form action="sendpm" method="POST">
	PM to:
	<br /><input type="text" style="text-align: center;" name="receiver_username" value="<?= $player ?>" />
	<br />
	<br />
	<textarea name="msg-text" maxlength="800"><?= $msg_text ?></textarea>
	<br />
	<br />
	<input type="submit" name="sendpm" value="Send" />
</form>

<?php require_once 'footer.php'; ?>