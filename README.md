# REQuiem

Make HTTP requests using simple JSON template documents.

## Installation

Clone the repo, then use Composer to install the dependencies.

You can then, if you like, use it from within the folder by calling `./req my-request.json`, or you can make it available system wide by symlinking it somewhere into your path:

`sudo ln -s /path/to/req /usr/local/bin/req`

## Usage

Create a `requestfile`, which is just simple, valid JSON document, that includes all information for the request. These documents must be valid JSON, and can't include comments. The only parameter that is required is the `url` parameter:

```
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