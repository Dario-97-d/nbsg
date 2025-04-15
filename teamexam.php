<?php

include("headeron.php");

extract( sql_mfa(
	$conn,
	"SELECT nin1, nin2, name, rank
	FROM team t
	JOIN user u ON t.id = u.id
	JOIN clan c ON u.id = c.id
	WHERE u.id = $uid" ) );

if ( $nin1 < 1 || $nin2 < 1 ) exiter("team");

$members = sql_query(
	$conn,
	"SELECT level, c.*, name
	FROM atts a
	JOIN clan c ON a.id = c.id
	JOIN user u ON a.id = u.id
	WHERE a.id IN ($uid, $nin1, $nin2)
	ORDER BY
		CASE a.id
			WHEN $uid THEN 1
			WHEN $nin1 THEN 2
			WHEN $nin2 THEN 3
		END" );

$nin_1 = $nin1;
$nin_2 = $nin2;

?>

<h1>
	Team
	<br />
	<?= $name ?>
</h1>

<table align="center" style="text-align: center" cellpadding="8" cellspacing="0">
	<tr>
		<th>Clan</th>
		<th>Nin</th>
		<th>Lv</th>
		<th>Jutsu</th>
	</tr>
	
	<?php
	
	$i = 0;
	while ( $row = mysqli_fetch_assoc($members) )
	{
		?>
		<tr>
			
			<td><?= $row['style'] ?></td>
			
			<td>
				<a href="nin?id=<?= $row['id'] ?>">
					<?= $row['name'] ?>
				</a>
			</td>
			
			<td><?= $row['level'] ?></td>
			
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
		
		$i++;
	}
	
	?>
</table>

<h3>
	Joint Skills
	<br />
	<?=
		( $ken0 + $ken1 + $ken2 )
		.' • '.
		( $shu0 + $shu1 + $shu2 )
		.' • '.
		( $tai0 + $tai1 + $tai2 )
		.' • '.
		( $nin0 + $nin1 + $nin2 )
		.' • '.
		( $gen0 + $gen1 + $gen2 )
	?>
</h3>

<?php

$ratio = 253 / (
	$ken0 + $ken1 + $ken2
	+
	$shu0 + $shu1 + $shu2
	+
	$tai0 + $tai1 + $tai2
	+
	$nin0 + $nin1 + $nin2
	+
	$gen0 + $gen1 + $gen2 );

?>

<table class="table-team" align="center">
	
	<tr>
		<th>Kenjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $ken0 + $ken1 + $ken2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Shuriken</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $shu0 + $shu1 + $shu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Taijutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $tai0 + $tai1 + $tai2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Ninjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $nin0 + $nin1 + $nin2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Genjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $gen0 + $gen1 + $gen2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
</table>

<br />

<form action="teamexamjoint" method="POST">
	
	<input type="submit" name="<?= $nin_1 .'-'. $nin_2 ?>" value="Team Battle" />
	
</form>

3v3 battle
<br />
standard bots

<?php include("footer.php"); ?>