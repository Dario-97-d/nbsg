<?php

include("headeron.php");

extract( sql_mfa(
	$conn,
	"SELECT level, style, rank, name, nin1, nin2
	FROM atts a
	JOIN clan c ON a.id = c.id
	JOIN user u ON c.id = u.id
	JOIN team t ON u.id = t.id
	WHERE u.id = $uid" ) );

?>

<h1>Team</h1>

<p style="padding: 0 32px;">
	Training is more effective as a team
	<br />
	A team may be started from bonds
	<br />
	and from strangers sorted together
</p>

<h3>Team <?= $name ?></h3>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
	<form method="POST">
		<tr>
			<th><?= $style ?></th>
			<td><?= $name ?></td>
			<td>Lv <?= $level ?></td>
		</tr>
		
		<?php
		
		if ( $nin1 > 0 || $nin2 > 0 )
		{
			$team = sql_query(
				$conn,
				"SELECT style, u.id, name, level
				FROM clan c
				JOIN user u ON c.id = u.id
				JOIN atts a ON a.id = u.id
				WHERE u.id = $nin1
				OR    u.id = $nin2
				ORDER BY level DESC" );
			
			while ( $member = mysqli_fetch_assoc($team) )
			{
				?>
				<tr>
					
					<th><?= $member['style'] ?></th>
					
					<td>
						<a href="nin?id=<?= $member['id'] ?>">
							<?= $member['name'] ?>
						</a>
					</td>
					
					<td>Lv <?= $member['level'] ?></td>
					
					<td>
						<input type="submit" name="<?= $member['id'] ?>" value="Sack" />
					</td>
					
				</tr>
				<?php
			}
		}
		
		?>
	</form>
</table>

<?php

if ( $nin1 > 0 && $nin2 > 0 )
{
	?>
	
	<h3>
		<a href="teamtrain">Team Train</a>
	</h3>
	
	Go on Team Exam
	<br />
	(chunnin shiken)
	
	<h3>Rank-D</h3>

	<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
		<form method="POST">
			<?php
			
			$picks = sql_query(
				$conn,
				"SELECT u.id, name, level, style
				FROM user u
				JOIN atts a ON u.id = a.id
				JOIN clan c ON u.id = c.id
				WHERE rank = 'D'
				AND level <= $level
				AND u.id NOT IN($uid, $nin1, $nin2)
				ORDER BY u.id DESC
				LIMIT 25" );
			
			if ( mysqli_num_rows($picks) < 1 )
			{
				echo "No nin available";
			}
			else
			{
				?>
				<tr>
					<th>Clan</th>
					<th>Nin</th>
					<th>Lv</th>
					<th>Select</th>
				</tr>
				<?php
				
				while ( $row = mysqli_fetch_assoc($picks) )
				{
					?>
					<tr>
						
						<th><?= $row['style'] ?></th>
						
						<td>
							<a href="nin?id=<?= $row['id'] ?>">
								<?= $row['name'] ?>
							</a>
						</td>
						
						<td><?= $row['level'] ?></td>
						
						<td>
							<input type="submit" name="<?= $row['id'] ?>" value="Pick"<?= ( $nin1 > 0 && $nin2 > 0 ? 'title="Team is full"disabled' : '' ) ?> />
						</td>
						
					</tr>
					<?php
				}
			}
			
			?>
		</form>
	</table>
	
	<?php
}
else
{
	?>
	<h3>
		<a href="bonds">Bonds</a>
	</h3>
	
	<h3>
		<a href="teampick">List of nin</a>
	</h3>
	<?php
}

include("footer.php");

?>