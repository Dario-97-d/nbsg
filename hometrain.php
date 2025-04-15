<?php

include("headeron.php");

extract( sql_mfa( $conn, "SELECT * FROM clan c JOIN styl s ON c.id = s.id WHERE c.id = $uid" ) );

$trained = '';
$done = 0;
$up_tken = '';
$up_tshu = '';
$up_ttai = '';
$up_tnin = '';
$up_tgen = '';

$tskls = array(
	'tken' => 'ken',
	'tshu' => 'shu',
	'ttai' => 'tai',
	'tnin' => 'nin',
	'tgen' => 'gen' );

$tskills = array(
	'tken' => 'Kenjutsu',
	'tshu' => 'Shuriken',
	'ttai' => 'Taijutsu',
	'tnin' => 'Ninjutsu',
	'tgen' => 'Genjutsu' );

if ( ! empty($_POST) )
{
	// test: if ( tskl, in_array, ctype_digit, n ) and if ( ! tskl, post[end], ready > time )
	if ( $tskl == '' )
	{
		if (
			in_array(
				$tskl = array_search('Train', $_POST),
				[ 'tken', 'tshu', 'ttai', 'tnin', 'tgen'] )
			&&
			ctype_digit( $ntrain = $_POST['n'] )
			&&
			$ntrain > 0
			&&
			$ntrain <= ${$tskls[$tskl]}
			&&
			$ntrain < 11 )
		{
			$ready = time() + ($ntrain * 1800);
			
			sql_query( $conn, "UPDATE styl SET tskl = '$tskl', ntrain = $ntrain, ready = $ready WHERE id = $uid" );
		}
	}
	else if ( isset($_POST['end']) && $ready > time() )
	{
		$ntrain -= ceil( ( $ready - time() ) / 1800 );
		$done = 1;
	}
}

if ( $done == 1 ||
	(
		empty($_POST)
		&&
		$tskl != ''
		&&
		$ready <= time()
	) )
{
	if ( $ntrain > 0 )
	{
		if ( ( $$tskl += $ntrain ) >= ${$tskls[$tskl]} )
		{
			$$tskl -= ${$tskls[$tskl]};
			${$tskls[$tskl]} += 1;
			
			sql_query( $conn, "UPDATE clan SET $tskls[$tskl] = $tskls[$tskl] + 1 WHERE id = $uid" );
			
			${'up_'.$tskl} = '+1';
		}
		
		sql_query( $conn, "UPDATE styl SET $tskl = ". $$tskl .", tskl = '', ntrain = 0, ready = 0 WHERE id = $uid" );
		
		$trained = $ntrain == 0 ? '' : "$tskills[$tskl] trained (+$ntrain)";
	}
	else
	{
		sql_query( $conn, "UPDATE styl SET $tskl = ". $$tskl .", tskl = '', ntrain = 0, ready = 0 WHERE id = $uid");
	}
	
	$tskl = '';
}

?>

<h1>Training Grounds</h1>

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

<?php

if ( $tskl == '' )
{
	?>
	
	<h3>
		<a href="clantrain">Train in Clan</a> || <a href="teamtrain">Train in Team</a>
	</h3>
	
	<table id="table-train" align="center" cellspacing="3">
		<tr>
			
			<th><?= $up_tken ?></th>
			
			<th>Kenjutsu</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $tken * 100 / $ken ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $tken .'/'. $ken ?></th>
			
			<form method="POST">
				<th>
					<select name="n">
						<?php
						
						for ( $i = 1; $i <= $ken && $i < 11; $i++ )
						{
							?>
							<option><?= $i ?></option>
							<?php
						}
						
						?>
					</select>
				</th>
				
				<th>
					<input type="submit" name="tken" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<tr>
			
			<th><?= $up_tshu ?></th>
			
			<th>Shuriken</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $tshu * 100 / $shu ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $tshu .'/'. $shu ?></th>
			
			<form method="POST">
				<th>
					<select name="n">
						<?php
						
						for ( $i = 1; $i <= $shu && $i < 11; $i++ )
						{
							?>
							<option><?= $i ?></option>
							<?php
						}
						
						?>
					</select>
				</th>
				
				<th>
					<input type="submit" name="tshu" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<tr>
			
			<th><?= $up_ttai ?></th>
			
			<th>Taijutsu</th>
			
			<td>
				<div id="bp">
					<div id="bt" style="width: <?= round( $ttai * 100 / $tai ) ?>px;"></div>
				</div>
			</td>
			
			<th><?= $ttai .'/'. $tai ?></th>
			
			<form method="POST">
				<th>
					<select name="n">
						<?php
						
						for ( $i = 1; $i <= $tai && $i < 11; $i++ )
						{
							?>
							<option><?= $i ?></option>
							<?php
						}
						
						?>
					</select>
				</th>
				
				<th>
					<input type="submit" name="ttai" value="Train" />
				</th>
			</form>
			
		</tr>
		
		<?php
		
		if ( $style != 'Tameru' )
		{
			?>
			
			<tr>
				
				<th><?= $up_tnin ?></th>
				
				<th>Ninjutsu</th>
				
				<td>
					<div id="bp">
						<div id="bt" style="width: <?= round( $tnin * 100 / $nin ) ?>px;"></div>
					</div>
				</td>
				
				<th><?= $tnin .'/'. $nin ?></th>
				
				<form method="POST">
					<th>
						<select name="n">
							<?php
							
							for ( $i = 1; $i <= $nin && $i < 11; $i++ )
							{
								?>
								<option><?= $i ?></option>
								<?php
							}
							
							?>
						</select>
					</th>
					
					<th>
						<input type="submit" name="tnin" value="Train" />
					</th>
				</form>
				
			</tr>
			
			<tr>
				
				<th><?= $up_tgen ?></th>
				
				<th>Genjutsu</th>
				
				<td>
					<div id="bp">
						<div id="bt" style="width: <?= round( $tgen * 100 / $gen ) ?>px;"></div>
					</div>
				</td>
			
				<th><?= $tgen .'/'. $gen ?></th>
				
				<form method="POST">
					<th>
						<select name="n">
							<?php
							
							for ( $i = 1; $i <= $gen && $i < 11; $i++ )
							{
								?>
								<option><?= $i ?></option>
								<?php
							}
							
							?>
						</select>
					</th>
					
					<th>
						<input type="submit" name="tgen" value="Train" />
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
	
	<b><?= $tskills[$tskl] ?></b>
	
	<p><?= $ntrain .' Sessions | '. ( $ntrain * 30 ) .' minutes' ?></p>
	
	Time left:
	<br />
	<?= date( "H:i:s", $ready - time() ) ?>
	
	<br /><br />
	
	<form method="POST">
		<input type="submit" name="end" value="Stop" />
	</form>
	
	<p>Can't train with other nin when training alone</p>
	
	<?php
}

include("footer.php");

?>