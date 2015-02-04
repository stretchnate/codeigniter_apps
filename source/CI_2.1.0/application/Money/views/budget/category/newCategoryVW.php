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
					<table>
						<tr>
							<td>
								<span class="error">*</span>
								<label for="account">Into Account: </label>
							</td>
							<td>
								<select name="account" class="required">
									<option value="">- - Select Account - -</option>
								<?
								if( is_array($this->accounts) ) {
									foreach($this->accounts as $account) {
										echo "<option value={$account->account_id}>{$account->account_name}</option>";
									}
								}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<span class="error">*</span>
								<label for="name">Category Name: </label>
							</td>
							<td><input type="text" name="name" id="name" /><br /></td>
							<td><div class="ajaxResult result"></div></td>
						</tr>
						<tr>
							<td>
								<span class="error">*</span>
								<label for="nec">
									<a href="javascript:void(null)" class="tool-tip" title="how much do you spend on this bill each month?">Amount Necessary:</a>
								</label>
							</td>
							<td><input type="text" class="required money" name="nec" id="nec" value="$" /></td>
							<td><div id="amt-message" class="result">&nbsp;</div></td>
						</tr>
						<tr>
							<td>
								<span class="error">*</span>
								<label for="startAmt">
									<a href="javascript:void(null)" class="tool-tip" title="how much do you have saved already for this bill?">Starting Amount:</a>
								</label>
							</td>
							<td>
								<input type="text" class="required money" name="startAmt" id="startAmt" value="$" />
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>
								<span class="error">*</span>
								<label for="dueDay">
									<a href="javascript:void(null);" class="tool-tip" title="Enter 0 to max this category each paycheck">
										Due Day
									</a>
								</label>
							</td>
							<td>
								<input type="text" class="required number" name="dueDay" id="dueDay" value="" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="due_months">
									<span class="error">*</span>
									<a href="javascript:void(null);" class="tool-tip" title="ctrl + click to select each month this category is due (ctrl + a to select all)">Due Months</a>
								</label>
							</td>
							<td>
								<select name="due_months[]" id="due_months" multiple="multiple" size="6">
									<?php
										$months = array(
										1 => "January",
										2 => "February",
										3 => "March",
										4 => "April",
										5 => "May",
										6 => "June",
										7 => "July",
										8 => "August",
										9 => "September",
										10 => "October",
										11 => "November",
										12 => "December");

										foreach($months as $index => $month) {
										?>
										<option value="<?= $index; ?>"><?= $month; ?></option>
										<?php
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="3"></td>
						</tr>
						<tr>
							<td colspan="3">
								<label for="interest">is this an interest bearing category?</label>
								<input type="radio" id="interest" name="interest" value="1" />Yes&nbsp;&nbsp;<input type="radio" name="interest" value="0" />No
							</td>
						</tr>
						<tr>
							<td>Interest Rate</td>
							<td class="interest-info" colspan="2">
								<input type="text" value="" name="rate" />
								<select name="rateType" id="rateType">
									<option value="1">Monthly</option>
									<option value="2">Per Diem</option>
									<option value="3" selected="selected">Annual (APR)</option>
									<option value="4">Don't Know</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Current balance: </td>
							<td class="interest-info"><input type="text" name="totalOwed" /></td>
						</tr>
						<tr>
							<td>Priority: </td>
							<td>
								<select name="priority">
								<?php
								for($i = 1;$i < 11; $i++) {
									echo "<option value='$i'>$i</option>";
								}
								?>
								</select>
							</td>
						</tr>
					</table>
					<input type="submit" value="Add Category" />
				</form>
			</div>
			<?
		}

		public function setAccounts($accounts) {
			$this->accounts = is_array($accounts) ? $accounts : array($accounts);
		}
	}