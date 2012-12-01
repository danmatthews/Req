<?php

namespace Req;

/**
 * Req is a wrapper class for cURL, to make working with, and making requests easier.
 */
class Req
{

    /**
     * Auth types as constants
     */
    const REQ_AUTH_BASIC = 1;

    /**
     * Stores the request options.
     * @var array
     */
    public $opts;

    /**
     * Only basic auth supported for now.
     * @var string
     */
    protected $authtype;

    /**
     * Credentials for auth.
     * @var array
     */
    protected $credentials = array();

    public function __construct($url = null)
    {
        $this->opts['url'] = $url ? $url : null;
    }

    public function setTimeout($timeout = 30)
    {
        $this->opts['timeout'] = $timeout;
    }

    public function basicAuth($username, $password)
    {
        $this->authtype = self::REQ_AUTH_BASIC;
        $this->credentials = array(
            'username' => $username,
            'password' => $password,
        );

        return $this;
    }

    public static function create($url = null)
    {
        return new static($url);
    }

    public function url($url)
    {
        $this->opts['url'] = $url;

        return $this;
    }

    public function get($params = null)
    {
        return $this->make('GET', $params);
    }

    public function post($params = null)
    {
        return $this->make('POST', $params);
    }

    public function headers($headers)
    {
        $this->opts['headers'] = $headers;

        return $this;
    }

    public function head()
    {
        return $this->make('HEAD');
    }

    /**
     * Send the request.
     */
    public function make($type = 'GET', $params = null)
    {

        $errors = $this->validate();

        if (empty($errors)) {

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($curl, CURLOPT_HEADER, 1);

            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

            if (isset($this->opts['timeout'])) {
                curl_setopt($curl, CURLOPT_TIMEOUT, $this->opts['timeout']);
            }

            if ($this->authtype == self::REQ_AUTH_BASIC) {
                curl_setopt(
                    $curl,
                    CURLOPT_USERPWD,
                    $this->credentials['username'].":" . $this->credentials['password']
                );
            }

            if (isset($this->opts['headers']) && count($this->opts['headers']) > 0) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $this->buildHeaders());
            }

            if (strtolower($type) == 'post') {

                $data = is_string($params) ? $params : $this->serializeData($params);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            } elseif (strtolower($type) == 'head') {

                curl_setopt($curl, CURLOPT_NOBODY, 1);

            } elseif (strtolower($type) == 'get') {

                $data = is_string($params) ? $params : $this->serializeData($params);
                $this->opts['url'] = $this->opts['url'].'?'.$data;
                curl_setopt($curl, CURLOPT_HTTPGET, true);

            } else {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($type));
            }

            curl_setopt($curl, CURLOPT_URL, $this->opts['url']);

            $body = curl_exec($curl);

            $info = curl_getinfo($curl);

            curl_close($curl);

            return new ReqResponse($body, $info);

        } else {
            throw new ReqException($errors);
        }

    }

    protected function buildHeaders()
    {

        $headers = array();

        foreach ($this->opts['headers'] as $key => $value) {

            $headers[] = "{$key}: {$value}";
        }

        return $headers;

    }

    protected function serializeData($params, $base = null)
    {

        $formData = array();

        foreach ($params as $key => $value) {

            if (is_array($value)) {
                $formData[] = $this->serializeData($value, $key);
            } else {
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
}
