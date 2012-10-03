<?php

require dirname(__FILE__).'/../vendor/autoload.php';
require dirname(__FILE__).'/../src/Req.php';

class RequiemTest extends PHPUnit_Framework_TestCase {

	public function testConstruct()
	{
		$req = new Req("http://test.com");
		$this->assertTrue($req instanceof Req);
	}

	public function testForge()
	{
		$req = Req::forge();
		$this->assertTrue($req instanceof Req);
	}

	/**
	 * @depends testConstruct
	 */
	public function testSetUrl()
	{
		$url = "http://danmatthews.me";
		$req = new Req($url);
		$this->assertEquals($url, $req->opts['url']);
	}

	public function testSetHeaders()
	{
		$headers = array(
			'Content-type' => 'application/json',
			'X-Test-Custom' => 'application-name',
		);

		$req = new Req("http://danmatthews.me");

		$req->headers($headers);

		// Assert that $req is still a valid isntance
		$this->assertTrue($req instanceof Req);

		// Assert that $req->opts['headers'] is an array.
		$this->assertInternalType('array', $req->opts['headers']);

		// Assert that the actual headers have been pulled through.
		foreach ($headers as $key => $value)
		{
			$this->assertEquals($value, $headers[$key]);
		}

	}

}