<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa("SELECT * FROM style_attributes WHERE char_id = $_uid") );

if ( $style_name != '' ) exiter('clan-hall');

if ( ! isset($_POST['xc']) ) exiter('start-clan');

$clan = (
	in_array(
		$_POST['xc'],
		array(
			'Tameru',
			'Tayuga',
			'Kensou',
			'Surike',
			'Geniru',
			'Faruni',
			'Wyroni',
			'Raiyni',
			'Rokuni',
			'Watoni' ) )
	? $_POST['xc']
	: 'shit' );
	
if ( $clan == 'shit' ) exiter('start-clan');

if ( isset($_POST['start']) )
{
	$skills = explode(',', $_POST['skills']);
	
	if ( $skills[5] == 0)
	{
		unset($skills[5]);
		
		if (
			array_sum($skills) != 10
			|| ( $clan == 'Tameru' &&
				( $skills[3] > 0 || $skills[4] > 0 ) ) )
		{
			exiter('start-clan');
		}
		else
		{
			mysqli_multi_query(
				$_CONN,
				"UPDATE style_attributes
				SET
					style_name = '$clan',
					kenjutsu = $skills[0],
					shuriken = $skills[1],
					taijutsu = $skills[2],
					ninjutsu = $skills[3],
					genjutsu = $skills[4]
				WHERE char_id = $_uid;
				
				UPDATE game_users SET char_rank = 'D' WHERE char_id = $_uid;" ) or die( mysqli_error( $_CONN ) );
		}
	}
	
	exiter('start-clan');
}

if ( isset($_POST['skills']) )
{
	$skills = explode(',', $_POST['skills']);
	
	if ( $skills[5] < 1)
	{
		// echo not working: it seems when coming from F5(refresh) POST[skills] is!set.
		// it was echo before JS_add_message().
		JS_add_message('Can\'t upgrade more');
	}
	else
	{
		switch( array_search('+1', $_POST) )
		{
			case 'kenjutsu':
				$skills[0] += 1;
				$skills[5] -= 1;
				break;
			
			case 'shuriken':
				$skills[1] += 1;
				$skills[5] -= 1;
				break;
			
			case 'taijutsu':
				$skills[2] += 1;
				$skills[5] -= 1;
				break;
			
			case 'ninjutsu':
				if ( $style_name == 'Tameru' ) exiter('start-clan');
				
				$skills[3] += 1;
				$skills[5] -= 1;
				break;
			
			case 'genjutsu':
				if ( $style_name == 'Tameru' ) exiter('start-clan');
				
				$skills[4] += 1;
				$skills[5] -= 1;
				break;
			
			default: exiter('start-clan');
		}
	}
}
else
{
	switch ($clan)
	{
		case 'Faruni':
		case 'Wyroni':
		case 'Raiyni':
		case 'Rokuni':
		case 'Watoni':
			
			$skills = [ 1, 1, 1, 3, 1, 3 ];
			break;
		
		case 'Tameru':
			$skills = [ 1, 1, 5, 0, 0, 3 ];
			break;
		
		case 'Tayuga':
			$skills = [ 1, 1, 3, 1, 1, 3 ];
			break;
		
		case 'Kensou':
			$skills = [ 3, 1, 1, 1, 1, 3 ];
			break;
		
		case 'Surike':
			$skills = [ 1, 3, 1, 1, 1, 3 ];
			break;
		
		case 'Geniru':
			$skills = [ 1, 1, 1, 1, 3, 3 ];
			break;
		
		default: JS_add_message('switch Error'); break;
	}
}

$skills_as_string = implode(",", $skills);

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $clan ?></h1>

<form method="POST">
	<input type="hidden" name="xc" value="<?= $clan ?>" />
	<input type="submit" value="Restart" />
</form>

<table class="table-skill" align="center">
	
	<tr>
		<th title="Sword Skill">kenjutsu</th>
		<th title="Shuriken Skill">shuriken</th>
		<th title="Melee Skill">taijutsu</th>
		<th title="Elemental Skill">ninjutsu</th>
		<th title="Illusion Skill">genjutsu</th>
	</tr>
	
	<tr>
		<td><?= $skills[0] ?></td>
		<td><?= $skills[1] ?></td>
		<td><?= $skills[2] ?></td>
		<td><?= $skills[3] ?></td>
		<td><?= $skills[4] ?></td>
	</tr>
	
	<?php
	
	if ( $skills[5] > 0 )
	{
		?>
		<tr>
			<form method="POST">
				<input type="hidden" name="xc" value="<?= $clan ?>" />
				<input type="hidden" name="skills" value="<?= $skills_as_string ?>" />
				
				<td><input type="submit" name="kenjutsu" value="+1" /></td>
				<td><input type="submit" name="shuriken" value="+1" /></td>
				<td><input type="submit" name="taijutsu" value="+1" /></td>
				
				<?php
				
				if ( $clan == 'Tameru')
				{
					?>
					<td colspan="2">No chakra control</td>
					<?php
				}
				else
				{
					?>
					
					<td><input type="submit" name="ninjutsu" value="+1" /></td>
					<td><input type="submit" name="genjutsu" value="+1" /></td>
					
					<?php
				}
				
				?>
			</form>
		</tr>
		<?php
	}
	
	?>
</table>

<?php

if ( $skills[5] > 0 )
{
	?>
	<h3>Still left to upgrade: <?= $skills[5] ?></h3>
	<?php
}
else
{
	?>
	
	<br />
	<form method="POST">
		<input type="hidden" name="xc" value="<?= $clan ?>" />
		<input type="hidden" name="skills" value="<?= $skills_as_string ?>" />
		
		<input type="submit" name="start" value="Start" />
	</form>
	
	<?php
}

?>
