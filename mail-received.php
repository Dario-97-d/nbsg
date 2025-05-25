<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

if ( is_int( $msg_id = array_search('Delete', $_POST) ) )
{
	sql_query( 'UPDATE mail SET seen = 2 WHERE msg_id = '. $msg_id );
	
	JS_add_message('PM deleted.');
}

if ( is_int( $msg_id = array_search('Set as Seen', $_POST) ) )
{
	sql_query( 'UPDATE mail SET seen = 1 WHERE msg_id = '. $msg_id );
	
	JS_add_message('PM seen.');
}

$messages = mysqli_fetch_all(
	sql_query(
		'SELECT m.*, u.char_id
		FROM      mail    m
		LEFT JOIN game_users u ON m.receiver_username = u.username
		WHERE char_id = '. $_uid .'
		AND seen <> 2' ),
	MYSQLI_ASSOC );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Mailbox</h1>

<h2>
	<a href="mail-received">Mailbox</a> || <a href="mail-write">Send pm</a> || <a href="mail-sent">PMs sent</a>
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
			
		</b> <a href="char-profile?id=<?= $row['char_id'] ?>">
			
			<?= $row['sender_username'] ?>
			
		</a> || <b>
			
			<?= $row['seen'] == 0 ? 'Not s' : 'S' ?>een
			
		</b> || <a href="mail-write?to=<?= $row['sender_username'] ?>">
			
			Reply
			
		</a>
		
		<textarea name="msg-text" disabled><?= $row['msg_text'] ?></textarea>
		
		<form action="mail-received" method="POST">
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

?>
