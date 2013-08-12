	<?php
	if($due_day > 0) {
		if( date('d') < $due_day ) {//TODO add logic for classes to determine if paid or not
			$due_date = date('m/d/Y', mktime(0,0,0,date('m'), $due_day, date('Y')));
			$class = 'unpaid';
		} else if( date('d') > $due_day ) {
			$due_date = date('m/d/Y', mktime(0,0,0,date('m')+1, $due_day, date('Y')));
			$class = 'paid';
		} else {
			$due_date = "TODAY!";
			$class = 'due';
		}
	}
	?>
	<h1><?php echo $bookName; ?></h1>
	<div id="edit"><a class="button border" href="/book/editBook/<?php echo $bookId; ?>/">Edit <?php echo $bookName; ?></a></div>
	<div id="account-general-info">
		<div class="left half">
			<div class="bucket-amount">
				<?php echo $parentAccount->account_name; ?> Amount: $<?php echo number_format($parentAccount->account_amount,2,'.',','); ?>
			</div>
			<div class="due-day">
				<?php
				if(isset($due_date)) {
				?>
				Due Date: <span class="<?php echo $class; ?>"><?php echo $due_date; ?></span>
				<?php
				} else echo "&nbsp";
				?>
			</div>
		</div>
		<div class="left">
			<div class="accounts-dropdown">
				Go To:
				<select name="accounts_select">
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
		</div>
	</div>
	<div class="clear">&nbsp;</div>
	<div id="book-summary">
	<?php
		$dif = $bookAmtNec - $bookAmtCurrent;
		if($dif > 0) {
			$class = 'red';
			$symbol = '-';
		} elseif($dif < 0) {
			$class = 'bold';
			$symbol = '+';
		} else {
			$class = '';
			$symbol = '';
		}
	?>
		<div class="dashed-bottom">
			<div class="label bold main_color">Account:</div>
			<div class="align-right bold main_color italics"><?php echo $bookName; ?></div>
		</div>
		<div class="dashed-bottom">
			<div class="label bold main_color">Goal:</div>
			<div class="align-right bold"><?php echo number_format($bookAmtNec,2,'.',','); ?></div>
		</div>
		<div class="dashed-bottom">
			<div class="label bold main_color">Saved:</div>
			<div class="align-right<?php if($bookAmtCurrent >= $bookAmtNec) echo ' bold'; ?>"><?php echo number_format($bookAmtCurrent,2,'.',','); ?></div>
		</div>
		<div class="dashed-bottom">
			<div class="label bold main_color">Difference:</div>
			<div class="align-right <?php echo $class;?>"><?php echo $symbol.number_format($dif,2,'.',','); ?></div>
		</div>
		<form name="bookEditForm" id="bookEditForm" action="/funds/updateAccountFunds/" method="post">
			<input type="hidden" name="id" value="<?php echo $bookId; ?>" />
			<input type="hidden" name="parent_account" value="<?php echo $parentAccount->account_id;?>" />
			<div class="form-element-group">
				<div class="form-element">
					<div class="label bold ta-right">Date:</div>
					<div>
						<input type="text" name="date" autocomplete="off" /> yyyy-mm-dd
					</div>
				</div>
				<div class="form-element">		
					<div class="label bold ta-right">Description:</div>
					<div>
						<textarea name="description" rows="1" cols="20"></textarea>
					</div>
				</div>
				<div class="form-element">		
					<div class="label bold ta-right">
						<span class="error">*</span>Amount:
					</div>
					<div><input type="text" class="required number" name="amount" autocomplete="off" /></div>
				</div>
			</div>
			<div class="radio-group">
				<span class="error" style="position:absolute; left:10px; top:0;">*</span>
				<div class="label bold ta-right">
					<label for="add_from_account">Add Funds from <?php echo $parentAccount->account_name; ?>: </label>
					<br />
					<label for="deduction">Deduction: </label>
					<br />
					<label for="refund_radio">Refund: </label>
				</div>
				<div>
					<input type="radio" id="add_from_account" class="required" name="operation" value="addFromBucket" />
				</div>
				<div>
					<input type="radio" id="deduction" class="required" name="operation" value="deduction" checked="checked" />
				</div>
				<div>
					<input type="radio" id="refund_radio" class="required" name="operation" value="refund" />
					<span id="refund">Refund Transaction ID: <input type="text" name="refundId" /></span>
				</div>
			</div>
			<div id="submit"><input type="submit" value="Submit" /></div>
		</form>
	</div>
