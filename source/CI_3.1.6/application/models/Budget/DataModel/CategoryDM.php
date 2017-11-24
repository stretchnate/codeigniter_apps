<?php
class Budget_DataModel_CategoryDM extends N8_Model {

	//@TODO adjust the database to use the following columns instead of what's in there, and change the table name to "categories", it's a big job so it'll take some time.
	private $category_id;      //maps to bookId
	private $category_name;    //maps to bookName
	private $amount_necessary; // maps to bookAmtNec
	private $current_amount;   //maps to bookAmtCurrent
	private $owner_id;         //maps to ownerId
	private $priority;
	private $active;
	private $due_day;
	private $due_months;
	private $parent_account_id; //maps to account_id
	private $interest_bearing;
	private $days_until_due;
	private $next_due_date;

	function __construct($category_id = null, $owner_id = null){
		parent::__construct();

		if($category_id && $owner_id) {
			$this->loadCategory($category_id, $owner_id);
		}
	}

	/**
	 * loads the category data into the class properties.
	 *
	 * @param category_id int
	 */
	function loadCategory($category_id, $owner_id) {
		$query = $this->db->get_where("booksummary", array("bookId" => $category_id, 'ownerId' => $owner_id));
		if($query->num_rows() < 1) {
			throw new Exception("Invalid Category ID [$category_id] for owner [".$owner_id."] (".__METHOD__.":".__LINE__.")");
		}

		$category = $query->row();

		foreach($category as $column => $value) {
			$value = trim($value);
			switch($column) {
                case "bookId":          $this->category_id             = $value; break;
                case "bookName":        $this->category_name           = $value; break;
                case "bookAmtNec":      (float)$this->amount_necessary = $value; break;
                case "bookAmtCurrent":  (float)$this->current_amount   = $value; break;
                case "ownerId":         $this->owner_id                = $value; break;
                case "account_id":      $this->parent_account_id       = $value; break;
				case "InterestBearing": $this->interest_bearing        = Utilities::getBoolean($value); break;
                case "due_months":      $this->due_months              = explode('|', $value); break;
				default:
					$this->$column = $value;
			}
		}
	}

	function checkExisitingCategory(&$category_name, &$user_id) {

	}

    /**
     * saves the category to the database
     *
     * @return boolean
     */
	public function saveCategory() {
		if( $this->validateCategory() ) {
			if($this->category_id > 0) {
				return $this->updateCategory();
			} else {
				$this->insertCategory();
				$this->category_id = $this->db->insert_id();
				return $this->category_id;
			}
		}
	}

    /**
     * updates an existing category
     *
     * @return boolean
     */
	private function updateCategory() {
		$result = false;

		$sets = array();
		$sets["bookId"]          = $this->category_id;
		$sets["bookName"]        = $this->category_name;
		$sets["bookAmtNec"]      = $this->dbNumberFormat($this->amount_necessary);
		$sets["bookAmtCurrent"]  = $this->dbNumberFormat($this->current_amount);
		$sets["ownerId"]         = $this->owner_id;
		$sets["priority"]        = $this->priority;
		$sets["active"]          = $this->active;
		$sets["due_day"]         = $this->due_day;
		$sets["due_months"]      = implode('|', $this->due_months);
		$sets["account_id"]      = $this->parent_account_id;
		$sets["InterestBearing"] = $this->interest_bearing;

		if($this->db->where("bookId", $this->category_id)->update("booksummary", $sets)) {
			$result = true;
		}

		if($result === false) {
			$this->setError("there was a problem updating the category ".$this->category_name);
		}

		return $result;
	}

    /**
     * inserts a new category
     *
     * @return type
     */
	function insertCategory() {
		$values = array();
		$values["bookName"]        = $this->category_name;
		$values["bookAmtNec"]      = $this->dbNumberFormat($this->amount_necessary);
		$values["bookAmtCurrent"]  = $this->dbNumberFormat($this->current_amount);
		$values["ownerId"]         = $this->owner_id;
		$values["priority"]        = $this->priority;
		$values["active"]          = ($this->active) ? $this->active : 0;
		$values["due_day"]         = $this->due_day;
		$values["due_months"]      = implode('|', $this->due_months);
		$values["account_id"]      = $this->parent_account_id;
		$values["InterestBearing"] = $this->interest_bearing;

		return $this->db->insert("booksummary", $values);
	}

    /**
     * validates the category data
     *
     * @return boolean
     */
	private function validateCategory() {
		$valid = true;

		if( !is_null($this->category_id) && !is_numeric($this->category_id) ) {
			$this->setError("Invalid Category id");
			$valid          = false;
		}

		if( empty($this->category_name) ) {
			$this->setError("Category name cannot be null");
			$valid          = false;
		}

		if( !is_numeric($this->amount_necessary) || $this->amount_necessary < 0 ) {
			$this->setError("Invalid Amount Necessary");
			$valid          = false;
		}

		if( !is_numeric($this->current_amount) && !is_float($this->current_amount) ) {
			$this->setError("Invalid Current Amount");
			$valid          = false;
		}

		if( !is_numeric($this->parent_account_id) ) {
			$this->setError("Invalid Parent Account");
			$valid          = false;
		}
		return $valid;
	}

    /**
     * fetches the transactions for the category
     *
     * @param string $direction
     * @return array
     */
	public function fetchTransactions($direction = "asc") {
		$this->db->select();

		if($this->category_id) {
			$this->db->where("to_category = ".$this->category_id)
					->or_where("from_category = ".$this->category_id);
		} else {
			if(!$this->owner_id) {
				$this->setOwnerId();
			}
			$this->db->where("owner_id = ".$this->owner_id);
		}

		$this->db->order_by("transaction_id", $direction);

		$results = $this->db->get("transactions");

		return $results->result();
	}

	/**
	 * determines the next due date of the category
	 *
	 * @return void (saves object (DateTime) to $this->next_due_date
	 * @since  04.28.2013
	 */
	private function calculateNextDueDate() {
		$due_date = new DateTime();
		$today    = new DateTime();
		$due_months_total = count($this->due_months);

		//if it's an every check category the due date is set to tomorrow
		if($this->due_day == 0) {
			$due_date = new DateTime(date('Y-m-d', strtotime('+1 day')));
		} else {
			if(strpos($this->due_day, '/') === false) {
				$this->due_day = date('Y').'-'.date('m').'-'.$this->due_day;
			}
			$due_day = new DateTime($this->due_day);
			switch($due_months_total) {
				case 1: //annually
					$due_date = $due_day;
					break;

				case 2: //semi-annually
					$increment = 6;
					break;

				case 4: //quarterly
					$increment = 3;
					break;

				case 12://monthly
				default:
					$increment = 1;
					break;
			}

			if(isset($increment)) {
				foreach($this->due_months as $due_month) {
					if($due_month == $today->format('n')) {
						if($due_day->format('j') >= $today->format('j')) {
							$i = '6';
						}

						$i = $due_month - $today->format('n');
					} elseif($due_month > $today->format('n')) {
						$i = $due_month - $today->format('n');
					}

					if(isset($i)) {
						$format = new DateInterval("P{$i}M");
					}
				}
			}

			if(isset($format)) {
				$today->add($format);
				$due_date->setDate($today->format('Y'), $today->format('n'), $due_day->format('j'));
			}
		}

		$this->next_due_date = $due_date;
	}

	/**
	 * determines how many days until due
	 *
	 * @return void
	 * @since  05.01.2013
	 */
	private function calculateDaysUntilDue($from_date = null) {
		$today         = new DateTime($from_date);
		$next_due_date = $this->getNextDueDate();

		//unfortunatley until I'm updated to 5.3.2 or higher this won't work so we have to use the below rudimentary code :(
		// $date_interval = $today->diff($next_due_date);
		// $days = $date_interval->format('%a');//total number of days as a result of DateTime::diff();

		$total_seconds = $next_due_date->getTimeStamp() - $today->getTimeStamp();
		$days          = ($total_seconds / 86400);

		$this->days_until_due = $days;
	}

	/* *****************************************************
	**	GETTERS AND SETTERS
	*******************************************************/
	public function getCategoryId() {
		return $this->category_id;
	}

	public function getCategoryName() {
		return $this->category_name;
	}

	public function setCategoryName($category_name) {
		$this->category_name = $category_name;
	}

	public function getAmountNecessary() {
		return (float)$this->amount_necessary;
	}

	public function setAmountNecessary($amount_necessary) {
		$this->amount_necessary = (float)$amount_necessary;
	}

	public function getCurrentAmount() {
		return (float)$this->current_amount;
	}

	public function setCurrentAmount($current_amount) {
		$this->current_amount = (float)$current_amount;
	}

	public function getOwnerId() {
		return $this->owner_id;
	}

	public function setOwnerId($owner_id = null) {
		if( $owner_id && $owner_id == $this->session->userdata("user_id")) {
			$this->owner_id = $owner_id;
		} else {
			$this->owner_id = $this->session->userdata("user_id");
		}
	}

	public function getPriority() {
		return $this->priority;
	}

	public function setPriority($priority) {
		$this->priority = $priority;
	}

	public function getActive() {
		return $this->active;
	}

	public function setActive($active) {
		$this->active = $active;
	}

	public function getDueDay() {
		return $this->due_day;
	}

	public function setDueDay($due_day) {
		$this->due_day = $due_day;
	}

	public function getDueMonths() {
		return $this->due_months;
	}

	public function setDueMonths(array $due_months) {
		$this->due_months = $due_months;
        return $this;
	}

	public function getParentAccountId() {
		return $this->parent_account_id;
	}

	public function setParentAccountId($parent_account_id) {
		$this->parent_account_id = $parent_account_id;
	}

	public function setInterestBearing($interest_bearing) {
		$this->interest_bearing = Utilities::getBoolean($interest_bearing);
	}

	public function getInterestBearing() {
		return $this->interest_bearing;
	}

	public function getDaysUntilDue($from_date = null) {
		if(is_null($this->days_until_due)) {
			$this->calculateDaysUntilDue($from_date);
		}

		return $this->days_until_due;
	}

	public function getNextDueDate() {
		if(is_null($this->next_due_date)) {
			$this->calculateNextDueDate();
		}

		return $this->next_due_date;
	}

	public function getID() {
		return $this->category_id;
	}
}
