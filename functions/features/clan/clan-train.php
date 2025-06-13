<?php

// -- Clan Train functions --

function CLAN_Train_get_char()
{
  global $_uid;
  
  return sql_mfa('
    SELECT
      u.char_rank,
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
    WHERE u.char_id = '. $_uid .'
  ');
}

function CLAN_Train_get_members_for_training( $char )
{
  global $_uid;
  
  $min_level = $char['char_level'] - 5;
  $max_level = $char['char_level'] + 5;
  
  return sql_all('
    SELECT
      u.char_id,
      u.username,
      a.char_level,
      s.kenjutsu,
      s.shuriken,
      s.taijutsu,
      s.ninjutsu,
      s.genjutsu
    FROM     game_users       u
    JOIN     char_attributes  a USING (char_id)
    JOIN     style_attributes s USING (char_id)
    WHERE    u.char_rank        = \''. $char['char_rank']  .'\'
    AND      s.style_name       = \''. $char['style_name'] .'\'
    AND      a.char_level BETWEEN   '. $min_level .' AND '. $max_level .'
    AND      u.char_id         <>   '. $_uid .'
    ORDER BY a.char_level DESC
    LIMIT    25
  ');
}

function CLAN_Train_get_other_char( $char_id )
{
  global $_uid;
  
  if ( ! VALIDATE_Char_id( $char_id ) ) return false;
  if (   $char_id == $_uid            ) return false;
  
  return sql_mfa('
    SELECT
      u.username,
      u.char_rank,
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
    WHERE u.char_id = '. $char_id .'
  ');
}

function CLAN_Train_get_own_char( $skill_name )
{
  global $_uid;
  
  if ( ! VALIDATE_Skill_name( $skill_name ) ) return false;
  
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
      s.genjutsu,
      s.skill_points,
      t.'.$skill_name.'_points
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    JOIN  skill_training   t USING (char_id)
    WHERE u.char_id = '. $_uid .'
  ');
}

function CLAN_Train_get_skill_upgrade( $own_char, $other_char, $skill_name )
{
  $result =
    ( $own_char[  'char_level'] * ( $own_char[   $skill_name ] ** 2 ) )
    /
    ( $other_char['char_level'] * ( $other_char[ $skill_name ] ** 2 ) )
    * 32;
  
  switch (true)
  {    
    case ( $result < 16 || $result >= 48 ): $trained_points = 0; break;
    case ( $result < 24 || $result >= 40 ): $trained_points = 1; break;
    case ( $result < 28 || $result >= 36 ): $trained_points = 2; break;
    case ( $result < 30 || $result >= 34 ): $trained_points = 3; break;
    case ( $result < 31 || $result >= 33 ): $trained_points = 4; break;
    case ( $result >= 31 && $result < 33 ): $trained_points = 5; break;
    
    default: return 'error';
  }
  
  $skill_level  = $own_char[ $skill_name ];
  $skill_points = $own_char[ $skill_name .'_points' ] + $trained_points;
  
  $upgrade = 0;
  
  while ( $skill_points >= $skill_level )
  {
    $skill_points -= $skill_level;
    $skill_level++;
    $upgrade++;
  }
  
  return
  [
    $trained_points,
    $skill_points,
    $skill_level,
    $upgrade
  ];
}

function CLAN_Train_with_other_char( & $_char, $other, $skill_name )
{
  global $_uid;
  
  if ( $_char['skill_points'] < 5                    ) return 'Not enough training points.';
  if ( $_char['style_name'] !== $other['style_name'] ) return 'Both chars must belong to the same clan.';
  if ( $_char['char_rank']  !== $other['char_rank']  ) return 'Both chars must have the same rank.';
  
  $level_difference = abs( $_char['char_level'] - $other['char_level'] );
  
  if ( $level_difference > 5 ) return 'Level difference is too high.';
  
  [
    $trained_points,
    $updated_points,
    $updated_skill,
    $skill_upgrade
  ] = CLAN_Train_get_skill_upgrade( $_char, $other, $skill_name );
  
  $attribute = [
    'ninjutsu' => 'flair',
    'kenjutsu' => 'strength',
    'taijutsu' => 'agility',
    'shuriken' => 'jutsu',
    'genjutsu' => 'tactics'
  ][ $skill_name ];
  
  $attribute_upgrade = $other['char_level'] > $_char['char_level'] ? 1 : 0;
  
  $updated_attribute = $_char[ $attribute ]  + $attribute_upgrade;
  
  $updated_level = floor(
  (
    $_char['flair']    +
    $_char['strength'] +
    $_char['agility']  +
    $_char['jutsu']    +
    $_char['tactics']  +
    $attribute_upgrade
  ) / 5 );
  
  $level_up = $updated_level > $_char['char_level'];
  
  $update_char = sql_transaction('
    UPDATE char_attributes  a
    JOIN   style_attributes s USING (char_id)
    JOIN   skill_training   t USING (char_id)
    SET
      s.skill_points           = s.skill_points - 5,
      a.char_level             = '. $updated_level     .',
      a.'.$attribute        .' = '. $updated_attribute .',
      s.'.$skill_name       .' = '. $updated_skill     .',
      t.'.$skill_name.'_points = '. $updated_points    .'
    WHERE a.char_id                = '. $_uid .'
    AND   s.skill_points           > 4
    AND   a.char_level             = '. $_char['char_level']  .'
    AND   a.'.$attribute        .' = '. $_char[ $attribute ]  .'
    AND   s.'.$skill_name       .' = '. $_char[ $skill_name ] .'
    AND   t.'.$skill_name.'_points = '. $_char[ $skill_name .'_points' ] .'
  ');
  
  $_char['char_level']  = $updated_level;
  $_char[ $attribute ]  = $updated_attribute;
  $_char[ $skill_name ] = $updated_skill;
  $_char[ $skill_name .'_points' ] = $updated_points;
  $_char['skill_points'] -= 5;
  
  return
  [
    'trained_points'    => $trained_points,
    'skill_upgrade'     => $skill_upgrade,
    'attribute_name'    => $attribute,
    'attribute_upgrade' => $attribute_upgrade,
    'level_up'          => $level_up
  ];
}
