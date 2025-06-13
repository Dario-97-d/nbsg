<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/team/team-exam.php';
  
  if ( ! isset( $_uid ) ) exiter('index');
  
  $_char = TEAM_get_char();
  
  if ( ! TEAM_is_full( $_char ) ) exiter('team-meet');
  
  $_team_members = TEAM_get_members( $_char['teammate1_id'], $_char['teammate2_id'] );
  
  $_team_skills = TEAM_get_skills( $_team_members );
  
  $_bar_widths = TEAM_get_bar_widths( $_team_skills );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>
  Team
  <br />
  <?= $_char['username'] ?>
</h1>

<table align="center" style="text-align: center" cellpadding="8" cellspacing="0">
  <tr>
    <th>Clan</th>
    <th>Nin</th>
    <th>Lv</th>
    <th>Jutsu</th>
  </tr>
  
  <?php foreach ( $_team_members as $row )
  {
    ?>
    <tr>
      
      <td><?= $row['style_name'] ?></td>
      
      <td>
        <a href="char-profile?id=<?= $row['char_id'] ?>">
          <?= $row['username'] ?>
        </a>
      </td>
      
      <td><?= $row['char_level'] ?></td>
      
      <th>
        <?=
          $row['kenjutsu']
          .' • '.
          $row['shuriken']
          .' • '.
          $row['taijutsu']
          .' • '.
          $row['ninjutsu']
          .' • '.
          $row['genjutsu']
        ?>
      </th>
      
    </tr>
    <?php
  }
  ?>
</table>

<h3>
  Joint Skills
  
  <br />
  
  <?=
    $_team_skills['kenjutsu']
    .' • '.
    $_team_skills['shuriken']
    .' • '.
    $_team_skills['taijutsu']
    .' • '.
    $_team_skills['ninjutsu']
    .' • '.
    $_team_skills['genjutsu']
  ?>
</h3>

<table class="table-team" align="center">
  
  <tr>
    <th>Kenjutsu</th>
    
    <td>
      <div id="ttd" style="width: <?= round( $_bar_widths['kenjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Shuriken</th>
    
    <td>
      <div id="ttd" style="width: <?= round( $_bar_widths['shuriken'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Taijutsu</th>
    
    <td>
      <div id="ttd" style="width: <?= round( $_bar_widths['taijutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Ninjutsu</th>
    
    <td>
      <div id="ttd" style="width: <?= round( $_bar_widths['ninjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Genjutsu</th>
    
    <td>
      <div id="ttd" style="width: <?= round( $_bar_widths['genjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
</table>

<br />

<form action="team-exam-joint" method="POST">
  
  <button type="submit" name="go-team-exam">Team Battle</button>
  
</form>

3v3 battle
<br />
standard bots
