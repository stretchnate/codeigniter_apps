<?php
class Budget_DataModel_AccountDM extends N8_Model {

	const TABLE = 'accounts';
	
	private $account_id;
	private $account_name;
	private $account_amount;
	private $owner_id;
	private $payschedule_code;
	private $active;
	private $categories = array();

	function __construct($account_id = null, $owner_id = null){
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
		$query = $this->db->get_where("accounts", array("account_id" => $account_id, 'owner_id' => $owner_id));

		if($query->num_rows() < 1) {
			throw new Exception("Invalid Account ID [$account_id] for owner [".$owner_id."] (".__METHOD__.":".__LINE__.")");
		}

		$account_columns = $query->result();

		if( is_array($account_columns) && count($account_columns) > 0 ) {
			$account_columns = $account_columns[0];

			foreach($account_columns as $column => $value) {
				if(property_exists($this, $column)) {
					$this->$column = $value;
				}
			}
		}
	}

	public function saveAccount() {
		if($this->account_id > 0) {
			$result = $this->updateAccount();
			if($result === false) {
				$this->setError("There was a problem updating the account ".$this->account_name);
			}
		} else {
			$this->insertAccount();
			$this->insert_id = $this->db->insert_id();
			$result = $this->insert_id;
		}

        return $result;
	}

	/**
	 * @return boolean
	 */
	private function updateAccount() {
        $result = false;
		$sets   = array();

		$sets["account_name"]     = $this->account_name;
		$sets["account_amount"]   = $this->dbNumberFormat($this->account_amount);
		$sets["payschedule_code"] = $this->payschedule_code;
		$sets["active"]           = $this->active;

		$this->db->where("account_id", $this->account_id);
		if($this->db->update("accounts", $sets)) {
			$result = true;
		}

        return $result;
	}

	private function insertAccount(){
		$values = array();

		$values["account_name"]     = $this->account_name;
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
		$category_ids = $this->getCategoryIds();

		if( is_array($category_ids) ) {
			foreach($category_ids as $category_id) {
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
		if(empty($this->categories)) {
			$this->loadCategories();
		}

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

		return $pay_frequency_array;
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
		$num_paychecks = (52 / $pay_frequency);

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

	public function getAccountId() {
		return $this->account_id;
	}

	public function getAccountName() {
		return $this->account_name;
	}

	public function setAccountName($account_name) {
		$this->account_name = $account_name;
	}

	public function getAccountAmount() {
		return $this->account_amount;
	}

	public function setAccountAmount($account_amount) {
		$this->account_amount = $account_amount;
	}

	public function getOwnerId() {
		return $this->owner_id;
	}

	public function setOwnerId($owner_id) {
		$this->owner_id = $owner_id;
	}

	public function getPayScheduleCode() {
		return $this->payschedule_code;
	}

	public function setPayScheduleCode($payschedule_code) {
		$this->payschedule_code = $payschedule_code;
	}

	public function getActive() {
		return $this->active;
	}

	public function setActive($active) {
		$this->active = $active;
	}

	public function getCategories() {
		return $this->categories;
	}

	public function getID() {
		return $this->getAccountId();
	}
}
