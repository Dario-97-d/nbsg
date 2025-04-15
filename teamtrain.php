<?php

include("headeron.php");

extract( sql_mfa(
	$conn,
	"SELECT nin1, nin2, rank, name, level, c.*
	FROM team t
	JOIN user u ON t.id = u.id
	JOIN atts a ON u.id = a.id
	JOIN clan c ON u.id = c.id
	WHERE u.id = $uid" ) );

if ( $nin1 < 1 || $nin2 < 1 ) exiter("team");

$nin_1 = $nin1;
$nin_2 = $nin2;

?>

<h1>Team <?= $name ?></h1>

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
		<td><?= $ken ?></td>
		<td><?= $shu ?></td>
		<td><?= $tai ?></td>
		<td><?= $nin ?></td>
		<td><?= $gen ?></td>
	</tr>
</table>

<h2>Rank-<?= $rank ?></h2>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
	<?php
	
	$getplayers = sql_query(
		$conn,
		"SELECT name, level, c.*
		FROM user u
		JOIN atts a ON u.id = a.id
		JOIN clan c on u.id = c.id
		WHERE u.id = $nin1
		OR u.id = $nin2
		ORDER BY level DESC
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
		
		$i = 1;
		while ( $row = mysqli_fetch_assoc($getplayers) )
		{
			$i++;
			?>
			<tr>
				
				<td><?= $row['style'] ?></td>
				
				<td><?= $row['level'] ?></td>
				
				<td>
					<a href="nin?id=<?= $row['id'] ?>">
						<?= $row['name'] ?>
					</a>
				</td>
				
				<th>
					<?=
					
					( ${'ken'. $i} = $row['ken'] )
					." • ".
					( ${'shu'. $i} = $row['shu'] )
					." • ".
					( ${'tai'. $i} = $row['tai'] )
					." • ".
					( ${'nin'. $i} = $row['nin'] )
					." • ".
					( ${'gen'. $i} = $row['gen'] )
					
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
	$ken + $ken1 + $ken2
	+
	$shu + $shu1 + $shu2
	+
	$tai + $tai1 + $tai2
	+
	$nin + $nin1 + $nin2
	+
	$gen + $gen1 + $gen2 );

echo
	( $ken + $ken1 + $ken2 )
	.' • '.
	( $shu + $shu1 + $shu2 )
	.' • '.
	( $tai + $tai1 + $tai2 )
	.' • '.
	( $nin + $nin1 + $nin2 )
	.' • '.
	( $gen + $gen1 + $gen2 );

?>

<br /><br />

<table class="table-team" align="center">
	
	<tr>
		<th>Kenjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $ken + $ken1 + $ken2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Shuriken</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $shu + $shu1 + $shu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Taijutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $tai + $tai1 + $tai2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Ninjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $nin + $nin1 + $nin2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Genjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $gen + $gen1 + $gen2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
</table>

<br />

<form action="teamtrainskill" method="POST">
	
	<input type="submit" name="<?= $nin_1 .'-'. $nin_2 ?>" value="Train" />
	
</form>

<?php include("footer.php"); ?>