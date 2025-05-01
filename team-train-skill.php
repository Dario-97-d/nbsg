<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

$ids = explode( '-', array_search('Train', $_POST) );

extract( sql_mfa(
	"SELECT teammate1_id, teammate2_id, s.*
	FROM char_team      t
	JOIN skill_training s ON t.char_id = s.char_id
	WHERE t.char_id = $_uid" ) );

if ( $teammate1_id < 1 || $teammate2_id < 1 || $teammate1_id != $ids[0] || $teammate2_id != $ids[1] ) exiter('team-meet');

/*
if ( min( $teammate1_level, $teammate2_level ) > $user_level )
{
	// remove nin from team
	exiter('team-meet');
}
*/

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

$team_kenjutsu = $team_members[0]['kenjutsu'] + $team_members[1]['kenjutsu'] + $team_members[2]['kenjutsu'];
$team_shuriken = $team_members[0]['shuriken'] + $team_members[1]['shuriken'] + $team_members[2]['shuriken'];
$team_taijutsu = $team_members[0]['taijutsu'] + $team_members[1]['taijutsu'] + $team_members[2]['taijutsu'];
$team_ninjutsu = $team_members[0]['ninjutsu'] + $team_members[1]['ninjutsu'] + $team_members[2]['ninjutsu'];
$team_genjutsu = $team_members[0]['genjutsu'] + $team_members[1]['genjutsu'] + $team_members[2]['genjutsu'];

$initial_kenjutsu = $team_members[0]['kenjutsu'];
$initial_shuriken = $team_members[0]['shuriken'];
$initial_taijutsu = $team_members[0]['taijutsu'];
$initial_ninjutsu = $team_members[0]['ninjutsu'];
$initial_genjutsu = $team_members[0]['genjutsu'];

$up_kenjutsu_points =
	max(
		round(
			(
				$team_members[0]['kenjutsu'] * (
					$team_members[1]['kenjutsu'] + $team_members[2]['kenjutsu'] - $team_members[0]['kenjutsu']
				) / (
					$team_members[0]['char_level'] * $team_kenjutsu / (
						$team_members[1]['char_level'] + $team_members[2]['char_level']
					)
				)
			)
		),
	0 );

$up_shuriken_points =
	max(
		round(
			(
				$team_members[0]['shuriken'] * (
					$team_members[1]['shuriken'] + $team_members[2]['shuriken'] - $team_members[0]['shuriken']
				) / (
					$team_members[0]['char_level'] * $team_shuriken / (
						$team_members[1]['char_level'] + $team_members[2]['char_level']
					)
				)
			)
		),
	0 );

$up_taijutsu_points =
	max(
		round(
			(
				$team_members[0]['taijutsu'] * (
					$team_members[1]['taijutsu'] + $team_members[2]['taijutsu'] - $team_members[0]['taijutsu']
				) / (
					$team_members[0]['char_level'] * $team_taijutsu / (
						$team_members[1]['char_level'] + $team_members[2]['char_level']
					)
				)
			)
		),
	0 );

$up_ninjutsu_points =
	max(
		round(
			(
				$team_members[0]['ninjutsu'] * (
					$team_members[1]['ninjutsu'] + $team_members[2]['ninjutsu'] - $team_members[0]['ninjutsu']
				) / (
					$team_members[0]['char_level'] * $team_ninjutsu / (
						$team_members[1]['char_level'] + $team_members[2]['char_level']
					)
				)
			)
		),
	0 );

$up_genjutsu_points =
	max(
		round(
			(
				$team_members[0]['genjutsu'] * (
					$team_members[1]['genjutsu'] + $team_members[2]['genjutsu'] - $team_members[0]['genjutsu']
				) / (
					$team_members[0]['char_level'] * $team_genjutsu / (
						$team_members[1]['char_level'] + $team_members[2]['char_level']
					)
				)
			)
		),
	0);

if ( $team_members[0]['style_name'] == 'Tameru' )
{
	$up_ninjutsu_points0 = 0;
	$up_genjutsu_points0 = 0;
}

$up_kenjutsu = 0;
$up_shuriken = 0;
$up_taijutsu = 0;
$up_ninjutsu = 0;
$up_genjutsu = 0;

if ( $up_kenjutsu_points > 0 )
{
	$kenjutsu_points += $up_kenjutsu_points;
	
	while ( $kenjutsu_points >= $team_members[0]['kenjutsu'] )
	{
		$kenjutsu_points -= $team_members[0]['kenjutsu'];
		$up_kenjutsu++;
	}
	
	$team_members[0]['kenjutsu'] += $up_kenjutsu;
}

if ( $up_shuriken_points > 0 )
{
	$shuriken_points += $up_shuriken_points;
	
	while ( $shuriken_points >= $team_members[0]['shuriken'] )
	{
		$shuriken_points -= $team_members[0]['shuriken'];
		$up_shuriken++;
	}
	
	$team_members[0]['shuriken'] += $up_shuriken;
}

if ( $up_taijutsu_points > 0 )
{
	$taijutsu_points += $up_taijutsu_points;
	
	while ( $taijutsu_points >= $team_members[0]['taijutsu'] )
	{
		$taijutsu_points -= $team_members[0]['taijutsu'];
		$up_taijutsu++;
	}
	
	$team_members[0]['taijutsu'] += $up_taijutsu;
}

if ( $up_ninjutsu_points > 0 )
{
	$ninjutsu_points += $up_ninjutsu_points;
	
	while ( $ninjutsu_points >= $team_members[0]['ninjutsu'] )
	{
		$ninjutsu_points -= $team_members[0]['ninjutsu'];
		$up_ninjutsu++;
	}
	
	$team_members[0]['ninjutsu'] += $up_ninjutsu;
}

if ( $up_genjutsu_points > 0 )
{
	$genjutsu_points += $up_genjutsu_points;
	
	while ( $genjutsu_points >= $team_members[0]['genjutsu'] )
	{
		$genjutsu_points -= $team_members[0]['genjutsu'];
		$up_genjutsu++;
	}
	
	$team_members[0]['genjutsu'] += $up_genjutsu;
}

sql_query(
	'UPDATE style_attributes SET
		kenjutsu = kenjutsu + '. $up_kenjutsu .',
		shuriken = shuriken + '. $up_shuriken .',
		taijutsu = taijutsu + '. $up_taijutsu .',
		ninjutsu = ninjutsu + '. $up_ninjutsu .',
		genjutsu = genjutsu + '. $up_genjutsu .',
		skill_points = skill_points - 0
	WHERE char_id = '. $_uid );

sql_query(
	'UPDATE skill_training SET
		kenjutsu_points = '. $kenjutsu_points .',
		shuriken_points = '. $shuriken_points .',
		taijutsu_points = '. $taijutsu_points .',
		ninjutsu_points = '. $ninjutsu_points .',
		genjutsu_points = '. $genjutsu_points .'
	WHERE char_id = '. $_uid );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team Train</h1>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
	<tr>
		<th>Clan</th>
		<th>Lv</th>
		<th>Nin</th>
		<th>Jutsu</th>
	</tr>
	
	<?php
	
	$i = 0;
	foreach ( $team_members as $row )
	{
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
		
		$i++;
	}
	
	?>
</table>

<h3>Joint Skills</h3>

<?= $team_kenjutsu .' • '. $team_shuriken .' • '. $team_taijutsu .' • '. $team_ninjutsu .' • '. $team_genjutsu ?>

<br />
<br />

<table class="table-skill" align="center">
	<tr>
		<th title="Sword Skill">kenjutsu</th>
		<th title="Shuriken Skill">shuriken</th>
		<th title="Melee Skill">taijutsu</th>
		<th title="Elemental Skill">ninjutsu</th>
		<th title="Illusion Skill">genjutsu</th>
	</tr>
	
	<tr>
		<td><?= $team_members[0]['kenjutsu'] ?></td>
		<td><?= $team_members[0]['shuriken'] ?></td>
		<td><?= $team_members[0]['taijutsu'] ?></td>
		<td><?= $team_members[0]['ninjutsu'] ?></td>
		<td><?= $team_members[0]['genjutsu'] ?></td>
	</tr>
</table>

<br /><br />

<table id="table-train" align="center" cellspacing="3">
	
	<tr>
		
		<th><?= ( $up_kenjutsu ? '+'. $up_kenjutsu : '' ) ?></th>
		
		<th>Kenjutsu</th>
		
		<td>
			<div id="bp">
				<div id="bt" style="width: <?= round( $kenjutsu_points * 100 / $team_members[0]['kenjutsu'] ) ?>px;"></div>
			</div>
		</td>
		
		<th><?= $kenjutsu_points .'/'. $team_members[0]['kenjutsu'] ?></th>
		
		<th><?= ( $up_kenjutsu_points > 0 ? '+'. $up_kenjutsu_points .' train' : '' ) ?></th>
	</tr>
	
	<tr>
		
		<th><?= ( $up_shuriken ? '+'. $up_shuriken : '' ) ?></th>
		
		<th>Shuriken</th>
		
		<td>
			<div id="bp">
				<div id="bt" style="width: <?= round( $shuriken_points * 100 / $team_members[0]['shuriken'] ) ?>px;"></div>
			</div>
		</td>
		
		<th><?= $shuriken_points .'/'. $team_members[0]['shuriken'] ?></th>
		
		<th><?= ( $up_shuriken_points > 0 ? '+'. $up_shuriken_points .' train' : '' ) ?></th>
		
	</tr>
	
	<tr>
		
		<th><?= ( $up_taijutsu ? '+'. $up_tajutsu : '' ) ?></th>
		
		<th>Taijutsu</th>
		
		<td>
			<div id="bp">
				<div id="bt" style="width: <?= round( $taijutsu_points * 100 / $team_members[0]['taijutsu'] ) ?>px;"></div>
			</div>
		</td>
		
		<th><?= $taijutsu_points .'/'. $team_members[0]['taijutsu'] ?></th>
		
		<th><?= ( $up_taijutsu_points > 0 ? '+'. $up_taijutsu_points .' train' : '' ) ?></th>
		
	</tr>
	
	<?php
	
	if ( $team_members[0]['style_name'] != 'Tameru' )
	{
		?>
		<tr>
			
			<th><?= ( $up_ninjutsu ? '+'. $up_ninjutsu : '' ) ?></th>
			
			<th>Ninjutsu</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $ninjutsu_points * 100 / $team_members[0]['ninjutsu'] ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $ninjutsu_points .'/'. $team_members[0]['ninjutsu'] ?></th>
			
			<th><?= ( $up_ninjutsu_points > 0 ? '+'. $up_ninjutsu_points .' train' : '' ) ?></th>
			
		</tr>
		
		<tr>
			
			<th><?= ( $up_genjutsu ? '+'. $up_genjutsu : '' ) ?></th>
			
			<th>Genjutsu</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $genjutsu_points * 100 / $team_members[0]['genjutsu'] ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $genjutsu_points .'/'. $team_members[0]['genjutsu'] ?></th>
			
			<th><?= ( $up_genjutsu_points > 0 ? '+'. $up_genjutsu_points .' train' : '' ) ?></th>
			
		</tr>
		<?php
	}
	
	?>
</table>
