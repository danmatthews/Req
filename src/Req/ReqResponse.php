<?php

namespace Req;

class ReqResponse
{
    /**
     * An array of headers in name => value format
     * @var array
     */
    public $headers;

    /**
     * The returned response body
     * @var string
     */
    public $body;

    /**
     * Curl's info array
     * @var array
     */
    public $info;

    /**
     * HTTP1.0 Status Code
     * @var int
     */
    public $status_code;

    /**
     * Total request time
     * @var float
     */
    public $time;

    public function __construct($body, $info)
    {

        list($headers, $body) = explode("\r\n\r\n", $body);

        $headers = explode("\n", $headers);

        $headerList = array();

        foreach ($headers as $header) {
            if (stristr($header, ':')) {
                list($key, $value) = explode(":", $header);
                $headerList[$key] = trim($value);
            }
        }

        $this->body = $body;

        $this->headers = $headerList;

        $this->info = $info;

        $this->status_code = (int) $info['http_code'];

        $this->time = (float) number_format($info['total_time'], 2);
    }

    /**
     * If you try to echo this object, just echo the body string.
     * @return string The response body.
     */
    public function __toString()
    {
        return $this->body;
    }
}
