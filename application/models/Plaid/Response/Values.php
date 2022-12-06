<?php

namespace Plaid\Response;

class Values extends \Validation implements \FieldsInterface {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $request_id;

    /**
     * @var string
     */
    private $product;

    /**
     * @var string
     */
    private $data;

    /**
     * @var \DateTime
     */
    private $added;

    /**
     * convert properties to array
     *
     * @return array
     */
    public function toArray() {
        $result = [];
        if($this->id) {
            $result['id'] = $this->getId();
        }
        if($this->request_id) {
            $result['request_id'] = $this->getRequestId();
        }
        if($this->product) {
            $result['product'] = $this->getProduct();
        }
        if($this->data) {
            $result['data'] = $this->getData();
        }
        if($this->added) {
            $result['added'] = $this->getAdded()->format('Y-m-d H:i:s');
        }

        return $result;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value) {
        if($this->id) {
            throw new Exception('Cannot overwrite primary key');
        }

        $this->id = $this->simple_validation->isInt($value);

        return $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRequestId($value) {
        $this->request_id = $this->simple_validation->isString($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestId() {
        return $this->request_id;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setProduct($value) {
        $this->product = $this->simple_validation->isString($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setData($value) {
        $this->data = $this->simple_validation->isString($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param \DateTime $value
     * @return $this
     */
    public function setAdded(\DateTime $value) {
        $this->added = $value;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAdded() {
        return $this->added ? clone $this->added : null;
    }
}