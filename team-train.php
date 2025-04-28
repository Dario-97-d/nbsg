<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa(
	$conn,
	'SELECT teammate1_id, teammate2_id, char_rank, username, char_level, c.*
	FROM char_team        t
	JOIN game_users       u ON t.char_id = u.char_id
	JOIN char_attributes  a ON u.char_id = a.char_id
	JOIN style_attributes c ON u.char_id = c.char_id
	WHERE u.char_id = '. $_uid ) );

if ( $teammate1_id < 1 || $teammate2_id < 1 ) exiter('team-meet');

$teammates = mysqli_fetch_all(
	sql_query(
		$conn,
		'SELECT username, char_level, c.*
		FROM game_users       u
		JOIN char_attributes  a ON u.char_id = a.char_id
		JOIN style_attributes c on u.char_id = c.char_id
		WHERE u.char_id = '. $teammate1_id .'
		OR    u.char_id = '. $teammate2_id .'
		ORDER BY char_level DESC
		LIMIT 25' ),
	MYSQLI_ASSOC );

$team_kenjutsu = $kenjutsu + $teammates[0]['kenjutsu'] + $teammates[1]['kenjutsu'];
$team_shuriken = $shuriken + $teammates[0]['shuriken'] + $teammates[1]['shuriken'];
$team_taijutsu = $taijutsu + $teammates[0]['taijutsu'] + $teammates[1]['taijutsu'];
$team_ninjutsu = $ninjutsu + $teammates[0]['ninjutsu'] + $teammates[1]['ninjutsu'];
$team_genjutsu = $genjutsu + $teammates[0]['genjutsu'] + $teammates[1]['genjutsu'];

$bar_scale = 253 / (
	$team_kenjutsu +
	$team_shuriken +
	$team_taijutsu +
	$team_ninjutsu +
	$team_genjutsu );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team <?= $username ?></h1>

<h4>
	In a forest between villages
	<br />
	room is there for nin to train
</h4>

<table class="table-skill" align="center">
	
	<tr>
		<th title="Sword Skill">kenjutsu</th>
		<th title="Shuriken Skill">shuriken</th>
		<th title="Melee Skill">taijutsu</th>
		<th title="Elemental Skill">ninjutsu</th>
		<th title="Illusion Skill">genjutsu</th>
	</tr>
	
	<tr>
		<td><?= $kenjutsu ?></td>
		<td><?= $shuriken ?></td>
		<td><?= $taijutsu ?></td>
		<td><?= $ninjutsu ?></td>
		<td><?= $genjutsu ?></td>
	</tr>
</table>

<h2>Rank-<?= $char_rank ?></h2>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
	<tr>
		<th>Clan</th>
		<th>Lv</th>
		<th>Nin</th>
		<th>Jutsu</th>
	</tr>
	
	<?php
	
	$i = 0;
	foreach ( $teammates as $row )
	{
		$i++;
		?>
		<tr>
			
			<td><?= $row['style_name'] ?></td>
			
			<td><?= $row['char_level'] ?></td>
			
			<td>
				<a href="char-profile?id=<?= $row['char_id'] ?>">
					<?= $row['username'] ?>
				</a>
			</td>
			
			<th>
				<?= $row['kenjutsu'] .' • '. $row['shuriken'] .' • '. $row['taijutsu'] .' • '. $row['ninjutsu'] .' • '. $row['genjutsu'] ?>
			</th>
			
		</tr>
		<?php
	}
	
	?>
</table>

<h3>Joint Skills</h3>

<?= $team_kenjutsu .' • '. $team_shuriken .' • '. $team_taijutsu .' • '. $team_ninjutsu .' • '. $team_genjutsu ?>

<br />
<br />

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

<form action="team-train-skill" method="POST">
	
	<input type="submit" name="<?= $teammate1_id .'-'. $teammate2_id ?>" value="Train" />
	
</form>
