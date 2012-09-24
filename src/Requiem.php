<?php

class Requiem {

	public function __construct()
	{

		$cmd = new Commando\Command();

		$cmd->option()
			->referToAs('Filename: ')
			->describedAs("The request JSON file")
			->require()
			->must(function($filename)
			{
				// Load the file, check that it exists.
				if (!is_file($filename))
				{
					return false;
				}
				$contents = file_get_contents($filename);
				if (!is_string($contents) || strlen($contents) < 2)
				{
					return false;
				}
				$json = json_decode($contents);
				if (!is_object($json) && !is_array($json))
				{
					return false;
				}
				return true;
			})
			->option('v')
			->aka('validate-only')
			->boolean();

		$this->setUp($cmd[0], $cmd);

	}

	/**
	 * Setup the RequiemRequest object.
	 * @param string   $filename  The filepath to the request.json file
	 * @param Command  $cmd       The commando app object.
	 */
	public function setUp($filename, $cmd)
	{

		$json = json_decode(file_get_contents($filename));

		$c = new \Colors\Color();

		// Run the validation on the JSON
		if ($this->validateJson($json))
		{

			

			// Is this set to validate only? Exit then.
			if ($cmd['v'] == 1)
			{
				exit ($c('Validation complete, all OK.')->white()->highlight('green') . PHP_EOL);
			}

		}

	}

	public function validateJson($json)
	{

		$c = new \Colors\Color();

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

		return true;

	}

}
