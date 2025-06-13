<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

extract( sql_mfa("SELECT teammate1_id, teammate2_id FROM char_team WHERE char_id = $_uid") );

if ( $teammate1_id < 1 || $teammate2_id < 1 ) exiter('team-meet');

$members = sql_query(
  "SELECT a.*, c.*, username
  FROM char_attributes  a
  JOIN style_attributes c ON a.char_id = c.char_id
  JOIN game_users       u ON a.char_id = u.char_id
  JOIN skill_training   s ON a.char_id = s.char_id
  WHERE a.char_id IN ($_uid, $teammate1_id, $teammate2_id)
  ORDER BY
    CASE a.char_id
      WHEN $_uid         THEN 1
      WHEN $teammate1_id THEN 2
      WHEN $teammate2_id THEN 3
    END" );

$prefix = [ 'user', 'teammate1', 'teammate2' ];

$i = 0;
while ( $row = mysqli_fetch_assoc($members) )
{
  extract( $row, EXTR_PREFIX_ALL, $prefix[$i] );
  $i++;
}

if ( min( $teammate1_level, $teammate2_level ) > $user_level )
{
  // remove nin from team
  // exiter(team-meet)
}

// clan-train-skill
/*
switch (true)
{
  case ( ! is_numeric($result) ): echo "switch_result Error"; break;
  
  case ( $result < 4 || $result >= 12 ):
    $placard =
      '<br />
      The difference in use of skill is too wide
      <br />
      There\'s no point in training together
      <br />';
    break;
  
  case ( $result < 6 || $result >= 10 ):
    $placard = "There's some gap";
    $$up_att = '+1';
    
    if ( floor( ( $u_flair + $u_strength + $u_agility + $u_jutsu + $u_tactics + 1 ) / 5 ) > $u_char_level )
    {
      $uplv = 'char_level = char_level + 1, ';
      $u_char_level += 1;
    }
    else
    {
      $uplv = '';
    }
    
    sql_query("UPDATE char_attributes SET $uplv $up_att = $up_att + 1 WHERE char_id = $_uid");
    
    $upgrade = ( $uplv != '' ? 'Lv: '. $u_char_level .'<br />' : '' ) . $atts[$up_att] . ' +1';
    
    break;
  
  case ( $result < 7 || $result >= 9 ):
    $placard = "Good training";
    
    if ( $$skill_training + 1 >= $$up_skill )
    {
      $$skill_training = 0;
      $up_skl = 2;
    }
    else
    {
      $$skill_training += 1;
    }
    
    sql_query('UPDATE skill_training SET $up_skill_training = '. $$skill_training .' WHERE char_id = $_uid');
    
    $upgrade = $skill .' training: +1';
    
    break;
  
  default:
    $placard = "Evenly matched";
    $up_skl = 1;
    break;
}

if ( isset($up_skl) )
{
  $$up_skill = 1;
  $set_up_skl = $up_skill .'='. $up_skill .'+1,';
  $upgrade = ( $up_skl == 2 ? $upgrade .'<br />' : '' ) . $skill .' +1';
}

sql_query("UPDATE style_attributes SET $set_up_skl skill_points = skill_points - 5 WHERE char_id = $_uid");
*/

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Team Train</h1>

<table align="center" style="text-align: center;">
  
  <tr>
    <th width="33%"><?= $teammate1_name ?></th>
    <th width="33%"><?= $user_name ?></th>
    <th width="33%"><?= $teammate2_name ?></th>
  </tr>
  
  <tr><td></td><th></th><td></td></tr>
  
  <tr>
    <th><?= $teammate1_kenjutsu ?> • <?= $teammate1_shuriken ?> • <?= $teammate1_taijutsu ?> • <?= $teammate1_ninjutsu ?> • <?= $teammate1_genjutsu ?></th>
    
    <th><?= $user_kenjutsu ?> • <?= $user_shuriken ?> • <?= $user_taijutsu ?> • <?= $user_ninjutsu ?> • <?= $user_genjutsu ?></th>
    
    <th><?= $teammate2_kenjutsu ?> • <?= $teammate2_shuriken ?> • <?= $teammate2_taijutsu ?> • <?= $teammate2_ninjutsu ?> • <?= $teammate2_genjutsu ?></th>
  </tr>
  
  <tr><td></td><th></th><td></td></tr>
  
  <tr>
    <th><?= $teammate1_level ?></th>
    <th><?= $user_level ?></th>
    <th><?= $teammate2_level ?></th>
  </tr>
  
  <tr>
    <td><?= $teammate1_fla ?></td>
    <th><?= $user_fla ?></th>
    <td><?= $teammate2_fla ?></td>
  </tr>
  
  <tr>
    <td><?= $teammate1_pow ?></td>
    <th><?= $user_pow ?></th>
    <td><?= $teammate2_pow ?></td>
  </tr>
  
  <tr>
    <td><?= $teammate1_agi ?></td>
    <th><?= $user_agi ?></th>
    <td><?= $teammate2_agi ?></td>
  </tr>
  
  <tr>
    <td><?= $teammate1_jut ?></td>
    <th><?= $user_jut ?></th>
    <td><?= $teammate2_jut ?></td>
  </tr>
  
  <tr>
    <td><?= $teammate1_tac ?></td>
    <th><?= $user_tac ?></th>
    <td><?= $teammate2_tac ?></td>
  </tr>
  
</table>
