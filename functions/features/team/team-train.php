<?php

require_once __DIR__ .'/team.php';

// -- Team Train functions --

function TEAM_Train_get_char()
{
  global $_uid;
  
  return sql_mfa('
    SELECT
      u.username,
      u.char_rank,
      t.teammate1_id,
      t.teammate2_id,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu
    FROM  game_users       u
    JOIN  char_team        t USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE u.char_id = '. $_uid .'
  ');
}

function TEAM_Train_get_char_with_training()
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
      st.kenjutsu_points,
      st.shuriken_points,
      st.taijutsu_points,
      st.ninjutsu_points,
      st.genjutsu_points,
      ct.teammate1_id,
      ct.teammate2_id
    FROM  style_attributes s
    JOIN  skill_training  st USING (char_id)
    JOIN  char_team       ct USING (char_id)
    WHERE s.char_id = '. $_uid .'
  ');
}

function TEAM_Train_get_mates( $char )
{
  return sql_all('
    SELECT
      u.char_id,
      u.username,
      a.char_level,
      s.style_name,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu
    FROM     game_users       u
    JOIN     char_attributes  a USING (char_id)
    JOIN     style_attributes s USING (char_id)
    WHERE    u.char_id = '. $char['teammate1_id'] .'
    OR       u.char_id = '. $char['teammate2_id'] .'
    ORDER BY a.char_level DESC
    LIMIT 25
  ');
}

function TEAM_Train_get_members( $char )
{
  global $_uid;
  
  return sql_all('
    SELECT
      u.char_id,
      u.username,
      a.char_level,
      s.style_name,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE u.char_id IN (
      '. $_uid                 .',
      '. $char['teammate1_id'] .',
      '. $char['teammate2_id'] .'
    )
    ORDER BY
      CASE u.char_id
        WHEN '. $_uid                 .' THEN 1
        WHEN '. $char['teammate1_id'] .' THEN 2
        WHEN '. $char['teammate2_id'] .' THEN 3
      END
  ');
}

function TEAM_Train_get_training_messages( $training )
{
  foreach ( $training as $skill => $points )
  {
    $messages[ $skill ] = $points ? '+'. $points : '';
  }
  
  return $messages;
}

function TEAM_Train_get_upgrade_messages( $upgrades )
{
  foreach ( $upgrades as $skill => $value )
  {
    $messages[ $skill ] = $value ? '+'. $value : '';
  }
  
  return $messages;
}

function TEAM_Train_skills( & $_char, $team_members, $team_skills )
{
  global $_uid;
  
  $skills = [ 'kenjutsu', 'shuriken', 'taijutsu', 'ninjutsu', 'genjutsu' ];
  
  foreach ( $skills as $skill_name )
  {
    $training[ $skill_name ] = 0;
    $upgrades[ $skill_name ] = 0;
    $original_values[ $skill_name ] = $_char[ $skill_name ];
    $original_points[ $skill_name ] = $_char[ $skill_name .'_points' ];
    
    // Skip loop for ninjutsu and genjutsu training of Tameru chars.
    if ( $_char['style_name'] === 'Tameru' )
    {
      if ( $skill_name === 'ninjutsu' ) continue;
      if ( $skill_name === 'genjutsu' ) continue;
    }
    
    // Determine training points for each skill.
    $training[ $skill_name ] =
      max(
        round(
          (
            $_char[ $skill_name ] * (
              $team_members[1][ $skill_name ] + $team_members[2][ $skill_name ] - $_char[ $skill_name ]
            ) / (
              $team_members[0]['char_level'] * $team_skills[ $skill_name ] / (
                $team_members[1]['char_level'] + $team_members[2]['char_level']
              )
            )
          )
        ),
      0 );
    
    if ( $training[ $skill_name ] > 0 )
    {
      $_char[ $skill_name .'_points' ] += $training[ $skill_name ];
      
      while ( $_char[ $skill_name .'_points' ] >= $_char[ $skill_name ] )
      {
        $_char[ $skill_name .'_points' ] -= $_char[ $skill_name ];
        $upgrades[ $skill_name ]++;
      }
      
      $_char[ $skill_name ] += $upgrades[ $skill_name ];
    }
  }
  
  sql_transaction('
    UPDATE style_attributes s
    JOIN   skill_training   t USING (char_id)
    SET
      s.skill_points = s.skill_points - 5,
      s.kenjutsu = '. $_char['kenjutsu'] .',
      s.shuriken = '. $_char['shuriken'] .',
      s.taijutsu = '. $_char['taijutsu'] .',
      s.ninjutsu = '. $_char['ninjutsu'] .',
      s.genjutsu = '. $_char['genjutsu'] .',
      t.kenjutsu_points = '. $_char['kenjutsu_points'] .',
      t.shuriken_points = '. $_char['shuriken_points'] .',
      t.taijutsu_points = '. $_char['taijutsu_points'] .',
      t.ninjutsu_points = '. $_char['ninjutsu_points'] .',
      t.genjutsu_points = '. $_char['genjutsu_points'] .'
    WHERE s.char_id = '. $_uid .'
    AND   s.skill_points > 4
    AND   s.kenjutsu = '. $original_values['kenjutsu'] .'
    AND   s.shuriken = '. $original_values['shuriken'] .'
    AND   s.taijutsu = '. $original_values['taijutsu'] .'
    AND   s.ninjutsu = '. $original_values['ninjutsu'] .'
    AND   s.genjutsu = '. $original_values['genjutsu'] .'
    AND   t.kenjutsu_points = '. $original_points['kenjutsu'] .'
    AND   t.shuriken_points = '. $original_points['shuriken'] .'
    AND   t.taijutsu_points = '. $original_points['taijutsu'] .'
    AND   t.ninjutsu_points = '. $original_points['ninjutsu'] .'
    AND   t.genjutsu_points = '. $original_points['genjutsu'] .'
  ');
  
  return
  [
    'training' => $training,
    'upgrades' => $upgrades
  ];
}
