<?php

include("headeron.php");

$getplayers = sql_query(
	$conn,
	"SELECT u.id, rank, name, level, style
	FROM user u
	JOIN atts a ON u.id = a.id
	JOIN clan c ON u.id = c.id
	ORDER BY rank, level DESC
	LIMIT 25" );

?>

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
	while ( $row = mysqli_fetch_assoc($getplayers) )
	{
		$r++;
		?>
		<tr>
			
			<th><?= $r ?></th>
			
			<td><?= $row['style'] ?></td>
			
			<td>
				<a href="nin?id=<?= $row['id'] ?>">
					<?= $row['name'] ?>
				</a>
			</td>
			
			<td><?= $row['rank'] ?></td>
			
			<td><?= $row['level'] ?></td>
			
		</tr>
		<?php
	}
	
	?>
</table>

<?php include("footer.php"); ?>