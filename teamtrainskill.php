<?php

require_once 'headeron.php';

$ids = explode( '-', array_search('Train', $_POST) );

extract( sql_mfa(
	$conn,
	"SELECT teammate1_id, teammate2_id, s.*
	FROM char_team      t
	JOIN skill_training s ON t.char_id = s.char_id
	WHERE t.char_id = $uid" ) );

if ( $teammate1_id < 1 || $teammate2_id < 1 || $teammate1_id != $ids[0] || $teammate2_id != $ids[1] ) exiter("team");

/*
if ( min( $teammate1_level, $teammate2_level ) > $user_level )
{
	// remove nin from team
	exiter('team');
}
*/

$members = sql_query(
	$conn,
	"SELECT char_level, c.*, username
	FROM char_attributes  a
	JOIN style_attributes c ON a.char_id = c.char_id
	JOIN game_users       u ON a.char_id = u.char_id
	WHERE a.char_id IN ($uid, $teammate1_id, $teammate2_id)
	ORDER BY
		CASE a.char_id
			WHEN $uid          THEN 1
			WHEN $teammate1_id THEN 2
			WHEN $teammate2_id THEN 3
		END" );

?>

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
	while ( $row = mysqli_fetch_assoc($members) )
	{
		?>
		<tr>
			
			<td><?= $row['style_name'] ?></td>
			
			<td><?= ( ${'char_level'. $i } = $row['char_level'] ) ?></td>
			
			<td>
				<a href="nin?id=<?= $row['char_id'] ?>">
					<?= $row['username'] ?>
				</a>
			</td>
			
			<th>
				<?= ( ${'kenjutsu'. $i} = $row['kenjutsu'] ) ?>
				 • 
				<?= ( ${'shuriken'. $i} = $row['shuriken'] ) ?>
				 • 
				<?= ( ${'taijutsu'. $i} = $row['taijutsu'] ) ?>
				 • 
				<?= ( ${'ninjutsu'. $i} = $row['ninjutsu'] ) ?>
				 • 
				<?= ( ${'genjutsu'. $i} = $row['genjutsu'] ) ?>
			</th>
			
		</tr>
		<?php
		
		${'style_name'. $i} = $row['style_name'];
		
		$i++;
	}
	
	?>
</table>

<h3>Joint Skills</h3>

<?php

echo
	( $kenjutsu = $kenjutsu0 + $kenjutsu1 + $kenjutsu2 )
	.' • '.
	( $shuriken = $shuriken0 + $shuriken1 + $shuriken2 )
	.' • '.
	( $taijutsu = $taijutsu0 + $taijutsu1 + $taijutsu2 )
	.' • '.
	( $ninjutsu = $ninjutsu0 + $ninjutsu1 + $ninjutsu2 )
	.' • '.
	( $genjutsu = $genjutsu0 + $genjutsu1 + $genjutsu2 );

$kenjutsui = $kenjutsu0;
$shurikeni = $shuriken0;
$taijutsui = $taijutsu0;
$ninjutsui = $ninjutsu0;
$genjutsui = $genjutsu0;

$up_kenjutsu =
	max(
		round(
			(
				$kenjutsu0 * (
					$kenjutsu1 + $kenjutsu2 - $kenjutsu0
				) / (
					$char_level0 * $kenjutsu / (
						$char_level1 + $char_level2
					)
				)
			)
		),
	0 );

$up_shuriken =
	max(
		round(
			(
				$shuriken0 * (
					$shuriken1 + $shuriken2 - $shuriken0
				) / (
					$char_level0 * $shuriken / (
						$char_level1 + $char_level2
					)
				)
			)
		),
	0);

$up_taijutsu =
	max(
		round(
			(
				$taijutsu0 * (
					$taijutsu1 + $taijutsu2 - $taijutsu0
				) / (
					$char_level0 * $taijutsu / (
						$char_level1 + $char_level2
					)
				)
			)
		),
	0);

$up_ninjutsu =
	max(
		round(
			(
				$ninjutsu0 * (
					$ninjutsu1 + $ninjutsu2 - $ninjutsu0
				) / (
					$char_level0 * $ninjutsu / (
						$char_level1 + $char_level2
					)
				)
			)
		),
	0);

$up_genjutsu =
	max(
		round(
			(
				$genjutsu0 * (
					$genjutsu1 + $genjutsu2 - $genjutsu0
				) / (
					$char_level0 * $genjutsu / (
						$char_level1 + $char_level2
					)
				)
			)
		),
	0);

if ( $style_name0 == 'Tameru' )
{
	$up_ninjutsu0 = 0;
	$up_genjutsu0 = 0;
}

if ( $up_kenjutsu > 0 ) $kenjutsu_points += $up_kenjutsu;
if ( $up_shuriken > 0 ) $shuriken_points += $up_shuriken;
if ( $up_taijutsu > 0 ) $taijutsu_points += $up_taijutsu;
if ( $up_ninjutsu > 0 ) $ninjutsu_points += $up_ninjutsu;
if ( $up_genjutsu > 0 ) $genjutsu_points += $up_genjutsu;

while ( $kenjutsu_points >= $kenjutsu0 ) { $kenjutsu_points -= $kenjutsu0; $kenjutsu0++; }
while ( $shuriken_points >= $shuriken0 ) { $shuriken_points -= $shuriken0; $shuriken0++; }
while ( $taijutsu_points >= $taijutsu0 ) { $taijutsu_points -= $taijutsu0; $taijutsu0++; }

while ( $ninjutsu_points >= $ninjutsu0 && $ninjutsu0 > 0 ) { $ninjutsu_points -= $ninjutsu0; $ninjutsu0++; }
while ( $genjutsu_points >= $genjutsu0 && $genjutsu0 > 0 ) { $genjutsu_points -= $genjutsu0; $genjutsu0++; }

sql_query(
	$conn,
	'UPDATE style_attributes SET
		kenjutsu = '. $kenjutsu0 .',
		shuriken = '. $shuriken0 .',
		taijutsu = '. $taijutsu0 .',
		ninjutsu = '. $ninjutsu0 .',
		genjutsu = '. $genjutsu0 .',
		skill_points = skill_points - 0
	WHERE char_id = '. $uid );

sql_query(
	$conn,
	'UPDATE skill_training SET
		kenjutsu_points = '. $kenjutsu_points .',
		shuriken_points = '. $shuriken_points .',
		taijutsu_points = '. $taijutsu_points .',
		ninjutsu_points = '. $ninjutsu_points .',
		genjutsu_points = '. $genjutsu_points .'
	WHERE char_id = '. $uid );

?>

<br /><br />

<table class="table-skill" align="center">
	<tr>
		<th title="Sword Skill">kenjutsu</th>
		<th title="Shuriken Skill">shuriken</th>
		<th title="Melee Skill">taijutsu</th>
		<th title="Elemental Skill">ninjutsu</th>
		<th title="Illusion Skill">genjutsu</th>
	</tr>
	
	<tr>
		<td><?= $kenjutsu0 ?></td>
		<td><?= $shuriken0 ?></td>
		<td><?= $taijutsu0 ?></td>
		<td><?= $ninjutsu0 ?></td>
		<td><?= $genjutsu0 ?></td>
	</tr>
</table>

<br /><br />

<table id="table-train" align="center" cellspacing="3">
	
	<tr>
		
		<th><?= ( $kenjutsu0 - $kenjutsui ? '+'. ( $kenjutsu0 - $kenjutsui ) : '' ) ?></th>
		
		<th>Kenjutsu</th>
		
		<td>
			<div id="bp">
				<div id="bt" style="width: <?= round( $kenjutsu_points * 100 / $kenjutsu0 ) ?>px;"></div>
			</div>
		</td>
		
		<th><?= $kenjutsu_points .'/'. $kenjutsu0 ?></th>
		
		<th><?= ( $up_kenjutsu > 0 ? '+'. $up_kenjutsu .' train' : '' ) ?></th>
	</tr>
	
	<tr>
		
		<th><?= ( $shuriken0 - $shurikeni ? '+'. ( $shuriken0 - $shurikeni ) : '' ) ?></th>
		
		<th>Shuriken</th>
		
		<td>
			<div id="bp">
				<div id="bt" style="width: <?= round( $shuriken_points * 100 / $shuriken0 ) ?>px;"></div>
			</div>
		</td>
		
		<th><?= $shuriken_points .'/'. $shuriken0 ?></th>
		
		<th><?= ( $up_shuriken > 0 ? '+'. $up_shuriken .' train' : '' ) ?></th>
		
	</tr>
	
	<tr>
		
		<th><?= ( $taijutsu0 - $taijutsui ? '+'. ( $taijutsu0 - $taijutsui ) : '' ) ?></th>
		
		<th>Taijutsu</th>
		
		<td>
			<div id="bp">
				<div id="bt" style="width: <?= round( $taijutsu_points * 100 / $taijutsu0 ) ?>px;"></div>
			</div>
		</td>
		
		<th><?= $taijutsu_points .'/'. $taijutsu0 ?></th>
		
		<th><?= ( $up_taijutsu > 0 ? '+'. $up_taijutsu .' train' : '' ) ?></th>
		
	</tr>
	
	<?php
	
	if ( $style_name0 != 'Tameru' )
	{
		?>
		<tr>
			
			<th><?= ( $ninjutsu0 - $ninjutsui ? '+'. ( $ninjutsu0 - $ninjutsui ) : '' ) ?></th>
			
			<th>Ninjutsu</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $ninjutsu_points * 100 / $ninjutsu0 ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $ninjutsu_points .'/'. $ninjutsu0 ?></th>
			
			<th><?= ( $up_ninjutsu > 0 ? '+'. $up_ninjutsu .' train' : '' ) ?></th>
			
		</tr>
		
		<tr>
			
			<th><?= ( $genjutsu0 - $genjutsui ? '+'. ( $genjutsu0 - $genjutsui ) : '' ) ?></th>
			
			<th>Genjutsu</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $genjutsu_points * 100 / $genjutsu0 ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $genjutsu_points .'/'. $genjutsu0 ?></th>
			
			<th><?= ( $up_genjutsu > 0 ? '+'. $up_genjutsu .' train' : '' ) ?></th>
			
		</tr>
		<?php
	}
	
	?>
</table>

<?php include("footer.php"); ?>