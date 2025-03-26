<?php include("headeron.php");
$player='';
$pmtext='';
if(isset($_GET['to'])){$player=$_GET['to'];if(strlen($perr=handle_name($_GET['to']))>16){$pmtext=$perr;}}
if(isset($_POST['sendpm'])){
	$player = $_POST['pmto'];
	$pmto = handle_name($player);
	$pmtext = $_POST['pmtext'];
	if(strlen($pmto)>16){echo $pmto;} // $pmto returns error
	elseif(mysqli_num_rows(sql_query($conn, "SELECT id FROM user WHERE name='$pmto'"))!=1){echo $pmto." not found";}
	else{
		$slpmt=strlen($pmtext);
		if($slpmt < 1 || $slpmt > 800){echo "Number of chars in text must be 1-800";} // 800 ?
		else{
			$user=mysqli_fetch_assoc(sql_query($conn, "SELECT name FROM user WHERE id='$uid'"));
			sql_prepstate($conn,
			"INSERT INTO mailbox (time,pmfrom,pmto,pmtext,seen) VALUES ('".time()."','".$user['name']."','$pmto',?,0)",
			"s","".$pmtext."");
			echo "PM sent";
		}
	}
}
?>
<h1>Private Message</h1>
<h2><a href="mailbox">Mailbox</a> || <a href="sendpm">Send pm</a> || <a href="pmsent">PMs sent</a></h2>
<form action="sendpm" method="POST">
	PM to:<br /><input type="text" style="text-align:center;" name="pmto" value="<?php echo $player;?>"/>
	<br /><br />
	<textarea name="pmtext" maxlength="800"><?php echo $pmtext;?></textarea>
	<br /><br />
	<input type="submit" name="sendpm" value="Send"/>
</form>
<?php include("footer.php");?>