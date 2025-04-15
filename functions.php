<?php

// -- Functions --

$conn = mysqli_connect( 'localhost', 'root', 'uchihasasukeitachi97D', 'nbs' );
if ( ! $conn )
{
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	
	exit( include("footer.php") );
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

function sql_query( $conn, $query )
{
	$result = mysqli_query ($conn, $query ) or die( mysqli_error($conn) );
	
	return $result;
}

function sql_mfa( $conn, $query )
{
	$result = mysqli_fetch_assoc( mysqli_query( $conn, $query ) ) or die( mysqli_error($conn) );
	
	return $result;
}

function sql_prepstate( $conn, $query, $types, $values )
{
	$stmt = mysqli_prepare( $conn, $query ) or die( mysqli_error($conn) );
	
	mysqli_stmt_bind_param( $stmt, $types, $values );
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

function getdata( $conn, $query, $prefix )
{
	extract(
		mysqli_fetch_assoc( sql_query( $conn, $query ) ),
		EXTR_PREFIX_ALL, $prefix);
}

function unset_key( $search, $array )
{
	$key = array_search($search, $array);
	
	unset($array[$key]);
}

?>