<?php
	require_once(APPPATH.'/views/budget/baseVW.php');

	class Budget_Category_EditCategoryVW extends Budget_BaseVW {

		private $category_dm;

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
			<h2>Edit <?php echo $this->category_dm->getCategoryName(); ?></h2>
			<div id="book-summary">
				<form name="editBookForm" action="/book/saveChange/<?php echo $this->category_dm->getCategoryId(); ?>/" method="post">
					<table>
						<?php
							$dif = bcsub($this->category_dm->getAmountNecessary(), $this->category_dm->getCurrentAmount());
						?>
						<tr>
							<td><label for="name"><span class="error">*</span>Account</label></td>
							<td><input type="text" class="required" name="name" id="name" value="<?php echo $this->category_dm->getCategoryName(); ?>" /></td>
						</tr>
						<tr>
							<td><label for="amtNec"><span class="error">*</span>Amount Necessary</label></td>
							<td>$<input type="text" class="required number" name="amtNec" id="amtNec" value="<?php echo number_format($this->category_dm->getAmountNecessary(), 2, '.', ',') ?>" /></td>
						</tr>
						<tr>
							<td>
								<label for="dueDay">
									<span class="error">*</span>
									<a href="javascript:void(null);" class="tool-tip" title="This is the day of the month this bill is due (enter 0 to max this account each paycheck)">
										Due Day
									</a>
								</label>
							</td>
							<td>
								<input type="text" class="required number" name="dueDay" id="dueDay" value="<?php echo $this->category_dm->getDueDay(); ?>" />
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
										$selected = '';
										if(in_array($index, $this->category_dm->getDueMonths())) {
											$selected = " selected='selected'";
										}
								?>
									<option value="<?= $index; ?>"<?=$selected; ?>><?= $month; ?></option>
								<?php
									}
								?>
								</select>
							</td>
						</tr>
						<tr><td>Amount Saved</td><td>$<?php echo number_format($this->category_dm->getCurrentAmount(), 2, '.', ',') ?></td></tr>
						<tr><td>Difference</td><td>$<?php echo number_format($dif, 2, '.', ',') ?></td></tr>
					</table>
					<input type="submit" value="save" />
				</form>
				<?php
					if($this->category_dm->getActive() == 1){
				?>
					<a href="javascript:changeConfirm('disable',<?php echo $this->category_dm->getCategoryId(); ?>);">Disable</a> <?php echo $this->category_dm->getCategoryName(); ?>
				<?php
					} elseif($this->category_dm->getActive() == 0){
				?>
					<a href="javascript:changeConfirm('enable',<?php echo $this->category_dm->getCategoryId(); ?>);">Enable</a> <?php echo $this->category_dm->getCategoryName(); ?>
				<?php
					}
				?>
			</div>
			<div id="transactions">
				<table border="0" cellpadding="3">
					<?php
		//				$this->BI->showTrans($bookId);
					?>
				</table>
			</div>
			<div class="clear"></div>
	<?php
		}

		public function setCategoryDM($category_dm) {
			$this->category_dm = $category_dm;
		}
	}