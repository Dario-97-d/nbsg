<?php include("headeron.php");
if(is_int($pmid=array_search('Delete', $_POST))){
	sql_query($conn,"UPDATE mailbox SET seen=2 WHERE pmid='$pmid'");
	echo "PM deleted";
}
if(is_int($pmid=array_search('Set as Seen', $_POST))){
	sql_query($conn,"UPDATE mailbox SET seen=1 WHERE pmid='$pmid'");
	echo "PM seen";
}
?>
<h1>Mailbox</h1>
<h2><a href="mailbox">Mailbox</a> || <a href="sendpm">Send pm</a> || <a href="pmsent">PMs sent</a></h2>
<?php
$getpms=sql_query($conn,"SELECT m.*,u.id FROM mailbox m LEFT JOIN user u ON m.pmto=u.name WHERE id=$uid AND seen<>2");
if(mysqli_num_rows($getpms)<1){echo "Mailbox is empty";}
else{
	while($pms=mysqli_fetch_assoc($getpms)){
		echo "<b>".date("d/m H:i:s",$pms['time'])."</b> || ";
		echo '<b>From:</b> <a href="nin?id='.$pms['id'].'">'.$pms['pmfrom']."</a> ";
		echo '<b>'.($pms['seen']==0?'Not s':'S').'een</b>';
		?>
		 || <a href="sendpm?to=<?php echo $pms['pmfrom'];?>">Reply</a>
		<textarea name="pmtext" disabled><?php echo nl2br($pms['pmtext']);?></textarea>
		<form action="mailbox" method="POST"><table align="center">
			<?php echo ($pms['seen']==1?'':'<td><input type="submit" name="'.$pms['pmid'].'" value="Set as Seen"/></td>');?>
			<td><input type="submit" name="<?php echo $pms['pmid'];?>" value="Delete"/></td>
		</table></form>
		<?php
	}
}
include("footer.php");?>