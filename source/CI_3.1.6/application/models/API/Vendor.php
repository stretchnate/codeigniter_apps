<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 3/13/18
 * Time: 10:03 PM
 */

namespace API;

use API\Vendor\Values;

/**
 * Class Vendor
 *
 * @package API
 */
class Vendor extends \CI_Model {

    /**
     *
     */
    const TABLE = 'api_vendor';

    /**
     * @var Values
     */
    private $values;

    /**
     * Vendor constructor.
     *
     * @param Values|null $values
     * @throws \Exception
     */
    public function __construct(Values $values = null) {
        if($values) {
            $this->load($values);
        }
    }

    /**
     * @param Values $values
     * @return $this
     * @throws \Exception
     */
    public function load(Values $values) {
        $this->values = $values;

        $query = $this->db->get_where(self::TABLE, $this->buildWhere());

        if(!$query) {
            $error = $this->db->error();
            throw new \Exception("Error: ". $error['message']);
        }

        $this->values->setId($query->row()->id);
        $this->values->setName($query->row()->name);
        $this->values->setUsername($query->row()->username);
        $this->values->setPassword($query->row()->password);
        $this->values->setCredentials($query->row()->credentials);
        $this->values->setAddedDate(new DateTime($query->row()->added_date));
        if($query->row()->disabled_date) {
            $this->values->setDisabledDate(new DateTime($query->row()->disabled_date));
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function save() {
        if($this->rowExists()) {
            $result = $this->update();
        } else {
            $result = $this->insert();
        }

        if($result === false) {
            $error = $this->db->error();
            throw new \Exception('Unable to save '.self::TABLE.': '.$error['message']);
        }
    }

    /**
     * @return mixed
     */
    private function update() {
        $this->db->where(['id' => $this->getValues()->getId()]);
        return $this->db->update(self::TABLE, $this->set());
    }

    /**
     * @return mixed
     */
    private function insert() {
        return $this->db->insert(self::TABLE, $this->set());
    }

    /**
     * @return \stdClass
     */
    private function buildWhere() {
        $obj = new \stdClass();
        if($this->values->getName()) {
            $obj->name = $this->values->getName();
        }
        if($this->values->getUsername()) {
            $obj->username = $this->values->getUsername();
        }
        if($this->values->getPassword()) {
            $obj->password = $this->values->getPassword();
        }
        if($this->values->getCredentials()) {
            $obj->credentials = $this->values->getCredentials();
        }
        if($this->values->getAddedDate()) {
            $obj->added_date = $this->values->getAddedDate()->format('Y-m-d');
        }
        if($this->values->getDisabledDate()) {
            $obj->disabled_date = $this->values->getDisabledDate()->format('Y-m-d');
        }

        return $obj;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function rowExists() {
        $result = false;
        if($this->getValues()->getId()) {
            $query = $this->db->get_where(self::TABLE, ['id' => $this->getValues()->getId()]);
            if(!$query) {
                $error = $this->db->error();
                throw new \Exception('Unable to load user row: '.$error['message']);
            }

            $result = $query->num_rows > 0;
        }

        return $result;
    }

    /**
     * @return \stdClass
     */
    private function set() {
        $set = new \stdClass();
        $set->name = $this->getValues()->getName();
        $set->username = $this->getValues()->getUsername();
        $set->password = $this->getValues()->getPassword();
        $set->credentials = $this->getValues()->getCredentials();
        $set->added_date = $this->getValues()->getCredentials();
        $set->disabled_date = $this->getValues()->getDisabledDate();

        return $set;
    }
    /**
     * @return Values
     */
    public function getValues() {
        return $this->values;
    }

    /**
     * @param Values $values
     * @return Vendor
     */
    public function setValues($values) {
        $this->values = $values;

        return $this;
    }
}