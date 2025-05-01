<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

if ( is_int( $msg_id = array_search('Delete', $_POST) ) )
{
	sql_query( 'UPDATE mail SET seen = 2 WHERE msg_id = '. $msg_id );
	
	echo "PM deleted";
}

$messages = mysqli_fetch_all(
	sql_query(
		'SELECT m.*, r.char_id
		FROM       mail       m
		LEFT  JOIN game_users s ON s.username = m.sender_username
		RIGHT JOIN game_users r ON r.username = m.receiver_username
		WHERE s.char_id = '. $_uid .'
		AND seen <> 2
		ORDER BY msg_time DESC' ),
	MYSQLI_ASSOC );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>PMs sent</h1>

<h2>
	<a href="mail-received">Mailbox</a> || <a href="mail-write">Send pm</a> || <a href="mail-sent">PMs sent</a>
</h2>

<?php

if ( empty($messages) )
{
	?>
	No sent messages to show
	<?php
}
else
{
	foreach ( $messages as $row )
	{
		?>
		
		<b>
			
			<?= $row['msg_time'] ?>
			
		</b> || <b>
			
			PM to:
			
		</b> <a href="char-profile?id=<?= $row['char_id'] ?>">
			
			<?= $row['receiver_username']?>
			
		</a> <b>
			
			<?= $row['seen'] == 0 ? 'Not s' : 'S' ?>een
			
		</b> || <a href="mail-write?to=<?= $row['sender_username'] ?>">
			
			Send PM
			
		</a>
		
		<textarea name="msg-text" disabled><?= $row['msg_text'] ?></textarea>
		
		<br />
		
		<form action="mail-sent" method="POST">
			<input type="submit" name="<?= $row['msg_id'] ?>" value="Delete" />
		</form>
		
		<?php
	}
}

?>
