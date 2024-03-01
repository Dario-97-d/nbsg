<?php include("headeron.php");
extract(sql_mfa($conn,"SELECT * FROM clan c JOIN styl s ON c.id=s.id WHERE c.id=$uid"));
$trained='';$tskls=['tken'=>'ken','tshu'=>'shu','ttai'=>'tai','tnin'=>'nin','tgen'=>'gen'];$tskills=['tken'=>'Kenjutsu','tshu'=>'Shuriken','ttai'=>'Taijutsu','tnin'=>'Ninjutsu','tgen'=>'Genjutsu'];
if(!empty($_POST)){if($tskl==''){if(in_array($skl=array_search('Train',$_POST),['tken','tshu','ttai','tnin','tgen'])&&ctype_digit($n=$_POST['n'])&&$n>0&&$n<=${$tskls[$skl]}&&$n<11){$tskl=$skl;$ntrain=$n;$ready=time()+($n*1800);sql_query($conn,"UPDATE styl SET tskl='$skl',ntrain=$n,ready=$ready WHERE id=$uid");}}elseif(isset($_POST['end'])&&$ready>time()){$tdone=$ntrain-ceil(($ready-time())/1800);$skillupgrade='';}}elseif($tskl!=''&&$ready<=time()){$skillupgrade='';}
if(isset($skillupgrade)){if(isset($tdone)){$ntrain=$tdone;}if(($$tskl+=$ntrain)>=${$tskls[$tskl]}){$$tskl-=${$tskls[$tskl]};${$tskls[$tskl]}+=1;sql_query($conn,"UPDATE clan SET $tskls[$tskl]=$tskls[$tskl]+1 WHERE id=$uid");$skillupgrade="<br />$tskills[$tskl] +1";}sql_query($conn,"UPDATE styl SET $tskl=".$$tskl.",tskl='',ntrain=0,ready=0 WHERE id=$uid");$trained=($ntrain==0?'':"$tskills[$tskl] trained(+$ntrain)$skillupgrade");$tskl='';}?>
<h1><a href="hometrain">Training Grounds</a></h1>
<h3><?php echo $style;?></h3>
<table class="table-skill" align="center"><tr><th title="Sword Skill">kenjutsu</th><th title="Shuriken Skill">shuriken</th><th title="Melee Skill">taijutsu</th><th title="Elemental Skill">ninjutsu</th><th title="Illusion Skill">genjutsu</th></tr><tr><td><?php echo $ken.'</td><td>'.$shu.'</td><td>'.$tai.'</td><td>'.$nin.'</td><td>'.$gen;?></td></tr></table>
<?php if($tskl==''){?>
	<h4><?php echo $trained;?></h4>
	<!--<div style="padding:4px;"><b>Skill Points: <?php echo $skp;?>/50</b><br />Train: 5 SP<br />(ainda não gasta)</div>-->
	<table id="table-train" align="center" cellspacing="3">
		<tr><th>Kenjutsu</th><td><div id="bp"><div id="bt" style="width:<?php echo round($tken*100/$ken);?>px;"></div></div></td><th><?php echo $tken.'/'.$ken;?></th><form method="POST"><th><select name="n"><?php for($i=1;$i<=$ken&&$i<11;$i++){echo '<option>'.$i.'</option>';}?></select></th><th><input type="submit" name="tken" value="Train"/></th></form></tr>
		<tr><th>Shuriken</th><td><div id="bp"><div id="bt" style="width:<?php echo round($tshu*100/$shu);?>px;"></div></div></td><th><?php echo $tshu.'/'.$shu;?></th><form method="POST"><th><select name="n"><?php for($i=1;$i<=$shu&&$i<11;$i++){echo '<option>'.$i.'</option>';}?></select></th><th><input type="submit" name="tshu" value="Train"/></th></form></tr>
		<tr><th>Taijutsu</th><td><div id="bp"><div id="bt" style="width:<?php echo round($ttai*100/$tai);?>px;"></div></div></td><th><?php echo $ttai.'/'.$tai;?></th><form method="POST"><th><select name="n"><?php for($i=1;$i<=$tai&&$i<11;$i++){echo '<option>'.$i.'</option>';}?></select></th><th><input type="submit" name="ttai" value="Train"/></th></form></tr>
		<?php if($style!='Tameru'){?>
			<tr><th>Ninjutsu</th><td><div id="bp"><div id="bt" style="width:<?php echo round($tnin*100/$nin);?>px;"></div></div></td><th><?php echo $tnin.'/'.$nin;?></th><form method="POST"><th><select name="n"><?php for($i=1;$i<=$nin&&$i<11;$i++){echo '<option>'.$i.'</option>';}?></select></th><th><input type="submit" name="tnin" value="Train"/></th></form></tr>
			<tr><th>Genjutsu</th><td><div id="bp"><div id="bt" style="width:<?php echo round($tgen*100/$gen);?>px;"></div></div></td><th><?php echo $tgen.'/'.$gen;?></th><form method="POST"><th><select name="n"><?php for($i=1;$i<=$gen&&$i<11;$i++){echo '<option>'.$i.'</option>';}?></select></th><th><input type="submit" name="tgen" value="Train"/></th></form></tr>
		<?php }?>
	</table>
<?php }else{ ?>
	<br />
	Training:
	<br /><br />
	<b><?php echo $tskills[$tskl];?></b>
	<br /><br />
	<?php echo "$ntrain Sessions | ".($ntrain*30)." minutes";?>
	<br /><br />
	Time left:
	<br />
	<?php echo date("H:i:s", $ready-time());?>
	<br /><br />
	<form method="POST"><input type="submit" name="end" value="Stop"/></form>
	<p>Can't train with other nin when training alone</p>
<?php }
include("footer.php");?>