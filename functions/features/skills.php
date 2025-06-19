<?php

// -- Skills functions --

function SKILLS_get_by_style( $style_name )
{
  if ( $style_name === 'Tameru' )
  {
    return [ 'kenjutsu', 'shuriken', 'taijutsu' ];
  }
  
  return
  [
    'kenjutsu',
    'shuriken',
    'taijutsu',
    'ninjutsu',
    'genjutsu',
  ];
}

function SKILLS_get_sum( $skills )
{
  return
  (
    $skills['kenjutsu'] +
    $skills['shuriken'] +
    $skills['taijutsu'] +
    $skills['ninjutsu'] +
    $skills['genjutsu']
  );
}
