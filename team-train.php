<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/team-train.php';
  
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
  In a forest between villages
  <br />
  room is there for nin to train
</h4>

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

<h2>Rank-<?= $_char['char_rank'] ?></h2>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
  <tr>
    <th>Clan</th>
    <th>Lv</th>
    <th>Nin</th>
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

<table class="table-team" align="center">
  
  <tr>
    <th>Kenjutsu</th>
    
    <td>
      <div id="ttd" style="width: <?= $_bar_widths['kenjutsu'] ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Shuriken</th>
    
    <td>
      <div id="ttd" style="width: <?= $_bar_widths['shuriken'] ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Taijutsu</th>
    
    <td>
      <div id="ttd" style="width: <?= $_bar_widths['taijutsu'] ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Ninjutsu</th>
    
    <td>
      <div id="ttd" style="width: <?= $_bar_widths['ninjutsu'] ?>px"></div>
    </td>
  </tr>
  
  <tr>
    <th>Genjutsu</th>
    
    <td>
      <div id="ttd" style="width: <?= $_bar_widths['genjutsu'] ?>px"></div>
    </td>
  </tr>
  
</table>

<br />

<form action="team-train-skill" method="POST">
  
  <button type="submit" name="team-train-skill">Train</button>
  
</form>
