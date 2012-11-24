[![Build Status](https://secure.travis-ci.org/danmatthews/Req.png)](http://travis-ci.org/danmatthews/Req)

# Req

Make making requests with PHP suck less.

Req has two components, a PHP class usable on it's own to make HTTP requests, and a command-line binary that is used alongside a JSON document to specify request details, and can also take a second filename argument that will be used as the request body.

Check out the docs for more detailed information and installation instructions.

# Why?

The Req PHP class is just a lovely little convenient wrapper for sending requests, but when paired with the command line utility, will allow you template HTTP requests, pipe their output, and feed in contents of files directly from any directory on your computer.

# Where?

Req is available as a [composer](http://getcomposer.org/) installable [package](https://packagist.org/packages/danmatthews/req), and will work with any PHP 5.3+ environment with php-curl installed. Req is continously tested [on Travis-CI](http://travis-ci.org/danmatthews/Req).

## Some quick examples

Send a GET request:

```php
$req = new Req("http://mysite.com");
$response = $req->get();
```

Set headers:

```php
$req = new Req("http://mysite.com");

$headers = array(
	'Content-type' => 'text/html',
	'X-Custom-Header' => 'Value',
);

$response = $req->headers($headers)->get();
```

Set post data:

```php
$req = new Req('http://mysite.com');

$postData = array('foo' => 'bar', 'woo' => 'sa');

// Will serialize $postData into foo=bar&woo=sa
$req->post($postData);
```

You can also pass a string of data:

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
	"method":"get/post",
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

Then send the templated request with the `make` command:

```shell
./req make my_requestfile.json
```

You can also provide a file as a second argument that will be substituted in as the POST data, this will be POSTed as a string:

```shell
# Either:
./req make <url> <filepath>
# Or:
./req make <request filename> <filepath>
```



Simple!
