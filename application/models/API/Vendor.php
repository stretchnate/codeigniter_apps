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
		$this->values = new Values();
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
        $query = $this->db->get_where(self::TABLE, $values->toArray());

        if(!$query) {
            $error = $this->db->error();
            throw new \Exception("Error: ". $error['message'], EXCEPTION_CODE_ERROR);
        }

        if($query->row()) {
            $this->values->setId($query->row()->id);
            $this->values->setName($query->row()->name);
            $this->values->setUsername($query->row()->username);
            $this->values->setPassword($query->row()->password);
            $this->values->setCredentials($query->row()->credentials);
            $this->values->setUrl($query->row()->url);
            $this->values->setAddedDate(new \DateTime($query->row()->added_date));

            if($query->row()->disabled_date) {
                $this->values->setDisabledDate(new \DateTime($query->row()->disabled_date));
            }
        }

        return $this;
    }

    /**
     * @throws \Exception
	 * @return bool
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
		
		return $result;
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
		$set = $this->set();
		if($this->getValues()->getId()) {
			$set->id = $this->getValues()->getId();
		}

        return $this->db->insert(self::TABLE, $set);
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

            $result = $query->num_rows() > 0;
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
        $set->added_date = $this->getValues()->getAddedDate()->format('Y-m-d H:i:s');
		if($this->getValues()->getDisabledDate()) {
			$set->disabled_date =$this->getValues()->getDisabledDate()->format('Y-m-d H:i:s');
		}

        return $set;
    }
    /**
     * @return Values
     */
    public function getValues() {
        return $this->values;
    }
}