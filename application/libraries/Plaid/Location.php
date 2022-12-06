<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 7:08 PM
 */

namespace Plaid;


/**
 * Class Location
 *
 * @package Plaid
 */
class Location {

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state;

    /**
     * @var numeric
     */
    private $zip;

    /**
     * @var numeric
     */
    private $lat;

    /**
     * @var numeric
     */
    private $lon;

    /**
     * @var \stdClass
     */
    private $raw_response;

    /**
     * Location constructor.
     *
     * @param $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        $this->raw_response = $raw_response;
        $address = isset($this->raw_response->address) ? $this->raw_response->address : isset($this->raw_response->street) ? $this->raw_response->street : null;
        $this->setAddress($address);
        $this->setCity($this->raw_response->city);
        $this->setState($this->raw_response->state);
        $this->setZip($this->raw_response->zip);
        if(isset($this->raw_response->lat)) {
            $this->setLat($this->raw_response->lat);
        }
        if(isset($this->raw_response->lon)) {
            $this->setLon($this->raw_response->lon);
        }
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Location
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Location
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Location
     */
    public function setState($state) {
        $this->state = $state;

        return $this;
    }

    /**
     * @return numeric
     */
    public function getZip() {
        return $this->zip;
    }

    /**
     * @param numeric $zip
     * @return Location
     */
    public function setZip($zip) {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return numeric
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * @param numeric $lat
     * @return Location
     */
    public function setLat($lat) {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return numeric
     */
    public function getLon() {
        return $this->lon;
    }

    /**
     * @param numeric $lon
     * @return Location
     */
    public function setLon($lon) {
        $this->lon = $lon;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }

    /**
     * alias for setAddress
     * @param $street
     * @return Location
     */
    public function setStreet($street) {
        return $this->setAddress($street);
    }

    /**
     * alias for getAddress
     * @return string
     */
    public function getStreet() {
        return $this->getAddress();
    }
}