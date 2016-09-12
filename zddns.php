<?php

define( 'ZDDNS_TOKEN',  'user:S3cur3ToKeN' );
define( 'ZDDNS_SECRET', 's3cr3t' );
define( 'ZDDNS_DOMAIN', 'example.com' );
define( 'ZDDNS_PREFIX', 'test' );

define( 'ZDDNS_HOST', sprintf( '%s.%s', ZDDNS_PREFIX, ZDDNS_DOMAIN ) );
define( 'ZDDNS_SAVE', sprintf( 'zddns.%s', hash( 'sha256', ZDDNS_TOKEN ) ) );
define( 'ZDDNS_ADDR', $_SERVER[ 'REMOTE_ADDR' ] );

function zone_api( $command, $query = array(), $post = false )
{
	$headers = array( sprintf( 'X-ZoneID-Token: %s', ZDDNS_TOKEN ), 'X-ResponseType: JSON' );
	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, sprintf( 'https://api.zone.eu/v1/%s?%s', $command, http_build_query( $query ) ) );
	curl_setopt( $curl, CURLOPT_POST, $post );
	curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	$response = curl_exec( $curl );
	curl_close( $curl );
	return json_decode( $response );
}

if ( ZDDNS_SECRET != $_SERVER[ 'QUERY_STRING' ] )
	exit( 'secret?' );

touch( ZDDNS_SAVE );

if ( ZDDNS_ADDR == @file_get_contents( ZDDNS_SAVE ) )
	exit( 'not needed' );

$get = zone_api( sprintf( 'domains/%s/records/', ZDDNS_DOMAIN ) );

if ( $get->status != 200 )
	exit;

foreach ( $get->params as $zone )
{
	if ( $zone->adomain != ZDDNS_DOMAIN )
		continue;
	
	if ( ! isset( $zone->A ) )
		continue;
	
	foreach ( $zone->A as $record )
	{
		if ( $record->host != ZDDNS_HOST )
			continue;

		$set = zone_api
		(
			sprintf( 'domains/%s/records/%s', ZDDNS_DOMAIN, $record->id ),
			array( 'prefix' => ZDDNS_PREFIX, 'content' => ZDDNS_ADDR ),
			true
		);

		if ( $set->status == 200 )
		{
			file_put_contents( ZDDNS_SAVE, ZDDNS_ADDR );
			echo 'updated';
		}	
	}
}
