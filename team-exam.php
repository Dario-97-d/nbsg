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

<table class="table-generic">
  <tr>
    <th>Clan</th>
    <th>Char</th>
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
        <?= VIEW_Skills_inline( $row ) ?>
      </th>
      
    </tr>
    <?php
  }
  ?>
</table>

<h3>
  Joint Skills
  
  <br />
  
  <?= VIEW_Skills_inline( $_team_skills ) ?>
</h3>

<table class="">
  
  <tr>
    <th>Kenjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bar_widths['kenjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Shuriken</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bar_widths['shuriken'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Taijutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bar_widths['taijutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Ninjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bar_widths['ninjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Genjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bar_widths['genjutsu'] ) ?>px"></div>
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
