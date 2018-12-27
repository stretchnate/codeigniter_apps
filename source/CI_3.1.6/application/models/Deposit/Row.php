<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/14/18
 * Time: 8:42 PM
 */
namespace Deposit;
use Deposit\Row\Fields;

/**
 * Class Deposit - adapter to new_funds table
 */
class Row extends \CI_Model {

    /**
     * @var \Deposit\Row\Fields
     */
    private $fields;

    /**
     * Deposit constructor.
     *
     * @param $fields
     * @throws \Exception
     */
    public function __construct($fields = null) {
        parent::__construct();

        $this->fields = new Fields();

        if($fields) {
            $this->load($fields);
        }
    }

    /**
     * @param \Deposit\Row\Fields $values
     * @throws \Exception
     */
    public function load(Fields $values) {
        $query = $this->db->get_where(\Deposit::TABLE, $values->toArray());

        if(!$query) {
            $error = $this->db->error();
            throw new \Exception($error['message'], EXCEPTION_CODE_ERROR);
        }

        if($query->num_rows() > 0) {
            $this->fields->setId((int)$query->row()->id)
                ->setOwnerId((int)$query->row()->ownerId)
                ->setAccountId((int)$query->row()->account_id)
                ->setDate(new \DateTime($query->row()->date))
                ->setGross($query->row()->gross)
                ->setSource($query->row()->source)
                ->setNet($query->row()->net)
                ->setRemaining($query->row()->remaining)
                ->setManualDistribution($query->row()->manual_distribution);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function save() {
        if($this->rowExists()) {
            $result = $this->update();
        } else {
            $result = $this->insert();
        }

        if(!$result) {
            $error = $this->db->error();
            throw new \Exception($error['message'], EXCEPTION_CODE_ERROR);
        }

        return true;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function insert() {
        $set = $this->fields->toArray();

        $result = $this->db->insert(\Deposit::TABLE, $set);

        if($result === true) {
            $this->fields->setId($this->db->insert_id());
        }

        return $result;
    }

    /**
     * @return mixed
     */
    private function update() {
        $set = $this->fields->toArray();
        unset($set['id']);

        $this->db->where(['id' => $this->fields->getId()]);

        return $this->db->update(\Deposit::TABLE, $set);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function rowExists() {
        $result = false;
        if($this->fields->getId()) {
            $query = $this->db->get_where(\Deposit::TABLE, ['id' => $this->fields->getId()]);

            if(!$query) {
                $error = $this->db->error();
                throw new \Exception($error['message'], EXCEPTION_CODE_ERROR);
            }

            if($query->num_rows() > 0) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @return \Deposit\Row\Fields
     */
    public function getFields() {
        return $this->fields;
    }

}