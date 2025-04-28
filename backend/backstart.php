<?php

session_start();

require_once __DIR__ .'/../functions/functions.php';

if ( isset( $_SESSION['uid'] ) )
{
	$_uid = $_SESSION['uid'];
}

?>