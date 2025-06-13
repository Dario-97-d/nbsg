<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/char/char-train.php';

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

<?php if ( $_char['is_training'] )
{
  ?>
  
  <p>Training:</p>
  
  <b><?= ucfirst( $_char['skill_training'] ) ?></b>
  
  <p><?= $_char['sessions_in_training'] .' Sessions | '. ( $_char['sessions_in_training'] * 30 ) .' minutes' ?></p>
  
  Time left:
  <br />
  <?= date( "H:i:s", $_char['time_left_seconds'] ) ?>
  
  <br /><br />
  
  <form method="POST">
    <button type="submit" name="stop-training">Stop</button>
  </form>
  
  <p>Can't train with other nin when training alone</p>
  
  <?php
}
else
{
  ?>
  <h3>
    <a href="clan-train">Train in Clan</a> || <a href="team-train">Train in Team</a>
  </h3>
  
  <table id="table-train" align="center" cellspacing="3">
    
    <tr>
      
      <th><?= $_upgrade['kenjutsu'] ?? '' ?></th>
      
      <th>Kenjutsu</th>
      
      <td>
        <div id="bp">
          <div id="bt" style="width: <?= round( $_char['kenjutsu_points'] * 100 / $_char['kenjutsu'] ) ?>px;"></div>
        </div>
      </td>
      
      <th><?= $_char['kenjutsu_points'] .'/'. $_char['kenjutsu'] ?></th>
      
      <form method="POST">
        <th>
          <select name="sessions">
            <?php for ( $i = 1; $i <= $_char['kenjutsu'] && $i < 11; $i++ )
            {
              ?>
              <option><?= $i ?></option>
              <?php
            }
            ?>
          </select>
        </th>
        
        <th>
          <button type="submit" name="train-skill" value="kenjutsu">Train</button>
        </th>
      </form>
      
    </tr>
    
    <tr>
      
      <th><?= $_upgrade['shuriken'] ?? '' ?></th>
      
      <th>Shuriken</th>
      
      <td>
        <div id="bp">
          <div id="bt" style="width: <?= round( $_char['shuriken_points'] * 100 / $_char['shuriken'] ) ?>px;"></div>
        </div>
      </td>
      
      <th><?= $_char['shuriken_points'] .'/'. $_char['shuriken'] ?></th>
      
      <form method="POST">
        <th>
          <select name="sessions">
            <?php for ( $i = 1; $i <= $_char['shuriken'] && $i < 11; $i++ )
            {
              ?>
              <option><?= $i ?></option>
              <?php
            }
            ?>
          </select>
        </th>
        
        <th>
          <button type="submit" name="train-skill" value="shuriken">Train</button>
        </th>
      </form>
      
    </tr>
    
    <tr>
      
      <th><?= $_upgrade['taijutsu'] ?? '' ?></th>
      
      <th>Taijutsu</th>
      
      <td>
        <div id="bp">
          <div id="bt" style="width: <?= round( $_char['taijutsu_points'] * 100 / $_char['taijutsu'] ) ?>px;"></div>
        </div>
      </td>
      
      <th><?= $_char['taijutsu_points'] .'/'. $_char['taijutsu'] ?></th>
      
      <form method="POST">
        <th>
          <select name="sessions">
            <?php for ( $i = 1; $i <= $_char['taijutsu'] && $i < 11; $i++ )
            {
              ?>
              <option><?= $i ?></option>
              <?php
            }
            ?>
          </select>
        </th>
        
        <th>
          <button type="submit" name="train-skill" value="taijutsu">Train</button>
        </th>
      </form>
      
    </tr>
    
    <?php if ( $_char['style_name'] != 'Tameru' )
    {
      ?>
      <tr>
        
        <th><?= $_upgrade['ninjutsu'] ?? '' ?></th>
        
        <th>Ninjutsu</th>
        
        <td>
          <div id="bp">
            <div id="bt" style="width: <?= round( $_char['ninjutsu_points'] * 100 / $_char['ninjutsu'] ) ?>px;"></div>
          </div>
        </td>
        
        <th><?= $_char['ninjutsu_points'] .'/'. $_char['ninjutsu'] ?></th>
        
        <form method="POST">
          <th>
            <select name="sessions">
              <?php for ( $i = 1; $i <= $_char['ninjutsu'] && $i < 11; $i++ )
              {
                ?>
                <option><?= $i ?></option>
                <?php
              }
              ?>
            </select>
          </th>
          
          <th>
            <button type="submit" name="train-skill" value="ninjutsu">Train</button>
          </th>
        </form>
        
      </tr>
      
      <tr>
        
        <th><?= $_upgrade['genjutsu'] ?? '' ?></th>
        
        <th>Genjutsu</th>
        
        <td>
          <div id="bp">
            <div id="bt" style="width: <?= round( $_char['genjutsu_points'] * 100 / $_char['genjutsu'] ) ?>px;"></div>
          </div>
        </td>
      
        <th><?= $_char['genjutsu_points'] .'/'. $_char['genjutsu'] ?></th>
        
        <form method="POST">
          <th>
            <select name="sessions">
              <?php for ( $i = 1; $i <= $_char['genjutsu'] && $i < 11; $i++ )
              {
                ?>
                <option><?= $i ?></option>
                <?php
              }
              ?>
            </select>
          </th>
          
          <th>
            <button type="submit" name="train-skill" value="genjutsu">Train</button>
          </th>
        </form>
        
      </tr>
      <?php
    }
    ?>
  </table>
  
  <h4><?= $_trained ?></h4>
  <?php
}
?>
