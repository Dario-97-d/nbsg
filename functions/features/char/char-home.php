<?php

// -- Char Home functions --

function CHAR_Home_get()
{
  global $_uid;
  
  return sql_mfa('
    SELECT
      u.username,
      u.char_rank,
      a.char_level,
      a.flair,
      a.strength,
      a.agility,
      a.jutsu,
      a.tactics,
      a.training_sessions_for_use,
      a.sessions_needed_for_upgrade,
      s.style_name,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE u.char_id = '. $_uid .'
  ');
}

function CHAR_Home_increment_attribute( $attribute )
{
  global $_uid;
  
  if ( ! VALIDATE_Char_attribute( $attribute ) ) return false;
  
  $char = sql_mfa('
    SELECT
      char_level,
      (
        flair    +
        strength +
        agility  +
        jutsu    +
        tactics
      ) AS total_atts,
      '. $attribute .',
      training_sessions_for_use >= sessions_needed_for_upgrade AS can_upgrade
    FROM  char_attributes
    WHERE char_id = '. $_uid .'
  ');
  
  if ( ! $char['can_upgrade'] ) return 'Not enough attribute points.';
  
  $new_level = ( $char['total_atts'] + 1 ) / 5;
  
  return sql_transaction('
    UPDATE char_attributes
    SET
      char_level = FLOOR( ( flair + strength + agility + jutsu + tactics + 1 ) / 5 ),
      '. $attribute .' = '. $attribute .' + 1,
      training_sessions_for_use   = training_sessions_for_use - sessions_needed_for_upgrade,
      sessions_needed_for_upgrade = sessions_needed_for_upgrade + 1
    WHERE char_id          = '. $_uid .'
    AND   char_level       = '. $char['char_level'] .'
    AND   '. $attribute .' = '. $char[ $attribute ]  .'
    AND   training_sessions_for_use >= sessions_needed_for_upgrade
  ');
}
