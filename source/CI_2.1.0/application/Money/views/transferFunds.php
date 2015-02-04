	<div id="book-summary">
	<h2>Transfer Funds</h2>
	<form name="transferForm" id="transferForm" action="/fundsTransferCTL/transferFunds/" method="post">
		<table>
			<tr>
				<td>Between:</td>
				<td>
					<input type="radio" name="transfer-funds" id="from-accounts-radio" value="from-accounts-radio" />
					<label for="from-accounts-radio">Accounts</label>

					<input type="radio" name="transfer-funds" id="from-categories-radio" value="from-categories-radio" checked="checked" />
					<label for="from-categories-radio">Categories</label>
				</td>
			</tr>
			<tr>
				<td><span class="error">*</span>From:</td>
				<td>
					<div class="transfer-categories">
						<select name="from" id="from" class="required">
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
						<select name="from-accounts">
							<option value="">- - Select Account - -</option>
							<?php
							foreach($accounts as $account){
							?>
								<option value="<?php echo $account->account_id;?>"><?php echo $account->account_name." (has $".number_format($account->account_amount,2).")";?></option>
							<?

							}
							?>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td><span class="error">*</span>To:</td>
				<td>
					<div class="transfer-categories">
						<select name="to" id="to" class="required">
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
						<select name="to-accounts">
							<option value="">- - Select Account - -</option>
							<?php
							foreach($accounts as $account){
							?>
								<option value="<?php echo $account->account_id;?>"><?php echo $account->account_name;?></option>
							<?

							}
							?>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td><span class="error">*</span>Amount:</td>
				<td><input type="text" class="required money" name="amount" /></td>
			<tr>
				<td colspan="2">
					<input type="submit" value="Transfer" />
				</td>
			</tr>
		</table>
	</form>
	</div>
