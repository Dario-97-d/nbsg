<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa(
	$conn,
	"SELECT a.*, c.*, username, char_rank
	FROM char_attributes  a
	JOIN style_attributes c ON a.char_id = c.char_id
	JOIN game_users       u ON a.char_id = u.char_id
	WHERE a.char_id = $_uid" ) );

if (
	in_array(
		$att = array_search('+', $_POST),
		[ 'flair', 'strength', 'agility', 'jutsu', 'tactics' ] )
	)
{
	if ( ($training_sessions_for_use - $sessions_needed_for_upgrade) < 0 )
	{
		echo "Not enough Training Sessions";
	}
	else
	{
		$training_sessions_for_use -= $sessions_needed_for_upgrade;
		$sessions_needed_for_upgrade += 1;
		$$att += 1;
		
		if (
			floor(
				( $flair + $strength + $agility + $jutsu + $tactics )
				/ 5 )
			> $char_level )
		{
			$uplv = 'char_level = char_level + 1, ';
			$char_level += 1;
		}
		else
		{
			$uplv = '';
		}
		
		sql_query(
			$conn,
			'UPDATE char_attributes SET
				'. $uplv .'
				sessions_needed_for_upgrade = sessions_needed_for_upgrade + 1,
				training_sessions_for_use   = '. $training_sessions_for_use .',
				'. $att .' = '. $att .' + 1
			WHERE char_id = '. $_uid );
	}
}

$disabled = $training_sessions_for_use - $sessions_needed_for_upgrade < 0 ? 'disabled' : '';

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $username ?></h1>

<h3><?= $style_name ?></h3>

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

<h3>
	<a href="char-train">Train</a>
</h3>

<table align="center">
	
	<tr>
		<th colspan="4" title="Average of stats">Rank-<?= $char_rank ?><br />Lv <?= $char_level ?></th>
	</tr>
	
	<tr>
		<th>Stat</th>
		
		<th colspan="2" width="50px">#</th>
		
		<td>Effect</td>
	</tr>
	
	<form method="POST">
		
		<tr>
			
			<th>Flair</th>
			
			<td style="text-align: right;"><?= $flair ?></td>
			
			<td>
				<input type="submit" name="flair" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Critical</td>
			
		</tr>
		
		<tr>
			
			<th>Power</th>
			
			<td style="text-align: right;"><?= $strength ?></td>
			
			<td>
				<input type="submit" name="strength" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Strength</td>
			
		</tr>
		
		<tr>
			
			<th>Speed</th>
			
			<td style="text-align: right;"><?= $agility ?></td>
			
			<td>
				<input type="submit" name="agility" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Reach</td>
			
		</tr>
		
		<tr>
			
			<th>Jutsu</th>
			
			<td style="text-align: right;"><?= $jutsu ?></td>
			
			<td>
				<input type="submit" name="jutsu" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Skill</td>
			
		</tr>
		
		<tr>
			
			<th>Tactics</th>
			
			<td style="text-align: right;"><?= $tactics ?></td>
			
			<td>
				<input type="submit" name="tactics" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Planning</td>
			
		</tr>
		
	</form>
	
	<tr>
		<td style="text-align: right">Need:</td>
		
		<th><?= $sessions_needed_for_upgrade ?></th>
		
		<td colspan="2">Stats: <?= $training_sessions_for_use ?>/50</td>
	</tr>
	
</table>
