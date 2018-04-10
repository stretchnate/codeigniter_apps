<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/14/18
 * Time: 8:57 PM
 */

namespace API\Vendor;


class Values extends \Validation implements \ValueInterface {

	/**
	 * @var int
	 */
    private $id;

	/**
	 * @var string
	 */
    private $name;

	/**
	 * @var string
	 */
    private $username;

	/**
	 * @var string
	 */
    private $password;

	/**
	 * @var string
	 */
    private $credentials;

	/**
	 * @var \DateTime
	 */
    private $added_date;

	/**
	 * @var \DateTime
	 */
    private $disabled_date;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function toArray() {
        $where = [];
        if($this->getId()) {
            $where['id'] = $this->getId();
        }
        if($this->getName()) {
            $where['name'] = $this->getName();
        }
        if($this->getUsername()) {
            $where['username'] = $this->getUsername();
        }
        if($this->getPassword()) {
            $where['password'] = $this->getPassword();
        }
        if($this->getCredentials()) {
            $where['credentials'] = $this->getCredentials();
        }
        if($this->getAddedDate()) {
            $where['added_date'] = $this->getAddedDate()->format('Y-m-d');
        }
        if($this->getDisabledDate()) {
            $where['disabled_date'] = $this->getDisabledDate()->format('Y-m-d');
        }

        return $where;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Values
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Values
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return Values
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return Values
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCredentials() {
        return $this->credentials;
    }

    /**
     * @param mixed $credentials
     * @return Values
     */
    public function setCredentials($credentials) {
        $this->credentials = $credentials;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAddedDate() {
        return isset($this->added_date) ? clone $this->added_date : null;
    }

    /**
     * @param \DateTime $added_date
     * @return Values
     */
    public function setAddedDate(\DateTime $added_date) {
        $this->added_date = $added_date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDisabledDate() {
        return isset($this->disabled_date) ? clone $this->disabled_date : null;
    }

    /**
     * @param \DateTime $disabled_date
     * @return Values
     */
    public function setDisabledDate(\DateTime $disabled_date) {
        $this->disabled_date = $disabled_date;

        return $this;
    }
}