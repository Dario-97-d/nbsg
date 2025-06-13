<?php

// -- Handle functions --

function handle_name( $name )
{
  $name = trim( $name );
  $length = strlen( $name );
  
  // Check length of $name.
  if ( $length < 4 || $length > 16 )
  {
    return 'Name must be 4-16 chars long.';
  }
  
  // Check allowed characters.
  if (
    ! ctype_alnum(
      str_replace(
        array( '_', '-', ' ' ),
        '',
        $name
  ) ) )
  {
    return 'Name may consist of numbers, letters, underscore, hyphen and space.';
  }
  
  // Check minimum of 4 letters.
  if (
    strlen(
      str_replace(
        array( '_', '-', ' ', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ),
        '',
        $name
    ) )
    < 4
  )
  {
    return 'Name must contain minimum 4 letters.';
  }
  
  return $name;
}

function handle_email( $email )
{
  $email = trim( $email );
  $length = strlen( $email );
  
  // Check length of $email.
  if ( $length < 8 || $length > 48 )
  {
    return 'The e-mail address is expected to be 12-48 characters long.';
  }
  
  // Check allowed characters.
  if (
    ! ctype_alnum(
      str_replace(
        array( '_', '-', '.', '@' ),
        '',
        $email
  ) ) )
  {
    return 'The e-mail address may consist of letters, numbers, underscore, hyphen, dot and @.';
  }
  
  return $email;
}
