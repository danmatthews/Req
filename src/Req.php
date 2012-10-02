<?php

include 'ReqResponse.php';

/**
 * Req is a wrapper class for cURL, to make working with and making requests easier.
 */
class Req {

	/**
	 * Stores the request options.
	 * @var array
	 */
	public $opts;

	public function __construct($url = null)
	{
		$this->opts['url'] = $url;
	}

	public static function forge($url = null)
	{
		return new static ($url);
	}

	public function url ($url)
	{
		$this->opts['url'] = $url;
	}

	public function get ($params = null)
	{
		return $this->make('GET', $params);
	}

	public function post ($params = null)
	{
		return $this->make('POST', $params);
	}

	public function headers($headers)
	{
		$this->opts['headers'] = $headers;
		return $this;
	}

	/**
	 * Send the request.
	 */
	public function make($type = 'GET', $params = null)
	{

		$errors = $this->validate();

		if (empty ($errors))
		{

			$curl = curl_init($this->opts['url']);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			curl_setopt($curl, CURLOPT_HEADER, 1);

			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			if (isset($this->opts['headers']) && count($this->opts['headers']) > 0)
			{
				curl_setopt($curl, CURLOPT_HTTPHEADER, $this->buildHeaders());
			}

			if (strtolower ($type) == 'post')
			{
				$data = is_string($params) ? $params : $this->serializeData($params);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}
			else
			{
				curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
			}

			$body = curl_exec($curl);

			$info = curl_getinfo($curl);

			curl_close($curl);

			return new ReqResponse($body, $info);

		}

	}

	protected function buildHeaders()
	{

		$headers = array();

		foreach ($this->opts['headers'] as $key => $value)
		{
			$headers[] = "{$key}: {$value}";
		}

		return $headers;

	}

	function serializeData($params, $base = null)
	{

		$formData = array();

		foreach ($params as $key => $value)
		{
			if (is_array($value))
			{
				$formData[] = $this->serializeData($value, $key);
			}
			else
			{
				$formData[] = $base ? "{$base}[{$key}]={$value}" : "$key=$value";
			}

		}

		return implode("&", $formData);

	}

	/**
	 * Validate the parameters.
	 */
	public function validate()
	{

		$errors = array();

		if (!isset($this->opts['url'])) {
			$errors[] = "No URL parameter was present.";
		}

		if (!isset($this->opts['method'])) {
			$this->opts['method'] = 'get';
		}

		if (isset($this->opts['headers']) && !is_array($this->opts['headers'])) {
			$errors[] = "The headers property is set, but is not an array.";
		}

		return $errors;

	}

	public function inspect()
	{
		echo '<pre>';
		print_r ($this);
		echo '</pre>';
	}

}
