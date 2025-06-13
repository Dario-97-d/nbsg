<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/char/char-home.php';

  if ( ! isset( $_uid ) ) exiter('index');

  // -- Train Attribute --
  if ( isset( $_POST['train-attribute'] ) )
  {
    CHAR_Home_increment_attribute( $_POST['train-attribute'] );
  }

  $_char = CHAR_Home_get();

  $_disabled = $_char['sessions_needed_for_upgrade'] > $_char['training_sessions_for_use'] ? 'disabled' : '';

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $_char['username'] ?></h1>

<h3><?= $_char['style_name'] ?></h3>

<table class="table-skill" align="center">
  <tr>
    <th title="Sword Skill">kenjutsu</th>
    <th title="Shuriken Skill">shuriken</th>
    <th title="Melee Skill">taijutsu</th>
    <th title="Elemental Skill">ninjutsu</th>
    <th title="Illusion Skill">genjutsu</th>
  </tr>
  
  <tr>
    <td><?= $_char['kenjutsu'] ?></td>
    <td><?= $_char['shuriken'] ?></td>
    <td><?= $_char['taijutsu'] ?></td>
    <td><?= $_char['ninjutsu'] ?></td>
    <td><?= $_char['genjutsu'] ?></td>
  </tr>
</table>

<h3>
  <a href="char-train">Train</a>
</h3>

<table align="center">
  
  <tr>
    <th colspan="4" title="Average of stats">
      Rank-<?= $_char['char_rank'] ?>
      <br />
      Lv <?=   $_char['char_level'] ?>
    </th>
  </tr>
  
  <tr>
    <th>Stat</th>
    
    <th colspan="2" width="50px">#</th>
    
    <td>Effect</td>
  </tr>
  
  <form method="POST">
    
    <tr>
      
      <th>Flair</th>
      
      <td style="text-align: right;"><?= $_char['flair'] ?></td>
      
      <td>
        <button type="submit" name="train-attribute" value="flair" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Critical</td>
      
    </tr>
    
    <tr>
      
      <th>Power</th>
      
      <td style="text-align: right;"><?= $_char['strength'] ?></td>
      
      <td>
        <button type="submit" name="train-attribute" value="strength" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Strength</td>
      
    </tr>
    
    <tr>
      
      <th>Speed</th>
      
      <td style="text-align: right;"><?= $_char['agility'] ?></td>
      
      <td>
        <button type="submit" name="train-attribute" value="agility" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Reach</td>
      
    </tr>
    
    <tr>
      
      <th>Jutsu</th>
      
      <td style="text-align: right;"><?= $_char['jutsu'] ?></td>
      
      <td>
        <button type="submit" name="train-attribute" value="jutsu" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Skill</td>
      
    </tr>
    
    <tr>
      
      <th>Tactics</th>
      
      <td style="text-align: right;"><?= $_char['tactics'] ?></td>
      
      <td>
        <button type="submit" name="train-attribute" value="tactics" <?= $_disabled ?>>+</button>
      </td>
      
      <td>Planning</td>
      
    </tr>
    
  </form>
  
  <tr>
    <td style="text-align: right">Need:</td>
    
    <th><?= $_char['sessions_needed_for_upgrade'] ?></th>
    
    <td colspan="2">Stats: <?= $_char['training_sessions_for_use'] ?>/50</td>
  </tr>
  
</table>
