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
				<ul>
				<?php
				if(is_array($this->totals_array)) {
					$i = 0;
					foreach($this->totals_array as $totals) {
                        $account_name = $totals->getAccountDM()->getAccountName();
						$class = "";
						if($i == 0) {
							$class = " class='selected_tab'";
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
					<p>
						<a id="expand-contract-all" class="button border" href="javascript:void(null)">Expand All Categories</a>
					</p>
					<?php
					$i = 0;

					if(!is_array($this->totals_array) || count($this->totals_array) < 1) {
						echo "<h3>Please <a class='button border' href='/accountCTL/addNewAccount'>add a new Account</a></h3>";
					}

					if(is_array($this->totals_array)) {
						foreach($this->totals_array as $total) {
                            $account_dm = $total->getAccountDM();

							$style = "";
							if($i == 0) {
								$style = " style='display:block'";
							}

							$acct_id = strtolower(preg_replace("/[\s]+/", "_", $account_dm->getAccountName()));
						?>
							<div class="account-container" id="<?php echo $acct_id;?>"<?php echo $style;?>>
								<!--h2><?php echo $account_dm->getAccountName();?></h2-->
								<div class="border">
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
								</div>
								<div class="book-summary">
									<div>
										<div class="bucket">
											<div class="category-container">
												<div class="label">Distributable Amount:</div>
												<div class="align-right">$<?php echo number_format($account_dm->getAccountAmount(),2, '.', ',') ?></div>
											</div>
										</div>
										<div class="bucket">
											<div class="category-container dashed-bottom">
												<div class="label">Account Balance:</div>
												<div id="<?php echo $acct_id;?>-total" class="align-right">
                                                    $ <?php echo number_format($total->getTotal(),2,'.',','); ?>
                                                </div>
											</div>
											<div class="category-container">
												<div class="label">Monthly Need:</div>
												<div id="<?php echo $acct_id;?>-total-necessary" class="align-right">
                                                    $ <?php echo number_format($total->getTotalNecessary(),2,'.',','); ?>
                                                </div>
											</div>
										</div>
									</div>

									<?php
									foreach($account_dm->getCategories() as $category_dm){
									?>
										<div class="book">
											<h3 class="border"><span class="text"><?php echo $category_dm->getCategoryName(); ?></span><span class="money">$<?php echo number_format($category_dm->getCurrentAmount(), 2, '.', ',') ?></span></h3>
											<div class="book-content">
												<div class="category-container dashed-bottom">
													<div class="label">Account:</div>
													<div class="account-name">
														<a href="/book/getBookInfo/<?php echo $category_dm->getCategoryId(); ?>/"><?php echo $category_dm->getCategoryName(); ?></a>
														<?php
															if($category_dm->getDueDay() > 0) {
																$due_date = $category_dm->getNextDueDate()->format("F d, Y");
																echo "Due: ".$due_date;
															}
														?>
													</div>
												</div>
												<div class="category-container dashed-bottom">
													<div class="label">Goal:</div>
													<div class="align-right">$<?php echo number_format($category_dm->getAmountNecessary(), 2, '.', ',') ?></div>
												</div>
												<div class="category-container dashed-bottom">
													<div class="label">Amount Saved:</div>
													<div class="align-right">$<?php echo number_format($category_dm->getCurrentAmount(), 2, '.', ',') ?></div>
												</div>
												<div class="category-container">
													<div class="label">Difference:</div>
													<?php
														$dif = bcsub($category_dm->getAmountNecessary(), $category_dm->getCurrentAmount());
														if($category_dm->getAmountNecessary() > $category_dm->getCurrentAmount()){
															echo '<div class="red align-right">-$'.number_format($dif, 2, '.', ',').'</div>';
														} else if ($category_dm->getAmountNecessary() < $category_dm->getCurrentAmount()){
															$dif = bcsub($category_dm->getCurrentAmount(), $category_dm->getAmountNecessary());
															echo '<div class="bold align-right">+$'.number_format($dif, 2, '.', ',').'</div>';
														} else {
															echo '<div class="align-right">$'.number_format($dif, 2, '.', ',').'</div>';
														}
													?>
												</div>
											</div>
										</div>
										<div class="clear">&nbsp;</div>
								<?php
									}
									?>
								</div>
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