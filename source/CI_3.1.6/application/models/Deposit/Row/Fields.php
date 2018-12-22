<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/14/18
 * Time: 8:45 PM
 */

namespace Deposit\Row;


/**
 * Class Values
 *
 * @package Deposit
 */
class Fields extends \Validation implements \FieldsInterface {

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
     * @var \DateTime
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

    /**
     * @var float
     */
    private $remaining;

    /**
     * @var bool
     */
    private $manual_distribution;

    private $operators;

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
            $result['date'] = $this->date->format('Y-m-d');
        }
        if($this->source) {
            $result['source'] = $this->source;
        }
        if(isset($this->gross)) {
            $result['gross'] = $this->gross;
        }
        if(isset($this->net)) {
            $result['net'] = $this->net;
        }
        if(isset($this->remaining)) {
            $result['remaining'] = $this->remaining;
        }
        if(isset($this->manual_distribution)) {
            $result['manual_distribution'] = $this->manual_distribution;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function whereString() {
        $where = [];

        foreach($this->toArray() as $field => $value) {
            if(array_key_exists($field, $this->operators)) {
                $where[] = operator($this->operators[$field], $field, $value);
            } else {
                $where[] = operator('=', $field, $value);
            }
        }

        return '(' . implode(') AND (', $where) . ')';
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Fields
     * @throws \Exception
     */
    public function setId($id) {
        if(isset($this->id)) {
            throw new \Exception("Overwriting id is not allowed", EXCEPTION_CODE_ERROR);
        }
        $this->id = $this->simple_validation->isNumeric($id);

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
     * @return Fields
     * @throws \Exception
     */
    public function setOwnerId($owner_id) {
        if(isset($this->owner_id)) {
            throw new \Exception("Overwriting owner id is not allowed", EXCEPTION_CODE_ERROR);
        }
        $this->owner_id = $this->simple_validation->isNumeric($owner_id);

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
     * @return Fields
     */
    public function setAccountId($account_id) {
        $this->account_id = $this->simple_validation->isNumeric($account_id);

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return clone $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Fields
     */
    public function setDate(\DateTime $date) {
        $this->date = $date;

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
     * @return Fields
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
     * @return Fields
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
     * @return Fields
     */
    public function setNet($net) {
        $this->net = $this->simple_validation->isNumeric($net);

        return $this;
    }

    /**
     * @return float
     */
    public function getRemaining() {
        return $this->remaining;
    }

    /**
     * @param float $remaining
     * @return Fields
     */
    public function setRemaining($remaining) {
        $this->remaining = $this->simple_validation->isNumeric($remaining);

        return $this;
    }

    /**
     * @return bool
     */
    public function isManualDistribution() {
        return $this->manual_distribution;
    }

    /**
     * @param bool $manual_distribution
     * @return Fields
     */
    public function setManualDistribution($manual_distribution) {
        $this->manual_distribution = (bool) $manual_distribution;
        return $this;
    }

    /**
     * set operator for where string
     * @param $field
     * @param $operator
     */
    public function setOperator($field, $operator) {
        $this->operators[$field] = $operator;
    }
}