    <h2>
        <a href="/book/editBook/<?php echo $bookId; ?>/"><?php echo $bookName; ?></a>
    </h2>

    <div id="account-general-info" class="accounts-dropdown">
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

    <div id="book-summary" class='well'>
        <div class="left-half">
            <div class="bold"><?php echo $parentAccount->account_name; ?> Amount: $<?php echo number_format($parentAccount->account_amount,2,'.',','); ?></div>
        </div>

        <div id="category_form" class="section">
            <form name="bookEditForm" id="bookEditForm" action="/funds/updateAccountFunds/" method="post" style="margin:10px 0 20px 0">
                <input type="hidden" name="id" value="<?php echo $bookId; ?>" />
                <input type="hidden" name="parent_account" value="<?php echo $parentAccount->account_id;?>" />
				<div class="row">
					<div class='col-xs-3'>
						<label for='date'>Date:</label>
					</div>
					<div class='col-xs-3'>
						<input type="text" name="date" id='date' autocomplete="off" />
					</div>
				</div>
				<div class="row">
					<div class='col-xs-3'>
						<label for='description'>Description:</label>
					</div>
					<div class='col-xs-3'>
						<input type="text" name="description" id='description' autocomplete="off" />
					</div>
				</div>
				<div class="row">
					<div class='col-xs-3'>
						<label for='amount'>
							Amount:
						</label>
					</div>
					<div class='col-xs-3'>
						<input type="text" class="required number money" name="amount" id='amount' autocomplete="off" />
					</div>
				</div>
				<div class="row">
					<div class='col-xs-3'>
						<label for="add_from_account" title='Add Funds from <?php echo $parentAccount->account_name; ?>'>Credit: </label>
					</div>
					<div class='col-xs-3'>
						<input type="radio" id="add_from_account" class="required" name="operation" value="addFromBucket" />
					</div>
				</div>
				<div class='row'>
					<div class='col-xs-3'>
						<label for="deduction" title='Deduct Funds from <?php echo $parentAccount->account_name; ?>'>Debit: </label>
					</div>
					<div class='col-xs-3'>
						<input type="radio" id="deduction" class="required" name="operation" value="deduction" checked="checked" />
					</div>
				</div>
				<div class='row'>
					<div class='col-xs-3'>
					<label for="refund_radio">Refund: </label>
					</div>
					<div class='col-xs-3'>
						<input type="radio" id="refund_radio" class="required" name="operation" value="refund" />
						<span id="refund">Refund Transaction ID: <input type="text" name="refundId" /></span>
					</div>
				</div>
                <div id="submit"><input type="submit" value="Submit" /></div>
            </form>
        </div>

        <div class="section">
            <div class="row">
                <div class="col-xs-3">Category:</div>
                <div class="col-xs-3"><?php echo $bookName; ?></div>
            </div>
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

            if(isset($due_date)) {
            ?>
                <div class="row">
                    <div class="col-xs-3">Due Date:</div>
                    <div class="col-xs-3"><span class="<?php echo $class; ?>"><?php echo $due_date; ?></span></div>
                </div>
            <?php
            }

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
            <div class="row">
                <div class="col-xs-3">Goal:</div>
                <div class="col-xs-3"><?php echo number_format($bookAmtNec,2,'.',','); ?></div>
            </div>
            <div class="row">
                <div class="col-xs-3">Saved:</div>
                <div class="col-xs-3"><?php echo number_format($bookAmtCurrent,2,'.',','); ?></div>
            </div>
            <div class="row">
                <div class="col-xs-3">Difference:</div>
                <div class="col-xs-3"><?php echo $symbol.number_format($dif,2,'.',','); ?></div>
            </div>
        </div>
	</div>
