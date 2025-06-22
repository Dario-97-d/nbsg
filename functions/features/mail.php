<?php

// -- Mail functions --

function MAIL_delete_message( $msg_id )
{
  return sql_transaction('
    DELETE FROM mail WHERE msg_id = '. $msg_id .'
  ');
}

function MAIL_get_received()
{
  global $_uid;
  
  return sql_all('
    SELECT
      m.msg_id,
      m.msg_time,
      m.sender_id,
      m.msg_text,
      s.username as sender_username
    FROM       mail       m
    LEFT  JOIN game_users r ON r.char_id = m.receiver_id
    RIGHT JOIN game_users s ON s.char_id = m.sender_id
    WHERE      r.char_id = '. $_uid .'
    ORDER BY   msg_time DESC
  ');
}

function MAIL_get_sent()
{
  global $_uid;
  
  return sql_all('
    SELECT
      m.msg_id,
      m.msg_time,
      m.receiver_id,
      m.msg_text,
      r.username as receiver_username
    FROM       mail       m
    LEFT  JOIN game_users s ON s.char_id = m.sender_id
    RIGHT JOIN game_users r ON r.char_id = m.receiver_id
    WHERE      s.char_id = '. $_uid .'
    ORDER BY   msg_time DESC
  ');
}

function MAIL_send( $msg_text, $to_username )
{
  global $_uid;
  
  $to_username = handle_name( $to_username );
  
  // after handle_name, $to_username holds error message if its length is > 16.
  if ( ! VALIDATE_User_name( $to_username ) )               return 'Invalid username: '. $to_username;
  if ( ! VALIDATE_Mail_text_length( strlen( $msg_text ) ) ) return 'Text length must be 1 to 800 chars.';
  
  $get_receiver_id = sql_mfa('
    SELECT char_id
    FROM   game_users
    WHERE  username = \''. $to_username .'\'
  ');
  
  if ( ! $get_receiver_id ) return 'User not found: '. $to_username;
  
  sql_transaction('
    INSERT INTO mail (
      sender_id,
      receiver_id,
      msg_text
    )
    VALUES (
      '. $_uid .',
      '. $get_receiver_id['char_id'] .',
      \''. $msg_text .'\'
    )
  ');
}
