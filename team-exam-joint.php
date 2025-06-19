<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/team/team-exam.php';
  
  if ( ! isset( $_uid ) ) exiter('index');
  
  if ( ! TEAM_Exam_is_allowed() ) exiter('team-meet');
  
  $_char = TEAM_Exam_get_char();
  
  $_teammates = TEAM_Exam_get_mates( $_char );
  
  $_team_skills = TEAM_get_skills( [ $_char, ...$_teammates ] );
  
  $_upgrade = TEAM_Exam_upgrade_char_attributes( $_char, $_team_skills );
  
  $_is_exam_passed = TEAM_Exam_is_passed( $_char, $_teammates, $_team_skills );
  
  $_bots_bar_widths = TEAM_Exam_get_bots_skill_ratios();
  $_team_bar_widths = TEAM_Exam_get_team_skill_ratios( $_team_skills );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team Exam</h1>

<table class="table-generic">
  
  <tr>
    <th width="33%">
      Team
      <br />
      <?= $_char['username'] ?>
    </th>
    
    <th width="10%">Lv</th>
    <td width="14%">VS</td>
    <th width="10%">Lv</th>
    
    <th width="33%">
      Team
      <br />
      Botuzo
    </th>
  </tr>
  
  <?php
  $i = 0;
  foreach ( $_teammates as $row )
  {
    $i++;
    ?>
    <tr>
      
      <td><?= $row['username']   ?></td>
      
      <td><?= $row['char_level'] ?></td>
      
      <td></td>
      
      <td>10</td>
      
      <td>BotNin_<?= $i ?></td>
      
    </tr>
    <?php
  }
  ?>
</table>

<table class="">
  <tr>
    <td width="33%"></td>
    <td width="34%"></td>
    <td width="33%"></td>
  </tr>
  
  <tr>
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_team_bar_widths['kenjutsu'] ) ?>px; float: right"></div>
    </td>
    
    <th>Kenjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bots_bar_widths['kenjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_team_bar_widths['shuriken'] ) ?>px; float: right"></div>
    </td>
    
    <th>Shuriken</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bots_bar_widths['shuriken'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_team_bar_widths['taijutsu'] ) ?>px; float: right"></div>
    </td>
    
    <th>Taijutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bots_bar_widths['taijutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_team_bar_widths['ninjutsu'] ) ?>px; float: right"></div>
    </td>
    
    <th>Ninjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bots_bar_widths['ninjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_team_bar_widths['genjutsu'] ) ?>px; float: right"></div>
    </td>
    
    <th>Genjutsu</th>
    
    <td>
      <div class="team-skill-bar" style="width: <?= round( $_bots_bar_widths['genjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
</table>

<?php if ( $_is_exam_passed )
{
  ?>
  <h4>
    Team exam is done.
    <br />
    A pvp battle will be prepared to proceed graduation.
  </h4>
  <?php
}
else
{
  ?>
  <h4>You didn't make it this time.</h4>
  <?php
}
?>

<table>
  
  <tr>
    <th colspan="2">
      Lv: <?= ( $_char['char_level'] + 2 ) ?>
    </th>
  </tr>
  
  <tr>
    <td>Flair</td>
    
    <td>
      <?= $_char['flair'] ?>
    </td>
    
    <td>+<?= $_upgrade['flair'] ?></td>
  </tr>
  
  <tr>
    <td>Power</td>
    
    <td>
      <?= $_char['strength'] ?>
    </td>
    
    <td>+<?= $_upgrade['strength'] ?></td>
  </tr>
  
  <tr>
    <td>Speed</td>
    
    <td>
      <?= $_char['agility'] ?>
    </td>
    
    <td>+<?= $_upgrade['agility'] ?></td>
  </tr>
  
  <tr>
    <td>Jutsu</td>
    
    <td>
      <?= $_char['jutsu'] ?>
    </td>
    
    <td>+<?= $_upgrade['jutsu'] ?></td>
  </tr>
  
  <tr>
    <td>Tactics</td>
    
    <td>
      <?= $_char['tactics'] ?>
    </td>
    
    <td>+<?= $_upgrade['tactics'] ?></td>
  </tr>
  
</table>
