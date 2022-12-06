<?php
	require_once('baseVW.php');

/**
 * Class Budget_HomeVW
 */
class Budget_HomeVW extends Budget_BaseVW {

    /**
     * @var
     */
    protected $last_transaction;
    /**
     * @var
     */
    protected $last_update;

    /**
     * @var array
     */
    private $totals_array = array();

    /**
     * @var \Plaid\Link
     */
    private $link;

    /**
     * @var array
     */
    private $linked_accounts;

    /**
     * @var array
     */
    private $account_amounts;

    /**
     * Budget_HomeVW constructor.
     *
     * @param $CI
     */public function __construct(&$CI) {
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
                        if($this->link) {
                            echo $this->link->getIntegrationJs();
                        }

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
                                        <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="right" data-trigger="focus" data-content='This is the amount you have in your account that has not been distributed to a category yet. To get an accurate Account Balance distribute this money into a category.'></a>
                                        <label>Amount to distribute:</label>
										<div class="inline-block align-right" id="distribute_<?php echo $account_dm->getAccountId();?>">
											$<?php echo isset($this->account_amounts[$account_dm->getAccountId()]) ? number_format($this->account_amounts[$account_dm->getAccountId()],2, '.', ',') : '0.00'; ?>
										</div>
									</div>
									<div class="category-container">
                                        <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="right" data-trigger="focus" data-content='This is the sum of all of your category totals added together. This number plus your Account Amount should equal your actual bank account balance.'></a>
                                        <label>Account Balance:</label>
										<div id="<?php echo $acct_id;?>-total" class="inline-block align-right">
											$ <?php echo number_format($total->getTotal(),2,'.',','); ?>
										</div>
									</div>
									<div class="category-container">
                                        <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="right" data-trigger="focus" data-content='This is an estimation of how much money you need to make each month based on your category needs. If this money is greater than your current take home pay you should consider adjusting your budget wherever possible.'></a>
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
                                        <?php
                                        if($this->link && !in_array($account_dm->getAccountId(), $this->linked_accounts)) {
                                        ?>
                                        &nbsp;&nbsp;
                                        <a href='javascript:void(0)' onclick="plaid.linkExistingAccount(<?= $account_dm->getAccountId(); ?>)" id="link_button">Link This Account</a>
                                        <?php
                                        }
                                        ?>
									</div>
                                </div>

								<?php
								if(is_array($account_dm->getCategories()) && count($account_dm->getCategories()) > 0) {
									foreach($account_dm->getCategories() as $category_dm){
									?>
                                        <div class="well category-well">
											<h3 class="border">
												<a class='text' href="/book/getCategory/<?php echo $category_dm->getCategoryId(); ?>/"><?php echo $category_dm->getCategoryName(); ?></a>
                                                <input type="hidden" value="<?=$category_dm->getcategoryid();?>" class="category_id"/>
											</h3>
											<div class="content">
												<div class="category-container">
												<?php
													if($category_dm->getDueDay() > 0) {
														$due_date = $category_dm->getNextDueDate()->format("F d, Y");
														echo "Due: <label class='due-date'>".$due_date."</label>";
													}
												?>
												</div>
												<div class="category-container">
                                                    <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="right" data-trigger="focus" data-content='This is the amount amount of money you need in this category each cycle (a cycle being whenever a payment is due, usually monthly but can be quarterly, semi-annual or annually or even every paycheck).'></a>
                                                    <label>Goal:</label>
													<div class='align-right inline-block'>$<?php echo number_format($category_dm->getAmountNecessary(), 2, '.', ',') ?></div>
												</div>
												<div class="category-container">
                                                    <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="right" data-trigger="focus" data-content='This is the amount of money you currently have saved for this category.'></a>
                                                    <label>Amount Saved:</label>
													<div class='align-right inline-block'>$<?php echo number_format($category_dm->getCurrentAmount(), 2, '.', ',') ?></div>
												</div>
												<div class="category-container">
                                                    <a href="javascript:void(0)" class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="popover" data-placement="right" data-trigger="focus" data-content='This is the difference between amount of money you need and the amount you actually have in this category.'></a>
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
                                                <?php
                                                if($category_dm->getDueDay()) {
                                                    ?>
                                                    <div id="category_<?= $category_dm->getCategoryId() ?>">
                                                        <a href="javascript:void(0)"
                                                           class="glyphicon glyphicon-info-sign" aria-hidden="true"
                                                           data-toggle="popover" data-placement="right"
                                                           data-trigger="focus"
                                                           data-content='This is the date when you last made a payment to this category.'></a>
                                                        <label>Last Paid:</label>
                                                        <div class="align-right inline-block last_paid"></div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
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

    /**
     * @return string
     */
    private function showAddAccount() {
        $content = "<h3>Please add an Account</h3>"
                . "Now that you're registered you'll need to create an account to represent your bank account,"
                . " piggy bank, mayonaise jar or whatever it is you use to store your cash. Don't worry,"
                . " we don't ask for any financial or personal information, we are simply simulating your bank"
                . " account so we can help you organize it. in fact all we ask for is a name for your account"
                . " and how often you get paid. The name is so you know which account your working with and the pay"
                . " schedule is so we can calculate the best way to organize your money.";
        $content .= "<br><br><a class='btn btn-primary' href='/accountCTL/addNewAccount'>add a new Account</a>";

        if($this->link) {
            $content .= "<button id='link_button' class='btn btn-primary'>Link my Account</button>";
            $content .= $this->link->getAutoLoadIntegrationJs();
        }

        return $content;
    }

    /**
     * @return string
     */
    private function showAddCategories() {
        $content = "<h3>Please add Categories to this Account</h3>"
                . "<p>Now that you have an account created you can begin adding categories. We ask for a little bit more"
                . " information on these but we are still careful to not ask for any personal or financial details."
                . " You can add as many categories as you would like, for example, you might want a category for"
                . " rent/mortgage, and another for car payment, perhaps groceries and gas would be good to have."
                . " you can be as broad or as detailed as you want, whatever helps you take control of your finances.</p>";

        $content .= "<br><br><a class='btn btn-primary' href='/book/newBookForm'>add a category</a>";

        return $content;
    }

    /**
     * @param $last_transaction
     */
    public function setLastTransaction($last_transaction) {
			$this->last_transaction = $last_transaction;
		}

    /**
     * @param $last_update
     */
    public function setLastUpdate($last_update) {
			$this->last_update = $last_update;
		}

    /**
     * @param array $totals
     */
    public function setTotalsArray(array $totals) {
        $this->totals_array = $totals;
    }

    /**
     * @param \Plaid\Link $link
     * @return Budget_HomeVW
     */
    public function setLink(\Plaid\Link $link) {
        $this->link = $link;

        return $this;
    }

    /**
     * @param array $account_ids
     * @return $this
     */
    public function setLinkedAccounts(array $account_ids) {
        $this->linked_accounts = $account_ids;

        return $this;
    }

    /**
     * @param array $account_amounts
     * @return Budget_HomeVW
     */
    public function setAccountAmounts($account_amounts) {
        $this->account_amounts = $account_amounts;
        return $this;
    }
}
