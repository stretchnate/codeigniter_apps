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
	private $plaid_category;

	function __construct($category_id = null, $owner_id = null){
		parent::__construct();

		if($category_id && $owner_id) {
			$this->loadCategory($category_id, $owner_id);
		}
	}

    /**
     * loads the category data into the class properties.
     *
     * @param $category_id
     * @param $owner_id
     * @throws Exception
     */
	function loadCategory($category_id, $owner_id) {
		$this->loadBy(array("bookId" => $category_id, 'ownerId' => $owner_id));
	}

    /**
     * loads the category based on array provided
     *
     * @param array $where
     * @throws Exception
     */
	public function loadBy($where = []) {
	    if(empty($where['ownerId'])) {
	        throw new Exception("Owner not authorized to view this category.", EXCEPTION_CODE_ERROR);
        }

        $query = $this->db->get_where("booksummary", $where);

        if($query->num_rows() > 0) {
            $this->load($query->row());
        }
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
     * loads the data into the category
     *
     * @param $category
     */
	private function load($category) {
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
                case "plaid_category":  $this->plaid_category          = $value; break;
                default:
                    $this->$column = $value;
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
		$sets["bookId"]            = $this->category_id;
		$sets["bookName"]          = $this->category_name;
		$sets["bookAmtNec"]        = $this->dbNumberFormat($this->amount_necessary);
		$sets["bookAmtCurrent"]    = $this->dbNumberFormat($this->current_amount);
		$sets["ownerId"]           = $this->owner_id;
		$sets["priority"]          = $this->priority;
		$sets["active"]            = $this->active;
		$sets["due_day"]           = $this->due_day;
		$sets["due_months"]        = implode('|', $this->due_months);
		$sets["account_id"]        = $this->parent_account_id;
		$sets["InterestBearing"]   = $this->interest_bearing;
		!empty($this->plaid_category) ? $sets["plaid_category"] = $this->plaid_category : null;

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
		!empty($this->plaid_category) ? $values["plaid_category"] = $this->plaid_category : null;

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
		//if it's an every check category the due date is set to tomorrow
		if($this->due_day == 0) {
			$this->next_due_date = new DateTime(date('Y-m-d', strtotime('+1 day')));
		} else {
			switch(count($this->due_months)) {
				case 1: //annually
					$this->next_due_date = $this->calculateAnnualDueDate();
					break;

				case 2: //semi-annually
					$this->next_due_date = $this->calculateSemiAnnualDueDate();
					break;

				case 4: //quarterly
					$this->next_due_date = $this->calculateQuarterlyDueDate();
					break;

				case 12://monthly
				default:
					$this->next_due_date = $this->calculateMonthlyDueDate();
					break;
			}
		}
	}

	/**
	 * @return \DateTime
	 */
	private function calculateAnnualDueDate() {
		$today = new \DateTime();
		$due_day = is_object($this->due_day) ? $this->due_day : new \DateTime($this->due_day);
		$due_date = new \DateTime(date('Y').'-'.$this->due_months[0].'-'.$due_day->format('j'));

		if($today->format('n') > $this->due_months[0]) {
			$due_date->add(new \DateInterval("P1Y"));
		} elseif($today->format('n') == $this->due_months[0]) {
			if($today->format('j') > $due_day->format('j')) {
				$due_date->add(new \DateInterval("P1Y"));
			}
		}

		return $due_date;
	}

	/**
	 * @return \DateTime
	 */
	private function calculateSemiAnnualDueDate() {
		sort($this->due_months);
		$today = new \DateTime();
		$found = array_search($today->format('n'), $this->due_months);

		$due_day = is_object($this->due_day) ? $this->due_day : new \DateTime($this->due_day);
		$due_date = new \DateTime(date('Y').'-'.$this->due_months[$found].'-'.$due_day->format('j'));

		if($found === false) {
			$this_month = $today->format('n');
			$due_date = new \DateTime(date('Y').'-'.$this->due_months[0].'-'.$due_day->format('j'));
			if($this_month > $this->due_months[0] && $this_month > $this->due_months[1]) {
				$due_date->add(new \DateInterval("P1Y"));
			} elseif($this_month > $this->due_months[0] && $this_month < $this->due_months[1]) {
				$due_date->add(new \DateInterval("P6M"));
			}
		} elseif($today->format('j') > $due_date->format('j')) {
			$due_date->add(new \DateInterval("P6M"));
		}

		return $due_date;
	}

    /**
     * @return DateTime
     * @throws Exception
     */
	private function calculateQuarterlyDueDate() {
		$today = new DateTime();
        $dd = new DateTime($this->due_day);
		$this_month = $today->format('m');
        $i = 0;

        foreach($this->getDueMonths() as $due_month) {
		    if($due_month <= $this_month) {
		        $diff = $this_month - $due_month;
		        switch($diff) {
                    case 0:
                        if($today->format('d') > $dd->format('d')) {
                            $i = 3;
                        }
                        break;
                    case 1:
                        $i = 2;
                        break;
                    case 2:
                        $i = 1;
                        break;
                    default:
                        continue 2;
                }

                $due_date = clone $today;
                $due_date->setDate($due_date->format('Y'), $due_date->format('m'), $dd->format('d'));
                $due_date->add(new DateInterval("P{$i}M"));

                return $due_date;
            } else {
		        $due_date = new DateTime();
		        $due_date->setDate($today->format('Y'), $due_month, $dd->format('d'));

		        return $due_date;
            }
        }
	}

	/**
	 * @return \DateTime
	 */
	private function calculateMonthlyDueDate() {
		$today = new \DateTime();
		$due_date = new \DateTime($this->due_day);
		if($today->format('d') > $due_date->format('d')) {
			$due_date->setDate($today->format('Y'), $today->format('n')+1, $due_date->format('j'));
		} elseif($today->format('d') < $due_date->format('d')) {
			$due_date->setDate($today->format('Y'), $today->format('n'), $due_date->format('j'));
		} else {
			$due_date = $today;
		}

		return $due_date;
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

        $date_interval = $today->diff($next_due_date);
        $days = $date_interval->format('%a');//total number of days as a result of DateTime::diff();

		$this->days_until_due = $days;
	}

	/* *****************************************************
	**	GETTERS AND SETTERS
	*******************************************************/
	public function getCategoryId() {
		return $this->category_id;
	}

    /**
     * @param $id
     * @return $this
     * @throws Exception
     */
	public function setCategoryId($id) {
	    if(isset($this->category_id)) {
	        throw new RuntimeException('Overwriting category id is not allowed.');
        }

        $this->category_id = $id;

	    return $this;
    }

	public function getCategoryName() {
		return $this->category_name;
	}

	public function setCategoryName($category_name) {
		$this->category_name = $category_name;

		return $this;
	}

	public function getAmountNecessary() {
		return (float)$this->amount_necessary;
	}

	public function setAmountNecessary($amount_necessary) {
		$this->amount_necessary = (float)$amount_necessary;

        return $this;
	}

	public function getCurrentAmount() {
		return (float)$this->current_amount;
	}

	public function setCurrentAmount($current_amount) {
		$this->current_amount = (float)$current_amount;

        return $this;
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

        return $this;
	}

	public function getPriority() {
		return $this->priority;
	}

	public function setPriority($priority) {
		$this->priority = $priority;

        return $this;
	}

	public function getActive() {
		return $this->active;
	}

	public function setActive($active) {
		$this->active = $active;

        return $this;
	}

	public function getDueDay() {
		return $this->due_day;
	}

	public function setDueDay($due_day) {
		$this->due_day = $due_day;

        return $this;
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

        return $this;
	}

	public function setInterestBearing($interest_bearing) {
		$this->interest_bearing = Utilities::getBoolean($interest_bearing);

        return $this;
	}

	public function getInterestBearing() {
		return $this->interest_bearing;
	}

	public function getDaysUntilDue($from_date = null) {
		if(!$this->days_until_due) {
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

    /**
     * @return mixed
     */
    public function getPlaidCategory() {
        return $this->plaid_category;
    }

    /**
     * @param mixed $plaid_category
     * @return Budget_DataModel_CategoryDM
     */
    public function setPlaidCategory($plaid_category) {
        $this->plaid_category = $plaid_category;
        return $this;
    }
}
