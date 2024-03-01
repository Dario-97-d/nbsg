<?php include("header.php");
if(isset($_SESSION['uid'])){exiter("overview");}
$username='';$email='';
if(isset($_POST['signup'])){
	$username=handle_name($_POST['username']);
	if(strlen($username)>16){echo $username;} // $username returns error
	elseif(mysqli_num_rows(sql_query($conn,"SELECT id FROM user WHERE name='$username'"))>0){echo$username." already in use";}
	else{
		$email=handle_email($_POST['email']);
		if(strlen($email)>48){echo $email;}// $email returns error
		elseif(mysqli_num_rows(sql_query($conn,"SELECT id FROM user WHERE email='$email'"))>0){echo$email." already in use";}
		else{
			$password=$_POST['password'];$slpw=strlen($password);
			if($slpw<8||$slpw>32){echo"Password must be 8-32 chars long";}
			else{
				mysqli_multi_query($conn,"
					INSERT INTO user (vid,name,rank,password,email) VALUES (1,'$username','E','".md5($password)."','$email');
					INSERT INTO atts (nrg,level,need,tss,fla,pow,agi,jut,tac) VALUES (100,1,6,50,1,1,1,1,1);
					INSERT INTO clan (style,ken,shu,tai,nin,gen,skp) VALUES ('',1,1,1,1,1,50);
					INSERT INTO styl (tken,tshu,ttai,tnin,tgen,tskl,ntrain,ready) VALUES (0,0,0,0,0,'',0,0);
					INSERT INTO stat (wins,patrol,anbu) VALUES (0,0,0);
					INSERT INTO team (nin1,nin2,joint) VALUES (0,0,0);
				") or die(mysqli_error($conn));
				while(mysqli_next_result($conn)){}
				$uid=sql_mfa($conn,"SELECT id FROM user WHERE name='$username'");
				$_SESSION['uid']=$uid['id'];
				exiter("clan");
			}
		}
	}
}
?>
<h1>Sign up</h1>
<form action="signup" method="POST">
	Username: <br /><input type="text" name="username" value="<?php echo $username;?>"/><br />
	Password: <br /><input type="password" name="password"/><br />
	E-mail: <br /><input type="email" name="email" value="<?php echo $email;?>"/><br />
	<br /><input type="submit" class="button1" name="signup" value="Start"/>
</form>
<?php include("footer.php");?>