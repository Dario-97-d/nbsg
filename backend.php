<?php

session_start();

include("functions.php");

if ( isset( $_SESSION['uid'] ) )
{
	$_uid = $_SESSION['uid'];
}

?>