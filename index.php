<?php

include 'src/Req.php';

$token  = 'e6eebda1-bb30-6e07-ab4b-1e380df6423d';

$secret = 'yz17wv0eelosm7s1zioijyig9bcp8q2mx765wgxb6ivgo28wb5';

$opts = array(
	'method' => 'get',
	'headers' => array(
		// 'Content-type' => 'application/json',
		// 'Accept' => 'application/json',
		'X-Auth-Token' => $token,
		'X-Auth-Secret' => $secret,
	),
);

$headers = $opts['headers'];

$response = Req::forge("https://api.sirportly.com/api/v1/tickets/search")
				->headers($headers)
				->post(array('query' => array('id' => 94971)));

$response->inspect();