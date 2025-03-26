<?php include("headeron.php");
$ids=explode('-',array_search('Train',$_POST));
extract(sql_mfa($conn,"SELECT nin1,nin2 FROM team WHERE id=$uid"));
if($nin1!=$ids[0]||$nin2!=$ids[1]){exiter("team");}
$members=sql_query($conn,"SELECT a.*,c.*,name FROM atts a JOIN clan c ON a.id=c.id JOIN user u ON a.id=u.id JOIN styl s ON a.id=s.id WHERE a.id IN ($uid,$nin1,$nin2) ORDER BY CASE a.id WHEN $uid THEN 1 WHEN $nin1 THEN 2 WHEN $nin2 THEN 3 END");
$prefix=['user','nin1','nin2'];$i=0;
while($row=mysqli_fetch_assoc($members)){extract($row,EXTR_PREFIX_ALL,$prefix[$i]);$i++;}
if(min($nin1_level,$nin2_level)>$user_level){
	// remove nin from team
	// exiter(team)
}
// clantrainskill
/*
switch(true){case (!is_numeric($result)): echo "switch_result Error";break;
	case ($result<4||$result>=12): $placard="<br />The difference in use of skill is too wide<br />There's no point in training together<br />";break;
	case ($result<6||$result>=10): $placard="There's some gap";$$up_att='+1';if(floor(($u_fla+$u_pow+$u_agi+$u_jut+$u_tac+1)/5)>$u_level){$uplv='level=level+1,';$u_level+=1;}else{$uplv='';}sql_query($conn,"UPDATE atts SET $uplv $up_att=$up_att+1 WHERE id=$uid");$upgrade=($uplv!=''?'Lv: '.$u_level.'<br />':'').$atts[$up_att].' +1';break;
	case ($result<7||$result>=9): $placard="Good training";
		if($$tskl+1>=$$up_skill){$$tskl=0;$up_skl=2;}else{$$tskl+=1;}
		sql_query($conn,"UPDATE styl SET $up_tskl=".$$tskl." WHERE id=$uid");$upgrade="$skill training: +1";break;
	default: $placard="Evenly matched";$up_skl=1;break;
}
if(isset($up_skl)){$$up_skill=1;$set_up_skl=$up_skill.'='.$up_skill.'+1,';$upgrade=($up_skl==2?$upgrade.'<br />':'').$skill.' +1';}
sql_query($conn,"UPDATE clan SET $set_up_skl skp=skp-5 WHERE id=$uid");
*/
?>
<h1>Team Train</h1>
<table align="center" style="text-align:center;">
	<tr><th width="33%"><?php echo $nin1_name;?></th><th width="33%"><?php echo $user_name;?></th><th width="33%"><?php echo $nin2_name;?></th></tr>
	<tr><td></td><th></th><td></td></tr>
	<tr>
		<th><?php echo "$nin1_ken • $nin1_shu • $nin1_tai • $nin1_nin • $nin1_gen";?></th>
		<th><?php echo "$user_ken • $user_shu • $user_tai • $user_nin • $user_gen";?></th>
		<th><?php echo "$nin2_ken • $nin2_shu • $nin2_tai • $nin2_nin • $nin2_gen";?></th>
	</tr>
	<tr><td></td><th></th><td></td></tr>
	<tr><th><?php echo $nin1_level;?></th><th><?php echo $user_level;?></th><th><?php echo $nin2_level;?></th></tr>
	<tr><td><?php echo $nin1_fla;?></td><th><?php echo $user_fla;?></th><td><?php echo $nin2_fla;?></td></tr>
	<tr><td><?php echo $nin1_pow;?></td><th><?php echo $user_pow;?></th><td><?php echo $nin2_pow;?></td></tr>
	<tr><td><?php echo $nin1_agi;?></td><th><?php echo $user_agi;?></th><td><?php echo $nin2_agi;?></td></tr>
	<tr><td><?php echo $nin1_jut;?></td><th><?php echo $user_jut;?></th><td><?php echo $nin2_jut;?></td></tr>
	<tr><td><?php echo $nin1_tac;?></td><th><?php echo $user_tac;?></th><td><?php echo $nin2_tac;?></td></tr>
</table>
<?php include("footer.php");?>