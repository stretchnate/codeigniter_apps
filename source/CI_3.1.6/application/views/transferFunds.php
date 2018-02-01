	<div id="book-summary">
	<h2>Transfer Funds</h2>
	<form name="transferForm" id="transferForm" action="/fundsTransferCTL/transferFunds/" method="post">
		<div class='form-group'>
			<div class="form-group-label">Between:</div>
			<div class="form-check form-check-inline">
				<label class="form-check-label">
					<input type="radio" name="transfer-funds" id="from-accounts-radio" value="from-accounts-radio" class="form-check-input" />
					Accounts
				</label>
				<label class="form-check-label">
					<input type="radio" name="transfer-funds" id="from-categories-radio" value="from-categories-radio" checked="checked" class="form-check-input" />
					Categories
				</label>
			</div>
		</div>
		<div class='form-group'>
			<div class="transfer-categories">
				<select name="from" id="from" class="required form-control">
					<option>-- From --</option>
				<?php
				foreach($accounts as $account){
				?>
					<optgroup label="<?php echo $account->account_name;?>">
				<?
					foreach($account->categories as $category) {
						if(!empty($category->bookName)){
							echo "<option value='{$category->bookId}'>{$category->bookName} (has $".number_format($category->bookAmtCurrent,2).")</option>\n";
						}
					}
				}
				?>
				</select>
			</div>
			<div class="transfer-accounts">
				<select name="from-accounts" class="form-control">
					<option value="">-- From Account --</option>
					<?php
					foreach($accounts as $account){
					?>
						<option value="<?php echo $account->account_id;?>"><?php echo $account->account_name." (has $".number_format($account->account_amount,2).")";?></option>
					<?

					}
					?>
				</select>
			</div>
		</div>
		<div class='form-group'>
			<div class="transfer-categories">
				<select name="to" id="to" class="required form-control">
					<option>-- To --</option>
				<?php
				foreach($accounts as $account){
				?>
					<optgroup label="<?php echo $account->account_name;?>">
				<?
					foreach($account->categories as $category) {
						if(!empty($category->bookName)){
							$need = $category->bookAmtNec - $category->bookAmtCurrent;
							echo "<option value='{$category->bookId}'>{$category->bookName} (needs $".number_format($need,2).")</option>\n";
						}
					}
				}
				?>
				</select>
			</div>
			<div class="transfer-accounts">
				<select name="to-accounts" class="form-control">
					<option value="">- - To Account - -</option>
					<?php
					foreach($accounts as $account){
					?>
						<option value="<?php echo $account->account_id;?>"><?php echo $account->account_name;?></option>
					<?

					}
					?>
				</select>
			</div>
		</div>
		<div class='form-group'>
			<input type="number" step="0.01" placeholder="Amount" class="required money form-control" name="amount" id="amount" value="" />
		</div>
		<input type="submit" value="Transfer" class="btn btn-primary" />
	</form>
	</div>
