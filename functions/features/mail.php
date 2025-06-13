<?php

// -- Mail functions --

function MAIL_delete_message( $msg_id )
{
  return sql_transaction('
    UPDATE mail
    SET    seen = 2
    WHERE  msg_id = '. $msg_id .'
  ');
}

function MAIL_get_received()
{
  global $_uid;
  
  return sql_all('
    SELECT
      m.msg_id,
      m.msg_time,
      m.sender_username,
      m.msg_text,
      m.seen,
      s.char_id as sender_id
    FROM       mail       m
    LEFT  JOIN game_users r ON r.username = m.receiver_username
    RIGHT JOIN game_users s ON s.username = m.sender_username
    WHERE      r.char_id = '. $_uid .'
    AND        seen <> 2
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
      m.receiver_username,
      m.msg_text,
      m.seen,
      r.char_id as receiver_id
    FROM       mail       m
    LEFT  JOIN game_users s ON s.username = m.sender_username
    RIGHT JOIN game_users r ON r.username = m.receiver_username
    WHERE      s.char_id = '. $_uid .'
    AND        seen <> 2
    ORDER BY   msg_time DESC
  ');
}

function MAIL_send( $to_username, $msg_text )
{
  global $_uid;
  
  $to_username = handle_name( $to_username );
  
  // after handle_name, $to_username holds error message if its length is > 16.
  if ( ! VALIDATE_User_name( $to_username ) )               return 'Invalid username: '. $to_username;
  if ( ! VALIDATE_Mail_text_length( strlen( $msg_text ) ) ) return 'Text length must be 1 to 800 chars.';
  
  sql_transaction('
    INSERT INTO mail (
      sender_username,
      receiver_username,
      msg_text
    )
    VALUES (
      ( SELECT username FROM game_users WHERE char_id = '. $_uid .' ),
      \''. $to_username .'\',
      \''. $msg_text     .'\'
    )
  ');
}

function MAIL_view_message( $msg_id )
{
  return sql_transaction('
    UPDATE mail
    SET    seen = 1
    WHERE  msg_id = '. $msg_id .'
  ');
}
