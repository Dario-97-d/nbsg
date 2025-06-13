<?php
  
  require_once 'backend/backstart.php';
  require_once 'functions/features/team/train.php';
  
  if ( ! isset( $_uid ) ) exiter('index');
  
  $_char = TEAM_Train_get_char_with_training();
  
  if ( ! TEAM_is_full( $_char ) ) exiter('team-meet');
  
  $_team_members = TEAM_Train_get_members( $_char );
  
  $_team_skills = TEAM_get_skills( $_team_members );
  
  $_train = TEAM_Train_skills( $_char, $_team_members, $_team_skills );
  
  $_upgrades = TEAM_Train_get_upgrade_messages( $_train['upgrades'] );
  $_training = TEAM_Train_get_training_messages( $_train['training'] );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team Train</h1>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
  <tr>
    <th>Clan</th>
    <th>Lv</th>
    <th>Nin</th>
    <th>Jutsu</th>
  </tr>
  
  <?php foreach ( $_team_members as $row )
  {
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

<h3>Joint Skills</h3>

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

<br />
<br />

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

<br /><br />

<table id="table-train" align="center" cellspacing="3">
  
  <tr>
    
    <th><?= $_upgrades['kenjutsu'] ?></th>
    
    <th>Kenjutsu</th>
    
    <td>
      <div id="bp">
        <div id="bt" style="width: <?= round( $_char['kenjutsu_points'] * 100 / $_char['kenjutsu'] ) ?>px;"></div>
      </div>
    </td>
    
    <th><?= $_char['kenjutsu_points'] .'/'. $_char['kenjutsu'] ?></th>
    
    <th><?= $_training['kenjutsu'] ?></th>
  </tr>
  
  <tr>
    
    <th><?= $_upgrades['shuriken'] ?></th>
    
    <th>Shuriken</th>
    
    <td>
      <div id="bp">
        <div id="bt" style="width: <?= round( $_char['shuriken_points'] * 100 / $_char['shuriken'] ) ?>px;"></div>
      </div>
    </td>
    
    <th><?= $_char['shuriken_points'] .'/'. $_char['shuriken'] ?></th>
    
    <th><?= $_training['shuriken'] ?></th>
    
  </tr>
  
  <tr>
    
    <th><?= $_upgrades['taijutsu'] ?></th>
    
    <th>Taijutsu</th>
    
    <td>
      <div id="bp">
        <div id="bt" style="width: <?= round( $_char['taijutsu_points'] * 100 / $_char['taijutsu'] ) ?>px;"></div>
      </div>
    </td>
    
    <th><?= $_char['taijutsu_points'] .'/'. $_char['taijutsu'] ?></th>
    
    <th><?= $_training['taijutsu'] ?></th>
    
  </tr>
  
  <?php if ( $_char['style_name'] != 'Tameru' )
  {
    ?>
    <tr>
      
      <th><?= $_upgrades['ninjutsu'] ?></th>
      
      <th>Ninjutsu</th>
      
      <td>
        <div id="bp">
          <div id="bt" style="width: <?= round( $_char['ninjutsu_points'] * 100 / $_char['ninjutsu'] ) ?>px;"></div>
        </div>
      </td>
      
      <th><?= $_char['ninjutsu_points'] .'/'. $_char['ninjutsu'] ?></th>
      
      <th><?= $_training['ninjutsu'] ?></th>
      
    </tr>
    
    <tr>
      
      <th><?= $_upgrades['genjutsu'] ?></th>
      
      <th>Genjutsu</th>
      
      <td>
        <div id="bp">
          <div id="bt" style="width: <?= round( $_char['genjutsu_points'] * 100 / $_char['genjutsu'] ) ?>px;"></div>
        </div>
      </td>
      
      <th><?= $_char['genjutsu_points'] .'/'. $_char['genjutsu'] ?></th>
      
      <th><?= $_training['genjutsu'] ?></th>
      
    </tr>
    <?php
  }
  ?>
</table>
