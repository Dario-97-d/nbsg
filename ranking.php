<?php

require_once 'backend.php';

if ( ! isset( $_uid ) ) exiter('index');

$nins = mysqli_fetch_all(
	sql_query(
		$conn,
		'SELECT u.char_id, char_rank, username, char_level, style_name
		FROM game_users       u
		JOIN char_attributes  a ON u.char_id = a.char_id
		JOIN style_attributes c ON u.char_id = c.char_id
		ORDER BY char_rank, char_level DESC
		LIMIT 25' ),
	MYSQLI_ASSOC );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Ranking</h1>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
	<tr>
		<th>#</th>
		<th>Clan</th>
		<th>Nin</th>
		<th>rank</th>
		<th>Lv</th>
	</tr>
	
	<?php
	
	$r = 0;
	foreach ( $nins as $row )
	{
		$r++;
		?>
		<tr>
			
			<th><?= $r ?></th>
			
			<td><?= $row['style_name'] ?></td>
			
			<td>
				<a href="nin?id=<?= $row['char_id'] ?>">
					<?= $row['username'] ?>
				</a>
			</td>
			
			<td><?= $row['char_rank'] ?></td>
			
			<td><?= $row['char_level'] ?></td>
			
		</tr>
		<?php
	}
	
	?>
</table>
