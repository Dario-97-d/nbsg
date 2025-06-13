<?php

// -- SQL functions --

// Connect automatically.
$_CONN = mysqli_connect( 'localhost', 'nbsg', '6suAq/PSX]gfIpSS', 'nbsg'  );
if ( ! $_CONN )
{
  echo "Error: Unable to connect to MySQL." . PHP_EOL;
  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
  
  exit();
}

function sql_all( $query )
{
  $result = sql_query( $query );
  
  if ( $result )
  {
    return mysqli_fetch_all( sql_query( $query ), MYSQLI_ASSOC );
  }
  
  return false;
}

function sql_query( $query )
{
  global $_CONN;
  
  $result = mysqli_query ( $_CONN, $query ) or die( mysqli_error( $_CONN ) );
  
  return $result;
}

function sql_mfa( $query )
{
  $result = sql_query( $query );
  
  if ( $result )
  {
    return mysqli_fetch_assoc( $result );
  }
  
  return false;
}

function sql_transaction( $query )
{
  global $_CONN;
  
  mysqli_begin_transaction( $_CONN );
  
  $result = mysqli_query( $_CONN, $query );
  
  if ( $result && mysqli_affected_rows( $_CONN ) > 0 )
  {
    mysqli_commit( $_CONN );
    return true;
  }
  else
  {
    mysqli_rollback( $_CONN );
    return false;
  }
}
