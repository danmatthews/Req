[![Build Status](https://secure.travis-ci.org/danmatthews/Req.png)](http://travis-ci.org/danmatthews/Req)

# Req

Pronounced 'Wreck'. Req has two components, a PHP class usable on it's own to make HTTP requests, and a command-line binary that is used alongside a JSON document to specify request details, and can also take a second filename argument that will be used as the request body.

## Installation.

If you're wanting to use the `Req` class by itself, simply do the following, this will also include the `ReqResponse` class too.

```php
include 'src/Req.php';
```

If you're wanting to also use the `req` binary, it has a few dependencies that are managed with [Composer](https://github.com/composer/composer), in order to use it, you must install the composer executable by typing:

```shell
curl -s http://getcomposer.org/installer | php
```

In the root directory of the projects, then simply run:

```shell
php composer.phar install
```

To install the dependencies.

You can then, if you like, use it from within the folder by calling `./req my-request.json`, or you can make it available system wide by symlinking it somewhere into your path:

```shell
sudo ln -s /path/to/req /usr/local/bin/req
```

which means you can use it system-wide like any other command:

```shell
req <filename> / <url>
```

## Usage

### The PHP Class.

The class simply wraps PHP's cURL library, but makes it much easier to use, for example, send a GET request like so:

```php
$req = new Req("http://mysite.com");
$response = $req->get();
```

Or do it in one line like:

```php
$response = Req::create("http://mysite.com")->get();
```

#### Setting headers

Pass headers as an associative array like so:

```php
$req = new Req("http://mysite.com");

$headers = array(
	'Content-type' => 'text/html',
	'X-Custom-Header' => 'Value',
);

$response = $req->headers($headers)->get();
```

#### Setting POST Data

The `post()` method in `Req` will accept a `string`, or `array` of POST data as it's first argument, string values will not be altered, so you can pass custom data like XML or JSON straight in there, but arrays will be serialized as POST data for you.

An example simply POST request:

```php
$req = new Req('http://mysite.com');

$postData = array('foo' => 'bar', 'woo' => 'sa');

// Will serialize $postData into foo=bar&woo=sa
$req->post($postData);
```

You can also pass a string of JSON, or XML data:

```php
$req = Req::create('http://mysite.com')->post('<xml><item><title>Item1</title></item></xml>');
```

Or even a file (which is essentially what the `req` command line tool does):

```php
$filepath = '/path/to/my/file';
$contents = file_get_contents($filepath);
$req = Req::create('http://mysite.com')->post($contents);
```

### Command Line

Create a `requestfile`, which is just simple, valid JSON document, that includes all information for the request. These documents must be valid JSON, and can't include comments. The only parameter that is required is the `url` parameter:

```javascript
{
	"url":"http://example.com",
	"method":"get/post", // Currently only supports get & post.
	"headers": {
		"Content-type" : "application/json",
		"Accept" : "application/json",
		"X-Custom-Header" : "Custom Value"
	},
	"data" : {
		"foo":"bar",
		"bar":"foo"
	}
}
```

Then to send a request, just do:

```shell
./req my_requestfile.json
```
And you can pipe the output to a file.

```shell
./req my_requestfile.json > my_output.txt
```

####Passing data using a second filename.

`req` accepts a second argument that will provide the POST data as a string, this will supersede any data supplied in the `data: {}` part of your requestfile.

```shell
./req my_requestfile.json my_data.xml
```

## TODO

* Error handling & exceptions
* Include examples
* Intelligent data rendering, eg: `Content-type: application/json` will send data JSON encoded, and `Accept: application/json` will decode returned JSON etc etc.
