<?php

/**
 * Class Deposit
 */
class Deposit extends IteratorBase {

    const TABLE = 'new_funds';

    /**
     * Deposit constructor.
     *
     * @param null $fields
     * @throws Exception
     */
    public function __construct($fields = null) {
        parent::__construct();
        if($fields) {
            $this->load($fields);
        }
    }

    /**
     * @param \Deposit\Row\Fields $fields
     * @throws Exception
     */
    public function load(\Deposit\Row\Fields $fields) {
        if($fields) {
            $this->db->where($fields->whereString());
        }

        $query = $this->db->get(self::TABLE);

        if(!$query) {
            throw new Exception($this->db->error()['message'], EXCEPTION_CODE_ERROR);
        }

        foreach($query->result() as $result) {
            $row = new \Deposit\Row();
            $row->getFields()->setId($result->id)
                ->setAccountId($result->account_id)
                ->setOwnerId($result->ownerId)
                ->setDate(new \DateTime($result->date))
                ->setGross($result->gross)
                ->setNet($result->net)
                ->setSource($result->source)
                ->setRemaining($result->remaining);

            $this->items[] = $row;
        }
    }

    /**
     * @return \Deposit\Row|null
     */
	public function current() {
        return $this->valid() ? $this->items[$this->key] : null;
    }
}
