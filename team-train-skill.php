<?php
  
  require_once 'backend/backstart.php';
  require_once 'functions/features/team/team-train.php';
  require_once 'functions/features/skills.php';
  
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

<table class="table-generic">
  <tr>
    <th>Clan</th>
    <th>Lv</th>
    <th>Char</th>
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

<?= VIEW_Char_skills( $_char ) ?>

<br />
<br />

<table cellspacing="4">
  <?php foreach ( SKILLS_get_by_style( $_char['style_name'] ) as $skill_name )
  {
    ?>
    <tr>
      <th><?= $_upgrades[ $skill_name ] ?? '' ?></th>
      
      <th><?= ucfirst( $skill_name ) ?></th>
      
      <td>
        <div class="skill-training-frame">
          <div
            class="skill-training-bar"
            style="width: <?= round( $_char[ $skill_name .'_points'] * 100 / $_char[ $skill_name ] ) ?>px;"
            >
          </div>
        </div>
      </td>
      
      <th><?= $_char[ $skill_name .'_points'] .'/'. $_char[ $skill_name ] ?></th>
      
      <th><?= $_training[ $skill_name ] ?? '' ?></th>
    </tr>
    <?php
  }
  ?>
</table>
