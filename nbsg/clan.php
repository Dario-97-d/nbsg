<?php include("headeron.php");
extract(mysqli_fetch_assoc(sql_query($conn,"SELECT style FROM clan WHERE id=$uid")));
if($style==''){?>
	<h1>Clan</h1>
	<p style="padding:0 32px;">
		Clans determine char's lineage.
		<br />
		Each clan has a specific set of characteristics,
		<br />
		making them more suited to certain fighting styles.
		<br />
		As an example, all Tameru are incapable of manipulating chakra,
		<br />
		so they must resort to tools and close body combat.
		<br />
		However, they are capable of opening the body chakra gates,
		<br />
		so they have unusually high physical capabilities.
	</p>
	<h3><a href="clanenter">Choose Clan</a></h3>
	<?php
}else{
	?>
	<h1><?php echo $style;?></h1>
	<h4>Clan members gather in the village</h4>
	<h2><a href="clantrain">Train</a></h2>
	<table align="center" style="text-align:center;" cellpadding="8" cellspacing="0">
		<tr><th>rank</th><th>Lv</th><th>Nin</th><th>Send</th></tr>
		<?php
		$getplayers = sql_query($conn, "SELECT u.id,rank,name,level FROM user u JOIN atts a ON u.id=a.id JOIN clan c ON u.id=c.id WHERE style='$style' ORDER BY rank, level DESC LIMIT 25");
		while($row=mysqli_fetch_assoc($getplayers)){
			echo '<tr'.($uid==$row['id']?' style="outline:1px solid #0033CC;"':'').'><td>'.$row['rank'].'</td><td>'.$row['level'].'</td>
			<td><a href="nin?id='.$row['id'].'">'.$row['name'].'</a></td>
			<td><a href="sendpm?to='.$row['name'].'">PM</a></td></tr>';
		}
		?>
	</table>
<?php }
include("footer.php");?>