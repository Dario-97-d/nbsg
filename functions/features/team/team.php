<?php

// -- Team functions --

function TEAM_get_bar_widths( $team_skills )
{
  if ( ! VALIDATE_Team_skills( $team_skills ) ) return 'invalid team_skills';
  
  $bar_scale = 253 / array_sum( $team_skills );
  
  return
  [
    'kenjutsu' => round( $team_skills['kenjutsu'] * $bar_scale ),
    'shuriken' => round( $team_skills['shuriken'] * $bar_scale ),
    'taijutsu' => round( $team_skills['taijutsu'] * $bar_scale ),
    'ninjutsu' => round( $team_skills['ninjutsu'] * $bar_scale ),
    'genjutsu' => round( $team_skills['genjutsu'] * $bar_scale )
  ];
}

function TEAM_get_char()
{
  global $_uid;
  
  return sql_mfa('
    SELECT
      u.username,
      u.char_rank,
      t.teammate1_id,
      t.teammate2_id
    FROM game_users u
    JOIN char_team  t USING (char_id)
    WHERE u.char_id = '. $_uid .'
  ');
}

function TEAM_get_members( $teammate1_id, $teammate2_id )
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
    FROM game_users       u
    JOIN char_attributes  a USING (char_id)
    JOIN style_attributes s USING (char_id)
    WHERE a.char_id IN
    (
      '. $_uid         .',
      '. $teammate1_id .',
      '. $teammate2_id .'
    )
    ORDER BY
      CASE u.char_id
        WHEN '. $_uid         .' THEN 1
        WHEN '. $teammate1_id .' THEN 2
        WHEN '. $teammate2_id .' THEN 3
      END
  ');
}

function TEAM_get_skills( $team_members )
{
  return
  [
    'kenjutsu' =>
      $team_members[0]['kenjutsu'] +
      $team_members[1]['kenjutsu'] +
      $team_members[2]['kenjutsu'],
    'shuriken' =>
      $team_members[0]['shuriken'] +
      $team_members[1]['shuriken'] +
      $team_members[2]['shuriken'],
    'taijutsu' =>
      $team_members[0]['taijutsu'] +
      $team_members[1]['taijutsu'] +
      $team_members[2]['taijutsu'],
    'ninjutsu' =>
      $team_members[0]['ninjutsu'] +
      $team_members[1]['ninjutsu'] +
      $team_members[2]['ninjutsu'],
    'genjutsu' =>
      $team_members[0]['genjutsu'] +
      $team_members[1]['genjutsu'] +
      $team_members[2]['genjutsu']
  ];
}

function TEAM_is_full( $char )
{
  return $char['teammate1_id'] > 0 && $char['teammate2_id'] > 0;
}
