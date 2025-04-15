<?php

$ken = 7;
$ken1 = 14;
$ken2 = 2;

$start = microtime(true);

for ( $i = 0; $i < 10000; ++$i )
{
	$kens = $ken + $ken1 + $ken2;
	
	echo $kens;
	echo $kens;
	echo $kens;
}

$dur = ( microtime(true) * 1000 ) - ( $start * 1000 );

echo "$i reps: $dur ms";

?>