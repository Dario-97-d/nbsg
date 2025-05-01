<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa(
	"SELECT char_level, style_name, char_rank, username, teammate1_id, teammate2_id, team_exam_phase
	FROM char_attributes  a
	JOIN style_attributes c ON a.char_id = c.char_id
	JOIN game_users       u ON c.char_id = u.char_id
	JOIN char_team        t ON u.char_id = t.char_id
	WHERE u.char_id = $_uid" ) );

if (
	( $teammate1_id < 1 || $teammate2_id < 1 )
	&&
	is_int( $pid = array_search('Pick', $_POST) )
	&&
	! in_array( $pid, [ $teammate1_id, $teammate2_id ] )
	&&
	mysqli_num_rows( sql_query("SELECT char_level FROM char_attributes WHERE char_id = $pid AND char_level <= $char_level") ) == 1 )
{
	if (
		( $tnin =
			( $teammate1_id == 0 ? 'teammate1_id' : (
				$teammate2_id == 0 ? 'teammate2_id' : 'nada' ) )
		) != 'nada' )
	{
		$$tnin = $pid;
		
		sql_query("UPDATE char_team SET $tnin = $pid WHERE char_id = $_uid");
	}
}

if (
	is_int ( $pid = array_search('Sack', $_POST) )
	&&
	$n = array_search( $pid, [ 0, $teammate1_id, $teammate2_id ] ) )
{
	$tnin = 'teammate'.$n.'_id';
	$$tnin = 0;
	
	sql_query("UPDATE char_team SET $tnin = 0 WHERE char_id = $_uid");
}

$has_any_teammate = $teammate1_id > 0 || $teammate2_id > 0;

if ( $has_any_teammate )
{
	$team_members = mysqli_fetch_all(
		sql_query(
			'SELECT style_name, u.char_id, username, char_level
			FROM style_attributes c
			JOIN game_users       u ON c.char_id = u.char_id
			JOIN char_attributes  a ON a.char_id = u.char_id
			WHERE u.char_id = '. $teammate1_id .'
			OR    u.char_id = '. $teammate2_id .'
			ORDER BY char_level DESC' ),
		MYSQLI_ASSOC );
}

if ( $team_exam_phase == 0 )
{
	$nins_eligible_for_team = mysqli_fetch_all(
		sql_query(
			'SELECT u.char_id, username, char_level, style_name
			FROM game_users       u
			JOIN char_attributes  a ON u.char_id = a.char_id
			JOIN style_attributes c ON u.char_id = c.char_id
			WHERE char_rank = \''. $char_rank .'\'
			AND char_level <= '. $char_level .'
			AND u.char_id NOT IN('. $_uid .', '. $teammate1_id .', '. $teammate2_id .')
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
					
					<th><?= $row['style_name'] ?></th>
					
					<td>
						<a href="char-profile?id=<?= $row['char_id'] ?>">
							<?=	$row['username'] ?>
						</a>
					</td>
					
					<td>Lv <?= $row['char_level'] ?></td>
					
					<td>
						<input type="submit" name="<?= $row['char_id'] ?>" value="Sack" />
					</td>
					
				</tr>
				<?php
			}
		}
		
		?>		
	</form>
</table>

<?php

if ( $teammate1_id > 0 && $teammate2_id > 0 )
{
	if ( $char_rank < 'D' && $team_exam_phase > 0 )
	{
		?>
		<h3>
			<a href="team-train">Team Train</a>
		</h3>
		<?php
	}
	else if ( $char_rank == 'D' && $team_exam_phase == 0 )
	{
		?>
		<h3>
			<a href="team-exam">Team Exam</a>
		</h3>
		<?php
	}
}
else if ( $team_exam_phase == 0 )
{
	?>
	<h3>
		<a href="char-bonds">Bonds</a>
	</h3>
	<?php
}
else
{
	?>
	Train jutsu and do battle
	<?php
}

if ( $team_exam_phase == 0 )
{
	?>
	<h3>Rank-<?= $char_rank ?></h3>
	
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
							<a href="char-profile?id=<?= $row['char_id'] ?>">
								<?= $row['username'] ?>
							</a>
						</td>
						
						<td><?= $row['char_level'] ?></td>
						
						<td>
							<input
								type="submit"
								name="<?= $row['char_id'] ?>"
								value="Pick"
								
								<?php
								
								if ( $teammate1_id > 0 && $teammate2_id > 0 )
								{
									?>
									title="Team is full" disabled
									<?php
								}
								
								?>
								
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

?>
