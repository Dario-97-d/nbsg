<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa( $conn, 'SELECT * FROM style_attributes c JOIN skill_training s ON c.char_id = s.char_id WHERE c.char_id = '. $_uid ) );

$trained = '';
$done = 0;
$up_kenjutsu = '';
$up_shuriken = '';
$up_taijutsu = '';
$up_ninjutsu = '';
$up_genjutsu = '';

$tskills = array(
	'kenjutsu' => 'Kenjutsu',
	'shuriken' => 'Shuriken',
	'taijutsu' => 'Taijutsu',
	'ninjutsu' => 'Ninjutsu',
	'genjutsu' => 'Genjutsu' );

if ( ! empty($_POST) )
{
	// test: if ( skill_training, in_array, ctype_digit, n ) and if ( ! skill_training, post[end], time_ready > time )
	if ( $skill_training == '' )
	{
		if (
			in_array(
				$skill_training = array_search('Train', $_POST),
				[ 'kenjutsu', 'shuriken', 'taijutsu', 'ninjutsu', 'genjutsu'] )
			&&
			ctype_digit( $sessions_in_training = $_POST['n'] )
			&&
			$sessions_in_training > 0
			&&
			$sessions_in_training <= $$skill_training
			&&
			$sessions_in_training < 11 )
		{
			$time_ready = time() + ($sessions_in_training * 1800);
			
			sql_query(
				$conn,
				"UPDATE skill_training SET
					skill_training = '$skill_training',
					sessions_in_training = $sessions_in_training,
					time_ready = $time_ready
				WHERE char_id = $_uid" );
		}
	}
	else if ( isset($_POST['end']) && $time_ready > time() )
	{
		$sessions_in_training -= ceil( ( $time_ready - time() ) / 1800 );
		$done = 1;
	}
}

if ( $done == 1 ||
	(
		empty($_POST)
		&&
		$skill_training != ''
		&&
		$time_ready <= time()
	) )
{
	$skill_to_upgrade = $skill_training .'_points';
	
	if ( $sessions_in_training > 0 )
	{
		$up = 0;
		if ( ( $$skill_to_upgrade += $sessions_in_training ) >= $$skill_training )
		{
			$$skill_to_upgrade -= $$skill_training;
			$$skill_training += 1;
			$up++;
		}
		
		sql_query(
			$conn,
			'UPDATE style_attributes SET
				'. $skill_training .' = '. $skill_training .' + '. $up .'
			WHERE char_id = '. $_uid );
		
		sql_query(
			$conn,
			'UPDATE skill_training SET
				'. $skill_to_upgrade .' = '. $$skill_to_upgrade .',
				skill_training = \'\',
				sessions_in_training = 0,
				time_ready = 0
			WHERE char_id = '. $_uid );
		
		
		if ( $up > 0 )
		{
			${'up_'. $skill_training} = '+'. $up;
		}
		
		$trained = $sessions_in_training === 0 ? '' : "$tskills[$skill_training] trained (+$sessions_in_training)";
	}
	else
	{
		sql_query(
			$conn,
			'UPDATE skill_training SET
				'. $skill_to_upgrade .' = '. $$skill_training .',
				skill_training = \'\',
				sessions_in_training = 0,
				time_ready = 0
			WHERE char_id = '. $_uid );
	}
	
	$skill_training = '';
}

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Training Grounds</h1>

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

<?php

if ( $skill_training == '' )
{
	?>
	
	<h3>
		<a href="clan-train">Train in Clan</a> || <a href="team-train">Train in Team</a>
	</h3>
	
	<table id="table-train" align="center" cellspacing="3">
		<tr>
			
			<th><?= $up_kenjutsu ?></th>
			
			<th>Kenjutsu</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $kenjutsu_points * 100 / $kenjutsu ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $kenjutsu_points .'/'. $kenjutsu ?></th>
			
			<form method="POST">
				<th>
					<select name="n">
						<?php
						
						for ( $i = 1; $i <= $kenjutsu && $i < 11; $i++ )
						{
							?>
							<option><?= $i ?></option>
							<?php
						}
						
						?>
					</select>
				</th>
				
				<th>
					<input type="submit" name="kenjutsu" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<tr>
			
			<th><?= $up_shuriken ?></th>
			
			<th>Shuriken</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $shuriken_points * 100 / $shuriken ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $shuriken_points .'/'. $shuriken ?></th>
			
			<form method="POST">
				<th>
					<select name="n">
						<?php
						
						for ( $i = 1; $i <= $shuriken && $i < 11; $i++ )
						{
							?>
							<option><?= $i ?></option>
							<?php
						}
						
						?>
					</select>
				</th>
				
				<th>
					<input type="submit" name="shuriken" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<tr>
			
			<th><?= $up_taijutsu ?></th>
			
			<th>Taijutsu</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $taijutsu_points * 100 / $taijutsu ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $taijutsu_points .'/'. $taijutsu ?></th>
			
			<form method="POST">
				<th>
					<select name="n">
						<?php
						
						for ( $i = 1; $i <= $taijutsu && $i < 11; $i++ )
						{
							?>
							<option><?= $i ?></option>
							<?php
						}
						
						?>
					</select>
				</th>
				
				<th>
					<input type="submit" name="taijutsu" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<?php
		
		if ( $style_name != 'Tameru' )
		{
			?>
			
			<tr>
				
				<th><?= $up_ninjutsu ?></th>
				
				<th>Ninjutsu</th>
				
				<td>
					<div id="bp">
						<div id="bt" style="width: <?= round( $ninjutsu_points * 100 / $ninjutsu ) ?>px;"></div>
					</div>
				</td>
				
				<th><?= $ninjutsu_points .'/'. $ninjutsu ?></th>
				
				<form method="POST">
					<th>
						<select name="n">
							<?php
							
							for ( $i = 1; $i <= $ninjutsu && $i < 11; $i++ )
							{
								?>
								<option><?= $i ?></option>
								<?php
							}
							
							?>
						</select>
					</th>
					
					<th>
						<input type="submit" name="ninjutsu" value="Train" />
					</th>
				</form>
				
			</tr>
			
			<tr>
				
				<th><?= $up_genjutsu ?></th>
				
				<th>Genjutsu</th>
				
				<td>
					<div id="bp">
						<div id="bt" style="width: <?= round( $genjutsu_points * 100 / $genjutsu ) ?>px;"></div>
					</div>
				</td>
			
				<th><?= $genjutsu_points .'/'. $genjutsu ?></th>
				
				<form method="POST">
					<th>
						<select name="n">
							<?php
							
							for ( $i = 1; $i <= $genjutsu && $i < 11; $i++ )
							{
								?>
								<option><?= $i ?></option>
								<?php
							}
							
							?>
						</select>
					</th>
					
					<th>
						<input type="submit" name="genjutsu" value="Train" />
					</th>
				</form>
				
			</tr>
			
			<?php
		}
		
		?>
	</table>
	
	<h4><?= $trained ?></h4>
	
	<?php
}
else
{
	?>
	
	<p>Training:</p>
	
	<b><?= $tskills[$skill_training] ?></b>
	
	<p><?= $sessions_in_training .' Sessions | '. ( $sessions_in_training * 30 ) .' minutes' ?></p>
	
	Time left:
	<br />
	<?= date( "H:i:s", $time_ready - time() ) ?>
	
	<br /><br />
	
	<form method="POST">
		<input type="submit" name="end" value="Stop" />
	</form>
	
	<p>Can't train with other nin when training alone</p>
	
	<?php
}

?>
