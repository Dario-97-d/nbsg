<?php include("headeron.php");
$ids=explode('-',array_search('Team Battle',$_POST));
extract(sql_mfa($conn,"SELECT nin1,nin2,joint,name,rank,style,tskl FROM team t JOIN user u ON t.id=u.id JOIN clan c ON u.id=c.id JOIN styl s ON c.id=s.id WHERE u.id=$uid"));
if($rank!='D'||$joint>1||$tskl!=''||$nin1<1||$nin2<1||$nin1!=$ids[0]||$nin2!=$ids[1]){exiter("team");}
$members=sql_query($conn,"SELECT level,c.*,name FROM atts a JOIN clan c ON a.id=c.id JOIN user u ON a.id=u.id WHERE a.id IN ($uid,$nin1,$nin2) ORDER BY CASE a.id WHEN $uid THEN 1 WHEN $nin1 THEN 2 WHEN $nin2 THEN 3 END");
$k=(substr(microtime(true),-1)%10)+3;$s=(substr(microtime(true),-2,1)%10)+3;$t=(substr(microtime(true),-3,1)%10)+3;$n=(substr(microtime(true),-4,1)%10)+3;$g=(substr(time(),0,-1)%10)+1;$r=125/($k+$s+$t+$n+$g);?>
<h1>Team Exam</h1>
<table class="table-team"align="center"style="text-align:center">
	<tr><th width="33">Team<br /><?php echo $name;?></th><th width="10%">Lv</th><th width="14%">VS</th><th width="10%">Lv</th><th width="33%">Team<br />Botuzo</th></tr>
	<?php $i=0;while($row=mysqli_fetch_assoc($members)){
		${'level'.$i}=$row['level'];${'ken'.$i}=$row['ken'];${'shu'.$i}=$row['shu'];${'tai'.$i}=$row['tai'];${'nin'.$i}=$row['nin'];${'gen'.$i}=$row['gen'];
		$i++;echo'<tr><td>'.$row['name'].'</td><td>'.$row['level'].'</td><td></td><td>10</td><td>BotNin_'.$i.'</td></tr>';
	}
	$ken=$ken0+$ken1+$ken2;$shu=$shu0+$shu1+$shu2;$tai=$tai0+$tai1+$tai2;$nin=$nin0+$nin1+$nin2;$gen=$gen0+$gen1+$gen2;
	$total=$ken+$shu+$tai+$nin+$gen;
	if($total>25){$ratio=(2-(25/$total))*100/$total;}else{$ratio=100/$total;}?>
	<tr><td colspan="5"></td></tr>
	<tr><td colspan="2"><div id="ttd"style="width:<?php echo round($ken*$ratio);?>px;float:right"></td><th>Kenjutsu</th><td colspan="2"><div id="ttd"style="width:<?php echo round($k*=$r);?>px"></td></tr>
	<tr><td colspan="2"><div id="ttd"style="width:<?php echo round($shu*$ratio);?>px;float:right"></td><th>Shuriken</th><td colspan="2"><div id="ttd"style="width:<?php echo round($s*=$r);?>px"></td></tr>
	<tr><td colspan="2"><div id="ttd"style="width:<?php echo round($tai*$ratio);?>px;float:right"></td><th>Taijutsu</th><td colspan="2"><div id="ttd"style="width:<?php echo round($t*=$r);?>px"></td></tr>
	<tr><td colspan="2"><div id="ttd"style="width:<?php echo round($nin*$ratio);?>px;float:right"></td><th>Ninjutsu</th><td colspan="2"><div id="ttd"style="width:<?php echo round($n*=$r);?>px"></td></tr>
	<tr><td colspan="2"><div id="ttd"style="width:<?php echo round($gen*$ratio);?>px;float:right"></td><th>Genjutsu</th><td colspan="2"><div id="ttd"style="width:<?php echo round($g*=$r);?>px"></td></tr>
</table>
<?php
if(($level0+$level1+$level2)*($total**2)>6250){
	echo "<h4>Team exam is done.<br />A pvp battle will be prepared to proceed graduation</h4>";
	sql_query($conn,"UPDATE team SET joint=1 WHERE id=$uid");
}else{
	echo "<h4>You didn't make it this time.</h4>";
}
$up_pow=round(9*$ken/$total);$up_jut=round(9*$shu/$total);$up_agi=round(9*$tai/$total);$up_fla=round(9*$nin/$total);$up_tac=round(9*$gen/$total);
$train=10-($up_fla+$up_pow+$up_agi+$up_jut+$up_tac);
switch($style){default: echo 'switch_style Error';break;case 'Kensou': $up_pow+=$train;break;case 'Surike': $up_jut+=$train;break;case 'Geniru': $up_tac+=$train;break;case 'Tameru': case 'Tayuga': $up_agi+=$train;break;case 'Faruni': case 'Wyroni': case 'Raiyni': case 'Rokuni': case 'Watoni': $up_fla+=$train;break;}
sql_query($conn,"UPDATE atts SET level=level+2,fla=fla+$up_fla,pow=pow+$up_pow,agi=agi+$up_agi,jut=jut+$up_jut,tac=tac+$up_tac WHERE id=$uid");?>
<table align="center">
	<tr><th colspan="2">Lv: <?php echo $level0+2?></th></tr>
	<tr><td title="Critical">Flair</td><!--<td><?php echo $p_fla;?></td>--><td>+<?php echo $up_fla?></td></tr>
	<tr><td title="Strength">Power</td><!--<td><td><?php echo $p_pow;?></td>--><td>+<?php echo $up_pow?></td></tr>
	<tr><td title="Reach">Speed</td><!--<td><td><?php echo $p_agi;?></td>--><td>+<?php echo $up_agi?></td></tr>
	<tr><td title="Effect">Jutsu</td><!--<td><td><?php echo $p_jut;?></td>--><td>+<?php echo $up_jut?></td></tr>
	<tr><td title="Planning">Tactics</td><!--<td><td><?php echo $p_tac;?></td>--><td>+<?php echo $up_tac?></td></tr>
</table>
<?php include("footer.php");?>