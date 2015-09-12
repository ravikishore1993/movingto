<?php

function generateToken($config)
{
	$ch = curl_init( $config['API_LINK'].$config['TOKEN_ENDPOINT']);
	$payload = json_encode( array( "appId"=> $config['APP_ID'], "signature" => hash_hmac('sha1', $config['EMAIL'].$config['APP_ID'].$config['DATE'], $config['APP_SECRET'])));
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($result);
	if($result->error == "false")
		return json_encode($result);
	else 
	{
		die();
		return 0;
	}
}

function generateCurl($ch,$api_name,$config)
{
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',
												'Cache-Control: no-cache',
												'Host: api.quikr.com',
												'X-Quikr-App-Id:'.$config['APP_ID'],
												'X-Quikr-Token-Id:'.$config['tokenobject']->tokenId,
												'X-Quikr-Signature:'.hash_hmac('sha1', $config['APP_ID'].$api_name.$config['DATE'], $config['tokenobject']->token)
												));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	return $ch;
	
}
