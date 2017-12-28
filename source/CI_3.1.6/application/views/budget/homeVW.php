<?php
	require_once('baseVW.php');

	class Budget_HomeVW extends Budget_BaseVW {

		protected $last_transaction;
		protected $last_update;

        private   $totals_array = array();

		public function __construct(&$CI) {
			parent::__construct($CI);
		}

		/**
		 * generates the body of the view
		 *
		 * @access  protected
		 * @since   07.01.2013
		 */
		protected function generateView() {
		?>
			<div id="accounts-tabs" class="tabs">
				<ul class='nav nav-tabs'>
				<?php
				if(is_array($this->totals_array)) {
					$i = 0;
					foreach($this->totals_array as $totals) {
                        $account_name = $totals->getAccountDM()->getAccountName();
						$class = "";
						if($i == 0) {
							$class = " class='active'";
							$i++;
						}

						$acct_id = strtolower(preg_replace("/[\s]+/", "_", $account_name));
					?>
							<li<?php echo $class; ?>><a id="<?php echo$acct_id;?>-tab" class="tabs-link" href="javascript:void(null)"><?php echo $account_name;?></a></li>
					<?php
					}
				}
				?>
				</ul>
				<div id="accounts-container">
					<?php
					$i = 0;

					if(!is_array($this->totals_array) || count($this->totals_array) < 1) {
						echo $this->showAddAccount();
					}else if(is_array($this->totals_array)) {
						foreach($this->totals_array as $total) {
                            $account_dm = $total->getAccountDM();

							$style = "";
							if($i == 0) {
								$style = " style='display:block'";
							}

							$acct_id = strtolower(preg_replace("/[\s]+/", "_", $account_dm->getAccountName()));
						?>
							<div class="account-container" id="<?php echo $acct_id;?>"<?php echo $style;?>>
								<div class='well'>
									<div class="category-container">
										<label>Amount to distribute:</label>
										<div class="inline-block align-right">$<?php echo number_format($account_dm->getAccountAmount(),2, '.', ',') ?></div>
									</div>
									<div class="category-container">
										<label>Account Balance:</label>
										<div id="<?php echo $acct_id;?>-total" class="inline-block align-right">
											$ <?php echo number_format($total->getTotal(),2,'.',','); ?>
										</div>
									</div>
									<div class="category-container">
										<label>Monthly Need:</label>
										<div id="<?php echo $acct_id;?>-total-necessary" class="inline-block align-right">
											$ <?php echo number_format($total->getTotalNecessary(),2,'.',','); ?>
										</div>
									</div>
									<div>
										Last Update:
										<?php
										if($this->last_update) {
											echo $this->last_update;
										}
										?>
									</div>
									<div>
										<?php
										echo ($this->last_transaction) ? "Last Transaction: ".$this->last_transaction : '';
										?>
									</div>
									<div>
										<a href='/accountCTL/editAccount/<?= $account_dm->getAccountId(); ?>'>Edit <?= $account_dm->getAccountName(); ?></a>
									</div>
								</div>

								<?php
								if(is_array($account_dm->getCategories()) && count($account_dm->getCategories()) > 0) {
									foreach($account_dm->getCategories() as $category_dm){
									?>
										<div class="well">
											<h3 class="border">
												<a class='text' href="/book/getBookInfo/<?php echo $category_dm->getCategoryId(); ?>/"><?php echo $category_dm->getCategoryName(); ?></a>
											</h3>
											<div class="content">
												<div class="category-container">
												<?php
													if($category_dm->getDueDay() > 0) {
														$due_date = $category_dm->getNextDueDate()->format("F d, Y");
														echo "Due: ".$due_date;
													}
												?>
												</div>
												<div class="category-container">
													<label>Goal:</label>
													<div class='align-right inline-block'>$<?php echo number_format($category_dm->getAmountNecessary(), 2, '.', ',') ?></div>
												</div>
												<div class="category-container">
													<label>Amount Saved:</label>
													<div class='align-right inline-block'>$<?php echo number_format($category_dm->getCurrentAmount(), 2, '.', ',') ?></div>
												</div>
												<div class="category-container">
													<label>Difference:</label>
													<?php
														$dif = subtract($category_dm->getAmountNecessary(), $category_dm->getCurrentAmount(), 2);
														if($category_dm->getAmountNecessary() > $category_dm->getCurrentAmount()){
															echo '<div class="red inline-block align-right">-$'.number_format($dif, 2, '.', ',').'</div>';
														} else if ($category_dm->getAmountNecessary() < $category_dm->getCurrentAmount()){
															$dif = subtract($category_dm->getCurrentAmount(), $category_dm->getAmountNecessary(), 2);
															echo '<div class="bold inline-block align-right">+$'.number_format($dif, 2, '.', ',').'</div>';
														} else {
															echo '<div class="align-right inline-block">$'.number_format($dif, 2, '.', ',').'</div>';
														}
													?>
												</div>
											</div>
										</div>
								<?php
									}
								} else {
									echo $this->showAddCategories();
								}
								?>
							</div>
						<?php
						$i++;
						}
					}
					?>
				</div>
			</div>
<?php
		}

		private function showAddAccount() {
			$content = "<h3>Please add an Account</h3>"
					. "Now that you're registered you'll need to create an account to represent your bank account,"
					. " piggy bank, mayonaise jar or whatever it is you use to store your cash. Don't worry,"
					. " we don't ask for any financial or personal information, we are simply simulating your bank"
					. " account so we can help you organize it. in fact all we ask for is a name for your account"
					. " and how often you get paid. The name is so you know which account your working with and the pay"
					. " schedule is so we can calculate the best way to organize your money.";
			$content .= "<br><br><a class='btn btn-primary' href='/accountCTL/addNewAccount'>add a new Account</a>";

			return $content;
		}
		private function showAddCategories() {
			$content = "<h3>Please add Categories to this Account</h3>"
					. "<p>Now that you have an account created you can begin adding categories. We ask for a little bit more"
					. " information on these but we are still careful to not ask for any personal or financial details."
					. " You can add as many categories as you would like, for example, you might want a category for"
					. " rent/mortgage, and another for car payment, perhaps groceries and gas would be good to have."
					. " you can be as broad or as detailed as you want, whatever helps you take control of your finances.</p>";

			$content .= "<br><br><a class='btn btn-primary' href='/book/newBookForm'>add a category</a></h3>";

			return $content;
		}

		public function setLastTransaction($last_transaction) {
			$this->last_transaction = $last_transaction;
		}

		public function setLastUpdate($last_update) {
			$this->last_update = $last_update;
		}

        public function setTotalsArray(array $totals) {
            $this->totals_array = $totals;
        }
	}