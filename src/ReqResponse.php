<?php

class ReqResponse {

	public $headers;

	public $body;

	public $info;

	public function __construct($body, $headers, $info)
	{

		list($headers, $body) = explode("\r\n\r\n", $body);

		$headers = explode("\n", $headers);

		$headerList = array();

		foreach ($headers as $header)
		{
			if (stristr($header, ':'))
			{
				list($key, $value) = explode(":", $header);
				$headerList[$key] = trim($value);
			}
		}

		$this->body = $body;

		$this->headers = $headerList;

		$this->info = $info;
	}

	public function inspect()
	{
		echo '<pre>'.print_r($this,1).'</pre>';
	}

}