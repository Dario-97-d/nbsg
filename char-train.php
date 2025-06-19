<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/char/char-train.php';
  require_once 'functions/features/skills.php';
  
  if ( ! isset( $_uid ) ) exiter('index');

  // -- End training skill --
  if ( isset( $_POST['stop-training'] ) )
  {
    CHAR_Train_stop();
  }

  // -- Start training skill --
  if ( isset( $_POST['train-skill'] ) )
  {
    CHAR_Train_start( $_POST['train-skill'], $_POST['sessions'] ?? 1 );
  }

  $_char = CHAR_Train_get();

  // -- Complete training and update char  --
  $_training = CHAR_Train_if_complete( $_char );

  // Set message for sessions trained. Ex: $_trained['ninjutsu'] = 'Ninjutsu trained (+5)'.
  $_trained = is_array( $_training ) ?
    ucfirst( $_training['skill'] ) .' trained (+'. $_training['sessions'] .')'
    : '';

  // Set message for upgrade if done. Ex: $_upgrade['ninjutsu'] = '+1'.
  $_upgrade = is_array( $_training ) && $_training['upgrade'] > 0 ?
    [
      $_training['skill'] => '+'. $_training['upgrade']
    ]
    : '';

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Training Grounds</h1>

<h3><?= $_char['style_name'] ?></h3>

<?= VIEW_Char_skills( $_char ) ?>

<?php if ( $_char['is_training'] )
{
  ?>
  
  <p>Training:</p>
  
  <b><?= ucfirst( $_char['skill_training'] ) ?></b>
  
  <p><?= $_char['sessions_in_training'] .' Sessions | '. ( $_char['sessions_in_training'] * 30 ) .' minutes' ?></p>
  
  Time left:
  
  <br />
  
  <?= date( "H:i:s", $_char['time_left_seconds'] ) ?>
  
  <br />
  <br />
  
  <form method="POST">
    <button type="submit" name="stop-training">Stop</button>
  </form>
  
  <p>Can't train with other chars when training alone.</p>
  
  <?php
}
else
{
  ?>
  
  <h3>
    <a href="clan-train">Train in Clan</a> || <a href="team-train">Train in Team</a>
  </h3>
  
  <form method="POST">
    
    <input type="hidden" id="input-skill-name" name="train-skill" value="" />
    <input type="hidden" id="input-sessions" name="sessions" value="" />
    
    <table cellspacing="4">
      <?php foreach ( SKILLS_get_by_style( $_char['style_name'] ) as $skill_name )
      {
        ?>
        <tr>
          
          <th><?= $_upgrade[ $skill_name ] ?? '' ?></th>
          
          <th><?= ucfirst( $skill_name ) ?></th>
          
          <td>
            <div class="skill-training-frame">
              <div
                class="skill-training-bar"
                style="width: <?= round( $_char[ $skill_name .'_points' ] * 100 / $_char[ $skill_name ] ) ?>%;"
                >
              </div>
            </div>
          </td>
          
          <th><?= $_char[ $skill_name .'_points' ] .'/'. $_char[ $skill_name ] ?></th>
          
          <th>
            <select id="select-sessions-<?= $skill_name ?>">
              <?php for ( $i = 1; $i <= $_char[ $skill_name ] && $i < 11; $i++ )
              {
                ?>
                <option><?= $i ?></option>
                <?php
              }
              ?>
            </select>
          </th>
          
          <th>
            <button type="submit" onclick="setInputValues('<?= $skill_name ?>')">Train</button>
          </th>
          
        </tr>
        <?php
      }
      ?>
    </table>
  </form>
  
  <h4><?= $_trained ?></h4>
  
  <script>
    function setInputValues(skill)
    {
      document.getElementById('input-skill-name').value = skill;
      document.getElementById('input-sessions').value = document.getElementById('select-sessions-' + skill).value;
    }
  </script>
  
  <?php
}
?>
