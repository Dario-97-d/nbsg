<?php

// -- Clan Hall functions --

function CLAN_Hall_get_members( $style_name )
{
  return sql_all('
    SELECT
      u.char_id,
      u.username,
      u.char_rank,
      a.char_level
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE s.style_name = \''. $style_name .'\'
    ORDER BY
      u.char_rank,
      a.char_level DESC
    LIMIT 25
  ');
}

function CLAN_Hall_get_style_name()
{
  global $_uid;
  
  return sql_mfa('
    SELECT style_name
    FROM   style_attributes
    WHERE  char_id = '. $_uid .'
  ')['style_name'];
}
