<?php

if ( false === filter_var( $_SERVER[ 'REMOTE_ADDR' ], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) )
    exit( 'ipv4 only' . PHP_EOL );

require 'config.php';

if ( isset( SUBDOMAINS[ $_SERVER[ 'QUERY_STRING' ] ] ) )
    define( 'NAME', sprintf( '%s.%s', SUBDOMAINS[ $_SERVER[ 'QUERY_STRING' ] ], DOMAIN ) );
else
    exit( 'unknown key' . PHP_EOL );

function zone_api_v2( $path, $data = null )
{
    $curl = curl_init();

    curl_setopt( $curl, CURLOPT_URL, sprintf( 'https://api.zone.eu/v2/%s', $path ) );

    curl_setopt
    (
        $curl,
        CURLOPT_HTTPHEADER,
        array( sprintf( 'Authorization: Basic %s', AUTH ), 'Content-Type: application/json' )
    );

    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

    if ( ! is_null( $data ) )
    {
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
    }

    $response = curl_exec( $curl );

    curl_close( $curl );

    return json_decode( $response );
}

$zone = zone_api_v2( sprintf( 'dns/%s/a', DOMAIN ) );

if ( empty( $zone ) )
    exit( 'empty zone' . PHP_EOL );

foreach ( $zone as $record )
{
    if ( $record->name !== NAME )
        continue;
    
    if ( $record->destination === $_SERVER[ 'REMOTE_ADDR' ] )
        exit( 'not needed' . PHP_EOL );

    $update = zone_api_v2
    (
        sprintf( 'dns/%s/a/%s', DOMAIN, $record->id ),
        array( 'name' => NAME, 'destination' => $_SERVER[ 'REMOTE_ADDR' ] )
    );

    if ( ! isset( $update[ 0 ]->destination ) )
        exit( 'update failed' . PHP_EOL );

    if ( $update[ 0 ]->destination === $_SERVER[ 'REMOTE_ADDR' ] )
        exit( 'updated' . PHP_EOL );
    else
        exit( 'wtf!?' . PHP_EOL );
}
