<?php

// -- Functions --

function exiter( $location )
{
  exit( header( 'Location: '. $location ) );
}

function LAYOUT_wrap_onwards()
{
  ob_start();
  
  register_shutdown_function(
    function ()
    {
      global $_uid;
      
      $_LAYOUT_VIEW_CONTENT = ob_get_clean();
      
      require_once __DIR__ .'/../views/layout.php';
    });
}
