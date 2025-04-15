<?php

include("headeron.php");

if ( is_int( $pmid = array_search('Delete', $_POST) ) )
{
	sql_query( $conn, "UPDATE mailbox SET seen = 2 WHERE pmid = '$pmid'" );
	
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
	"SELECT m.*, u.id
	FROM mailbox m
	LEFT JOIN user u ON m.pmfrom = u.name
	WHERE id = $uid
	AND seen <> 2" );

if ( mysqli_num_rows($getpms) < 1 )
{
	echo "No sent pms to show";
}
else
{
	while ( $pms = mysqli_fetch_assoc($getpms) )
	{
		?>
		
		<b>
			
			<?= date( "d/m H:i:s", $pms['time'] ) ?>
			
		</b> || <b>
			
			PM to:
			
		</b> <a href="nin?id=<?= $pms['id'] ?>">
			
			<?= $pms['pmto']?>
			
		</a> <b>
			
			<?= $pms['seen'] == 0 ? 'Not s' : 'S' ?>een
			
		</b> || <a href="sendpm?to=<?= $pms['pmfrom'] ?>">
			
			Send PM
			
		</a>
		
		<textarea name="pmtext" disabled>
			<?= nl2br($pms['pmtext']) ?>
		</textarea>
		
		<br />
		
		<form action="pmsent" method="POST">
			<input type="submit" name="<?= $pms['pmid'] ?>" value="Delete" />
		</form>
		
		<?php
	}
}

include("footer.php");

?>