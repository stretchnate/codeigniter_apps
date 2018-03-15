<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/14/18
 * Time: 8:57 PM
 */

namespace API\Vendor;


class Values {

    private $id;

    private $name;

    private $username;

    private $password;

    private $credentials;

    private $added_date;

    private $disabled_date;

    public function __construct() {
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
     * @return mixed
     */
    public function getAddedDate() {
        return $this->added_date;
    }

    /**
     * @param mixed $added_date
     * @return Values
     */
    public function setAddedDate($added_date) {
        $this->added_date = $added_date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisabledDate() {
        return $this->disabled_date;
    }

    /**
     * @param mixed $disabled_date
     * @return Values
     */
    public function setDisabledDate($disabled_date) {
        $this->disabled_date = $disabled_date;

        return $this;
    }
}