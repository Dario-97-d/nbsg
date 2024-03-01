<?php include("headeron.php");
extract(sql_mfa($conn,"SELECT level,style,rank,name,nin1,nin2,joint FROM atts a JOIN clan c ON a.id=c.id JOIN user u ON c.id=u.id JOIN team t ON u.id=t.id WHERE u.id=$uid"));
if(($nin1<1||$nin2<1)&&is_int($pid=array_search('Pick',$_POST))&&!in_array($pid,[$nin1,$nin2])&&mysqli_num_rows(sql_query($conn,"SELECT level FROM atts WHERE id=$pid AND level<=$level"))==1){if(($tnin=($nin1==0?'nin1':($nin2==0?'nin2':'nada')))!='nada'){$$tnin=$pid;sql_query($conn,"UPDATE team SET $tnin=$pid WHERE id=$uid");}}
if(is_int($pid=array_search('Sack',$_POST))&&$n=array_search($pid,[0,$nin1,$nin2])){$tnin="nin$n";$$tnin=0;sql_query($conn,"UPDATE team SET $tnin=0 WHERE id=$uid");}?>
<h1>Team</h1>
<p style="padding:0 32px;">Training is more effective as a team<br />A team may be started from bonds<br />and from strangers sorted together</p>
<h3>Team <?php echo $name;?></h3>
<table align="center"style="text-align:center"cellpadding="8"cellspacing="0">
	<form method="POST">
		<?php echo "<tr><td>$style</td><td>$name</td><td>Lv $level</td></tr>";
		if($nin1>0||$nin2>0){
			$team=sql_query($conn,"SELECT style,u.id,name,level FROM clan c JOIN user u ON c.id=u.id JOIN atts a ON a.id=u.id WHERE u.id=$nin1 OR u.id=$nin2 ORDER BY level DESC");
			while($member=mysqli_fetch_assoc($team)){echo"<tr><th>".$member['style'].'</th><td><a href="nin?id='.$member['id'].'">'.$member['name'].'</a></td><td>Lv '.$member['level'].'</td><td><input type="submit" name="'.$member['id'].'" value="Sack"/></td></tr>';}
		}?>		
	</form>
</table>
<?php if($nin1>0&&$nin2>0){
	if($rank<'D'&&$joint>0){?><h3><a href="teamtrain">Team Train</a></h3>
	<?php }elseif($rank=='D'&&$joint==0){?><h3><a href="teamexam">Team Exam</a></h3>
<?php }}elseif($joint==0){?><h3><a href="bonds">Bonds</a></h3><?php }
else{echo "Train jutsu and do battle";}
if($joint==0){?>
	<h3>Rank-<?php echo $rank;?></h3>
	<table align="center"style="text-align:center"cellpadding="8"cellspacing="0">
		<form method="POST">
			<?php $picks=sql_query($conn,"SELECT u.id,name,level,style FROM user u JOIN atts a ON u.id=a.id JOIN clan c ON u.id=c.id WHERE rank='$rank' AND level<=$level AND u.id NOT IN($uid,$nin1,$nin2) ORDER BY u.id DESC LIMIT 25");
			if(mysqli_num_rows($picks)<1){echo"No nin available";}
			else{echo '<tr><th>Clan</th><th>Nin</th><th>Lv</th><th>Select</th></tr>';while($row=mysqli_fetch_assoc($picks)){echo '<tr><th>'.$row['style'].'</th><td><a href="nin?id='.$row['id'].'">'.$row['name'].'</a></td><td>'.$row['level'].'</td><td><input type="submit" name="'.$row['id'].'" value="Pick"'.($nin1>0&&$nin2>0?'title="Team is full"disabled':'').'/></td></tr>';}}?>
		</form>
	</table>
<?php }
include("footer.php");?>