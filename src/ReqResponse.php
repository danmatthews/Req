<?php

class ReqResponse {

	public $headers;

	public $body;

	public $info;

	public function __construct($body, $headers, $info) {

		$split = explode("\r\n\r\n", $body);

		$headers = $split[0];

		$headers = explode("\n", $headers);

		$body = $split[1];

		$this->body = $body;

		$this->headers = $headers;

		$this->info = $info;
	}

	public function inspect()
	{
		echo '<pre>'.print_r($this,1).'</pre>';
	}

}