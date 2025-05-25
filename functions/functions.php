<?php

// -- Functions --

$_CONN = mysqli_connect( 'localhost', 'nbsg', '6suAq/PSX]gfIpSS', 'nbsg'  );
if ( ! $_CONN )
{
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	
	exit();
}

function exiter( $loc )
{
	exit( header("Location: $loc") );
}

function handle_name( $name )
{
	$name = trim($name);
	$sln = strlen($name);
	
	if ( $sln < 4 || $sln > 16 )
	{
		return "Name must be 4-16 chars long";
	}
	
	if (
		! ctype_alnum(
			str_replace(
				array( '_', '-', ' ' ),
				'',
				$name ) ) )
	{
		return "Characters allowed include only numbers, letters, underscore, hyphen and space";
	}
	
	if (
		strlen(
			str_replace(
				array( '_', '-', ' ', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ),
				'',
				$name ) )
		< 4 )
	{
		return "Name must contain minimum 4 letters";
	}
	
	return $name;
}

function handle_email( $email )
{
	$email = trim($email);
	$slem = strlen($email);
	
	if ( $slem < 8 || $slem > 48 )
	{
		return "E-mail expected to be min 12 et max 48 chars long";
	}
	
	if (
		! ctype_alnum(
			str_replace(
				array( '_', '-', '.', '@' ),
				'',
				$email ) ) )
	{
		return "E-mail expected to include only letters, numbers, underscore, hyphen, dot and @";
	}
	
	return $email;
}

function sql_query( $query )
{
	global $_CONN;
	
	$result = mysqli_query ( $_CONN, $query ) or die( mysqli_error( $_CONN ) );
	
	return $result;
}

function sql_mfa( $query )
{
	return mysqli_fetch_assoc( sql_query( $query ) );
}

$_JS_messages;

function JS_add_message( $msg )
{
	global $_JS_messages;
	
	if ( isset( $_JS_messages ) && is_array( $_JS_messages ) )
	{
		$_JS_messages[] = $msg;
	}
	else
	{
		$_JS_messages = [ $msg ];
	}
}

function JS_console_log( $msg )
{
	echo '<script>console.log("'. $msg .'");</script>';
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

function SQL_perform_transaction( $query )
{
	global $_CONN;
	
	mysqli_begin_transaction( $_CONN );
	
	$result = mysqli_query( $_CONN, $query );
	
	if ( $result && mysqli_affected_rows( $_CONN ) === 1 )
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

?>
