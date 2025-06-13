<?php

// -- Start functions --

function START_char( $style_name, $skills )
{
  global $_uid;
  
  if ( ! START_validate_clan_name( $style_name ) )       return 'invalid clan name';
  if ( ! START_validate_skills( $style_name, $skills ) ) return 'invalid skill values';
  
  return sql_transaction('
    UPDATE game_users       u
    JOIN   style_attributes s USING (char_id)
    SET
      u.char_rank  = \'D\',
      s.style_name = \''. $style_name .'\',
      s.kenjutsu   = \''. $skills['kenjutsu'] .'\',
      s.shuriken   = \''. $skills['shuriken'] .'\',
      s.taijutsu   = \''. $skills['taijutsu'] .'\',
      s.ninjutsu   = \''. $skills['ninjutsu'] .'\',
      s.genjutsu   = \''. $skills['genjutsu'] .'\'
    WHERE u.char_id    = '. $_uid .'
    AND   s.style_name = \'\'
  ');
}

function START_has_clan()
{
  global $_uid;
  
  $has_clan = sql_query('
    SELECT 1
    FROM   style_attributes
    WHERE  char_id = '. $_uid .'
    AND    style_name <> \'\'
  ');
  
  return $has_clan && mysqli_num_rows( $has_clan );
}

function START_validate_clan_name( $clan_name )
{
  return in_array(
    $clan_name,
    [
      'Tameru',
      'Tayuga',
      'Kensou',
      'Surike',
      'Geniru',
      'Faruni',
      'Wyroni',
      'Raiyni',
      'Rokuni',
      'Watoni'
    ]
  );
}

function START_validate_skills( $style_name, $skills )
{
  return
    is_array( $skills )
    &&
    count( $skills ) === 5
    &&
    array_sum( $skills ) === 10
    &&
    isset( $skills['kenjutsu'] )
    &&
    isset( $skills['shuriken'] )
    &&
    isset( $skills['taijutsu'] )
    &&
    isset( $skills['ninjutsu'] )
    &&
    isset( $skills['genjutsu'] )
    &&
    (
      (
        $style_name === 'Tameru'
        &&
        (
          $skills['kenjutsu'] >= 1
          &&
          $skills['shuriken'] >= 1
          &&
          $skills['taijutsu'] >= 5
          &&
          intval( $skills['ninjutsu'] ) === 0
          &&
          intval( $skills['ninjutsu'] ) === 0
        )
      )
      ||
      (
        min( $skills ) >= 1
        &&
        (
          ( $style_name === 'Tayuga' && $skills['taijutsu'] >= 3 )
          ||
          ( $style_name === 'Kensou' && $skills['kenjutsu'] >= 3 )
          ||
          ( $style_name === 'Surike' && $skills['shuriken'] >= 3 )
          ||
          ( $style_name === 'Geniru' && $skills['genjutsu'] >= 3 )
          ||
          (
            in_array( $style_name, [ 'Faruni', 'Wyroni', 'Raiyni', 'Rokuni', 'Watoni' ] )
            &&
            $skills['ninjutsu'] >= 3
          )
        )
      )
    );
}
