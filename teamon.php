<?php

require_once 'backend.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa(
	$conn,
	'SELECT char_level, style_name, char_rank, username, teammate1_id, teammate2_id
	FROM char_attributes  a
	JOIN style_attributes c ON a.char_id = c.char_id
	JOIN game_users       u ON c.char_id = u.char_id
	JOIN char_team        t ON u.char_id = t.char_id
	WHERE u.char_id = '. $_uid ) );

$has_any_teammate = $teammate_id1 > 0 || $teammate_id2 > 0;

if ( $has_any_teammate )
{
	$team_members = mysqli_fetch_all(
		sql_query(
			$conn,
			'SELECT style_name, u.char_id, username, char_level
			FROM style_attributes c
			JOIN game_users       u ON c.char_id = u.char_id
			JOIN char_attributes  a ON a.char_id = u.char_id
			WHERE u.char_id = '. $teammate1_id .'
			OR    u.char_id = '. $teammate2_id .'
			ORDER BY char_level DESC' ),
		MYSQLI_ASSOC );
}

$is_team_full = $teammate1_id > 0 && $teammate2_id > 0;

if ( $is_team_full )
{
	$nins_eligible_for_team = mysqli_fetch_all(
		sql_query(
			$conn,
			'SELECT u.char_id, username, char_level, style_name
			FROM game_users       u
			JOIN char_attributes  a ON u.char_id = a.char_id
			JOIN style_attributes c ON u.char_id = c.char_id
			WHERE char_rank = \'D\'
			AND   char_level <= '. $char_level .'
			AND   u.char_id NOT IN('. $_uid .', '. $teammate1_id .', '. $teammate2_id .')
			ORDER BY u.char_id DESC
			LIMIT 25' ),
		MYSQLI_ASSOC );
}

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team</h1>

<p style="padding: 0 32px;">
	Training is more effective as a team
	<br />
	A team may be started from bonds
	<br />
	and from strangers sorted together
</p>

<h3>Team <?= $username ?></h3>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
	<form method="POST">
		<tr>
			<th><?= $style_name ?></th>
			<td><?= $username ?></td>
			<td>Lv <?= $char_level ?></td>
		</tr>
		
		<?php
		
		if ( $has_any_teammate )
		{
			foreach ( $team_members as $row )
			{
				?>
				<tr>
					
					<th><?= $member['style_name'] ?></th>
					
					<td>
						<a href="nin?id=<?= $member['char_id'] ?>">
							<?= $member['username'] ?>
						</a>
					</td>
					
					<td>Lv <?= $member['char_level'] ?></td>
					
					<td>
						<input type="submit" name="<?= $member['char_id'] ?>" value="Sack" />
					</td>
					
				</tr>
				<?php
			}
		}
		
		?>
	</form>
</table>

<?php

if ( $is_team_full )
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
			
			if ( empty( $nins_eligible_for_team ) )
			{
				?>
				No nin available
				<?php
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
				
				foreach ( $nins_eligible_for_team as $row )
				{
					?>
					<tr>
						
						<th><?= $row['style_name'] ?></th>
						
						<td>
							<a href="nin?id=<?= $row['char_id'] ?>">
								<?= $row['username'] ?>
							</a>
						</td>
						
						<td><?= $row['char_level'] ?></td>
						
						<td>
							<input
								type="submit"
								name="<?= $row['char_id'] ?>"
								value="Pick"
								<?= ( $teammate1_id > 0 && $teammate2_id > 0 ? ' title="Team is full" disabled' : '' ) ?>
							/>
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

?>
