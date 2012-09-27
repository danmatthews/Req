<?php

require dirname(__FILE__).'/../vendor/autoload.php';
require dirname(__FILE__).'/../src/Requiem.php';

class RequiemTest extends PHPUnit_Framework_TestCase {

	public function testConstruct()
	{
		$filename = 'test.json';
		$req = new Requiem($filename);
		$this->assertEquals($filename, $req->filename);
	}

	/**
	 * @depends testConstruct
	 */
	public function testValidateJson()
	{
		$req = new Requiem();
	}

}