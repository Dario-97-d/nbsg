<html lang="en">

<head>
  <link rel="stylesheet" type="text/css" href="css/layout.css" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  
  <meta charset="UTF-8" />
  
  <title>Hachimaki</title>
</head>

<body>

<div class="container">
  
  <header class="header">
    <?php if ( isset( $_uid ) )
    {
      ?>
      <a href="logout" class="header-logout-link">Log out</a>
      <?php
    }
    ?>
    
    <a href="index" class="header-index-link"><b>8-maki</b></a>
  </header>

  <nav class="menu">
    <?php if ( isset( $_uid ) )
    {
      ?>
      
      <h4><a href="char-home">HOME</a></h4>
      <h4><a href="clan-hall">CLAN</a></h4>
      <h4><a href="team-meet">TEAM</a></h4>
      <h4><a href="rank-chars">RANK</a></h4>
      
      <!--
      <br />
      <h4><a href="char-bonds">BONDS</a></h4>
      <h4><a href="sato-squad">SQUAD</a></h4>
      <h4><a href="sato-intel">INTEL</a></h4>
      -->
      
      <br />
      
      <h4><a href="mail-received">MAIL</a></h4>
      <!--
      <h4><a href="mail-sent">SENT</a></h4>
      <h4><a href="mail-write">SEND</a></h4>
      -->
      
      <br />
      
      <h4><a href="user-account">Account</a></h4>
      
      <?php
    }
    ?>
  </nav>
  
  <div class="content">
    
    <?= JS_render_messages() ?>
    
    <?= $_LAYOUT_VIEW_CONTENT ?>
    
  </div>

  <footer class="footer">
    <!--8-maki &copy; 2018-->
    Server time: <?= date( "H:i:s", time() ) ?>
  </footer>
  
</div>

</body>
</html>