<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa(
	"SELECT teammate1_id, teammate2_id, username, char_rank
	FROM char_team        t
	JOIN game_users       u ON t.char_id = u.char_id
	JOIN style_attributes c ON u.char_id = c.char_id
	WHERE u.char_id = $_uid" ) );

if ( $teammate1_id < 1 || $teammate2_id < 1 ) exiter('team-meet');

$team_members = mysqli_fetch_all(
	sql_query(
		'SELECT char_level, c.*, username
		FROM char_attributes  a
		JOIN style_attributes c ON a.char_id = c.char_id
		JOIN game_users       u ON a.char_id = u.char_id
		WHERE a.char_id IN ('. $_uid .', '. $teammate1_id .', '. $teammate2_id .')
		ORDER BY
			CASE a.char_id
				WHEN '. $_uid          .' THEN 1
				WHEN '. $teammate1_id .' THEN 2
				WHEN '. $teammate2_id .' THEN 3
			END' ),
	MYSQLI_ASSOC );

$team_kenjutsu =
	$team_members[0]['kenjutsu'] +
	$team_members[1]['kenjutsu'] +
	$team_members[2]['kenjutsu'];
$team_shuriken =
	$team_members[0]['shuriken'] +
	$team_members[1]['shuriken'] +
	$team_members[2]['shuriken'];
$team_taijutsu =
	$team_members[0]['taijutsu'] +
	$team_members[1]['taijutsu'] +
	$team_members[2]['taijutsu'];
$team_ninjutsu =
	$team_members[0]['ninjutsu'] +
	$team_members[1]['ninjutsu'] +
	$team_members[2]['ninjutsu'];
$team_genjutsu =
	$team_members[0]['genjutsu'] +
	$team_members[1]['genjutsu'] +
	$team_members[2]['genjutsu'];

$bar_scale = 253 / (
	$team_kenjutsu +
	$team_shuriken +
	$team_taijutsu +
	$team_ninjutsu +
	$team_genjutsu );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>
	Team
	<br />
	<?= $username ?>
</h1>

<table align="center" style="text-align: center" cellpadding="8" cellspacing="0">
	<tr>
		<th>Clan</th>
		<th>Nin</th>
		<th>Lv</th>
		<th>Jutsu</th>
	</tr>
	
	<?php
	
	$i = 0;
	foreach ( $team_members as $row )
	{
		?>
		<tr>
			
			<td><?= $row['style_name'] ?></td>
			
			<td>
				<a href="char-profile?id=<?= $row['char_id'] ?>">
					<?= $row['username'] ?>
				</a>
			</td>
			
			<td><?= $row['char_level'] ?></td>
			
			<th>
				<?= $row['kenjutsu'] .' • '. $row['shuriken'] .' • '. $row['taijutsu'] .' • '. $row['ninjutsu'] .' • '. $row['genjutsu'] ?>
			</th>
			
		</tr>
		<?php
		
		$i++;
	}
	
	?>
</table>

<h3>
	Joint Skills
	<br />
	<?= $team_kenjutsu .' • '. $team_shuriken .' • '. $team_taijutsu .' • '. $team_ninjutsu .' • '. $team_genjutsu ?>
</h3>

<table class="table-team" align="center">
	
	<tr>
		<th>Kenjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( $team_kenjutsu * $bar_scale ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Shuriken</th>
		
		<td>
			<div id="ttd" style="width: <?= round( $team_shuriken * $bar_scale ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Taijutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( $team_taijutsu * $bar_scale ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Ninjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( $team_ninjutsu * $bar_scale ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Genjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( $team_genjutsu * $bar_scale ) ?>px"></div>
		</td>
	</tr>
	
</table>

<br />

<form action="team-exam-joint" method="POST">
	
	<input type="submit" name="<?= $teammate1_id .'-'. $teammate2_id ?>" value="Team Battle" />
	
</form>

3v3 battle
<br />
standard bots
