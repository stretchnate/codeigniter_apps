<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 10:02 PM
 */

namespace Plaid\IdentityResponse\Identity;


/**
 * Class Email
 *
 * @package Plaid\IdentityResponse\Identity
 */
class Email {

    /**
     * @var string
     */
    private $data;

    /**
     * @var bool
     */
    private $primary;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \stdClass
     */
    private $raw_response;

    /**
     * Email constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        $this->raw_response = $raw_response;
        $this->setData($this->raw_response->data);
        $this->setPrimary($this->raw_response->primary);
        $this->setType($this->raw_response->type);
    }

    /**
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param string $data
     * @return Email
     */
    public function setData($data) {
        $this->data = $data;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrimary() {
        return $this->primary;
    }

    /**
     * @param bool $primary
     * @return Email
     */
    public function setPrimary($primary) {
        $this->primary = $primary;

        return $this;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Email
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getRawResponse() {
        return $this->raw_response;
    }

}