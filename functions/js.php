<?php

// -- JavaScript functions --

// Global var for user messages to display in view.
$_JS_messages;

function JS_add_message( $msg )
{
  global $_JS_messages;
  
  $_JS_messages[] = $msg;
}

function JS_render_messages()
{
  global $_JS_messages;
  
  if ( isset( $_JS_messages ) && is_array( $_JS_messages ) )
  {
    foreach ( $_JS_messages as $msg )
    {
      echo '<br>'. $msg;
    }
  }
}
