<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/char/char-home.php';

  if ( ! isset( $_uid ) ) exiter('index');

  // -- Train Attribute --
  if ( isset( $_POST['upgrade-attribute'] ) )
  {
    CHAR_Home_increment_attribute( $_POST['upgrade-attribute'] );
  }

  $_char = CHAR_Home_get();

  $_disabled = $_char['sessions_needed_for_upgrade'] > $_char['training_sessions_for_use'] ? 'disabled' : '';

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $_char['username'] ?></h1>

<h3><?= $_char['style_name'] ?></h3>

<?= VIEW_Char_skills( $_char ) ?>

<h3>
  <a href="char-train">Train</a>
</h3>

<h4>
  Rank-<?= $_char['char_rank'] ?>
  
  <br />
  
  Lv <?= $_char['char_level'] ?>
</h4>

<form method="POST">
  <table class="">
    
    <tr>
      
      <th>Flair</th>
      
      <td style="text-align: right;"><?= $_char['flair'] ?></td>
      
      <td>
        <button type="submit" name="upgrade-attribute" value="flair" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Critical</td>
      
    </tr>
    
    <tr>
      
      <th>Power</th>
      
      <td style="text-align: right;"><?= $_char['strength'] ?></td>
      
      <td>
        <button type="submit" name="upgrade-attribute" value="strength" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Strength</td>
      
    </tr>
    
    <tr>
      
      <th>Speed</th>
      
      <td style="text-align: right;"><?= $_char['agility'] ?></td>
      
      <td>
        <button type="submit" name="upgrade-attribute" value="agility" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Reach</td>
      
    </tr>
    
    <tr>
      
      <th>Jutsu</th>
      
      <td style="text-align: right;"><?= $_char['jutsu'] ?></td>
      
      <td>
        <button type="submit" name="upgrade-attribute" value="jutsu" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Skill</td>
      
    </tr>
    
    <tr>
      
      <th>Tactics</th>
      
      <td style="text-align: right;"><?= $_char['tactics'] ?></td>
      
      <td>
        <button type="submit" name="upgrade-attribute" value="tactics" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Planning</td>
      
    </tr>
    
    <tr><th colspan="4"></th></tr>
    
    <tr><th colspan="4">Attribute points</th></tr>
    
    <tr>
      <td style="text-align: right;">Needs:</td>
      
      <td><?= $_char['sessions_needed_for_upgrade'] ?></td>
      
      <td colspan="2" style="text-align: center;">Has: <b><?= $_char['training_sessions_for_use'] ?></b> /50</td>
    </tr>
    
  </table>
</form>
