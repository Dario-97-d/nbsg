<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/team/team-meet.php';
  
  if ( ! isset( $_uid ) ) exiter('index');
  
  // -- Pick Teammate --
  if ( isset( $_POST['pick-char-id'] ) )
  {
    TEAM_Meet_pick_teammate( $_POST['pick-char-id'] );
  }
  
  // -- Sack Teammate --
  if ( isset( $_POST['sack-char-id'] ) )
  {
    TEAM_Meet_sack_teammate( $_POST['sack-char-id'] );
  }
  
  $_char = TEAM_Meet_get_char();
  
  $_team_members = $_char['has_any_teammate'] ? TEAM_Meet_get_members( $_char ) : null;
  
  $_chars_for_team = $_char['has_passed_team_exam'] ? null : TEAM_Meet_get_chars_for_team( $_char );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team</h1>

<p style="padding: 0 32px;">
  Training is more effective as a team
  <br />
  A team may be started from bonds
  <br />
  and from strangers sorted together
</p>

<h3>Team <?= $_char['username'] ?></h3>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
  <form method="POST">
    <tr>
      <th><?=    $_char['style_name'] ?></th>
      <td><?=    $_char['username']   ?></td>
      <td>Lv <?= $_char['char_level'] ?></td>
    </tr>
    
    <?php if ( $_char['has_any_teammate'] )
    {
      foreach ( $_team_members as $row )
      {
        ?>
        <tr>
          
          <th><?= $row['style_name'] ?></th>
          
          <td>
            <a href="char-profile?id=<?= $row['char_id'] ?>">
              <?=  $row['username'] ?>
            </a>
          </td>
          
          <td>Lv <?= $row['char_level'] ?></td>
          
          <td>
            <button type="submit" name="sack-char-id" value="<?= $row['char_id'] ?>">Sack</button>
          </td>
          
        </tr>
        <?php
      }
    }
    ?>    
  </form>
</table>

<?php if ( $_char['is_team_full'] )
{
  if ( $_char['char_rank'] < 'D' && $_char['has_passed_team_exam'] )
  {
    ?>
    <h3>
      <a href="team-train">Team Train</a>
    </h3>
    <?php
  }
  else if ( $_char['char_rank'] == 'D' && ! $_char['has_passed_team_exam'] )
  {
    ?>
    <h3>
      <a href="team-exam">Team Exam</a>
    </h3>
    <?php
  }
}
else if ( ! $_char['has_passed_team_exam'] )
{
  ?>
  <h3>
    <a href="char-bonds">Bonds</a>
  </h3>
  <?php
}
else
{
  ?>
  Train jutsu and do battle
  <?php
}

if ( ! $_char['has_passed_team_exam'] )
{
  ?>
  <h3>Rank-<?= $_char['char_rank'] ?></h3>
  
  <table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
    <form method="POST">
      <?php if ( empty( $_chars_for_team ) )
      {
        ?>
        No nin available
        <?php
      }
      else
      {
        ?>
        <tr>
          <th>Clan</th>
          <th>Nin</th>
          <th>Lv</th>
          <th>Select</th>
        </tr>
        
        <?php foreach ( $_chars_for_team as $row )
        {
          ?>
          <tr>
            
            <th><?= $row['style_name'] ?></th>
            
            <td>
              <a href="char-profile?id=<?= $row['char_id'] ?>">
                <?= $row['username'] ?>
              </a>
            </td>
            
            <td><?= $row['char_level'] ?></td>
            
            <td>
              <button
                type="submit"
                name="pick-char-id"
                value="<?= $row['char_id'] ?>"
                <?php if ( $_char['is_team_full'] )
                {
                  ?>
                  title="Team is full" disabled
                  <?php
                }
                ?>
                >Pick
              </button>
            </td>
            
          </tr>
          <?php
        }
      }
      ?>
    </form>
  </table>
  <?php
}
?>
