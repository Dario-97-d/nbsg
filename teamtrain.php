<?php

require_once 'headeron.php';

extract( sql_mfa(
	$conn,
	'SELECT teammate1_id, teammate2_id, char_rank, username, char_level, c.*
	FROM char_team        t
	JOIN game_users       u ON t.char_id = u.char_id
	JOIN char_attributes  a ON u.char_id = a.char_id
	JOIN style_attributes c ON u.char_id = c.char_id
	WHERE u.char_id = '. $uid ) );

if ( $teammate1_id < 1 || $teammate2_id < 1 ) exiter("team");

$nin_1 = $teammate1_id;
$nin_2 = $teammate2_id;

?>

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
	<?php
	
	$getplayers = sql_query(
		$conn,
		"SELECT username, char_level, c.*
		FROM game_users       u
		JOIN char_attributes  a ON u.char_id = a.char_id
		JOIN style_attributes c on u.char_id = c.char_id
		WHERE u.char_id = $teammate1_id
		OR    u.char_id = $teammate2_id
		ORDER BY char_level DESC
		LIMIT 25" );
	
	if ( mysqli_num_rows($getplayers) < 1 )
	{
		echo "There's no nin to train with";
	}
	else
	{
		?>
		<tr>
			<th>Clan</th>
			<th>Lv</th>
			<th>Nin</th>
			<th>Jutsu</th>
		</tr>
		
		<?php
		
		$i = 0;
		while ( $row = mysqli_fetch_assoc($getplayers) )
		{
			$i++;
			?>
			<tr>
				
				<td><?= $row['style_name'] ?></td>
				
				<td><?= $row['char_level'] ?></td>
				
				<td>
					<a href="nin?id=<?= $row['char_id'] ?>">
						<?= $row['username'] ?>
					</a>
				</td>
				
				<th>
					<?=
					
					( ${'kenjutsu'. $i} = $row['kenjutsu'] )
					." • ".
					( ${'shuriken'. $i} = $row['shuriken'] )
					." • ".
					( ${'taijutsu'. $i} = $row['taijutsu'] )
					." • ".
					( ${'ninjutsu'. $i} = $row['ninjutsu'] )
					." • ".
					( ${'genjutsu'. $i} = $row['genjutsu'] )
					
					?>
				</th>
				
			</tr>
			<?php
		}
	}
	
	?>
</table>

<h3>Joint Skills</h3>

<?php

$ratio = 253 / (
	$kenjutsu + $kenjutsu1 + $kenjutsu2
	+
	$shuriken + $shuriken1 + $shuriken2
	+
	$taijutsu + $taijutsu1 + $taijutsu2
	+
	$ninjutsu + $ninjutsu1 + $ninjutsu2
	+
	$genjutsu + $genjutsu1 + $genjutsu2 );

echo
	( $kenjutsu + $kenjutsu1 + $kenjutsu2 )
	.' • '.
	( $shuriken + $shuriken1 + $shuriken2 )
	.' • '.
	( $taijutsu + $taijutsu1 + $taijutsu2 )
	.' • '.
	( $ninjutsu + $ninjutsu1 + $ninjutsu2 )
	.' • '.
	( $genjutsu + $genjutsu1 + $genjutsu2 );

?>

<br /><br />

<table class="table-team" align="center">
	
	<tr>
		<th>Kenjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $kenjutsu + $kenjutsu1 + $kenjutsu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Shuriken</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $shuriken + $shuriken1 + $shuriken2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Taijutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $taijutsu + $taijutsu1 + $taijutsu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Ninjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $ninjutsu + $ninjutsu1 + $ninjutsu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Genjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $genjutsu + $genjutsu1 + $genjutsu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
</table>

<br />

<form action="teamtrainskill" method="POST">
	
	<input type="submit" name="<?= $nin_1 .'-'. $nin_2 ?>" value="Train" />
	
</form>

<?php include("footer.php"); ?>