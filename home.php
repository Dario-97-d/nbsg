<?php

include("headeron.php");

extract( sql_mfa(
	$conn,
	"SELECT a.*, c.*, name, rank
	FROM atts a
	JOIN clan c ON a.id = c.id
	JOIN user u ON a.id = u.id
	WHERE a.id = '$uid'" ) );

if (
	in_array(
		$att = array_search('+', $_POST),
		[ 'fla', 'pow', 'agi', 'jut', 'tac' ] )
	)
{
	if ( ($tss - $need) < 0 )
	{
		echo "Not enough Training Sessions";
	}
	else
	{
		$tss -= $need;
		$need += 1;
		$$att += 1;
		
		if (
			floor(
				( $fla + $pow + $agi + $jut + $tac )
				/ 5 )
			> $level )
		{
			$uplv = 'level = level + 1, ';
			$level += 1;
		}
		else
		{
			$uplv = '';
		}
		
		sql_query( $conn, "UPDATE atts SET $uplv need = need + 1, tss = $tss, $att = $att + 1 WHERE id = $uid" );
	}
}

$disabled = $tss - $need < 0 ? 'disabled' : '';

?>

<h1><?= $name ?></h1>

<h3><?= $style ?></h3>

<table class="table-skill" align="center">
	<tr>
		<th title="Sword Skill">kenjutsu</th>
		<th title="Shuriken Skill">shuriken</th>
		<th title="Melee Skill">taijutsu</th>
		<th title="Elemental Skill">ninjutsu</th>
		<th title="Illusion Skill">genjutsu</th>
	</tr>
	
	<tr>
		<td><?= $ken ?></td>
		<td><?= $shu ?></td>
		<td><?= $tai ?></td>
		<td><?= $nin ?></td>
		<td><?= $gen ?></td>
	</tr>
</table>

<h3>
	<a href="hometrain">Train</a>
</h3>

<table align="center">
	
	<tr>
		<th colspan="4" title="Average of stats">Rank-<?= $rank ?><br />Lv <?= $level ?></th>
	</tr>
	
	<tr>
		<th>Stat</th>
		
		<th colspan="2" width="50px">#</th>
		
		<td>Effect</td>
	</tr>
	
	<form method="POST">
		
		<tr>
			
			<th>Flair</th>
			
			<td style="text-align: right;"><?= $fla ?></td>
			
			<td>
				<input type="submit" name="fla" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Critical</td>
			
		</tr>
		
		<tr>
			
			<th>Power</th>
			
			<td style="text-align: right;"><?= $pow ?></td>
			
			<td>
				<input type="submit" name="pow" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Strength</td>
			
		</tr>
		
		<tr>
			
			<th>Speed</th>
			
			<td style="text-align: right;"><?= $agi ?></td>
			
			<td>
				<input type="submit" name="agi" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Reach</td>
			
		</tr>
		
		<tr>
			
			<th>Jutsu</th>
			
			<td style="text-align: right;"><?= $jut ?></td>
			
			<td>
				<input type="submit" name="jut" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Skill</td>
			
		</tr>
		
		<tr>
			
			<th>Tactics</th>
			
			<td style="text-align: right;"><?= $tac ?></td>
			
			<td>
				<input type="submit" name="tac" value="+" <?= $disabled ?>/>
			</td>
			
			<td>Planning</td>
			
		</tr>
		
	</form>
	
	<tr>
		<td style="text-align: right">Need:</td>
		
		<th><?= $need ?></th>
		
		<td colspan="2">Stats: <?= $tss ?>/50</td>
	</tr>
	
</table>

<?php include("footer.php"); ?>