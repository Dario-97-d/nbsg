<?php

require_once 'backend/backstart.php';

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

if ( isset($_POST['mail-write']) )
{
	$player = $_POST['receiver_username'];
	$receiver_username = handle_name($player);
	$msg_text = $_POST['msg-text'];
	
	if ( strlen($receiver_username) > 16 )
	{
		// $receiver_username returns error.
		JS_add_message( $receiver_username );
	}
	else if ( mysqli_num_rows( sql_query("SELECT char_id FROM game_users WHERE username = '$receiver_username'") ) != 1)
	{
		JS_add_message( $receiver_username .' not found'. );
	}
	else
	{
		$slpmt = strlen($msg_text);
		
		if ( $slpmt < 1 || $slpmt > 800)
		{
			// 800 ?
			JS_add_message('Number of chars in text must be 1-800');
		}
		else
		{
			$user = sql_mfa('SELECT username FROM game_users WHERE char_id = '. $_uid);
			
			SQL_perform_transaction(
				'INSERT INTO mail (
					sender_username,
					receiver_username,
					msg_text,
					seen)
				VALUES (
					\''. $user['username']  .'\',
					\''. $receiver_username .'\',
					\''. $msg_text          .'\',
					0)' );
			
			JS_add_message('PM sent.');
			
			exiter('mail-sent');
		}
	}
}

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Private Message</h1>

<h2>
	<a href="mail-received">Mailbox</a> || <a href="mail-write">Send pm</a> || <a href="mail-sent">PMs sent</a>
</h2>

<form action="mail-write" method="POST">
	PM to:
	<br /><input type="text" style="text-align: center;" name="receiver_username" value="<?= $player ?>" />
	<br />
	<br />
	<textarea name="msg-text" maxlength="800"><?= $msg_text ?></textarea>
	<br />
	<br />
	<input type="submit" name="mail-write" value="Send" />
</form>
