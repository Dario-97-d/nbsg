<?php include("headeron.php");
// check id was provided as int
if(!is_int($pid=array_search('Train', $_POST))){exiter"Location: clandojo");}
if($uid==$pid){exiter("nin?id=$pid");}
// check skill was given
$skills=['ken'=>'Kenjutsu','shu'=>'Shuriken','tai'=>'Taijutsu','nin'=>'Ninjutsu','gen'=>'Genjutsu']
if(in_array($_POST['skill'],$skills)){$skill=array_search($_POST['skill'],$skills)}else{exiter("nin?id=$pid");}
echo $skill;
// START functions
// randomize att to be upgraded
function upatt($c,$a,$m,$b){
	if(in_array($c,['fla','pow','agi','jut','tac'])){$upatt=$c;}
	elseif($c==3){if($e=substr(microtime(true),-4)!=0){$d=$e%3;}else{$d=date("s",time())%3;}}
	else{$d=substr(microtime(true),-2)%$c;}
	foreach($b as $key => $value){if($key==$d){$upatt=$value;}}
	return $upatt."=".$upatt."+1";
}
// upgrade atts (bup: battle upgrade)
function sql_bup($conn,$setupatt,$uid){mysqli_query($conn,"UPDATE atts SET $setupatt WHERE id=$uid") or die(mysqli_error($conn));}
// END functions
// fetch atts from user and player
extract(sql_mfa($conn,"SELECT a.*,c.*,name FROM atts a JOIN clan c ON a.id=c.id JOIN user u ON a.id=u.id WHERE a.id=$uid"),EXTR_PREFIX_ALL,"u");
extract(sql_mfa($conn,"SELECT a.*,c.*,name FROM atts a JOIN clan c ON a.id=c.id JOIN user u ON a.id=u.id WHERE a.id=$pid"),EXTR_PREFIX_ALL,"p");
// calc scores for battle
$uscore=$u_fla+$u_pow+$u_agi+$u_jut+$u_tac;$pscore=$p_fla+$p_pow+$p_agi+$p_jut+$p_tac;
// determine result, based on (user/player) _total_atts
$result=$uscore/$pscore*8;
// go back if too much difference
if($result<4 || $result>=12){exiter("nin?id=$pid");}
// relações de atributos
$fla=$u_fla/$p_fla;$pow=$u_pow/$p_pow;$agi=$u_agi/$p_agi;$jut=$u_jut/$p_jut;$tac=$u_tac/$p_tac;
// array com as relações
$a=["fla"=>$fla,"pow"=>$pow,"agi"=>$agi,"jut"=>$jut,"tac"=>$tac];
// isto - comentários à direita
$m=($result>8?'max':'min'); // victor: max; victum: min
$b=array_keys($a,$m($a)); // chaves dos valores correspondentes ao min/max das relações
// how many atts considered for upgrade, se 1 ent $c='att'
$c=($result>=6 && $result<10?'random':(count($b)>1?count($b):array_search($m($a),$a)));
// se $c= [att], ent siga; se há random, ent seja
if(in_array($c,['fla','pow','agi','jut','tac'])){if($result>=4&&$result<12){$setupatt=$c."=".$c."+1";}else{$setupatt=$upgrade='';}}
elseif('random'){
	// random number from microtime two chars from last three chars (diferente do upatt, que é -2 em vez de -3,2)
	switch(substr(microtime(true),-3,2)%5){
		default: $rupatt="microtime Error";break;
		case 0: $rupatt="fla=fla+1";break;
		case 1: $rupatt="pow=pow+1";break;
		case 2: $rupatt="agi=agi+1";break;
		case 3: $rupatt="jut=jut+1";break;
		case 4: $rupatt="tac=tac+1";break;
	}
	// check draw, if so then just upgrade $rupatt
	if($result<7 || $result>=9){
		// no draw, so let's upgrade $rupatt + $upatt
		$c=(count($b)>1?count($b):array_search($m($a),$a));
		$upatt=upatt($c,$a,$m,$b);
		// just in case $rupatt == $upatt
		if($rupatt==$upatt){$setupatt=str_replace(1,2,$upatt);}
		else{$setupatt=$rupatt.','.$upatt;}
	}else{$setupatt=$rupatt;}
}else{die(print "c Error");}
$atts=['fla'=>'Flair','pow'=>'Power','agi'=>'Speed','jut'=>'Jutsu','tac'=>'Tactics'];
// result !!
switch(true){
	default: echo "switch_result Error";break;
	case ($result<6): $placard="Major Loss";sql_bup($conn,$setupatt=upatt($c,$a,'min',$b),$uid);break;
	case ($result<7): $placard="Minor Loss";sql_bup($conn,$setupatt,$uid);break;
	case ($result<9): $placard="Draw";sql_bup($conn,$setupatt,$uid);/sql_query($conn,"UPDATE clan SET tai=tai+1 WHERE id=$uid");break;
	case ($result<10): $placard="Minor Win";sql_bup($conn,$setupatt,$uid);break;
	case ($result<12): $placard="Major Win";sql_bup($conn,$setupatt=upatt($c,$a,'max',$b),$uid);break;
}
// End message and '+1' after the att in table
$fla=$pow=$agi=$jut=$tac='';
// 1 or 2 diff atts
if(strlen($setupatt)==9){$att=substr($setupatt,0,3);$n=substr($setupatt,-1);$upgrade=$atts[$att]." +".$n;$$att="+".$n;}
elseif(strlen($setupatt)==19){$att1=substr($setupatt,0,3);$att2=substr($setupatt,10,3);$upgrade=$atts[$att1]." +1<br />".$atts[$att2]." +1";$$att1="+1";$$att2="+1";}
// switch do clantrainskill, antes de ser if/else e upa/rna
/*
switch(true){default: echo "switch_result Error";break;
	case ($result<4||$result>=12): $placard="The difference in skill is too wide<br />There's no point in training together";break;
	case ($result<6||$result>=10):
		$placard="There's some gap";
		sql_query($conn,"UPDATE atts SET $up_att=$up_att+1 WHERE id=$uid");
		$upgrade=$atts[$up_att]." +1";$$up_att="+1";
		break;
	case ($result>=6&&$result<10): switch(substr(microtime(true),-1)%5){default: "switch_microtime Error";break;case 0: $ranatt="fla";break;case 1: $ranatt="pow";break;case 2: $ranatt="agi";break;case 3: $ranatt="jut";break;case 4: $ranatt="tac";break;}
	case ($result<7||$result>=9):
		$placard="Good training";
		if($u_att/$p_att<$u_skill/$p_skill){
			if(floor(($u_fla+$u_pow+$u_agi+$u_jut+$u_tac+2)/5)>$u_level){$uplv='level=level+1,';$u_level+=1;$level='Lv: '.$u_level.'<br />';}else{$uplv='';}
			if($up_att==$ranatt){sql_query($conn,"Update atts SET $uplv $up_att=$up_att+2 WHERE id=$uid");$upgrade=$atts[$up_att]." +2";$$up_att="+2";}
			else{sql_query($conn,"Update atts SET $uplv $up_att=$up_att+1,$ranatt=$ranatt+1 WHERE id=$uid");$upgrade=$atts[$up_att]." +1<br />".$atts[$ranatt]." +1";$$up_att="+1";$$ranatt="+1";}
		}else{
			$$up_skill=1;
			sql_query($conn,"Update clan SET $up_skill=$up_skill+1 WHERE id=$uid");
			if(floor(($u_fla+$u_pow+$u_agi+$u_jut+$u_tac+1)/5)>$u_level){$uplv='level=level+1,';$u_level+=1;$level='Lv: '.$u_level.'<br />';}else{$uplv='';}
			sql_query($conn,"Update atts SET $uplv $ranatt=$ranatt+1 WHERE id=$uid");
			$$ranatt="+1";
			$upgrade=$skills[$up_skill]." +1<br />".$atts[$ranatt]." +1";
		}
		break;
	case ($result<9||$result>=7):
		$placard="Evenly matched";$$up_skill=1;
		sql_query($conn,"Update clan SET $up_skill=$up_skill+1 WHERE id=$uid");
		if(floor(($u_fla+$u_pow+$u_agi+$u_jut+$u_tac+2)/5)>$u_level){$uplv='level=level+1,';$u_level+=1;$level='Lv: '.$u_level.'<br />';}else{$uplv='';}
		if($up_att==$ranatt){sql_query($conn,"Update atts SET $uplv $up_att=$up_att+2 WHERE id=$uid");$upgrade=$skills[$up_skill]." +1<br />".$atts[$up_att]." +2";$$up_att="+2";}
		else{sql_query($conn,"Update atts SET $uplv $up_att=$up_att+1,$ranatt=$ranatt+1 WHERE id=$uid");$upgrade=$skills[$up_skill]." +1<br />".$atts[$up_att]." +1<br />".$atts[$ranatt]." +1";$$up_att="+1";$$ranatt="+1";}
		break;
}
if(floor(($u_fla+$u_pow+$u_agi+$u_jut+$u_tac+$nflv)/5)>$u_level){$uplv='sta=sta+10,cha=cha+10,level=level+1,';$u_level+=1;$level='Lv: '.$u_level.'<br />';}else{$uplv='';}
sql_query($conn,"UPDATE clan SET skp=skp-5");
*/
/*
if($result<4||$result>=12){$placard="The difference in use of skill is too wide<br />There's no point in training together";}
else{
	if($result<6||$result>=10){$placard="There's some gap";$upa=1;}
	else{
		switch(substr(microtime(true),-1)%5){default: "switch_microtime Error";break;case 0: $ranatt="fla";break;case 1: $ranatt="pow";break;case 2: $ranatt="agi";break;case 3: $ranatt="jut";break;case 4: $ranatt="tac";break;}
		if($result<7||$result>=9){$placard="Good training";if($u_level/$p_level<$u_skill/$p_skill){if($up_att==$ranatt){$upa=2;}else{$upa=1;$rna=1;}}else{$skl=1;$rna=1;}}
		else{$placard="Evenly matched";$skl=1;if($up_att==$ranatt){$upa=2;}else{$upa=1;$rna=1;}}
	}
	if(isset($upa)){$$up_att='+'.$upa;$upgrade_upa=$atts[$up_att].' +'.$upa;$set_up_att=$up_att.'='.$up_att.'+'.$upa;}else{$upa=0;$upgrade_upa='';$set_up_att='';}
	if(isset($rna)){$$ranatt='+'.$rna;$upgrade_rna=$atts[$ranatt].' +'.$rna;$set_ranatt=$ranatt.'='.$ranatt.'+'.$rna;}else{$rna=0;$upgrade_rna='';$set_ranatt='';}
	if(isset($skl)){$$up_skill=$skl;$upgrade_skl=$skill.' +1<br />';$set_up_skl=$up_skill.'='.$up_skill.'+1,';}else{$upgrade_skl='';$set_up_skl='';}
	$upgrade=$upgrade_skl.$upgrade_upa.($upa==1&&$rna==1?'<br />':'').$upgrade_rna;
	if(floor(($u_fla+$u_pow+$u_agi+$u_jut+$u_tac+$upa+$rna)/5)>$u_level){$uplv='level=level+1,';$u_level+=1;$level='Lv: '.$u_level.'<br />';}else{$uplv='';}
	sql_query($conn,"UPDATE atts SET $uplv $set_up_att".($rna==1?',':'')." $set_ranatt WHERE id=$uid");
	sql_query($conn,"UPDATE clan SET $set_up_skl skp=skp-5 WHERE id=$uid");
}*/
?>
<h1>Battle</h1>
<table align="center" style="text-align:center;">
	<tr><th><?php echo $u_name;?></th><th>VS</th><th><?php echo $p_name;?></th></tr>
	<tr><td><?php echo $u_style;?></td><th></th><td><?php echo $p_style;?></td></tr>
	<tr><td></td><th></th><td></td></tr>
	<tr>
		<th><?php echo $u_ken." • ".$u_shu." • ".$u_tai." • ".$u_nin." • ".$u_gen;?></th>
		<th>K•S•T•N•G</th>
		<th><?php echo $p_ken." • ".$p_shu." • ".$p_tai." • ".$p_nin." • ".$p_gen;?></th>
	</tr>
	<tr><td><?php echo $u_sta." - ".$u_cha;?></td><th>Stamina - Chakra</th><td><?php echo $p_sta." - ".$p_cha;?></td></tr>
	<tr><td></td><th></th><td></td></tr>
	<tr><td><?php echo $u_fla.$fla;?></td><th>Flair</th><td><?php echo $p_fla;?></td></tr>
	<tr><td><?php echo $u_pow.$pow;?></td><th>Power</th><td><?php echo $p_pow;?></td></tr>
	<tr><td><?php echo $u_agi.$agi;?></td><th>Speed</th><td><?php echo $p_agi;?></td></tr>
	<tr><td><?php echo $u_jut.$jut;?></td><th>Jutsu</th><td><?php echo $p_jut;?></td></tr>
	<tr><td><?php echo $u_tac.$tac;?></td><th>Tactics</th><td><?php echo $p_tac;?></td></tr>
</table>
<b>-- <?php echo $placard;?> --</b>
<br />Major Loss<br />The difference is still too significant.
<br />Minor Loss<br />You're getting closer.
<br />Draw<br />Too close to tell who's stronger.
<br />Minor Win<br />You're leaving this nin behind.
<br />Major Win<br />Is it worth it to keep training with this nin?
<br />
<?php echo $upgrade;
include("footer.php");?>