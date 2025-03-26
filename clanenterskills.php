<?php include("headeron.php");
extract(mysqli_fetch_assoc(sql_query($conn, "SELECT * FROM clan WHERE id=$uid")));
if($style!=''){exiter("clan");}
if(!isset($_POST['xc'])){exiter("clanenter");}
$clan=(in_array($_POST['xc'],['Tameru','Tayuga','Kensou','Surike','Geniru','Faruni','Wyroni','Raiyni','Rokuni','Watoni'])?$_POST['xc']:'shit');
if($clan=='shit'){exiter("clanenter");}
if(isset($_POST['start'])){$skills=explode(',',$_POST['skills']);if($skills[5]==0){unset($skills[5]);if(array_sum($skills)!=10||($clan=='Tameru'&&($skills[3]>0||$skills[4]>0))){exiter("clanenter");}else{mysqli_multi_query($conn,"UPDATE clan SET style='$clan',ken=$skills[0],shu=$skills[1],tai=$skills[2],nin=$skills[3],gen=$skills[4] WHERE id=$uid;UPDATE user SET rank='D' WHERE id=$uid;") or die(mysqli_error($conn));}}exiter("clanenter");}
if(isset($_POST['skills'])){$skills=explode(',',$_POST['skills']);if($skills[5]<1){echo "Can't upgrade more";/* echo não funciona: parece que com F5(refresh) POST[skills] is!set */}else{switch(array_search('+1',$_POST)){ default: exiter("clanenter");case 'ken': $skills[0]+=1;$skills[5]-=1;break;case 'shu': $skills[1]+=1;$skills[5]-=1;break;case 'tai': $skills[2]+=1;$skills[5]-=1;break;case 'nin': if($style=='Tameru'){exiter("clanenter");}$skills[3]+=1;$skills[5]-=1;break;case 'gen': if($style=='Tameru'){exiter("clanenter");}$skills[4]+=1;$skills[5]-=1;break;}}}
else{switch($clan){default: echo 'switch Error';break; case 'Faruni': case 'Wyroni': case 'Raiyni': case 'Rokuni': case 'Watoni': $skills=[1,1,1,3,1,3];break; case 'Tameru': $skills=[1,1,5,0,0,3];break; case 'Tayuga': $skills=[1,1,3,1,1,3];break; case 'Kensou': $skills=[3,1,1,1,1,3];break; case 'Surike': $skills=[1,3,1,1,1,3];break; case 'Geniru': $skills=[1,1,1,1,3,3];break;}}?>
<h1><?php echo $clan;?></h1>
<form method="POST"><input type="hidden" name="xc" value="<?php echo $clan;?>"><input type="submit" value="Restart"></form>
<table class="table-skill" align="center">
	<tr><th title="Sword Skill">kenjutsu</th><th title="Shuriken Skill">shuriken</th><th title="Melee Skill">taijutsu</th><th title="Elemental Skill">ninjutsu</th><th title="Illusion Skill">genjutsu</th></tr>
	<tr><td><?php echo $skills[0].'</td><td>'.$skills[1].'</td><td>'.$skills[2].'</td><td>'.$skills[3].'</td><td>'.$skills[4];?></td></tr>
	<?php if($skills[5]>0){?>
		<tr><form method="POST">
			<input type="hidden" name="xc" value="'.$clan.'">
			<input type="hidden" name="skills" value="<?php echo implode(",",$skills);?>">
			<td><input type="submit" name="ken" value="+1"></td>
			<td><input type="submit" name="shu" value="+1"></td>
			<td><input type="submit" name="tai" value="+1"></td>
			<?php echo($clan=='Tameru'?'<td colspan="2">No chakra control</td>':
			'<td><input type="submit" name="nin" value="+1"></td>
			<td><input type="submit" name="gen" value="+1"></td>'
			);?>
		</form></tr>
</table>
<?php if($skills[5]>0){echo"<h3>Still left to upgrade: $skills[5]</h3>";}
else{?>
	<br /><form method="POST"><input type="hidden" name="xc" value="<?php echo $clan?>"><input type="hidden" name="skills" value="<?php echo implode(",",$skills)?>"><input type="submit" name="start" value="Start"></form>
<?php }
include("footer.php");?>