<html>

<head>
	<title>Hachimaki</title>

	<link href="style.css" rel="stylesheet" type="text/css"/>
</head>

<body>

<div class="hfer">
	<?php
	if ( isset( $_uid ) )
	{
		?>
		<a style="float: left; padding-left: 16px;" href="index?log=out">Log out</a>
		<?php
	}
	?>
	
	<a href="index"><b>8-maki</b></a>
</div>

<div id="container">
	<div id="menu">
		<?php
		if ( isset( $_uid ) )
		{
			?>
			
			<h5><a href="home" title="Overview" accesskey="g">GERAL</a></h5>
			<h5><a href="bonds" title="Family" accesskey="b">BONDS</a></h5>
			<h5><a href="clan" title="Upgrades" accesskey="c">CLAN</a></h5>
			<h5><a href="ranking" title="Ranking" accesskey="r">RANK</a></h5>
			
			<br />
			<!--
			<h5><a href="train" title="" accesskey="h">TRAIN</a></h5>
			<h5><a href="bpvp" title="" accesskey="p">DOJO</a></h5>
			<h5><a href="team" title="" accesskey="t">TEAM</a></h5>
			<h5><a href="squad" title="" accesskey="s">SQUAD</a></h5>
			<h5><a href="intel" title="" accesskey="i">INTEL</a></h5>
			<br />
			-->
			
			<h5><a href="mailbox" accesskey="m">MAIL</a></h5>
			<h5><a href="pmsent">SENT</a></h5>
			<h5><a href="sendpm">SEND</a></h5>
			
			<br />
			
			<h5><a href="account" accesskey="a">Account</a></h5>
			
			<?php
		}
		else
		{
			?>
			<?php
		}
		?>
	</div>
	
	<div id="content"></div>
<div class="hfer">8-maki &copy; 2018</div>
<div id="over-content">Ainda não fiz isto, depois vejo