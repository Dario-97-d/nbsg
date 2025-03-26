<?php include("headeron.php");
extract(sql_mfa($conn,"SELECT tskl,rank,level,c.* FROM styl s JOIN user u ON u.id=s.id JOIN atts a ON u.id=a.id JOIN clan c ON u.id=c.id WHERE u.id=$uid"));
if($style==''){exiter("clan");}?>
<h1><?php echo $style;?></h1>
<h4>In the training grounds of the village<br />nin from the clan train their skills</h4>
<table class="table-skill" align="center"><tr><th title="Sword Skill">kenjutsu</th><th title="Shuriken Skill">shuriken</th><th title="Melee Skill">taijutsu</th><th title="Elemental Skill">ninjutsu</th><th title="Illusion Skill">genjutsu</th></tr><tr><td><?php echo $ken.'</td><td>'.$shu.'</td><td>'.$tai.'</td><td>'.$nin.'</td><td>'.$gen;?></td></tr></table>
<a href="hometrain">Train alone</a>
<h2>Rank-<?php echo $rank;?></h2>
<form action="clantrainskill" method="POST">
	<select class="select-skill" name="skill"><option hidden>-- skill --</option><option>Kenjutsu</option><option>Shuriken</option><option>Taijutsu</option><?php echo ($style=='Tameru'?'':'<option>Ninjutsu</option><option>Genjutsu</option>');?></select><input type="submit" value="Train"/>
	<table align="center" style="text-align:center;" cellpadding="8" cellspacing="0">
		<?php $getplayers=sql_query($conn,"SELECT name,level,c.* FROM user u JOIN atts a ON u.id=a.id JOIN clan c on u.id=c.id WHERE style='$style' AND rank='$rank' AND level BETWEEN $level-5 AND $level+5 AND u.id<>$uid ORDER BY level DESC LIMIT 25");
		if(mysqli_num_rows($getplayers)<1){echo"There's no nin to train with";}
		else{
			echo '<tr><th>Lv</th><th>Nin</th><th>Jutsu</th><th>Select</th></tr>';
			while($row=mysqli_fetch_assoc($getplayers)){echo '<tr><td>'.$row['level'].'</td><td><a href="nin?id='.$row['id'].'">'.$row['name'].'</a></td><th>'.$row['ken']." • ".$row['shu']." • ".$row['tai']." • ".$row['nin']." • ".$row['gen'].'</th><td><input type="radio" name="pick" value="'.$row['id'].'"/></td>';}
		}?>
	</table>
</form>
<?php include("footer.php");?>