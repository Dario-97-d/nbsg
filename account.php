<?php include("headeron.php");
$getdata = sql_query($conn, "SELECT * FROM user WHERE id='$uid'");
extract($udata = mysqli_fetch_assoc($getdata));

if(isset($_POST['chemail'])){
	$pw = md5($_POST['pw']);
	if($pw != $password){echo "Wrong password";}
	else{
		$chemail = handle_email($_POST['chemail']);
		if(strlen($chemail) > 48 ){echo $chemail;} // $chemail returns error
		else{
			sql_query($conn, "UPDATE user SET email='$chemail' WHERE id=".$_SESSION['uid']."");
			echo "Email has been updated";
			$email = $chemail;
		}
	}
}elseif(isset($_POST['newpw'])){
	$oldpw = md5($_POST['oldpw']);
	if($oldpw != $password){echo "Wrong password";}
	else{
		$newpw = md5($_POST['newpw']);
		$slpw = strlen($newpw);
		if($slpw < 8 || $slpw > 32){echo "Password must be 8-32 chars long";}
		else{
			sql_query($conn, "UPDATE user SET password='$newpw' WHERE id=".$_SESSION['uid']."");
			echo "Password has been updated";
		}
	}
}

?>
<h1>Settings</h1>
<form action="account" method="POST">
	<br />New e-mail:<br /><input type="email" style="color:gray;width: 256px" name="chemail" value="<?php echo $email;?>" maxlength="48"/><br />
	<br />Password:<br /><input type="password" name="pw"/><br />
	<br /><input type="submit" value="Change E-mail"/><br />
</form>
<form action="account" method="POST">
	<br />Old password:<br /><input type="password" name="oldpw"/><br />
	<br />New password:<br /><input type="password" name="newpw"/><br />
	<br /><input type="submit" value="Change Password"/><br />
</form>
<?php include("footer.php");?>