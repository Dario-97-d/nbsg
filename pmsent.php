<?php

require_once 'headeron.php';

if ( is_int( $msg_id = array_search('Delete', $_POST) ) )
{
	sql_query( $conn, 'UPDATE mail SET seen = 2 WHERE msg_id = '. $msg_id );
	
	echo "PM deleted";
}

?>

<h1>PMs sent</h1>

<h2>
	<a href="mailbox">Mailbox</a> || <a href="sendpm">Send pm</a> || <a href="pmsent">PMs sent</a>
</h2>

<?php

$getpms = sql_query(
	$conn,
	"SELECT m.*, r.char_id
	FROM       mail       m
	LEFT  JOIN game_users s ON s.username = m.sender_username
	RIGHT JOIN game_users r ON r.username = m.receiver_username
	WHERE s.char_id = $uid
	AND seen <> 2
	ORDER BY msg_time DESC" );

if ( mysqli_num_rows($getpms) < 1 )
{
	echo "No sent messages to show";
}
else
{
	while ( $row = mysqli_fetch_assoc($getpms) )
	{
		?>
		
		<b>
			
			<?= $row['msg_time'] ?>
			
		</b> || <b>
			
			PM to:
			
		</b> <a href="nin?id=<?= $row['char_id'] ?>">
			
			<?= $row['receiver_username']?>
			
		</a> <b>
			
			<?= $row['seen'] == 0 ? 'Not s' : 'S' ?>een
			
		</b> || <a href="sendpm?to=<?= $row['sender_username'] ?>">
			
			Send PM
			
		</a>
		
		<textarea name="msg-text" disabled><?= $row['msg_text'] ?></textarea>
		
		<br />
		
		<form action="pmsent" method="POST">
			<input type="submit" name="<?= $row['msg_id'] ?>" value="Delete" />
		</form>
		
		<?php
	}
}

include("footer.php");

?>