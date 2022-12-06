<?php

namespace API;
use API\Vendor\Values;

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
     * @var Vendor
     */
    protected $vendor_data;

    /**
     * @var bool
     */
    public $debug = false;

    /**
     * @return mixed
     */
    abstract protected function start();

    /**
     * @param $response
     * @return mixed
     */
    abstract protected function parseResponse($response);

    /**
     * REST constructor.
     *
     * @param string $vendor_name
     * @throws \Exception
     */
    public function __construct($vendor_name) {
        $values = new Values();
        $values->setName($vendor_name);
        $this->vendor_data = new Vendor($values);

		if(!$this->vendor_data->getValues()->getId()) {
			throw new \Exception("Vendor $vendor_name does not exist.");
		}
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
    protected function put($uri, $file) {

    }

    /**
     * execute a GET curl call
     *
     * @param string $uri
     * @return mixed
     * @throws \Exception
     */
    protected function get($uri) {
        $this->start();

        $url = $this->vendor_data->getValues()->getUrl() . $uri;

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_FORBID_REUSE, true);

        return $this->send();
    }

    /**
     * send a curl post request
     *
     * @param strin g$uri
     * @param mixed $postfields
     * @return mixed mixed
     * @throws \Exception
     */
    protected function post($uri, $postfields) {
        $this->start();

        $url = $this->vendor_data->getValues()->getUrl() . $uri;

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postfields);

        return $this->send(600);
    }

    /**
     * send a curl request
     *
     * @param int $timeout
     * @return mixed
     * @throws \Exception
     */
    protected function send($timeout = 90) {
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
            log_message('error', $debug_info);
        }

        curl_close($this->ch);

        if($errno) {
            throw new \Exception("Unable to process request [$errno: $error]");
        }

        if($this->debug === true && !preg_match('/200 OK/', $header)) {
            throw new \Exception("Unable to process request [".$header."]");
        }

        return $body;
    }
}
