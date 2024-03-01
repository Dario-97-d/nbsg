<?php include("headeron.php");
$ids=explode('-',array_search('Train',$_POST));
extract(sql_mfa($conn,"SELECT nin1,nin2,s.* FROM team t JOIN styl s ON t.id=s.id WHERE t.id=$uid"));
if($nin1<1||$nin2<1||$nin1!=$ids[0]||$nin2!=$ids[1]){exiter("team");}
//if(min($nin1_level,$nin2_level)>$user_level){// remove nin from team // exiter(team)}
$members=sql_query($conn,"SELECT level,c.*,name FROM atts a JOIN clan c ON a.id=c.id JOIN user u ON a.id=u.id WHERE a.id IN ($uid,$nin1,$nin2) ORDER BY CASE a.id WHEN $uid THEN 1 WHEN $nin1 THEN 2 WHEN $nin2 THEN 3 END");?>
<h1>Team Train</h1>
<table align="center" style="text-align:center;" cellpadding="8" cellspacing="0">
	<tr><th>Clan</th><th>Lv</th><th>Nin</th><th>Jutsu</th></tr>
	<?php $i=0;while($row=mysqli_fetch_assoc($members)){echo'<tr><td>'.$row['style'].'</td><td>'.(${'level'.$i}=$row['level']).'</td><td><a href="nin?id='.$row['id'].'">'.$row['name'].'</a></td><th>'.(${'ken'.$i}=$row['ken'])." • ".(${'shu'.$i}=$row['shu'])." • ".(${'tai'.$i}=$row['tai'])." • ".(${'nin'.$i}=$row['nin'])." • ".(${'gen'.$i}=$row['gen']).'</th></tr>';${'style'.$i}=$row['style'];$i++;}?>
</table>
<h3>Joint Skills</h3>
<?php echo ($ken=$ken0+$ken1+$ken2).' • '.($shu=$shu0+$shu1+$shu2).' • '.($tai=$tai0+$tai1+$tai2).' • '.($nin=$nin0+$nin1+$nin2).' • '.($gen=$gen0+$gen1+$gen2);$keni=$ken0;$shui=$shu0;$taii=$tai0;$nini=$nin0;$geni=$gen0;
$up_ken=max(round(($ken0*($ken1+$ken2-$ken0)/($level0*$ken/($level1+$level2)))),0);$up_shu=max(round(($shu0*($shu1+$shu2-$shu0)/($level0*$shu/($level1+$level2)))),0);$up_tai=max(round(($tai0*($tai1+$tai2-$tai0)/($level0*$tai/($level1+$level2)))),0);$up_nin=max(round(($nin0*($nin1+$nin2-$nin0)/($level0*$nin/($level1+$level2)))),0);$up_gen=max(round(($gen0*($gen1+$gen2-$gen0)/($level0*$gen/($level1+$level2)))),0);
if($style0=='Tameru'){$up_nin0=0;$up_gen0=0;}if($up_ken>0){$tken+=$up_ken;}if($up_shu>0){$tshu+=$up_shu;}if($up_tai>0){$ttai+=$up_tai;}if($up_nin>0){$tnin+=$up_nin;}if($up_gen>0){$tgen+=$up_gen;}
while($tken>=$ken0){$tken-=$ken0;$ken0+=1;}while($tshu>=$shu0){$tshu-=$shu0;$shu0+=1;}while($ttai>=$tai0){$ttai-=$tai0;$tai0+=1;}while($tnin>=$nin0&&$nin0>0){$tnin-=$nin0;$nin0+=1;}while($tgen>=$gen0&&$gen0>0){$tgen-=$gen0;$gen0+=1;}
sql_query($conn,"UPDATE clan SET ken=$ken0,shu=$shu0,tai=$tai0,nin=$nin0,gen=$gen0,skp=skp-0 WHERE id=$uid");sql_query($conn,"UPDATE styl SET tken=$tken,tshu=$tshu,ttai=$ttai,tnin=$tnin,tgen=$tgen WHERE id=$uid");?>
<br /><br />
<table class="table-skill" align="center"><tr><th title="Sword Skill">kenjutsu</th><th title="Shuriken Skill">shuriken</th><th title="Melee Skill">taijutsu</th><th title="Elemental Skill">ninjutsu</th><th title="Illusion Skill">genjutsu</th></tr><tr><td><?php echo $ken0.'</td><td>'.$shu0.'</td><td>'.$tai0.'</td><td>'.$nin0.'</td><td>'.$gen0;?></td></tr></table>
<br /><br />
<table id="table-train" align="center" cellspacing="3">
	<tr><th><?php echo ($ken0-$keni?'+'.($ken0-$keni):'');?></th><th>Kenjutsu</th><td><div id="bp"><div id="bt" style="width:<?php echo round($tken*100/$ken0);?>px;"></div></div></td><th><?php echo $tken.'/'.$ken0;?></th><th><?php echo ($up_ken>0?'+'.$up_ken.' train':'');?></th></tr>
	<tr><th><?php echo ($shu0-$shui?'+'.($shu0-$shui):'');?></th><th>Shuriken</th><td><div id="bp"><div id="bt" style="width:<?php echo round($tshu*100/$shu0);?>px;"></div></div></td><th><?php echo $tshu.'/'.$shu0;?></th><th><?php echo ($up_shu>0?'+'.$up_shu.' train':'');?></th></tr>
	<tr><th><?php echo ($tai0-$taii?'+'.($tai0-$taii):'');?></th><th>Taijutsu</th><td><div id="bp"><div id="bt" style="width:<?php echo round($ttai*100/$tai0);?>px;"></div></div></td><th><?php echo $ttai.'/'.$tai0;?></th><th><?php echo ($up_tai>0?'+'.$up_tai.' train':'');?></th></tr>
	<?php if($style0!='Tameru'){?>
		<tr><th><?php echo ($nin0-$nini?'+'.($nin0-$nini):'');?></th><th>Ninjutsu</th><td><div id="bp"><div id="bt" style="width:<?php echo round($tnin*100/$nin0);?>px;"></div></div></td><th><?php echo $tnin.'/'.$nin0;?></th><th><?php echo ($up_nin>0?'+'.$up_nin.' train':'');?></th></tr>
		<tr><th><?php echo ($gen0-$geni?'+'.($gen0-$geni):'');?></th><th>Genjutsu</th><td><div id="bp"><div id="bt" style="width:<?php echo round($tgen*100/$gen0);?>px;"></div></div></td><th><?php echo $tgen.'/'.$gen0;?></th><th><?php echo ($up_gen>0?'+'.$up_gen.' train':'');?></th></tr>
	<?php }?>
</table>
<?php include("footer.php");?>