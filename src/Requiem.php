<?php

class Requiem {

	protected $filename;

	protected $json;

	protected $valid_params = array(
		'url',
		'method',
		'data',
		'auth' => array(
			'type',
			'username',
			'password'
		),
		'headers',
	);

	public function __construct($filename)
	{
		$this->filename = $filename;
	}

	/**
	 * Send the request.
	 */
	public function makeRequest()
	{

		if ($this->validate())
		{

			$curl = curl_init($this->json->url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);

			if (!isset($json->method))
			{
				curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
			}
			else if (strtolower ($json->method) == 'post') {
				curl_setopt($curl, CURLOPT_POST, TRUE);
			}
			else if (strtolower ($json->method) == 'put') {
				curl_setopt($curl, CURLOPT_PUT, TRUE);
			}

			$result = curl_exec($curl);

			curl_close($curl);

		}

	}

	/**
	 * Validate the JSON.
	 */
	public function validate()
	{

		$json = json_decode(file_get_contents($this->filename));

		$c = new \Colors\Color();

		// Check it's parsed correctly.
		if (!is_object($json) && !is_array($json))
		{
			exit ($c("Invalid JSON Provided")->white()->highlight('red').PHP_EOL);
			return false;
		}

		// Check it has the required 'url' parameter.
		if (!isset ($json->url) || !is_string ($json->url))
		{
			exit ($c("The 'url' parameter is missing from the JSON file, and is required.")->white()->highlight('red').PHP_EOL);
			return false;
		}
		else {

			if (!filter_var ($json->url, FILTER_VALIDATE_URL))
			{
				exit ($c("The 'url' you supplied doesn't appear to be valid.")->white()->highlight('red').PHP_EOL);
				return false;
			}

		}

		$this->json = $json;

		return true;

	}

}
