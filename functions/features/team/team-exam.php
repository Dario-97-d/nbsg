<?php

require_once __DIR__ .'/team.php';

// -- Team Exam functions --

function TEAM_Exam_get_bots_skill_ratios()
{
  $bots =
  [
    'ninjutsu' => intval( substr(microtime(true), -1, 1) ) + 3,
    'kenjutsu' => intval( substr(microtime(true), -2, 1) ) + 3,
    'taijutsu' => intval( substr(microtime(true), -3, 1) ) + 3,
    'shuriken' => intval( substr(microtime(true), -4, 1) ) + 3,
    'genjutsu' => intval( substr(         time(), -1, 1) ) + 3
  ];
  
  $bar_scale = 125 / array_sum( $bots );
  
  $bots['kenjutsu'] *= $bar_scale;
  $bots['shuriken'] *= $bar_scale;
  $bots['taijutsu'] *= $bar_scale;
  $bots['ninjutsu'] *= $bar_scale;
  $bots['genjutsu'] *= $bar_scale;
  
  return $bots;
}

function TEAM_Exam_get_char()
{
  global $_uid;
  
  return sql_mfa('
    SELECT
      u.username,
      t.teammate1_id,
      t.teammate2_id,
      a.char_level,
      a.flair,
      a.strength,
      a.agility,
      a.jutsu,
      a.tactics,
      s.style_name,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu
    FROM  game_users       u
    JOIN  char_team        t USING (char_id)
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE u.char_id = '. $_uid .'
  ');
}

function TEAM_Exam_get_mates( $char )
{
  global $_uid;
  
  return sql_all('
    SELECT
      u.username,
      a.char_level,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu
    FROM game_users       u
    JOIN char_attributes  a USING (char_id)
    JOIN style_attributes s USING (char_id)
    WHERE a.char_id IN ('. $_uid .', '. $char['teammate1_id'] .', '. $char['teammate2_id'] .')
    ORDER BY
      CASE a.char_id
        WHEN '. $_uid                 .' THEN 1
        WHEN '. $char['teammate1_id'] .' THEN 2
        WHEN '. $char['teammate2_id'] .' THEN 3
      END
  ');
}

function TEAM_Exam_get_team_skill_ratios( $team_skills )
{
  $total = array_sum( $team_skills );
  
  $bar_scale = $total > 25 ?
    ( 2 - ( 25 / $total ) ) * 100 / $total
    :
    100 / $total;
  
  return
  [
    'kenjutsu' => $team_skills['kenjutsu'] * $bar_scale,
    'shuriken' => $team_skills['shuriken'] * $bar_scale,
    'taijutsu' => $team_skills['taijutsu'] * $bar_scale,
    'ninjutsu' => $team_skills['ninjutsu'] * $bar_scale,
    'genjutsu' => $team_skills['genjutsu'] * $bar_scale,
  ];
}

function TEAM_Exam_is_allowed()
{
  global $_uid;
  
  $is_allowed = sql_query('
    SELECT 1
    FROM   game_users       u
    JOIN   char_team        t USING (char_id)
    JOIN   style_attributes c USING (char_id)
    JOIN   skill_training   s USING (char_id)
    WHERE  u.char_id         = '. $_uid .'
    AND    t.team_exam_phase = 0
    AND    t.teammate1_id    > 0
    AND    t.teammate2_id    > 0
    AND    s.skill_training  = \'\'
  ');
  
  return $is_allowed && mysqli_num_rows( $is_allowed );
}

function TEAM_Exam_is_passed( $char, $teammates, $team )
{
  global $_uid;
  
  $is_passed =
  (
    $char        ['char_level'] +
    $teammates[0]['char_level'] +
    $teammates[1]['char_level']
  )
  * ( $team['total_skills'] ** 2 )
  > 6250;
  
  if ( $is_passed )
  {
    sql_transaction('
      UPDATE char_team
      SET    team_exam_phase = 1
      WHERE  char_id = '. $_uid .'
    ');
  }
  
  return $is_passed;
}

function TEAM_Exam_upgrade_char_attributes( $char, $team )
{
  global $_uid;
  
  $upgrade =
  [
    'flair'    => round( 9 * $team['ninjutsu'] / $team['total_skills'] ),
    'strength' => round( 9 * $team['kenjutsu'] / $team['total_skills'] ),
    'agility'  => round( 9 * $team['taijutsu'] / $team['total_skills'] ),
    'jutsu'    => round( 9 * $team['shuriken'] / $team['total_skills'] ),
    'tactics'  => round( 9 * $team['genjutsu'] / $team['total_skills'] )
  ];

  $train = 10 - array_sum( $upgrade );

  switch ( $char['style_name'] )
  {
    case 'Kensou': $upgrade['strength'] += $train; break;
    case 'Surike': $upgrade['jutsu']    += $train; break;
    case 'Geniru': $upgrade['tactics']  += $train; break;
    
    case 'Tameru':
    case 'Tayuga':
      
      $upgrade['agility'] += $train;
      break;
    
    case 'Faruni':
    case 'Wyroni':
    case 'Raiyni':
    case 'Rokuni':
    case 'Watoni':
      
      $upgrade['flair'] += $train;
      break;
    
    default: return false;
  }
  
  sql_transaction('
    UPDATE char_attributes
    SET
      char_level = char_level + 2,
      flair      = flair      + '. $upgrade['flair'   ] .',
      strength   = strength   + '. $upgrade['strength'] .',
      agility    = agility    + '. $upgrade['agility' ] .',
      jutsu      = jutsu      + '. $upgrade['jutsu'   ] .',
      tactics    = tactics    + '. $upgrade['tactics' ] .'
    WHERE char_id  = '. $_uid .'
    AND   flair    = '. $char['flair']    .'
    AND   strength = '. $char['strength'] .'
    AND   agility  = '. $char['agility']  .'
    AND   jutsu    = '. $char['jutsu']    .'
    AND   tactics  = '. $char['tactics']  .'
  ');
  
  return $upgrade;
}
