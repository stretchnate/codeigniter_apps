<?php

/**
 * Class Budget_DataModel_AccountDM
 */
class Budget_DataModel_AccountDM extends N8_Model {

    /**
     *
     */
    const TABLE = 'accounts';

    /**
     * @var int
     */
    private $account_id;

    /**
     * @var string
     */
    private $account_name;

    /**
     * @var string
     */
    private $account_type;

    /**
     * @var float
     */
    private $account_amount;

    /**
     * @var int
     */
    private $owner_id;

    /**
     * @var int
     */
    private $payschedule_code;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var array
     */
    private $categories = array();

    /**
     * Budget_DataModel_AccountDM constructor.
     *
     * @param null $account_id
     * @param null $owner_id
     * @throws Exception
     */
    public function __construct($account_id = null, $owner_id = null){
		parent::__construct();

		if($account_id && $owner_id) {
			$this->loadAccount($account_id, $owner_id);
		}
	}

	/**
	 * @param type $account_id
	 * @throws Exception
	 */
	public function loadAccount($account_id, $owner_id) {
        $this->loadBy(['account_id' => $account_id, 'owner_id' => $owner_id]);
	}

    /**
     * @param array $where
     * @throws Exception
     */
	public function loadBy($where = []) {
        if(empty($where['owner_id'])) {
            throw new Exception("Owner not authorized to view this category.", EXCEPTION_CODE_ERROR);
        }

        $query = $this->db->get_where("accounts", $where);

        if(!$query || $query->num_rows() < 1) {
            throw new Exception("Invalid Account for owner [".$where['owner_id']."] (".__METHOD__.":".__LINE__.")", EXCEPTION_CODE_ERROR);
        }

        $this->load($query->row());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function saveAccount() {
		if($this->account_id > 0) {
			$result = $this->updateAccount();
			if($result === false) {
				$this->setError("There was a problem updating the account ".$this->account_name);
			}
		} else {
			if(!$this->insertAccount()) {
			    $error = $this->db->error();
			    throw new \Exception($error['message'], EXCEPTION_CODE_ERROR);
            }
            $result = $this->account_id = (int)$this->db->insert_id();
		}

        return $result;
	}

    /**
     * @param $row
     */
	private function load($row) {
        $this->account_id = (int)$row->account_id;
        $this->account_name = $row->account_name;
        $this->account_type = $row->account_type;
        $this->account_amount = $row->account_amount;
        $this->owner_id = (int)$row->owner_id;
        $this->payschedule_code = $row->payschedule_code;
        $this->active = $row->active;
    }

	/**
	 * @return boolean
	 */
	private function updateAccount() {
        $result = false;
		$sets   = array();

		$sets["account_name"]     = $this->account_name;
        $sets["account_type"]     = $this->account_type;
        $sets["account_amount"]   = $this->dbNumberFormat($this->account_amount);
		$sets["payschedule_code"] = $this->payschedule_code;
		$sets["active"]           = $this->active;

		$this->db->where("account_id", $this->account_id);
		if($this->db->update("accounts", $sets)) {
			$result = true;
		}

        return $result;
	}

    /**
     * @return mixed
     */
    private function insertAccount(){
		$values = array();

		$values["account_name"]     = $this->account_name;
        $values["account_type"]     = $this->account_type;
        $values["account_amount"]   = $this->dbNumberFormat($this->account_amount);
		$values["owner_id"]         = ($this->owner_id) ? $this->owner_id : $this->session->userdata("user_id");
		$values["payschedule_code"] = $this->payschedule_code;
		$values["active"]           = ($this->active) ? $this->active : 1;

		return $this->db->insert("accounts", $values);
	}

	/**
	 * load categories in account
	 */
	public function loadCategories() {
	    $this->categories = [];
        $category_ids = $this->getCategoryIds();

        if (is_array($category_ids)) {
            foreach ($category_ids as $category_id) {
                $this->categories[] = new Budget_DataModel_CategoryDM($category_id->bookId, $this->owner_id);
            }
        }
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getCategoryIds() {
		if(!$this->owner_id) {
			throw new Exception('Unable to load categories without owner id');
		}
		$this->db->select('bookId');
		$this->db->order_by('due_day');
		$this->db->order_by('priority');
		$query = $this->db->get_where('booksummary',array('account_id' => $this->account_id, 'ownerId' => $this->owner_id, 'active' => 1));

		return $query->result();
	}

	/**
	 * returns the categories in a multi dimensional array ordered by due date
	 * example
	 * <code>
	 *      array(
	 *          [15] => array(here is where categoryDMs go if they are due within 15 days)
	 * 	        [30] => array(here is where categoryDMs go if they are due within 30 days)
	 *          [60] => array(here is where categoryDMs go if they are due within 60 days)
	 *          [90] => array(here is where categoryDMs go if they are due within 90 days)
	 *          [120] => array(here is where categoryDMs go if they are due within 120 days)
	 *          ...on up to one year away
	 *          )
	 * </code>
	 * @return array
	 * @since 05.01.2013
	 */
	public function orderCategoriesByDueFirst($from_date) {
        $this->loadCategories();

		$pay_frequency_array      = $this->buildPayFrequencyArray($this->getPayFrequency());
		$pay_frequency_array_keys = array_keys($pay_frequency_array);

		//order the categoryDM's by due first
		foreach($this->categories as $category_dm) {
			$days_until_due = $category_dm->getDaysUntilDue($from_date);

			foreach($pay_frequency_array_keys as $key) {
				if($key > $days_until_due) {
					$pay_frequency_array[$key][] = $category_dm;
					break;
				}
			}
		}

		$this->categories = $pay_frequency_array;
		return $this->categories;
	}

	/**
	 * builds the category array based on pay frequency
	 *
	 * @param  int    $pay_frequency
	 * @return array
	 * @since  05.01.2013
	 */
	private function buildPayFrequencyArray($pay_frequency) {
		$i             = $pay_frequency;
		$result        = array();

		while($i < 365) { //do one years worth of paychecks
			$result[$i] = array();

			$i = ($i + $pay_frequency);

		}

		return $result;
	}

	/**
	 * returns the pay frequency (in days) for the account
	 *
	 * @return int
	 * @since 05.01.2013
	 */
	public function getPayFrequency() {
		switch($this->getPayScheduleCode()) {
			case 3:
			case 24: //checks per year
				$num_days = 15;
				break;
			case 4:
			case 12: //checks per year
				$num_days = 30;
				break;
			case 2:
			case 52: //checks per year
				$num_days = 7;
				break;
			case 1:
			case 26: //checks per year
			default:
				$num_days = 14;
				break;
		}

		return $num_days;
	}

    /**
     * @return mixed
     */
    public function getAccountId() {
		return $this->account_id;
	}

    /**
     * @return mixed
     */
    public function getAccountName() {
		return $this->account_name;
	}

    /**
     * @param $account_name
     */
    public function setAccountName($account_name) {
		$this->account_name = $account_name;
	}

    /**
     * @return string
     */
    public function getAccountType() {
        return $this->account_type;
    }

    /**
     * @param string $account_type
     * @return Budget_DataModel_AccountDM
     */
    public function setAccountType($account_type) {
        $this->account_type = $account_type;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getAccountAmount() {
		return $this->account_amount;
	}

    /**
     * @param $account_amount
     */
    public function setAccountAmount($account_amount) {
		$this->account_amount = $account_amount;
	}

    /**
     * @return mixed
     */
    public function getOwnerId() {
		return $this->owner_id;
	}

    /**
     * @param $owner_id
     */
    public function setOwnerId($owner_id) {
		$this->owner_id = $owner_id;
	}

    /**
     * @return mixed
     */
    public function getPayScheduleCode() {
		return $this->payschedule_code;
	}

    /**
     * @param $payschedule_code
     */
    public function setPayScheduleCode($payschedule_code) {
		$this->payschedule_code = $payschedule_code;
	}

    /**
     * @return mixed
     */
    public function getActive() {
		return $this->active;
	}

    /**
     * @param $active
     */
    public function setActive($active) {
		$this->active = $active;
	}

    /**
     * @return Budget_DataModel_CategoryDM[]
     */
    public function getCategories() {
		return $this->categories;
	}

    /**
     * @return mixed
     */
    public function getID() {
		return $this->getAccountId();
	}
}
