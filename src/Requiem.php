<?php

class Requiem {

	/**
	 * [$filename description]
	 * @var [type]
	 */
	public $filename;
	public $json;

	/**
	 * Contains the request object.
	 */
	protected $data;

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

	public function __construct($filename = null)
	{
		$this->filename = $filename;
	}

	public function setFilename($filename)
	{

	}

	public function setJson($raw_json)
	{

	}

	/**
	 * Send the request.
	 */
	public function makeRequest()
	{

		if ($this->validate())
		{

			$c = new \Colors\Color();

			$curl = curl_init($this->data->url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);

			if (isset($this->data->headers) && count($this->data->headers) > 0)
			{
				curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers());
			}

			if (strtolower ($this->data->method) == 'post') {
				echo 'HERE';
				curl_setopt($curl, CURLOPT_POST, TRUE);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $this->serializeData());
			}
			else if (strtolower ($this->data->method) == 'put') {
				curl_setopt($curl, CURLOPT_PUT, TRUE);
			}
			else {
				curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
			}

			$result = curl_exec($curl);

			$info = curl_getinfo($curl);

			curl_close($curl);

			return array(
				'body' => $result,
				'data' => $info,
			);

		}

	}

	public function headers()
	{

		$headers = array();

		foreach ($this->data->headers as $key => $value)
		{
			$headers[] = "{$key}: {$value}";
		}

		return $headers;

	}

	function serializeData()
	{
		$formData = array();

		foreach ($this->data->data as $key => $value)
		{
			$formData[] = "$key=$value";
		}

		return implode("&", $formData);

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
