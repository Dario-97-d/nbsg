<?php include("header.php");
// Logout
if(isset($_GET['log'])&&$_GET['log']=='out'){session_destroy();exit(header("Location: index"));}
// Login
if(isset($_POST['login'])){
	if(isset($_SESSION['uid'])){exit(header("Location: overview"));}
	else{
		$username = handle_name($_POST['username']);
		if(strlen($username)>16){echo $username;} // $username returns error
		else{
			$password = md5($_POST['password']);
			$checkuser = mysqli_query($conn, "SELECT id FROM user WHERE name='$username' AND password='$password'") or die(mysqli_error($conn));
			if(mysqli_num_rows($checkuser) != 1){echo "Invalid Username / Password Combination";}
			else{
				$uid = mysqli_fetch_assoc($checkuser);
				$_SESSION['uid'] = $uid['id'];
				exit(header("Location: home"));
			}
		}
	}
}
if(!isset($_SESSION['uid'])){
	?>
	<h1>Login</h1>
	<form action="index" method="POST">
		Username: <br /><input type="text" name="username"/><br />
		Password: <br /><input type="password" name="password"/><br />
		<br /><input type="submit" class="button1" name="login" value="Login"/>
	</form>
	<a href="signup">Sign up</a>
	<hr>
	<?php
}
echo "<br />Hachi-maki";
include("footer.php");?>