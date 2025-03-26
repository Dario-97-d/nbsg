<?php include("headeron.php");
if(!isset($_GET['id'])||!ctype_digit($pid=$_GET['id'])){$pid=$uid;}
extract(sql_mfa($conn,"SELECT level,style,skp,tskl FROM atts a JOIN clan c ON a.id=c.id JOIN styl s ON a.id=s.id WHERE a.id=$uid"));
extract(sql_mfa($conn,"SELECT a.*,c.*,name,rank FROM atts a JOIN clan c ON a.id=c.id JOIN user u ON a.id=u.id WHERE u.id=$pid"),EXTR_PREFIX_ALL,"p");
?>
<h1><?php echo $p_name;?></h1>
<h3><?php echo $p_style;?></h3>
<?php echo ($uid==$pid?'':'<h3><a href="sendpm?to='.$p_name.'">pm</a></h3>');?>
<table class="table-skill" align="center"><tr><th title="Sword Skill">kenjutsu</th><th title="Shuriken Skill">shuriken</th><th title="Melee Skill">taijutsu</th><th title="Elemental Skill">ninjutsu</th><th title="Illusion Skill">genjutsu</th></tr><tr><td><?php echo $p_ken.'</td><td>'.$p_shu.'</td><td>'.$p_tai.'</td><td>'.$p_nin.'</td><td>'.$p_gen;?></td></tr></table>
<br />
<b>Skill Points: <?php echo $skp;?>/5</b>
<?php if($skp>4 && $pid!=$uid && $style==$p_style && ($level-$p_level<=5&&$p_level-$level<=5) && $tskl==''){?>
	<form action="clantrainskill" method="POST">
		<select class="select-skill" name="skill">
			<option hidden>-- skill --</option>
			<option>Kenjutsu</option>
			<option>Shuriken</option>
			<option>Taijutsu</option>
			<?php echo ($style=='Tameru'?'':
			'<option>Ninjutsu</option>
			<option>Genjutsu</option>');?>
		</select>
		<input type="submit" name="<?php echo $pid;?>" value="Train"/>
	</form>
<?php }?>
<p>Rank-<?php echo $p_rank;?><br /><b title="Average of stats">Lv <?php echo $p_level;?></b></p>
<?php if(true){//if(in_bonds)?>
<table align="center">
	<tr><td title="Critical">Flair</td><td><?php echo $p_fla;?></td></tr>
	<tr><td title="Strength">Power</td><td><?php echo $p_pow;?></td></tr>
	<tr><td title="Reach">Speed</td><td><?php echo $p_agi;?></td></tr>
	<tr><td title="Effect">Jutsu</td><td><?php echo $p_jut;?></td></tr>
	<tr><td title="Planning">Tactics</td><td><?php echo $p_tac;?></td></tr>
</table>
<?php }?>
<br />pvp wins
<br />
<br />missions:
<br />patrol - anbu
<?php include("footer.php");?>