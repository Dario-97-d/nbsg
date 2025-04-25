<?php

require_once 'backend.php';

if ( ! isset( $_uid ) ) exiter('index');

if ( is_int( $msg_id = array_search('Delete', $_POST) ) )
{
	sql_query( $conn, 'UPDATE mail SET seen = 2 WHERE msg_id = '. $msg_id );
	
	echo "PM deleted";
}

if ( is_int( $msg_id = array_search('Set as Seen', $_POST) ) )
{
	sql_query( $conn, 'UPDATE mail SET seen = 1 WHERE msg_id = '. $msg_id );
	
	echo "PM seen";
}

$messages = mysqli_fetch_all(
	sql_query(
		$conn,
		'SELECT m.*, u.char_id
		FROM      mail    m
		LEFT JOIN game_users u ON m.receiver_username = u.username
		WHERE char_id = '. $_uid .'
		AND seen <> 2' ),
	MYSQLI_ASSOC );

?>

<?php require_once 'header.php'; ?>

<h1>Mailbox</h1>

<h2>
	<a href="mailbox">Mailbox</a> || <a href="sendpm">Send pm</a> || <a href="pmsent">PMs sent</a>
</h2>

<?php

if ( empty($messages) )
{
	?>
	Mailbox is empty
	<?php
}
else
{
	foreach( $messages as $row )
	{
		?>
		
		<b>
			
			<?= $row['msg_time'] ?>
			
		</b> || <b>
			
			From:
			
		</b> <a href="nin?id=<?= $row['char_id'] ?>">
			
			<?= $row['sender_username'] ?>
			
		</a> || <b>
			
			<?= $row['seen'] == 0 ? 'Not s' : 'S' ?>een
			
		</b> || <a href="sendpm?to=<?= $row['sender_username'] ?>">
			
			Reply
			
		</a>
		
		<textarea name="msg-text" disabled><?= $row['msg_text'] ?></textarea>
		
		<form action="mailbox" method="POST">
			<table align="center">
				<?php
				
				if ( $row['seen'] != 1 )
				{
					?>
					<td>
						<input type="submit" name="<?= $row['msg_id'] ?>" value="Set as Seen" />
					</td>
					<?php
				}
				
				?>
				
				<td>
					<input type="submit" name="<?= $row['msg_id'] ?>" value="Delete" />
				</td>
			</table>
		</form>
		
		<?php
	}
}

require_once 'footer.php';

?>