<?php

require_once 'backend.php';

if ( ! isset( $_uid ) ) exiter('index');

if (
	! isset($_GET['id'])
	||
	! ctype_digit( $pid = $_GET['id']) )
{
	$pid = $_uid;
}

extract( sql_mfa(
	$conn,
	'SELECT char_level, style_name, skill_points, skill_training
	FROM char_attributes  a
	JOIN style_attributes c ON a.char_id = c.char_id
	JOIN skill_training   s ON a.char_id = s.char_id
	WHERE a.char_id = '. $_uid ) );

extract(
	sql_mfa(
		$conn,
		'SELECT a.*, c.*, username, char_rank
		FROM char_attributes  a
		JOIN style_attributes c ON a.char_id = c.char_id
		JOIN game_users       u ON a.char_id = u.char_id
		WHERE u.char_id = '. $pid ),
	EXTR_PREFIX_ALL, 'p' );

$can_train_with_nin =
	$skill_points > 4
	&&
	$pid != $_uid
	&&
	$skill_training == ''
	&&
	$style_name === $p_style_name
	&& (
		$char_level - $p_char_level <= 5
		&&
		$p_char_level - $char_level <= 5 )

?>

<?php require_once 'header.php'; ?>

<h1><?= $p_username ?></h1>

<h3><?= $p_style_name ?></h3>

<?php

if ( $_uid != $pid )
{
	?>
	<h3>
		<a href="sendpm?to=<?= $p_username ?>">pm</a>
	</h3>
	<?php
}

?>

<table class="table-skill" align="center">
	<tr>
		<th title="Sword Skill">kenjutsu</th>
		<th title="Shuriken Skill">shuriken</th>
		<th title="Melee Skill">taijutsu</th>
		<th title="Elemental Skill">ninjutsu</th>
		<th title="Illusion Skill">genjutsu</th>
	</tr>
	
	<tr>
		<td><?= $p_kenjutsu ?></td>
		<td><?= $p_shuriken ?></td>
		<td><?= $p_taijutsu ?></td>
		<td><?= $p_ninjutsu ?></td>
		<td><?= $p_genjutsu ?></td>
	</tr>
</table>

<br />

<b>Skill Points: <?= $skill_points ?> / 5</b>

<?php

if ( $can_train_with_nin )
{
	?>
	<form action="clantrainskill" method="POST">
		<select class="select-skill" name="skill">
			<option hidden>-- skill --</option>
			
			<option>Kenjutsu</option>
			<option>Shuriken</option>
			<option>Taijutsu</option>
			<?php
			
			if ( $style_name !== 'Tameru' )
			{
				?>
				<option>Ninjutsu</option>
				<option>Genjutsu</option>
				<?php
			}
			
			?>
		</select>
		
		<input type="submit" name="<?= $pid ?>" value="Train" />
	</form>
	<?php
}

?>

<p>
	Rank-<?= $p_char_rank ?>
	<br />
	<b title="Average of stats">Lv <?= $p_char_level ?></b>
</p>

<?php

//if ( in_bonds )
if ( true )
{
	?>
	<table align="center">
		
		<tr>
			<td title="Critical">Flair</td>
			
			<td><?= $p_flair ?></td>
		</tr>
		
		<tr>
			<td title="Strength">Power</td>
			
			<td><?= $p_strength ?></td>
		</tr>
		
		<tr>
			<td title="Reach">Speed</td>
			
			<td><?= $p_agility ?></td>
		</tr>
		
		<tr>
			<td title="Effect">Jutsu</td>
			
			<td><?= $p_jutsu ?></td>
		</tr>
		
		<tr>
			<td title="Planning">Tactics</td>
			
			<td><?= $p_tactics ?></td>
		</tr>
		
	</table>
	<?php
}

?>

<br />pvp wins
<br />
<br />missions:
<br />patrol - anbu

<?php require_once 'footer.php'; ?>