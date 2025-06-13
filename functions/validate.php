<?php

// -- Validate functions --

function VALIDATE_Char_attribute( $attribute )
{
  // Maybe use strtolower().
  return in_array( $attribute, [ 'flair', 'strength', 'agility', 'jutsu', 'tactics' ] );
}

function VALIDATE_Char_id( $id )
{
  // Is digits only and not 0.
  return ctype_digit( strval( $id ) ) && $id;
}

function VALIDATE_Mail_text_length( $length )
{
  return $length > 0 && $length < 801;
}

function VALIDATE_Skill_name( $skill_name )
{
  return in_array( strtolower( $skill_name ), [ 'kenjutsu', 'shuriken', 'taijutsu', 'ninjutsu', 'genjutsu' ] );
}

function VALIDATE_Team_skills( $team_skills )
{
  return
    is_array( $team_skills )          &&
    isset( $team_skills['kenjutsu'] ) &&
    isset( $team_skills['shuriken'] ) &&
    isset( $team_skills['taijutsu'] ) &&
    isset( $team_skills['ninjutsu'] ) &&
    isset( $team_skills['genjutsu'] );
}

function VALIDATE_User_email( $email )
{
  // Update later - now it's meant to be used after handle_email.
  return strlen( $email ) < 17;
}

function VALIDATE_User_name( $username )
{
  // Update later - now it's meant to be used after handle_name.
  return strlen( $username ) < 17;
}

function VALIDATE_User_password( $password )
{
  $length = strlen( $password );
  
  return $length > 7 && $length < 33;
}
