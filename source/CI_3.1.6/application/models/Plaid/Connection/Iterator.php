<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/14/18
 * Time: 7:46 AM
 */

namespace Plaid\Connection;


use Plaid\Connection;

/**
 * Class Iterator
 *
 * @package Plaid\Connection
 */
class Iterator extends \IteratorBase {

    /**
     * @var Connection[]
     */
    protected $items;

    private $item_id;

    /**
     * Iterator constructor.
     * @param $item_id
     * @param array $where
     * @throws \Exception
     */
    public function __construct($item_id, $where = []) {
        parent::__construct();
        $this->rewind();

        $this->item_id = $item_id;
        if(!empty($where)) {
            $this->load($where);
        }
    }

    /**
     * @param array $where
     * @throws \Exception
     */
    public function load($where = []) {
        $where['item_id'] = $this->item_id;

        $query = $this->db->get_where(Connection::TABLE, $where);

        if(!$query) {
            $error = $this->db->error();
            throw new \Exception($error['message'], EXCEPTION_CODE_ERROR);
        }

        foreach($query->result() as $row) {
            $connection = new Connection();
            $connection->getValues()
                ->setItemId($row->item_id)
                ->setPlaidAccountId($row->plaid_account_id)
                ->setAccountId((int)$row->account_id)
                ->setAccessToken($row->access_token)
                ->setTransactionsReady($row->transactions_ready)
                ->setDtAdded(new \DateTime($row->dt_added));

            $this->items[] = $connection;
        }
    }

    /**
     * @return mixed|Connection
     */
    public function current() {
        return $this->items[$this->key];
    }
}