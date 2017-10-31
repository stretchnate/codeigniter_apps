<?php
	require_once(APPPATH.'/views/budget/baseVW.php');

	class Budget_Category_NewCategoryVW extends Budget_BaseVW {

		private $accounts;

		public function __construct(&$CI) {
			parent::__construct($CI);
		}

		/**
		 * generates the body of the view
		 *
		 * @access  protected
		 * @since   07.01.2013
		 */
		public function generateView() {
			?>
			<h2>New Category Form</h2>
			<div class="formResult result">
				<span id="ajax-load"><img src='<?php echo IMG_PATH; ?>ajax-loader.gif' alt='loading...' /></span>
				<span id="result-message">&nbsp;</span>
			</div>
			<div id="new-book">
				<form name="newBookForm" id="newBookForm" action="/book/createCategory/" method="post">
					<div class='form-group'>
						<select name="account" id='account' class="required form-control">
							<option value="">- - Select Account - -</option>
						<?
						if( is_array($this->accounts) ) {
							foreach($this->accounts as $account) {
								echo "<option value={$account->account_id}>{$account->account_name}</option>";
							}
						}
						?>
						</select>
					</div>
					<div class='form-group'>
						<input type="text" name="name" id="name" class='form-control' value='Name' />
					</div>
					<div class='form-group'>
						<div class="ajaxResult result"></div>
						<input type="text" class="required money form-control" name="nec" id="amount_due" value="Amount Due" />
					</div>
					<div class='form-group'>
						<input type="text" class="required money form-control" name="startAmt" id="starting_amount" value="Starting Amount" />
					</div>
					<div class='form-group'>
						<input type="text" class="required number form-control" name="dueDay" id="due_date" value="Due Date" />
					</div>
					<div class='form-group-label'>

					</div>
					<?php
						$bill_schedule = array(
							'' => ' -- Bill Schedule -- ',
							'per_check' => "Every Paycheck",
							'monthly' => "Monthly",
							'quarterly' => "Quarterly",
							'semi_annual' => "Every 6 Months",
							'annual' => "Yearly"
						);
					?>
					<div class='form-group'>
						<select name='bill_schedule' class='form-control'>
							<?php
							foreach($bill_schedule as $index => $schedule) {
							 echo "<option value='$index'>$schedule</option>";
							}
							?>
						</select>
					</div>
					<div class='form-group'>
						<input type="text" name="totalOwed" class='form-control' value='Current Balance' id="current_balance" />
					</div>

					<div class='form-group'>
						<select name="priority" id='priority' class='form-control'>
							<option value=''>Priority</option>
							<?php
							for($i = 1;$i < 11; $i++) {
								echo "<option value='$i'>$i</option>";
							}
							?>
						</select>
					</div>

					<input type="submit" value="Add Category" class='btn btn-primary' />
				</form>
			</div>
			<?
		}

		public function setAccounts($accounts) {
			$this->accounts = is_array($accounts) ? $accounts : array($accounts);
		}
	}