<?php

function zone_api( $command, $query = array(), $post = false )
{
	$headers = array
	(
		'X-ZoneID-Token: ' . ZONE_API_TOKEN,
		'X-ResponseType: JSON'
	);

	if ( ! empty( $query ) )
		$command .= '?';

	$uri = 'https://api.zone.eu/v1/' . $command . http_build_query( $query );

	if ( defined( 'ZONE_API_DEBUG' ) )
	{
		printf( "%s %s\n", $post ? 'SET' : 'GET', $uri );
		return;
	}

	$curl = curl_init();

	curl_setopt( $curl, CURLOPT_URL, $uri );
	curl_setopt( $curl, CURLOPT_POST, $post );
	curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

	$response = curl_exec( $curl );

	curl_close( $curl );

	return json_decode( $response );
}

function zone_api_get_records( $domain = '', $id = '' )
{
	return zone_api( sprintf( 'domains/%s/records/%s', $domain, $id ) );
}

function zone_api_update_record( $domain, $id, $prefix, $content )
{
	return zone_api
	(
		sprintf( 'domains/%s/records/%s', $domain, $id ),
		array( 'prefix' => $prefix, 'content' => $content ),
		true
	);
}
