<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 2/24/18
 * Time: 10:02 PM
 */

namespace Plaid\IdentityResponse\Identity;
use Plaid\Plaid;


/**
 * Class Phone
 *
 * @package Plaid\IdentityResponse\Identity
 */
class Phone extends Plaid {

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
     * Phone constructor.
     *
     * @param \stdClass $raw_response
     */
    public function __construct(\stdClass $raw_response) {
        parent::__construct($raw_response);
        $this->data = $this->getRawResponse()->data;
        $this->primary = $this->getRawResponse()->primary;
        $this->type = $this->getRawResponse()->type;
    }

    /**
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isPrimary() {
        return $this->primary;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }
}