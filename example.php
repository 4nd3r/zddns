<?php

define( 'ZONE_API_TOKEN', 'user:token' );

require 'zone_api.php';

$save = 'example.last';
$last = file_get_contents( $save );
$addr = $_SERVER[ 'REMOTE_ADDR' ];

if ( $addr != $last )
{
	zone_api_update_record( 'example.com', 'A_000000', 'test', $addr );
	file_put_contents( $save, $addr );
}
