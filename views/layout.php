<html>

<head>
  <title>Hachimaki</title>
  
  <link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="hfer">
  <?php if ( isset( $_uid ) )
  {
    ?>
    <a style="float: left; padding-left: 16px;" href="logout">Log out</a>
    <?php
  }
  ?>
  
  <a href="index"><b>8-maki</b></a>
</div>
  
<div id="container">
  
  <div id="menu">
    <?php if ( isset( $_uid ) )
    {
      ?>
      
      <h5><a href="char-home" title="Overview" accesskey="h">HOME</a></h5>
      <h5><a href="clan-hall" title="Lineage" accesskey="c">CLAN</a></h5>
      <h5><a href="team-meet" title="Team" accesskey="t">TEAM</a></h5>
      <h5><a href="rank-chars" title="Ranking" accesskey="r">RANK</a></h5>
      
      <!--
      <br />
      <h5><a href="sato-squad" title="" accesskey="s">SQUAD</a></h5>
      <h5><a href="sato-intel" title="" accesskey="i">INTEL</a></h5>
      <h5><a href="char-bonds" title="Family" accesskey="b">BONDS</a></h5>
      -->
      
      <br />
      
      <h5><a href="mail-received" accesskey="m">MAIL</a></h5>
      <h5><a href="mail-sent">SENT</a></h5>
      <h5><a href="mail-write">SEND</a></h5>
      <br />
      <h5><a href="user-account" accesskey="a">Account</a></h5>
      
      <?php
    }
    ?>
  </div>
  
  <div id="content">
    
    <?= JS_render_messages() ?>
    
    <?= $_LAYOUT_VIEW_CONTENT ?>
    
  </div>
</div>

<div class="hfer">
  <!--8-maki &copy; 2018-->
  Server time: <?= date( "H:i:s", time() ) ?>
</div>

</body>
</html>