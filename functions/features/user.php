<?php

// -- User functions --

function USER_get_current_email()
{
  global $_uid;
  
  $char = sql_mfa('
    SELECT email
    FROM   game_users
    WHERE  char_id = '. $_uid .'
  ');
  
  if ( $char )
  {
    return $char['email'];
  }
  
  return false;
}

function USER_login( $username, $password )
{
  $username = handle_name( $username );
  
  if ( ! VALIDATE_User_name( $username ) ) return false;
  
  $char_id = sql_mfa('
    SELECT char_id
    FROM   game_users
    WHERE  username  = \''. $username        .'\'
    AND    pass_word = \''. md5( $password ) .'\'
  ');
  
  if ( $char_id )
  {
    $_SESSION['uid'] = $char_id['char_id'];
    
    return true;
  }
  
  return false;
}

function USER_register( $username, $password, $email )
{
  $username = handle_name( $username );
  $email    = handle_email( $email );
  
  // after handle_name, $username holds error message if its length is > 16.
  // after handle_email, $email holds error message if its length is > 48.
  if ( ! VALIDATE_User_name(     $username ) ) return 'Invalid username: '. $username;
  if ( ! VALIDATE_User_email(    $email    ) ) return 'Invalid email: '.    $email;
  if ( ! VALIDATE_User_password( $password ) ) return 'Invalid password: must be 8-32 characters.';
  
  $register = sql_mfa('CALL sp_register_userchar(\''. $username .'\', \''. $email .'\', \''. md5( $password ) .'\')');
  
  if ( $register['result'] === 'success' )
  {
    $_SESSION['uid'] = $register['char_id'];
    return true;
  }
  
  if ( $register['result'] === 'fail' )
  {
    return $register['message'];
  }
  
  return 'Could not register.';
}

function USER_update_email( $new_email, $password )
{
  global $_uid;
  
  $new_email = handle_email( $new_email );
  
  // after handle_email, $email holds error message if its length is > 48.
  if ( ! VALIDATE_User_email(    $new_email ) ) return 'Invalid email: '. $email;
  if ( ! VALIDATE_User_password( $password  ) ) return 'Wrong password.';
  
  $update = sql_transaction('
    UPDATE game_users
    SET    email     = \''. $new_email .'\'
    WHERE  char_id   =   '. $_uid      .'
    AND    pass_word = \''. $password  .'\'
  ');
  
  return $update ? true : 'Wrong password.';
}

function USER_update_password( $new_password, $current_password )
{
  global $_uid;
  
  if ( ! VALIDATE_User_password( $new_password     ) ) return 'Invalid new password: must be 8-32 characters.';
  if ( ! VALIDATE_User_password( $current_password ) ) return 'Wrong current password.';
  
  $update = sql_transaction('
    UPDATE game_users
    SET    pass_word = \''. md5( $new_password )     .'\'
    WHERE  char_id   =   '. $_uid                    .'
    AND    pass_word = \''. md5( $current_password ) .'\'
  ');
  
  return $update ? true : 'wrong current password';
}
