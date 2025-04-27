<?php

require_once 'backend.php';

if ( ! isset( $_uid ) ) exiter('index');

$ids = explode('-', array_search('Team Battle', $_POST));

extract( sql_mfa(
	$conn,
	'SELECT teammate1_id, teammate2_id, team_exam_phase, username, char_rank, style_name, skill_training
	FROM char_team        t
	JOIN game_users       u ON t.char_id = u.char_id
	JOIN style_attributes c ON u.char_id = c.char_id
	JOIN skill_training   s ON c.char_id = s.char_id
	WHERE u.char_id = '. $_uid ) );

if (
	$char_rank != 'D'
	|| $team_exam_phase > 1
	|| $skill_training != ''
	|| $teammate1_id < 1
	|| $teammate2_id < 1
	|| $teammate1_id != $ids[0]
	|| $teammate2_id != $ids[1] )
{
	exiter("team");
}

$team_members = mysqli_fetch_all(
	sql_query(
		$conn,
		'SELECT char_level, c.*, username
		FROM char_attributes  a
		JOIN style_attributes c ON a.char_id = c.char_id
		JOIN game_users       u ON a.char_id = u.char_id
		WHERE a.char_id IN ('. $_uid .', '. $teammate1_id .', '. $teammate2_id .')
		ORDER BY
			CASE a.char_id
				WHEN '. $_uid .'          THEN 1
				WHEN '. $teammate1_id .' THEN 2
				WHEN '. $teammate2_id .' THEN 3
			END' ),
	MYSQLI_ASSOC );

$bots_kenjutsu = (intval( substr(microtime(true), -1) ) % 10) + 3;
$bots_shuriken = (intval( substr(microtime(true), -2, 1) ) % 10) + 3;
$bots_taijutsu = (intval( substr(microtime(true), -3, 1) ) % 10) + 3;
$bots_ninjutsu = (intval( substr(microtime(true), -4, 1) ) % 10) + 3;
$bots_genjutsu = (substr(time(), 0, -1) % 10) + 1;

$bots_bar_scale = 125 / (
	$bots_kenjutsu +
	$bots_shuriken +
	$bots_taijutsu +
	$bots_ninjutsu +
	$bots_genjutsu );

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

$team_total =
	$team_kenjutsu +
	$team_shuriken +
	$team_taijutsu +
	$team_ninjutsu +
	$team_genjutsu;

if ( $team_total > 25 )
{
	$team_bar_scale = ( 2 - (25 / $team_total) ) * 100 / $team_total;
}
else
{
	$team_bar_scale = 100 / $team_total;
}

$up_strength = round( 9 * $team_kenjutsu / $team_total );
$up_jutsu    = round( 9 * $team_shuriken / $team_total );
$up_agility  = round( 9 * $team_taijutsu / $team_total );
$up_flair    = round( 9 * $team_ninjutsu / $team_total );
$up_tactics  = round( 9 * $team_genjutsu / $team_total );

$train = 10 - ( $up_flair + $up_strength + $up_agility + $up_jutsu + $up_tactics );

switch ( $style_name )
{
	case 'Kensou': $up_strength += $train; break;
	case 'Surike': $up_jutsu    += $train; break;
	case 'Geniru': $up_tactics  += $train; break;
	
	case 'Tameru':
	case 'Tayuga':
		$up_agility += $train;
		break;
	
	case 'Faruni':
	case 'Wyroni':
	case 'Raiyni':
	case 'Rokuni':
	case 'Watoni':
		$up_flair += $train;
		break;
	
	default: echo 'switch_style Error'; break;
}

sql_query(
	$conn,
	'UPDATE char_attributes SET
		char_level = char_level + 2,
		flair      = flair      + '. $up_flair    .',
		strength   = strength   + '. $up_strength .',
		agility    = agility    + '. $up_agility  .',
		jutsu      = jutsu      + '. $up_jutsu    .',
		tactics    = tactics    + '. $up_tactics  .'
	WHERE char_id = '. $_uid );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team Exam</h1>

<table class="table-team" align="center" style="text-align: center;">
	
	<tr>
		<th width="33%">
			Team
			<br />
			<?= $username ?>
		</th>
		
		<th width="10%">Lv</th>
		<th width="14%">VS</th>
		<th width="10%">Lv</th>
		
		<th width="33%">
			Team
			<br />
			Botuzo
		</th>
	</tr>
	
	<?php
	
	$i = 0;
	foreach ( $team_members as $row )
	{
		$i++;
		?>
		<tr>
			
			<td><?= $row['username'] ?></td>
			
			<td><?= $row['char_level'] ?></td>
			
			<td></td>
			
			<td>10</td>
			
			<td>BotNin_<?= $i ?></td>
			
		</tr>
		<?php
	}
	
	?>
	
	<tr><td colspan="5"></td></tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $team_kenjutsu * $team_bar_scale ) ?>px; float: right"></div>
		</td>
		
		<th>Kenjutsu</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $bots_kenjutsu * $bots_bar_scale ) ?>px"></td>
		</tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $team_shuriken * $team_bar_scale ) ?>px; float: right"></div>
		</td>
		
		<th>Shuriken</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $bots_shuriken * $bots_bar_scale ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $team_taijutsu * $team_bar_scale ) ?>px; float: right"></div>
		</td>
		
		<th>Taijutsu</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $bots_taijutsu * $bots_bar_scale ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $team_ninjutsu * $team_bar_scale ) ?>px; float: right"></div>
		</td>
		
		<th>Ninjutsu</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $bots_ninjutsu * $bots_bar_scale ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $team_genjutsu * $team_bar_scale ) ?>px; float: right"></div>
		</td>
		
		<th>Genjutsu</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $bots_genjutsu * $bots_bar_scale ) ?>px"></div>
		</td>
	</tr>
	
</table>

<?php

if ( ( $team_members[0]['char_level'] + $team_members[1]['char_level'] + $team_members[2]['char_level'] ) * ($team_total ** 2) > 6250 )
{
	?>
	<h4>
		Team exam is done.
		<br />
		A pvp battle will be prepared to proceed graduation
	</h4>
	<?php
	
	sql_query( $conn, 'UPDATE char_team SET team_exam_phase = 1 WHERE char_id = '. $_uid );
}
else
{
	?>
	<h4>You didn't make it this time.</h4>
	<?php
}

?>

<table align="center">
	
	<tr>
		<th colspan="2">
			Lv: <?= ($team_members[0]['char_level'] + 2) ?>
		</th>
	</tr>
	
	<tr>
		<td title="Critical">Flair</td>
		
		<!--<td>
			<?= $p_flair ?>
		</td>-->
		
		<td>+<?= $up_flair ?></td>
	</tr>
	
	<tr>
		<td title="Strength">Power</td>
		
		<!--<td>
			<?= $p_strength ?>
		</td>-->
		
		<td>+<?= $up_strength ?></td>
	</tr>
	
	<tr>
		<td title="Reach">Speed</td>
		
		<!--<td>
			<?= $p_agility ?>
		</td>-->
		
		<td>+<?= $up_agility ?></td>
	</tr>
	
	<tr>
		<td title="Effect">Jutsu</td>
		
		<!--<td>
			<?= $p_jutsu ?>
		</td>-->
		
		<td>+<?= $up_jutsu ?></td>
	</tr>
	
	<tr>
		<td title="Planning">Tactics</td>
		
		<!--<td>
			<?= $p_tactics ?>
		</td>-->
		
		<td>+<?= $up_tactics ?></td>
	</tr>
	
</table>
