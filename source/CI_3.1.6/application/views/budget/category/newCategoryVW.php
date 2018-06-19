<?php
	require_once(APPPATH.'/views/budget/baseVW.php');

	class Budget_Category_NewCategoryVW extends Budget_BaseVW {

		private $accounts;

		/**
		 * @var Budget_DataModel_CategoryDM
		 */
		private $category_dm;

		private $action;

		private $categories;

		public function __construct(&$CI, $categories) {
			parent::__construct($CI);
			$this->categories = $categories;
		}

		/**
		 * generates the body of the view
		 *
		 * @access  protected
		 * @since   07.01.2013
		 */
		public function generateView() {
			?>
			<h2><?= $this->title ? $this->title : 'New Category Form' ?></h2>
			<div class="formResult result">
				<span id="ajax-load"><img src='<?php echo IMG_PATH; ?>ajax-loader.gif' alt='loading...' /></span>
				<span id="result-message">&nbsp;</span>
			</div>
			<div id="new-book">
                <?= form_open($this->action, ['id'=>'newBookForm', 'name'=>'newBookForm']); ?>
					<div class='form-group'>
						<select name="account" id='account' class="form-control" required>
							<option value="">- - Select Account - -</option>
						<?php
						if( is_array($this->accounts) ) {
							foreach($this->accounts as $account) {
								$selected = null;
								if($this->category_dm && $account->account_id == $this->category_dm->getParentAccountId()) {
									$selected = ' selected';
								}
								echo "<option value={$account->account_id}{$selected}>{$account->account_name}</option>";
							}
						}
						?>
						</select>
					</div>
					<div class='form-group'>
						<input type="text" name="name" placeholder="Name" id="name" class='form-control' value="<?= $this->category_dm ? $this->category_dm->getCategoryName() : ''; ?>" required>
						<div class="ajaxResult result"></div>
					</div>
                    <div class="form-group">
                        <select name="category_type" id='category_type' class="form-control">
                            <option value="">- - Select Category Type - -</option>
                        <?php
                        foreach($this->categories as $category) {
                            echo $category;
                        }
                        ?>
                        </select>
                    </div>
					<div class='form-group'>
						<div class="input-group">
							<input type="number" step="0.01" class="money form-control" name="nec" placeholder="Amount Due" id="amount_due" value="<?= $this->category_dm ? $this->category_dm->getAmountNecessary() : '';?>" required>
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-usd"></span>
							</div>
						</div>
					</div>
					<?php
					if(!isset($this->category_dm)) {?>
					<div class='form-group'>
						<div class="input-group">
							<input type="number" step="0.01" placeholder="Starting Amount" class="money form-control" name="startAmt" id="starting_amount" value="" required>
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-usd"></span>
							</div>
						</div>
					</div>
					<?php
					} ?>
					<div class='form-group'>
						<?php
							if($this->category_dm) {
								switch(count($this->category_dm->getDueMonths())) {
									case 1:
										$selected_option = 'annual';
										break;
									case 2:
										$selected_option = 'semi_annual';
										break;
									case 4:
										$selected_option = 'quarterly';
										break;
									case 12:
									default:
										$selected_option = 'monthly';
										if($this->category_dm->getDueDay() == 0) {
											$selected_option = 'per_check';
										}
								}
							}
							$bill_schedule = array(
								'' => ' -- Fill this category -- ',
								'per_check' => "Every Paycheck",
								'monthly' => "Monthly",
								'quarterly' => "Quarterly",
								'semi_annual' => "Every 6 Months",
								'annual' => "Yearly"
							);
						?>
						<select name='bill_schedule' class='form-control' required>
							<?php
							foreach($bill_schedule as $index => $schedule) {
								$selected = null;
								if($index == $selected_option) {
									$selected = ' selected';
								}
							 echo "<option value='$index'$selected>$schedule</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<div class="input-group date">
							<input type="date" class="form-control" name="dueDay" id="next_due_date" value="<?= $this->category_dm ? $this->category_dm->getDueDay() : ''; ?>" autocomplete="off">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</div>
						</div>
					</div>
					<div class='form-group'>
						<select name="priority" id='priority' class='form-control' required>
							<option value=''>Priority</option>
							<?php
							for($i = 1;$i < 11; $i++) {
								$selected = null;
								if($this->category_dm && $this->category_dm->getPriority() == $i) {
									$selected = ' selected';
								}
								echo "<option value='$i'$selected>$i</option>";
							}
							?>
						</select>
					</div>

					<button id="submit_category" class='btn btn-primary'>Submit Category</button>
				</form>
			</div>
			<?php
		}

		public function setAccounts($accounts) {
			$this->accounts = is_array($accounts) ? $accounts : array($accounts);
		}

		/**
		 * @param Budget_DataModel_CategoryDM $category_dm
		 */
		public function setCategoryDM(Budget_DataModel_CategoryDM $category_dm) {
			$this->category_dm = $category_dm;
		}

		public function setAction($action) {
			$this->action = $action;
		}
	}