<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/team/team-train.php';
  
  if ( ! isset( $_uid ) ) exiter('index');
  
  $_char = TEAM_Train_get_char();
  
  if ( ! TEAM_is_full( $_char ) ) exiter('team-meet');
  
  $_teammates = TEAM_Train_get_mates( $_char );
  
  $_team_skills = TEAM_get_skills( [ $_char, ...$_teammates ] );
  
  $_bar_widths = TEAM_get_bar_widths( $_team_skills );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team <?= $_char['username'] ?></h1>

<h4>
  In a forest between villages,
  <br />
  room is there for chars to train.
</h4>

<?= VIEW_Char_skills( $_char ); ?>

<h2>Rank-<?= $_char['char_rank'] ?></h2>

<table class="table-generic">
  <tr>
    <th>Clan</th>
    <th>Lv</th>
    <th>Char</th>
    <th>Jutsu</th>
  </tr>
  
  <?php
  $i = 0;
  foreach ( $_teammates as $row )
  {
    $i++;
    ?>
    <tr>
      
      <td><?= $row['style_name'] ?></td>
      
      <td><?= $row['char_level'] ?></td>
      
      <td>
        <a href="char-profile?id=<?= $row['char_id'] ?>">
          <?= $row['username'] ?>
        </a>
      </td>
      
      <th>
        <?= VIEW_Skills_inline( $row ) ?>
      </th>
      
    </tr>
    <?php
  }
  ?>
</table>

<h3>Joint Skills</h3>

<?= VIEW_Skills_inline( $_team_skills ) ?>

<br />
<br />

<table class="">
  
  <tr>
    <th>Kenjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= $_bar_widths['kenjutsu'] ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Shuriken</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= $_bar_widths['shuriken'] ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Taijutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= $_bar_widths['taijutsu'] ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Ninjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= $_bar_widths['ninjutsu'] ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Genjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= $_bar_widths['genjutsu'] ?>px"></div>
    </td>
  </tr>
  
</table>

<br />

<form action="team-train-skill" method="POST">
  <button type="submit" name="team-train-skill">Train</button>
</form>
