<?php

include 'src/Req.php';
include 'src/ReqResponse.php';

$opts = array(
	'url' => 'http://danmatthews.me',
	'method' => 'get',
	'headers' => array(
		'Content-type' => 'text/json',
		'Accept' => 'application/json',
	),
);

$req = new Req('http://danmatthews.me');

$response = $req->get();
