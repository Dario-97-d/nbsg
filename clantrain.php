<?php

require_once 'backend.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa(
	$conn,
	'SELECT char_rank, char_level, c.*
	FROM game_users       u
	JOIN char_attributes  a ON u.char_id = a.char_id
	JOIN style_attributes c ON u.char_id = c.char_id
	WHERE u.char_id = '. $_uid ) );

if ( $style_name === '' ) exiter('clan');

$clan_members_to_train_with = mysqli_fetch_all(
	sql_query(
		$conn,
		'SELECT username, char_level, c.*
		FROM game_users       u
		JOIN char_attributes  a ON u.char_id = a.char_id
		JOIN style_attributes c on u.char_id = c.char_id
		WHERE style_name = \''. $style_name .'\'
		AND   char_rank  = \''. $char_rank  .'\'
		AND   char_level BETWEEN '. $char_level .' - 5 AND '. $char_level .' + 5
		AND   u.char_id <> '. $_uid .'
		ORDER BY char_level DESC
		LIMIT 25' ),
	MYSQLI_ASSOC );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $style_name ?></h1>

<h4>
	In the training grounds of the village
	<br />
	nin from the clan train their skills
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

<a href="hometrain">Train alone</a>

<h2>Rank-<?= $char_rank ?></h2>

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
	
	<input type="submit" value="Train" />
	
	<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
		<?php
		
		if ( empty( $clan_members_to_train_with ) )
		{
			?>
			There's no nin to train with
			<?php
		}
		else
		{
			?>
			<tr>
				<th>Lv</th>
				<th>Nin</th>
				<th>Jutsu</th>
				<th>Select</th>
			</tr>
			<?php
			
			foreach ( $clan_members_to_train_with as $row )
			{
				?>
				<tr>
					
					<td><?= $row['char_level']?></td>
					
					<td>
						<a href="nin?id=<?= $row['char_id'] ?>">
							<?= $row['username'] ?>
						</a>
					</td>
					
					<th><?= $row['kenjutsu'] ?> • <?= $row['shuriken'] ?> • <?= $row['taijutsu'] ?> • <?= $row['ninjutsu'] ?> • <?= $row['genjutsu'] ?></th>
					
					<td>
						<input type="radio" name="pick" value="<?= $row['char_id'] ?>" />
					</td>
					
				</tr>
				<?php
			}
		}
		
		?>
	</table>
	
</form>
