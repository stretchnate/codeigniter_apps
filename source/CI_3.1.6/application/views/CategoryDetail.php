    <div>
		<?php
		if($parentAccount->account_amount > 0) {?>
		<div class="bold">
			<?php echo $parentAccount->account_name; ?> Amount: $<?php echo number_format($parentAccount->account_amount,2,'.',','); ?>
		</div>
		<?php
		} ?>
		<form action='' method='post' class='form-inline'>
			<div id="account-general-info" class="accounts-dropdown form-group">
				<label>Go To:</label>
				<select name="accounts_select" class="form-control">
				<?php
					foreach($accounts as $account) {?>
					<optgroup label="<?php echo $account->account_name;?>">
					<?
						foreach($account->categories as $category) {
							$selected = "";
							if($category->bookId == $bookId) {
								$selected = " selected='selected'";
							}
					?>
						<option value="<?php echo $category->bookId; ?>"<?php echo $selected; ?>><?php echo $category->bookName; ?></option>
					<?php
						}
					}
				?>
				</select>
			</div>
		</form>
	</div>

	<?php
		$due_date = sprintf(" <small class='text-muted'>(%s)</small>", $due_date->format('m/d/Y'));
	?>

	<h2>
        <a href="/book/editBook/<?php echo $bookId; ?>/">
			<?php echo $bookName . $due_date; ?>

		</a>
    </h2>

	<div class="section">
		<?php
		$dif = $bookAmtNec - $bookAmtCurrent;
		if($dif > 0) {
			$class = 'text-danger';
			$symbol = '-';
		} elseif($dif < 0) {
			$class = 'bold';
			$symbol = '+';
		} else {
			$class = '';
			$symbol = '';
		}
		?>
		<div class="row">
			<div class="col-xs-6">Goal:</div>
			<div class="col-xs-6 text-right"><?php echo number_format($bookAmtNec,2,'.',','); ?></div>
		</div>
		<div class="row">
			<div class="col-xs-6">Saved:</div>
			<div class="col-xs-6 text-right"><?php echo number_format($bookAmtCurrent,2,'.',','); ?></div>
		</div>
		<div class="row">
			<div class="col-xs-6">Difference:</div>
			<div class="col-xs-6 text-right"><?php echo $symbol.number_format($dif,2,'.',','); ?></div>
		</div>
	</div>

    <div id="book-summary">
        <div id="category_form" class="section">
            <form name="bookEditForm" id="bookEditForm" action="/funds/updateAccountFunds/" method="post" style="margin:10px 0 20px 0">
				<div class="form-group">
					<input type="hidden" name="id" value="<?php echo $bookId; ?>" />
					<input type="hidden" name="parent_account" value="<?php echo $parentAccount->account_id;?>" />
					<div class="input-group date">
						<input type="date" class="form-control" name='date' id="date" value="" placeholder="Date" autocomplete="off">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<input type="text" name="description" class="form-control" id='description' placeholder="Description" value="" autocomplete="off" />
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-pencil"></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<input type="number" class="required number money form-control" name="amount" id='amount' placeholder="Amount" step="0.01" autocomplete="off" />
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-usd"></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<select class="required form-control" name="operation">
						<option value="0">-- Select Operation --</option>
						<option value="deduction" selected="selected">Debit (-)</option>
						<option value="addFromBucket">Credit (+)</option>
						<option value="refund">Refund (+)</option>
					</select>
				</div>
				<div class="form-group">
					<input id="refund" type="text" class="numeric" name="refundId" value="Refund Transaction ID" />
				</div>
				<input type="submit" value="Submit" class="btn btn-primary" />
            </form>
        </div>

		<?= isset($transactions) ? $transactions : null; ?>
	</div>
