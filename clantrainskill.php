<?php

include("headeron.php");

if ( ! is_int( $pid = array_search('Train', $_POST) ) ) exiter("clantrain");

if ( $uid == $pid ) exiter("nin?id=$pid");

$skills = array(
	'ken' => 'Kenjutsu',
	'shu' => 'Shuriken',
	'tai' => 'Taijutsu',
	'nin' => 'Ninjutsu',
	'gen' => 'Genjutsu' );

if ( ! in_array($_POST['skill'], $skills) ) exiter("nin?id=$pid");

$skl = array_search( $skill = $_POST['skill'], $skills );

extract(
	sql_mfa(
		$conn,
		"SELECT a.*, c.*, name, rank, s.*
		FROM atts a
		JOIN clan c ON a.id = c.id
		JOIN user u ON a.id = u.id
		JOIN styl s ON a.id = s.id
		WHERE a.id = $uid" ),
	EXTR_PREFIX_ALL, "u" );

extract(
	sql_mfa(
		$conn,
		"SELECT level ,c.*, name, rank
		FROM atts a
		JOIN clan c ON a.id = c.id
		JOIN user u ON a.id = u.id
		WHERE a.id = $pid" ),
	EXTR_PREFIX_ALL, "p" );

if (
	$u_skp < 5              ||
	$u_level - $p_level > 5 ||
	$p_level - $u_level > 5 ||
	$u_style != $p_style    ||
	$u_rank  != $p_rank )
{
	exiter("nin?id=$pid");
}

$result = ( $u_level * ( ${'u_'. $skl} ** 2 ) ) / ( $p_level * ( ${'p_'. $skl} ** 2) ) * 32;

$atts = array(
	'fla' => 'Flair',
	'pow' => 'Power',
	'agi' => 'Speed',
	'jut' => 'Jutsu',
	'tac' => 'Tactics' );

$sklatts = array(
	'nin' => 'fla',
	'ken' => 'pow',
	'tai' => 'agi',
	'shu' => 'jut',
	'gen' => 'tac' );

$att = $sklatts[$skl];
$tskl = "u_t". $skl;

switch (true)
{
	case ( ! is_numeric($result) ):
		echo "switch_result Error";
		break;
	
	case ( $result < 16 || $result >= 48 ): $t_up = 0; break;
	case ( $result < 24 || $result >= 40 ): $t_up = 1; break;
	case ( $result < 28 || $result >= 36 ): $t_up = 2; break;
	case ( $result < 30 || $result >= 34 ): $t_up = 3; break;
	case ( $result < 31 || $result >= 33 ): $t_up = 4; break;
	
	default: $t_up = 5; break;
}

?>

<h1><?= $u_style ?></h1>

<table align="center" style="text-align: center;">
	
	<tr>
		<th width="33%"><?= $u_name ?></th>
		<th width="33%"></th>
		<th width="33%"><?= $p_name ?></th>
	</tr>
	
	<tr>
		<th><?=  $u_level ?></th>
		<th>Lv</th>
		<th><?=  $p_level ?></th>
	</tr>
	
	<tr>
		<th><?= $u_ken ?> • <?= $u_shu ?> • <?= $u_tai ?> • <?= $u_nin ?> • <?= $u_gen ?></th>
		<th>JUTSU</th>
		<th><?= $p_ken ?> • <?= $p_shu ?> • <?= $p_tai ?> • <?= $p_nin ?> • <?= $p_gen ?></th>
	</tr>
	
</table>

<?php

$a_up = $p_level > $u_level ? 1 : 0;

if (
	floor(
		( $u_fla + $u_pow + $u_agi + $u_jut + $u_tac + $a_up )
		/ 5 )
	> $u_level )
{
	$uplv = 'level = level + 1, ';
	$u_level += 1;
}
else $uplv = '';

$$tskl += $t_up;
$up = 0;

while ( $$tskl >= ${'u_'. $skl} )
{
	$$tskl -= ${'u_'. $skl}; ${'u_'. $skl} += 1;
	$up++;
}

sql_query( $conn, "UPDATE atts SET $uplv $att = $att + $a_up WHERE id=$uid");
sql_query( $conn, "UPDATE clan SET ". ( $up > 0 ? "$skl = $skl + $up, " : '' ) ."skp = skp - 5 WHERE id = $uid");
sql_query( $conn, "UPDATE styl SET t$skl = ". $$tskl ." WHERE id = $uid" );

?>

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
		<td><?= $u_ken ?></td>
		<td><?= $u_shu ?></td>
		<td><?= $u_tai ?></td>
		<td><?= $u_nin ?></td>
		<td><?= $u_gen ?></td>
	</tr>
</table>

<br />

<table id="table-train" align="center" cellspacing="3">
	<tr>
		
		<?= ( $up > 0 ? "<th>+ $up</th>" : '' ) ?>
		
		<th><?= $skill ?></th>
		
		<td>
			<div id="bp">
				<div id="bt" style="width: <?= round( $$tskl * 100 / ${'u_'. $skl} ) ?>px;"></div>
			</div>
		</td>
		
		<th><?= $$tskl .'/'. ${'u_'. $skl} ?></th>
		
		<th><?= "+$t_up train" ?></th>
	
	</tr>
</table>

<table align="center">
	
	<tr>
		<th colspan="3" title="Average of stats">Lv <?= $u_level ?></th>
	</tr>
	
	<tr>
		<th>Stat</th>
		<th width="50px">#</th>
		<td>Effect</td>
	</tr>
	
	<form method="POST">
		
		<tr>
			<th>Flair</th>
			<th><?= $u_fla ?></td>
			<td>Critical</td>
		</tr>
		
		<tr>
			<th>Power</th>
			<th><?= $u_pow ?></td>
			<td>Strength</td>
		</tr>
		
		<tr>
			<th>Speed</th>
			<th><?= $u_agi ?></td>
			<td>Reach</td>
		</tr>
		
		<tr>
			<th>Jutsu</th>
			<th><?= $u_jut ?></td>
			<td>Skill</td>
		</tr>
		
		<tr>
			<th>Tactics</th>
			<th><?= $u_tac ?></td>
			<td>Planning</td>
		</tr>
		
	</form>
	
</table>

<?php

echo ( $uplv == '' ? '' : "Level UP <br />" ) . ( $a_up == 1 ? '+1 '. $atts[$att] : '' );

include("footer.php");

?>