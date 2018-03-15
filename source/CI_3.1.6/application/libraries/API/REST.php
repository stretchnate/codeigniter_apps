<?php

namespace API;

/**
 * Class REST
 *
 * @package API
 */
abstract class REST {

    /**
     * @var resource
     */
    protected $ch;

    /**
     * @var APIVendor
     */
    protected $vendor_data;

    /**
     * @var bool
     */
    public $debug = false;

    /**
     * @var string
     */
    public $debug_prefix = 'CURL_DEBG_';

    /**
     * @return mixed
     */
    abstract protected function start();

    /**
     * @param $response
     * @return mixed
     */
    abstract protected function formatResponse($response);

    /**
     * REST constructor.
     *
     * @param $vendor_name
     */
    public function __construct($vendor_name) {
        $this->vendor_data = new APIVendor();//new model and db table
    }

    /**
     * set common curl opts
     *
     * @param int $timeout
     */
    protected function setCommonOpts($timeout = 90) {
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * set curl options to allow for easier debugging of api connection issues
     * this should only be used when debugging
     */
    protected function setDebugOpts() {
        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLINFO_HEADER_OUT, true);
    }

    /**
     * @todo - build this method out
     * @param string $uri
     * @param resource $file
     * @return mixed
     */
    protected function executeCurlPUT($uri, $file) {

    }

    /**
     * execute a GET curl call
     *
     * @param string $uri
     * @return mixed
     * @throws Exception
     */
    protected function executeCurlGET($uri) {
        $this->startCURL();

        $url = $this->vendor_data->url . $uri;

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_FORBID_REUSE, true);

        return $this->sendCurl();
    }

    /**
     * send a curl post request
     *
     * @param strin g$uri
     * @param mixed $postfields
     * @return mixed mixed
     * @throws Exception
     */
    protected function executeCurlPOST($uri, $postfields) {
        $this->startCURL();

        $url = $this->vendor_data->url . $uri;

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postfields);

        return $this->sendCurl(600);
    }

    /**
     * send a curl request
     *
     * @return mixed
     * @throws Exception
     */
    protected function sendCurl($timeout = 90) {
        $this->setCommonOpts($timeout);

        if($this->debug === true) {
            $this->setDebugOpts();
        }

        $curl_result = curl_exec($this->ch);

        $errno = curl_errno($this->ch);
        $error = curl_error($this->ch);

        $body = $curl_result;
        if($this->debug === true) {
            $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
            $header = substr($curl_result, 0, $header_size);
            $body = substr($curl_result, $header_size);

            $debug_info = curl_getinfo($this->ch);
            if(class_exists('Business_Log', false)) {
                Business_Log::logDebug($debug_info, $this->debug_prefix);
            }
        }

        curl_close($this->ch);

        if($errno) {
            throw new Exception("Unable to process request [$errno: $error]");
        }

        if($this->debug === true && !preg_match('/200 OK/', $header)) {
            throw new Exception("Unable to process request [".$header."]");
        }

        return $body;
    }
}
