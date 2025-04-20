<?php

$kenjutsu = 7;
$kenjutsu1 = 14;
$kenjutsu2 = 2;

$start = microtime(true);

for ( $i = 0; $i < 10000; ++$i )
{
	$kenjutsus = $kenjutsu + $kenjutsu1 + $kenjutsu2;
	
	echo $kenjutsus;
	echo $kenjutsus;
	echo $kenjutsus;
}

$dur = ( microtime(true) * 1000 ) - ( $start * 1000 );

echo "$i reps: $dur ms";

?>