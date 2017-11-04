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
			$due_date = 'Due Date';
			if($this->category_dm->getDueDay()) {
				$date = new DateTime(date('Y-m-').$this->category_dm->getDueDay());
				$due_date = $date->format('m/d/Y');
			}
			$dd_checked = $this->category_dm->getDueDay() === 0 ? ' checked' : null;
		?>
			<div>
				<h2>Edit <?php echo $this->category_dm->getCategoryName(); ?></h2>
				<div id="book-summary">
					<form class="inline" name="editBookForm" action="/book/saveChange/<?php echo $this->category_dm->getCategoryId(); ?>/" method="post">
						<?php
							$dif = bcsub($this->category_dm->getAmountNecessary(), $this->category_dm->getCurrentAmount());
						?>
						<div class="form-group">
							<input type="text" class="form-control required" name="name" id="name" value="<?= $this->category_dm->getCategoryName() ? $this->category_dm->getCategoryName() : 'Name'; ?>" />
						</div>
						<div class="form-group">
							<input type="text" class="required number money form-control" name="amtNec" id="amtNec" value="<?= $this->category_dm->getAmountNecessary() ? number_format($this->category_dm->getAmountNecessary(), 2, '.', ',') : 'Amount Due' ?>" />
						</div>
						<div class="form-group">
							<div class="input-group date" data-provide='datepicker'>
								<input type="text" class="form-control" name="dueDay" id="due_date" value="<?= $due_date; ?>" />
								<!--<input type="text" class="form-control" name="dueDay">-->
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input id='due_date_checkbox' type="checkbox" class="form-check-input" name="dueDay"<?=$dd_checked; ?>>
									No Due Date
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="form-group-label">
								<a href="javascript:void(null);" class="tool-tip" title="ctrl + click to select each month this category is due (ctrl + a to select all)">Months Due</a>
							</label>
							<?php
								$months = array(
									1 => "january",
									2 => "february",
									3 => "march",
									4 => "april",
									5 => "may",
									6 => "june",
									7 => "july",
									8 => "august",
									9 => "september",
									10 => "october",
									11 => "november",
									12 => "december");

								foreach($months as $index => $month) {
									$checked = '';
									if(in_array($index, $this->category_dm->getDueMonths())) {
										$checked = " checked";
									}
							?>
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="checkbox" class="form-check-input" name="due_months[<?= $index; ?>]"<?=$checked; ?>>
									<?= ucfirst($month); ?>
								</label>
							</div>
							<?php
								}
							?>
						</div>
						<input type="submit" value="save" class="btn btn-primary" />
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
			</div>
	<?php
		}

		public function setCategoryDM($category_dm) {
			$this->category_dm = $category_dm;
		}
	}