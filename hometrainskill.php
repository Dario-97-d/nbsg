<?php

require_once 'backend.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa( $conn, "SELECT * FROM style_attributes c JOIN skill_training s ON c.char_id = s.char_id WHERE c.char_id = $_uid" ) );

$trained = '';

$skill_trainings = array(
	'kenjutsu_points' => 'kenjutsu',
	'shuriken_points' => 'shuriken',
	'taijutsu_points' => 'taijutsu',
	'ninjutsu_points' => 'ninjutsu',
	'genjutsu_points' => 'genjutsu' );

$tskills = array(
	'kenjutsu_points' => 'Kenjutsu',
	'shuriken_points' => 'Shuriken',
	'taijutsu_points' => 'Taijutsu',
	'ninjutsu_points' => 'Ninjutsu',
	'genjutsu_points' => 'Genjutsu' );

if ( ! empty($_POST) )
{
	if ( $skill_training == '' )
	{
		if (
			in_array(
				$skl = array_search('Train', $_POST),
				[ 'kenjutsu_points', 'shuriken_points', 'taijutsu_points', 'ninjutsu_points', 'genjutsu_points'] )
			&&
			ctype_digit( $n = $_POST['n'] )
			&&
			$n > 0
			&&
			$n <= ${$skill_trainings[$skl]}
			&&
			$n < 11 )
		{
			$skill_training = $skl;
			$sessions_in_training = $n;
			$time_ready = time() + ($n * 1800);
			
			sql_query(
				$conn,
					"UPDATE skill_training SET
						skill_training       = '$skl',
						sessions_in_training = $n,
						ready                = $time_ready
					WHERE char_id = $_uid" );
		}
	}
	else if ( isset($_POST['end']) && $time_ready > time() )
	{
		$tdone = $sessions_in_training - ceil( ( $time_ready - time() ) / 1800 );
		
		$skillupgrade = '';
	}
}
else if ( $skill_training != '' && $time_ready <= time() )
{
	$skillupgrade = '';
}

if ( isset($skillupgrade) )
{
	if ( isset($tdone) )
	{
		$sessions_in_training = $tdone;
	}
	
	if ( ($$skill_training += $sessions_in_training) >= ${$skill_trainings[$skill_training]} )
	{
		$$skill_training -= ${$skill_trainings[$skill_training]};
		${$skill_trainings[$skill_training]} += 1;
		
		sql_query(
			$conn,
			"UPDATE style_attributes SET
				$skill_trainings[$skill_training] = $skill_trainings[$skill_training] + 1
			WHERE char_id = $_uid" );
		
		$skillupgrade = "<br />$tskills[$skill_training] +1";
	}
	
	sql_query(
		$conn,
		"UPDATE skill_training SET
			$skill_training = ". $$skill_training .",
			skill_training = '',
			sessions_in_training = 0,
			ready = 0
		WHERE char_id = $_uid" );
	
	$trained = $sessions_in_training == 0 ? '' : "$tskills[$skill_training] trained(+$sessions_in_training)$skillupgrade");
	$skill_training = '';
}

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>
	<a href="hometrain">Training Grounds</a>
</h1>

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

if ( $skill_training == '')
{
	?>
	
	<h4><?= $trained ?></h4>
	
	<!--<div style="padding:4px;"><b>Skill Points: <?= $skill_points ?>/50</b><br />Train: 5 SP<br />(ainda não gasta)</div>-->
	
	<table id="table-train" align="center" cellspacing="3">
		
		<tr>
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
					<input type="submit" name="kenjutsu_points" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<tr>
			
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
					<input type="submit" name="shuriken_points" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<tr>
			
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
					<input type="submit" name="taijutsu_points" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<?php
		
		if ( $style_name != 'Tameru' )
		{
			?>
			
			<tr>
				
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
						<input type="submit" name="ninjutsu_points" value="Train" />
					</th>
				</form>
				
			</tr>
			
			<tr>
				
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
						<input type="submit" name="genjutsu_points" value="Train" />
					</th>
				</form>
				
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
	
	<br />
	Training:
	
	<br /><br />
	
	<b><?= $tskills[$skill_training] ?></b>
	
	<br /><br />
	
	<?= $sessions_in_training .' Sessions | '. ($sessions_in_training * 30) .' minutes' ?>
	
	<br /><br />
	
	Time left:
	<br />
	
	<?= date( "H:i:s", $time_ready-time() ) ?>
	
	<br /><br />
	
	<form method="POST">
		<input type="submit" name="end" value="Stop" />
	</form>
	
	<p>Can't train with other nin when training alone</p>
	
	<?php
}

?>
