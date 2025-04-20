<?php

require_once 'headeron.php';

extract( sql_mfa(
	$conn,
	"SELECT teammate1_id, teammate2_id, username, char_rank
	FROM char_team        t
	JOIN game_users       u ON t.char_id = u.char_id
	JOIN style_attributes c ON u.char_id = c.char_id
	WHERE u.char_id = $uid" ) );

if ( $teammate1_id < 1 || $teammate2_id < 1 ) exiter("team");

$members = sql_query(
	$conn,
	"SELECT char_level, c.*, username
	FROM char_attributes  a
	JOIN style_attributes c ON a.char_id = c.char_id
	JOIN game_users       u ON a.char_id = u.char_id
	WHERE a.char_id IN ($uid, $teammate1_id, $teammate2_id)
	ORDER BY
		CASE a.char_id
			WHEN $uid          THEN 1
			WHEN $teammate1_id THEN 2
			WHEN $teammate2_id THEN 3
		END" );

$nin_1 = $teammate1_id;
$nin_2 = $teammate2_id;

?>

<h1>
	Team
	<br />
	<?= $username ?>
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
			
			<td><?= $row['style_name'] ?></td>
			
			<td>
				<a href="nin?id=<?= $row['char_id'] ?>">
					<?= $row['username'] ?>
				</a>
			</td>
			
			<td><?= $row['char_level'] ?></td>
			
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
		
		$i++;
	}
	
	?>
</table>

<h3>
	Joint Skills
	<br />
	<?=
		( $kenjutsu0 + $kenjutsu1 + $kenjutsu2 )
		.' • '.
		( $shuriken0 + $shuriken1 + $shuriken2 )
		.' • '.
		( $taijutsu0 + $taijutsu1 + $taijutsu2 )
		.' • '.
		( $ninjutsu0 + $ninjutsu1 + $ninjutsu2 )
		.' • '.
		( $genjutsu0 + $genjutsu1 + $genjutsu2 )
	?>
</h3>

<?php

$ratio = 253 / (
	$kenjutsu0 + $kenjutsu1 + $kenjutsu2
	+
	$shuriken0 + $shuriken1 + $shuriken2
	+
	$taijutsu0 + $taijutsu1 + $taijutsu2
	+
	$ninjutsu0 + $ninjutsu1 + $ninjutsu2
	+
	$genjutsu0 + $genjutsu1 + $genjutsu2 );

?>

<table class="table-team" align="center">
	
	<tr>
		<th>Kenjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $kenjutsu0 + $kenjutsu1 + $kenjutsu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Shuriken</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $shuriken0 + $shuriken1 + $shuriken2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Taijutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $taijutsu0 + $taijutsu1 + $taijutsu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Ninjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $ninjutsu0 + $ninjutsu1 + $ninjutsu2 ) * $ratio ) ?>px"></div>
		</td>
	</tr>
	
	<tr>
		<th>Genjutsu</th>
		
		<td>
			<div id="ttd" style="width: <?= round( ( $genjutsu0 + $genjutsu1 + $genjutsu2 ) * $ratio ) ?>px"></div>
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