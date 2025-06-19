<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/clan/clan-train.php';

  if ( ! isset( $_uid ) ) exiter('index');

  // Ensure a char id was given.
  if ( ! isset( $_POST['char-id'] ) ) exiter('clan-train');

  // Ensure the given char id is not the user's id.
  if ( $_POST['char-id'] == $_uid ) exiter('char-profile?id='. $_uid);

  // Ensure a skill name was given.
  if ( ! isset( $_POST['skill-name'] ) ) exiter('clan-train');
  
  $_skill = strtolower( $_POST['skill-name'] );
  
  $_own   = CLAN_Train_get_own_char( $_skill );
  $_other = CLAN_Train_get_other_char( $_POST['char-id'] );
  
  $_training = CLAN_Train_with_other_char( $_own, $_other, $_skill );
  
  if ( ! is_array( $_training ) )
  {
    exiter('index');
  }

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $_own['style_name'] ?></h1>

<table class="">
  <tr>
    <th width="33%"><?= $_own[  'username'] ?></th>
    <th></th>
    <th width="33%"><?= $_other['username'] ?></th>
  </tr>
  
  <tr>
    <th><?=  $_own[  'char_level'] ?></th>
    <th>Lv</th>
    <th><?=  $_other['char_level'] ?></th>
  </tr>
  
  <tr>
    <th>
      <?= VIEW_Skills_inline( $_own ) ?>
    </th>
    
    <th>JUTSU</th>
    
    <th>
      <?= VIEW_Skills_inline( $_other ) ?>
    </th>
  </tr>
</table>

<br />

<?= VIEW_Char_skills( $_own ); ?>

<br />

<table cellspacing="4">
  <tr>
    
    <?php if ( $_training['skill_upgrade'] )
    {
      ?>
      <th>+ <?= $_training['skill_upgrade'] ?></th>
      <?php
    }
    ?>
    
    <th><?= ucfirst( $_skill ) ?></th>
    
    <td>
      <div class="skill-training-frame">
        <div class="skill-training-bar" style="width: <?= round( $_own[ $_skill .'_points'] * 100 / $_own[ $_skill ] ) ?>px;"></div>
      </div>
    </td>
    
    <th><?= $_own[ $_skill .'_points'] .'/'. $_own[ $_skill ] ?></th>
    
    
    <th>+<?= $_training['trained_points'] ?></th>
  
  </tr>
</table>

<?php if ( $_training['level_up'] )
{
  ?>
  Level UP!
  <br />
  <?php
}
?>

<table>
  <tr>
    <th colspan="3">Lv <?= $_own['char_level'] ?></th>
  </tr>
  
  <tr>
    <th>Stat</th>
    <th width="50px">#</th>
    <td>Effect</td>
  </tr>
  
  <tr>
    <th>Flair</th>
    <th><?= $_own['flair'] ?></td>
    <td>Critical</td>
  </tr>
  
  <tr>
    <th>Power</th>
    <th><?= $_own['strength'] ?></td>
    <td>Strength</td>
  </tr>
  
  <tr>
    <th>Speed</th>
    <th><?= $_own['agility'] ?></td>
    <td>Reach</td>
  </tr>
  
  <tr>
    <th>Jutsu</th>
    <th><?= $_own['jutsu'] ?></td>
    <td>Skill</td>
  </tr>
  
  <tr>
    <th>Tactics</th>
    <th><?= $_own['tactics'] ?></td>
    <td>Planning</td>
  </tr>
  
</table>
