<?php

require_once 'headeron.php';

$ids = explode('-', array_search('Team Battle', $_POST));

extract( sql_mfa(
	$conn,
	'SELECT teammate1_id, teammate2_id, team_exam_phase, username, char_rank, style_name, skill_training
	FROM char_team        t
	JOIN game_users       u ON t.char_id = u.char_id
	JOIN style_attributes c ON u.char_id = c.char_id
	JOIN skill_training   s ON c.char_id = s.char_id
	WHERE u.char_id = '. $uid ) );

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

$members = sql_query(
	$conn,
	'SELECT char_level, c.*, username
	FROM char_attributes  a
	JOIN style_attributes c ON a.char_id = c.char_id
	JOIN game_users       u ON a.char_id = u.char_id
	WHERE a.char_id IN ('. $uid .', '. $teammate1_id .', '. $teammate2_id .')
	ORDER BY
		CASE a.char_id
			WHEN '. $uid .'          THEN 1
			WHEN '. $teammate1_id .' THEN 2
			WHEN '. $teammate2_id .' THEN 3
		END' );

$k = (intval( substr(microtime(true), -1) ) % 10) + 3;
$s = (intval( substr(microtime(true), -2, 1) ) % 10) + 3;
$t = (intval( substr(microtime(true), -3, 1) ) % 10) + 3;
$n = (intval( substr(microtime(true), -4, 1) ) % 10) + 3;

$g = (substr(time(), 0, -1) % 10) + 1;

$r = 125 / ( $k + $s + $t + $n + $g );

?>

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
	while ( $row = mysqli_fetch_assoc($members) )
	{
		${'char_level'. $i} = $row['char_level'];
		
		${'kenjutsu'. $i} = $row['kenjutsu'];
		${'shuriken'. $i} = $row['shuriken'];
		${'taijutsu'. $i} = $row['taijutsu'];
		${'ninjutsu'. $i} = $row['ninjutsu'];
		${'genjutsu'. $i} = $row['genjutsu'];
		
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
	
	$kenjutsu = $kenjutsu0 + $kenjutsu1 + $kenjutsu2;
	$shuriken = $shuriken0 + $shuriken1 + $shuriken2;
	$taijutsu = $taijutsu0 + $taijutsu1 + $taijutsu2;
	$ninjutsu = $ninjutsu0 + $ninjutsu1 + $ninjutsu2;
	$genjutsu = $genjutsu0 + $genjutsu1 + $genjutsu2;
	
	$total = $kenjutsu + $shuriken + $taijutsu + $ninjutsu + $genjutsu;
	
	if ( $total > 25 )
	{
		$ratio = ( 2 - (25 / $total) ) * 100 / $total;
	}
	else
	{
		$ratio = 100 / $total;
	}
	
	?>
	
	<tr><td colspan="5"></td></tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $kenjutsu * $ratio ) ?>px; float: right"></div>
		</td>
		
		<th>Kenjutsu</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $k * $r ) ?>px"></td>
		</tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $shuriken * $ratio ) ?>px; float: right"></div>
		</td>
		
		<th>Shuriken</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $s * $r ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $taijutsu * $ratio ) ?>px; float: right"></div>
		</td>
		
		<th>Taijutsu</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $t * $r ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $ninjutsu * $ratio ) ?>px; float: right"></div>
		</td>
		
		<th>Ninjutsu</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $n * $r ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $genjutsu * $ratio ) ?>px; float: right"></div>
		</td>
		
		<th>Genjutsu</th>
		
		<td colspan="2">
			<div id="ttd" style="width: <?= round( $g * $r ) ?>px"></div>
		</td>
	</tr>
	
</table>

<?php

if ( ( $char_level0 + $char_level1 + $char_level2 ) * ($total ** 2) > 6250 )
{
	?>
	<h4>
		Team exam is done.
		<br />
		A pvp battle will be prepared to proceed graduation
	</h4>
	<?php
	
	sql_query( $conn, "UPDATE char_team SET team_exam_phase = 1 WHERE char_id = $uid" );
}
else
{
	?>
	<h4>You didn't make it this time.</h4>
	<?php
}

$up_strength = round( 9 * $kenjutsu / $total );
$up_jutsu    = round( 9 * $shuriken / $total );
$up_agility  = round( 9 * $taijutsu / $total );
$up_flair    = round( 9 * $ninjutsu / $total );
$up_tactics  = round( 9 * $genjutsu / $total );

$train = 10 - ( $up_flair + $up_strength + $up_agility + $up_jutsu + $up_tactics );

switch ( $style_name )
{
	case 'Kensou': $up_strength += $train; break;
	case 'Surike': $up_jutsu    += $train; break;
	case 'Geniru': $up_tactics  += $train; break;
	
	case 'Tameru':
	case 'Tayuga':
		$up_agility += $train; break;
	
	case 'Faruni':
	case 'Wyroni':
	case 'Raiyni':
	case 'Rokuni':
	case 'Watoni':
		$up_flair += $train; break;
	
	default: echo 'switch_style Error'; break;
}

sql_query(
	$conn,
	"update char_attributes set
		char_level = char_level + 2,
		flair      = flair      + $up_flair,
		strength   = strength   + $up_strength,
		agility    = agility    + $up_agility,
		jutsu      = jutsu      + $up_jutsu,
		tactics    = tactics    + $up_tactics
	where char_id = ". $uid );

?>

<table align="center">
	
	<tr>
		<th colspan="2">
			Lv: <?= ($char_level0 + 2) ?>
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

<?php include("footer.php"); ?>