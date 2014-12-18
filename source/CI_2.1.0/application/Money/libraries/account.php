<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class is used to hold an account data for adding funds
 */
class Account {

	function Account() {
		$this->CI =& get_instance();
		$this->CI->load->database();
	}

	function distributeFunds($depositAmount, $total, &$category) {
		$this->CI->auth->restrict();
		$this->CI->load->model('Funds_operations', 'Fops',TRUE);
		$this->CI->load->model("accounts", "ACCT", TRUE);

		$response['total'] = $total;
		$response['message'] = '';
		if($depositAmount > $total) {
			$depositAmount = $total;
		}
		$amount = $depositAmount + $category->bookAmtCurrent;

		$this->CI->db->trans_start();//@TODO verify this is working, remember there is a hack that needs to be done for nested transactions in CI check google.
		$this->CI->Fops->setAccountAmount($category->bookId, $amount);
		$total = (float)$total - (float)$depositAmount;

		$this->CI->auth->updateLoginHistory(TRUE);
		$this->CI->db->trans_complete();

		if( $this->CI->db->trans_status() === FALSE ) {
			$response['message'] = "there was a problem distributing your funds into account {$category->bookName}.";
			$response['total'] = $total + $depositAmount;
			log_message('error', $response['message']);
		} else {
			$response['total'] = $total;
			log_message('info', "successful update on account {$category->bookName}");
		}
		return $response;
	}

	/**
	 * DEPRECATED 2012.03.24
	 */
	public function addTransaction($fromId = null,
									$toId = null,
									$amount,
									$type = 's',
									$category = 'deduction',
									$refund = 0,
									$date = '') {
		$this->CI->auth->restrict();
		$this->CI->load->model('book_info','BI',TRUE);
		$Account = '';
		if($toId) {
			$toAccount = $this->CI->BI->getAccountData($toId);
			$Account = $toAccount->bookName;
		}
		if($fromId) {
			//get from name
			$fromAccount = $this->CI->BI->getAccountData($fromId);
			$Account = $fromAccount->bookName;
		}
		$data = array('ownerId' => $this->CI->session->userdata('user_id'), 'bookTransAmt' => $amount, 'TransType' => $type);
		switch($category) {
			case 'AutomaticBucketSubtract':
				$data['bookTransPlace'] = "Automatic Distribution to account $Account on ".date("m/d/Y");
				$data['bookId'] = "B".$this->CI->session->userdata('user_id');
				break;
			case 'AutomaticBucketAdd':
				$data['bookTransPlace'] = "Automatic Distribution on ".date("m/d/Y");
				$data['bookId'] = $toId;
				break;
			case 'transferTo':
				$data['bookTransPlace'] = "Transfer to $Account";
				$data['bookId'] = $toId;
				break;
			case 'transferFrom':
				$data['bookTransPlace'] = "Transfer from $Account";
				$data['bookId'] = $fromId;
				break;
			case 'addFromBucket':
				$data['bookTransPlace'] = "Funds added from Parent Account";
				$data['bookId'] = $toId;
				break;
			case 'refund':
				$data['bookTransPlace'] = "Refund on transaction ID: $refund";
				$data['bookId'] = $toId;
				break;
			case 'transactionFailed':
				$data['bookTransPlace'] = "Transfer Failed: Reversing previous transaction";
				$data['bookId'] = "B".$this->CI->session->userdata('user_id');
			case 'deduction':
				$data['bookTransPlace'] = "Deduction";
				$data['bookId'] = $fromId;
				break;
			default:
				$data['bookTransPlace'] = $category;
				if(!empty($refund) && $type == 'a') {
					$data['bookTransPlace'] .= " Transaction ID: $refund";
				}
				if($type == 's') {
					$data['bookId'] = $fromId;
				} else {
					$data['bookId'] = $toId;
				}
				break;
		}
		if(!empty($date)) {
			$data['bookTransDate'] = $date;
		}
		$transaction = $this->CI->BI->newTransaction($data);
		if(!$transaction) {
			return false;
		}
		return true;
	}
}