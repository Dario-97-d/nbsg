<?php

// -- Char Profile functions --

function CHAR_Profile_can_train_with( $own, $other )
{
  global $_uid, $_pid;
  
  return
    // Aren't the same char?
    $_uid != $_pid
    &&
    // Has enough skill points?
    $own['skill_points'] > 4
    &&
    // Isn't training?
    $own['skill_training'] === ''
    &&
    // Both have the same style?
    $own['style_name'] === $other['style_name']
    &&
    // Is level difference not greater than 5?
    abs( $own['char_level'] - $other['char_level'] ) <= 5;
}

function CHAR_Profile_get( $id )
{
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
      s.style_name,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE u.char_id = '. $id .'
  ');
}

function CHAR_Profile_get_own_info()
{
  global $_uid;
  
  return sql_mfa('
    SELECT
      a.char_level,
      s.style_name,
      s.skill_points,
      t.skill_training
    FROM  char_attributes  a
    JOIN  style_attributes s USING (char_id)
    JOIN  skill_training   t USING (char_id)
    WHERE a.char_id = '. $_uid .'
  ');
}
