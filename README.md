# Req



Pronounced 'Wreck'. Req has two components, a PHP class usable on it's own to make HTTP requests, and a command-line binary that is used alongisde a JSON document to specify request details, and can also take a second filename argument that will be used as the request body.

## Installation.

If you're wanting to use the `Req` class by itself, simply do the following:

```php
include 'src/req.php';
```

If you're wanting to also use the `req` binary, it has a view dependencies that are managed with [Composer](https://github.com/composer/composer), in order to use it, you must install the composer executable by typing:

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

## Usage

Create a `requestfile`, which is just simple, valid JSON document, that includes all information for the request. These documents must be valid JSON, and can't include comments. The only parameter that is required is the `url` parameter:

```json
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

## TODO

* Include Examples
* Nested POST data handling.
* Use another file to supply the `data` for the request for XML and larger JSON/YML/HTML requests.
* Intelligent data rendering, eg: `Content-type: application/json` will send data JSON encoded, and `Accept: application/json` will decode returned JSON etc etc.