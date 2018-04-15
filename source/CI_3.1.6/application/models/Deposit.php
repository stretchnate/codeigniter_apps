<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/14/18
 * Time: 8:42 PM
 */

/**
 * Class Deposit - adapter to new_funds table
 */
class Deposit extends CI_Model {

    const TABLE = 'new_funds';

    /**
     * @var \Deposit\Values
     */
    private $values;

    /**
     * Deposit constructor.
     *
     * @param $values
     * @throws Exception
     */
    public function __construct($values = null) {
        parent::__construct();

        $this->values = new \Deposit\Values();

        if($values) {
            $this->load($values);
        }
    }

    /**
     * @param \Deposit\Values $values
     * @throws Exception
     */
    public function load(\Deposit\Values $values) {
        $query = $this->db->get_where(self::TABLE, $values->toArray());

        if(!$query) {
            $error = $this->db->error();
            throw new Exception($error['message'], EXCEPTION_CODE_ERROR);
        }

        if($query->num_rows() > 0) {
            $this->values->setId((int)$query->row()->id)
                ->setOwnerId((int)$query->row()->ownerId)
                ->setAccountId((int)$query->row()->account_id)
                ->setDate($query->row()->date)
                ->setGross($query->row()->gross)
                ->setSource($query->row()->source)
                ->setNet($query->row()->net);
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save() {
        if($this->rowExists()) {
            $result = $this->update();
        } else {
            $result = $this->insert();
        }

        if(!$result) {
            $error = $this->db->error();
            throw new Exception($error['message'], EXCEPTION_CODE_ERROR);
        }

        return true;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function insert() {
        $set = $this->values->toArray();

        $result = $this->db->insert(self::TABLE, $set);

        if($result === true) {
            $this->values->setId($this->db->insert_id());
        }

        return $result;
    }

    /**
     * @return mixed
     */
    private function update() {
        $set = $this->values->toArray();
        unset($set['id']);

        $this->db->where(['id' => $this->values->getId()]);

        return $this->db->update(self::TABLE, $set);
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function rowExists() {
        $result = false;
        if($this->values->getId()) {
            $query = $this->db->get_where(self::TABLE, ['id' => $this->values->getId()]);

            if(!$query) {
                $error = $this->db->error();
                throw new Exception($error['message'], EXCEPTION_CODE_ERROR);
            }

            if($query->num_rows() > 0) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @return \Deposit\Values
     */
    public function getValues() {
        return $this->values;
    }

}