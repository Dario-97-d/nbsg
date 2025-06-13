<?php

require_once __DIR__ .'/team.php';

// -- Team Meet functions --

function TEAM_Meet_get_char()
{
  global $_uid;
  
  return sql_mfa('
    SELECT
      u.username,
      u.char_rank,
      a.char_level,
      s.style_name,
      t.teammate1_id,
      t.teammate2_id,
      t.teammate1_id > 0 OR  t.teammate2_id > 0 AS has_any_teammate,
      t.teammate1_id > 0 AND t.teammate2_id > 0 AS is_team_full,
      t.team_exam_phase > 0                     AS has_passed_team_exam
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    JOIN  char_team        t USING (char_id)
    WHERE u.char_id = '. $_uid .'
  ');
}

function TEAM_Meet_get_chars_for_team( $char )
{
  global $_uid;
  
  return sql_all('
    SELECT
      u.char_id,
      u.username,
      a.char_level,
      s.style_name
    FROM     game_users       u
    JOIN     char_attributes  a USING (char_id)
    JOIN     style_attributes s USING (char_id)
    WHERE    u.char_rank = \''. $char['char_rank'] .'\'
    AND      a.char_level <= '. $char['char_level'] .'
    AND      u.char_id NOT IN (
      '. $_uid .',
      '. $char['teammate1_id'] .',
      '. $char['teammate2_id'] .'
    )
    ORDER BY u.char_id DESC
    LIMIT    25
  ');
}

function TEAM_Meet_get_members( $char )
{
  return sql_all('
    SELECT
      u.char_id,
      u.username,
      a.char_level,
      s.style_name
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE u.char_id = '. $char['teammate1_id'] .'
    OR    u.char_id = '. $char['teammate2_id'] .'
    ORDER BY char_level DESC
  ');
}

function TEAM_Meet_pick_teammate( $char_id )
{
  global $_uid;
  
  if ( ! VALIDATE_Char_id( $char_id ) ) return 'invalid char id';
  
  // Get ids of current teammates.
  $ids = sql_mfa('
    SELECT
      teammate1_id,
      teammate2_id
    FROM  char_team
    WHERE char_id = '. $_uid .'
  ');
  
  if ( $ids['teammate1_id'] == 0 )
  {
    $which = 1;
  }
  else if ( $ids['teammate2_id'] == 0 )
  {
    $which = 2;
  }
  else
  {
    return false;
  }
  
  sql_transaction('
    UPDATE char_team       t
    JOIN   char_attributes a USING (char_id)
    SET    t.teammate'.$which.'_id = '. $char_id .'
    WHERE  t.char_id      = '. $_uid                .'
    AND    t.char_id     <> '. $char_id             .'
    AND    t.teammate1_id = '. $ids['teammate1_id'] .'
    AND    t.teammate2_id = '. $ids['teammate2_id'] .'
    AND    a.char_level  >= ( SELECT char_level FROM char_attributes WHERE char_id = '. $char_id .' )
  ');
}

function TEAM_Meet_sack_teammate( $char_id )
{
  global $_uid;
  
  if ( ! VALIDATE_Char_id( $char_id ) ) return 'invalid char id';
  
  return sql_transaction('
    UPDATE char_team
    SET
      teammate1_id = IF( teammate1_id = '. $char_id .', 0, teammate1_id ),
      teammate2_id = IF( teammate2_id = '. $char_id .', 0, teammate2_id )
    WHERE char_id = '. $_uid .'
  ');
}
