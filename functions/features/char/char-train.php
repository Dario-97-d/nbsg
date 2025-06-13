<?php

// -- Char Train functions --

function CHAR_Train_get()
{
  global $_uid;
  
  return sql_mfa('
    SELECT
      s.style_name,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu,
      t.kenjutsu_points,
      t.shuriken_points,
      t.taijutsu_points,
      t.ninjutsu_points,
      t.genjutsu_points,
      t.skill_training,
      t.sessions_in_training,
      t.time_ready,
      t.time_ready > NOW() AS is_training,
      TIMESTAMPDIFF( SECOND, NOW(), t.time_ready ) AS time_left_seconds
    FROM  style_attributes s
    JOIN  skill_training   t USING (char_id)
    WHERE s.char_id = '. $_uid .'
  ');
}

function CHAR_Train_if_complete( & $_char )
{
  global $_uid;
  
  if ( $_char['skill_training'] === '' ) return 'none';
  if ( (int)$_char['is_training']      ) return 'training';
  
  $sessions     = $_char['sessions_in_training'];
  $skill_name   = $_char['skill_training'];
  $skill_level  = $_char[ $skill_name ];
  $skill_points = $_char[ $skill_name .'_points' ];
  
  $skill_points += $sessions;
  
  while ( $skill_points >= $skill_level )
  {
    $skill_points -= $skill_level;
    $skill_level++;
  }
  
  $upgrade = $skill_level - $_char[ $skill_name ];
  
  $train_skill = sql_transaction('
    UPDATE style_attributes s
    JOIN   skill_training   t USING (char_id)
    SET
      s.'.$skill_name       .' = '. $skill_level .',
      t.'.$skill_name.'_points = '. $skill_points .',
      t.skill_training         = \'\',
      t.sessions_in_training   = 0
    WHERE s.char_id                = '. $_uid .'
    AND   t.skill_training         = \''. $skill_name .'\'
    AND   t.sessions_in_training   = '. $_char['sessions_in_training']   .'
    AND   t.'.$skill_name.'_points = '. $_char[ $skill_name .'_points' ] .'
    AND   s.'.$skill_name       .' = '. $_char[ $skill_name ] .'
  ');
  
  if ( ! $train_skill ) return 'error';
  
  $_char[ $skill_name ]            = $skill_level;
  $_char[ $skill_name .'_points' ] = $skill_points;
  $_char['skill_training']         = '';
  $_char['sessions_in_training']   = 0;
  
  return
  [
    'skill'          => $skill_name,
    'sessions'       => $sessions,
    'current_points' => $skill_points,
    'upgrade'        => $upgrade
  ];
}

function CHAR_Train_start( $skill, $sessions )
{
  global $_uid;
  
  if ( ! VALIDATE_Skill_name( $skill ) ) return 'invalid skill name';
  
  if ( $sessions > 10 ) return 'invalid sessions';
  
  return sql_transaction('
    UPDATE skill_training   t
    JOIN   style_attributes a USING (char_id)
    SET
      t.skill_training       = \''. $skill .'\',
      t.sessions_in_training = '.   $sessions .',
      t.time_ready           = DATE_ADD( NOW(), INTERVAL '. 30 * $sessions .' MINUTE )
    WHERE t.char_id              = '. $_uid .'
    AND   t.skill_training       = \'\'
    AND   t.sessions_in_training = 0
    AND   a.'.$skill         .' >= '. $sessions .'
  ');
}

function CHAR_Train_stop()
{
  global $_uid;
  
  return sql_transaction('
    UPDATE skill_training
    SET
      skill_training = IF (  sessions_in_training - CEIL( TIMESTAMPDIFF( MINUTE, NOW(), time_ready ) / 30 ), skill_training, \'\' ),
      sessions_in_training = sessions_in_training - CEIL( TIMESTAMPDIFF( MINUTE, NOW(), time_ready ) / 30 ),
      time_ready           = NOW()
    WHERE char_id = '. $_uid .'
  ');
}
