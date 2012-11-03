<?php

require dirname(__FILE__).'/../vendor/autoload.php';
require dirname(__FILE__).'/../src/Req.php';

class ReqTest extends PHPUnit_Framework_TestCase {

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

	public function testChangeUrl()
	{
		$initialUrl = 'http://example.com/1';
		$changedUrl = 'http://example.com/2';

		$req = new Req($initialUrl);
		$req->url($changedUrl);

		$this->assertEquals($req->opts['url'], $changedUrl);
	}

	public function testBuildHeadersString()
	{
		$headers = array(
			'Content-type' => 'application/json',
			'X-Test-Custom' => 'application-name',
		);

		$stringvals = array(
			'Content-type: application/json',
			'X-Test-Custom: application-name',
		);

		// Use Reflection Method to test this, as it's a protected function.
		$method = new ReflectionMethod('Req', 'buildHeaders');

		$method->setAccessible(true);

		$req = new Req("http://example.com");

		$req->headers($headers);

		$result = $method->invoke($req);

		$this->assertEquals($result[0], $stringvals[0]);

		$this->assertEquals($result[1], $stringvals[1]);

	}

}