<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa('SELECT style_name FROM style_attributes WHERE char_id = '. $_uid) );

$is_user_in_clan = $style_name !== '';

if ( $is_user_in_clan )
{
	$clan_members = mysqli_fetch_all(
		sql_query(
			'SELECT u.char_id, char_rank, username, char_level
			FROM game_users       u
			JOIN char_attributes  a ON u.char_id = a.char_id
			JOIN style_attributes c ON u.char_id = c.char_id
			WHERE style_name = \''. $style_name .'\'
			ORDER BY char_rank, char_level DESC
			LIMIT 25' ),
		MYSQLI_ASSOC );
}

?>

<?php LAYOUT_wrap_onwards(); ?>

<?php

if ( $is_user_in_clan )
{
	?>
	
	<h1><?= $style_name ?></h1>
	
	<h4>Clan members gather in the village</h4>
	
	<h2>
		<a href="clan-train">Train</a>
	</h2>
	
	<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
		<tr>
			<th>rank</th>
			<th>Lv</th>
			<th>Nin</th>
			<th>Send</th>
		</tr>
		
		<?php
		
		foreach ( $clan_members as $row )
		{
			?>
			<tr<?= $_uid === $row['char_id'] ? ' style="outline: 1px solid #0033CC;"' : '' ?>>
				
				<td><?= $row['char_rank'] ?></td>
				
				<td><?= $row['char_level'] ?></td>
				
				<td>
					<a href="char-profile?id=<?= $row['char_id'] ?>">
						<?= $row['username'] ?>
					</a>
				</td>
				
				<td>
					<a href="mail-write?to=<?= $row['username'] ?>">PM</a>
				</td>
				
			</tr>
			<?php
		}
		
		?>
	</table>
	
	<?php
}
else
{
	?>
	
	<h1>Clan</h1>
	
	<p style="padding: 0 32px;">
		Clans determine char's lineage.
		<br />
		Each clan has a specific set of characteristics,
		<br />
		making them more suited to certain fighting styles.
		<br />
		As an example, all Tameru are incapable of manipulating chakra,
		<br />
		so they must resort to tools and close body combat.
		<br />
		However, they are capable of opening the body chakra gates,
		<br />
		so they have unusually high physical capabilities.
	</p>
	
	<h3>
		<a href="start-clan">Choose Clan</a>
	</h3>
	
	<?php
}

?>
