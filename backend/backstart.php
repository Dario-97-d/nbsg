<?php

  session_start();
  
  require_once __DIR__ .'/../functions/functions.php';
  require_once __DIR__ .'/../functions/handle.php';
  require_once __DIR__ .'/../functions/js.php';
  require_once __DIR__ .'/../functions/sql.php';
  require_once __DIR__ .'/../functions/validate.php';
  require_once __DIR__ .'/../functions/view.php';
  
  // $_SESSION['uid'] = 8;
  if ( isset( $_SESSION['uid'] ) )
  {
    $_uid = $_SESSION['uid'];
  }
