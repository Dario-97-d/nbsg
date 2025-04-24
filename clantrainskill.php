<?php

require_once 'headeron.php';

if (
	! is_int( $pid = array_search('Train', $_POST) )
	&&
	! (
		isset($_POST['pick']) && $pid = intval($_POST['pick']) )
	)
{
	exiter('clantrain');
}

if ( $uid == $pid ) exiter("nin?id=$pid");

$skills = array(
	'kenjutsu' => 'Kenjutsu',
	'shuriken' => 'Shuriken',
	'taijutsu' => 'Taijutsu',
	'ninjutsu' => 'Ninjutsu',
	'genjutsu' => 'Genjutsu' );

if ( ! in_array($_POST['skill'], $skills) ) exiter("nin?id=$pid");

$skill_to_train = array_search( $skill = $_POST['skill'], $skills );

extract(
	sql_mfa(
		$conn,
		'SELECT a.*, c.*, username, char_rank, s.*
		FROM char_attributes  a
		JOIN style_attributes c ON a.char_id = c.char_id
		JOIN game_users       u ON a.char_id = u.char_id
		JOIN skill_training   s ON a.char_id = s.char_id
		WHERE a.char_id = '. $uid ),
	EXTR_PREFIX_ALL, 'u' );

extract(
	sql_mfa(
		$conn,
		'SELECT char_level, c.*, username, char_rank
		FROM char_attributes  a
		JOIN style_attributes c ON a.char_id = c.char_id
		JOIN game_users       u ON a.char_id = u.char_id
		WHERE a.char_id = '. $pid ),
	EXTR_PREFIX_ALL, 'p' );

if (
	$u_skill_points < 5               ||
	$u_char_level - $p_char_level > 5 ||
	$p_char_level - $u_char_level > 5 ||
	$u_style_name != $p_style_name    ||
	$u_char_rank  != $p_char_rank )
{
	exiter("nin?id=$pid");
}

$atts = array(
	'flair'    => 'Flair',
	'strength' => 'Power',
	'agility'  => 'Speed',
	'jutsu'    => 'Jutsu',
	'tactics'  => 'Tactics' );

$skills_to_atts = array(
	'ninjutsu' => 'flair',
	'kenjutsu' => 'strength',
	'taijutsu' => 'agility',
	'shuriken' => 'jutsu',
	'genjutsu' => 'tactics' );

$att = $skills_to_atts[$skill_to_train];

$p_skill_to_train = 'p_'. $skill_to_train;
$u_skill_to_train = 'u_'. $skill_to_train;
$u_skill_to_train_points = 'u_'. $skill_to_train .'_points';

$result = ( $u_char_level * ( $$u_skill_to_train ** 2 ) ) / ( $p_char_level * ( $$p_skill_to_train ** 2) ) * 32;

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

$a_up = $p_char_level > $u_char_level ? 1 : 0;

if (
	floor(
		( $u_flair + $u_strength + $u_agility + $u_jutsu + $u_tactics + $a_up )
		/ 5 )
	> $u_char_level )
{
	$uplv = 'char_level = char_level + 1, ';
	$u_char_level += 1;
}
else $uplv = '';

$$u_skill_to_train_points += $t_up;
$up = 0;

while ( $$u_skill_to_train_points >= $$u_skill_to_train )
{
	$$u_skill_to_train_points -= $$u_skill_to_train;
	$$u_skill_to_train += 1;
	$up++;
}

sql_query(
	$conn,
	'UPDATE char_attributes SET
		'. $uplv .'
		'. $att .' = '. $att .' + '. $a_up .'
	WHERE char_id = '. $uid );

sql_query(
	$conn,
	'UPDATE style_attributes SET
		'. ( $up > 0 ?
			$skill_to_train .' = '. $skill_to_train .' + '. $up .', '
		: '' ) .'
		skill_points = skill_points - 5
	WHERE char_id = '. $uid);

sql_query(
	$conn,
	'UPDATE skill_training SET
		'.$skill_to_train .'_points' .' = '. $$u_skill_to_train_points .'
	WHERE char_id = '. $uid );

?>

<h1><?= $u_style_name ?></h1>

<table align="center" style="text-align: center;">
	
	<tr>
		<th width="33%"><?= $u_username ?></th>
		<th width="33%"></th>
		<th width="33%"><?= $p_username ?></th>
	</tr>
	
	<tr>
		<th><?=  $u_char_level ?></th>
		<th>Lv</th>
		<th><?=  $p_char_level ?></th>
	</tr>
	
	<tr>
		<th><?= $u_kenjutsu ?> • <?= $u_shuriken ?> • <?= $u_taijutsu ?> • <?= $u_ninjutsu ?> • <?= $u_genjutsu ?></th>
		<th>JUTSU</th>
		<th><?= $p_kenjutsu ?> • <?= $p_shuriken ?> • <?= $p_taijutsu ?> • <?= $p_ninjutsu ?> • <?= $p_genjutsu ?></th>
	</tr>
	
</table>

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
		<td><?= $u_kenjutsu ?></td>
		<td><?= $u_shuriken ?></td>
		<td><?= $u_taijutsu ?></td>
		<td><?= $u_ninjutsu ?></td>
		<td><?= $u_genjutsu ?></td>
	</tr>
</table>

<br />

<table id="table-train" align="center" cellspacing="3">
	<tr>
		
		<?= ( $up > 0 ? "<th>+ $up</th>" : '' ) ?>
		
		<th><?= $skill ?></th>
		
		<td>
			<div id="bp">
				<div id="bt" style="width: <?= round( $$u_skill_to_train_points * 100 / $$u_skill_to_train ) ?>px;"></div>
			</div>
		</td>
		
		<th><?= $$u_skill_to_train_points .'/'. ${'u_'. $skill_to_train} ?></th>
		
		<th><?= "+$t_up train" ?></th>
	
	</tr>
</table>

<table align="center">
	
	<tr>
		<th colspan="3" title="Average of stats">Lv <?= $u_char_level ?></th>
	</tr>
	
	<tr>
		<th>Stat</th>
		<th width="50px">#</th>
		<td>Effect</td>
	</tr>
	
	<form method="POST">
		
		<tr>
			<th>Flair</th>
			<th><?= $u_flair ?></td>
			<td>Critical</td>
		</tr>
		
		<tr>
			<th>Power</th>
			<th><?= $u_strength ?></td>
			<td>Strength</td>
		</tr>
		
		<tr>
			<th>Speed</th>
			<th><?= $u_agility ?></td>
			<td>Reach</td>
		</tr>
		
		<tr>
			<th>Jutsu</th>
			<th><?= $u_jutsu ?></td>
			<td>Skill</td>
		</tr>
		
		<tr>
			<th>Tactics</th>
			<th><?= $u_tactics ?></td>
			<td>Planning</td>
		</tr>
		
	</form>
	
</table>

<?php

echo ( $uplv == '' ? '' : "Level UP <br />" ) . ( $a_up == 1 ? '+1 '. $atts[$att] : '' );

include("footer.php");

?>