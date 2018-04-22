<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/14/18
 * Time: 8:45 PM
 */

namespace Deposit;


/**
 * Class Values
 *
 * @package Deposit
 */
class Values extends \Validation implements \ValueInterface {

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $owner_id;

    /**
     * @var int
     */
    private $account_id;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $source;

    /**
     * @var float
     */
    private $gross;

    /**
     * @var float
     */
    private $net;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function toArray() {
        $result = [];
        if($this->id) {
            $result['id'] = $this->id;
        }
        if($this->owner_id) {
            $result['ownerId'] = $this->owner_id;
        }
        if($this->account_id) {
            $result['account_id'] = $this->account_id;
        }
        if($this->date) {
            $result['date'] = $this->date;
        }
        if($this->source) {
            $result['source'] = $this->source;
        }
        if($this->gross) {
            $result['gross'] = $this->gross;
        }
        if($this->net) {
            $result['net'] = $this->net;
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Values
     * @throws \Exception
     */
    public function setId($id) {
        if(isset($this->id)) {
            throw new \Exception("Overwriting id is not allowed", EXCEPTION_CODE_ERROR);
        }
        $this->id = $this->simple_validation->isInt($id);

        return $this;
    }

    /**
     * @return int
     */
    public function getOwnerId() {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id
     * @return Values
     * @throws \Exception
     */
    public function setOwnerId($owner_id) {
        if(isset($this->owner_id)) {
            throw new \Exception("Overwriting owner id is not allowed", EXCEPTION_CODE_ERROR);
        }
        $this->owner_id = $this->simple_validation->isInt($owner_id);

        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * @param int $account_id
     * @return Values
     */
    public function setAccountId($account_id) {
        $this->account_id = $this->simple_validation->isInt($account_id);

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return new \DateTime($this->date);
    }

    /**
     * @param string $date
     * @return Values
     */
    public function setDate($date) {
        $this->date = $this->simple_validation->isValidDate($date);

        return $this;
    }

    /**
     * @return string
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * @param string $source
     * @return Values
     */
    public function setSource($source) {
        $this->source = $source;

        return $this;
    }

    /**
     * @return float
     */
    public function getGross() {
        return $this->gross;
    }

    /**
     * @param float $gross
     * @return Values
     */
    public function setGross($gross) {
        $this->gross = $this->simple_validation->isNumeric($gross);

        return $this;
    }

    /**
     * @return float
     */
    public function getNet() {
        return $this->net;
    }

    /**
     * @param float $net
     * @return Values
     */
    public function setNet($net) {
        $this->net = $this->simple_validation->isNumeric($net);

        return $this;
    }
}