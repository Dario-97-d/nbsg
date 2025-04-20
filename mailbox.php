<?php

require_once 'headeron.php';

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

?>

<h1>Mailbox</h1>

<h2>
	<a href="mailbox">Mailbox</a> || <a href="sendpm">Send pm</a> || <a href="pmsent">PMs sent</a>
</h2>

<?php

$getpms = sql_query(
	$conn,
	"SELECT m.*, u.char_id
	FROM      mail    m
	LEFT JOIN game_users u ON m.receiver_username = u.username
	WHERE char_id = $uid
	AND seen <> 2" );

if ( mysqli_num_rows($getpms) < 1 )
{
	echo "Mailbox is empty";
}
else
{
	while( $row = mysqli_fetch_assoc($getpms) )
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

include("footer.php");

?>