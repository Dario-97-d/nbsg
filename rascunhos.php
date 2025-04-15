<!--

<table class="table-skill" align="center">
	
	<tr>
		<th>Kenjutsu</th>
		<th>Shuriken</th>
		<th>Taijutsu</th>
		<th>Ninjutsu</th>
		<th>Genjutsu</th>
	</tr>
	
	<tr>
		<td><?= $ken ?></td>
		<td><?= $shu ?></td>
		<td><?= $tai ?></td>
		<td><?= $nin ?></td>
		<td><?= $gen ?></td>
	</tr>
	
	<tr>
		<td>Progress</td>
		<td>Progress</td>
		<td>Progress</td>
		<td>Progress</td>
		<td>Progress</td>
	</tr>
	
	<tr>
		
		<td>
			<div class="barprogt">
				<div class="bartrain" style="width: <?= $tken * 100 / $ken ?>px;"></div>
			</div>
			
			<?= $tken .'/'. $ken ?>
		</td>
		
		<td>
			<div class="barprogt">
				<div class="bartrain" style="width: <?= $tshu * 100 / $shu ?>px;"></div>
			</div>
			
			<?= $tshu .'/'. $shu ?>
		</td>
		
		<td>
			<div class="barprogt">
				<div class="bartrain" style="width: <?= $ttai * 100 / $tai ?>px;"></div>
			</div>
			
			<?= $ttai .'/'. $tai ?>
		</td>
		
		<td>
			<div class="barprogt">
				<div class="bartrain" style="width: <?= $tnin * 100 / $nin ?>px;"></div>
			</div>
			
			<?= $tnin .'/'. $nin ?>
		</td>
		
		<td>
			<div class="barprogt">
				<div class="bartrain" style="width: <?= $tgen * 100 / $gen ?>px;"></div>
			</div>
			
			<?= $tgen .'/'. $gen ?>
		</td>
		
	</tr>
	
	<tr>
		
		<td>
			<select name="skill">
				<?php
				
				for ( $i = 1; $i <= $ken; $i++ )
				{
					?>
					<option><?= $i ?></option>
					<?php
				}
				
				?>
			</select>
		</td>
		
		<td>
			<select name="skill">
				<option>1</option>
			</select>
		</td>
		
		<td>
			<select name="skill">
				<option>1</option>
			</select>
		</td>
		
		<td>
			<select name="skill">
				<option>1</option>
			</select>
		</td>
		
		<td>
			<select name="skill">
				<option>1</option>
			</select>
		</td>
		
	</tr>
	
</table>

<table id="table-train" align="center">
	<tr>
		<td>
			<input type="submit" value="Train" />
		</td>
		
		<td>
			<input type="submit" value="Train" />
		</td>
		
		<td>
			<input type="submit" value="Train" />
		</td>
		
		<td>
			<input type="submit" value="Train" />
		</td>
		
		<td>
			<input type="submit" value="Train" />
		</td>
	</tr>
</table>

-->