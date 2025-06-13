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

<table class="table-team" align="center" style="text-align: center;">
  
  <tr>
    <th width="33%">
      Team
      <br />
      <?= $_char['username'] ?>
    </th>
    
    <th width="10%">Lv</th>
    <th width="14%">VS</th>
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
  
  <tr><td colspan="5"></td></tr>
  
  <tr>
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_team_bar_widths['kenjutsu'] ) ?>px; float: right"></div>
    </td>
    
    <th>Kenjutsu</th>
    
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_bots_bar_widths['kenjutsu'] ) ?>px"></td>
    </tr>
  
  <tr>
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_team_bar_widths['shuriken'] ) ?>px; float: right"></div>
    </td>
    
    <th>Shuriken</th>
    
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_bots_bar_widths['shuriken'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_team_bar_widths['taijutsu'] ) ?>px; float: right"></div>
    </td>
    
    <th>Taijutsu</th>
    
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_bots_bar_widths['taijutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_team_bar_widths['ninjutsu'] ) ?>px; float: right"></div>
    </td>
    
    <th>Ninjutsu</th>
    
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_bots_bar_widths['ninjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_team_bar_widths['genjutsu'] ) ?>px; float: right"></div>
    </td>
    
    <th>Genjutsu</th>
    
    <td colspan="2">
      <div id="ttd" style="width: <?= round( $_bots_bar_widths['genjutsu'] ) ?>px"></div>
    </td>
  </tr>
  
</table>

<?php if ( $_is_exam_passed )
{
  ?>
  <h4>
    Team exam is done.
    <br />
    A pvp battle will be prepared to proceed graduation
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

<table align="center">
  
  <tr>
    <th colspan="2">
      Lv: <?= ( $_char['char_level'] + 2 ) ?>
    </th>
  </tr>
  
  <tr>
    <td title="Critical">Flair</td>
    
    <td>
      <?= $_char['flair'] ?>
    </td>
    
    <td>+<?= $_upgrade['flair'] ?></td>
  </tr>
  
  <tr>
    <td title="Strength">Power</td>
    
    <td>
      <?= $_char['strength'] ?>
    </td>
    
    <td>+<?= $_upgrade['strength'] ?></td>
  </tr>
  
  <tr>
    <td title="Reach">Speed</td>
    
    <td>
      <?= $_char['agility'] ?>
    </td>
    
    <td>+<?= $_upgrade['agility'] ?></td>
  </tr>
  
  <tr>
    <td title="Effect">Jutsu</td>
    
    <td>
      <?= $_char['jutsu'] ?>
    </td>
    
    <td>+<?= $_upgrade['jutsu'] ?></td>
  </tr>
  
  <tr>
    <td title="Planning">Tactics</td>
    
    <td>
      <?= $_char['tactics'] ?>
    </td>
    
    <td>+<?= $_upgrade['tactics'] ?></td>
  </tr>
  
</table>
