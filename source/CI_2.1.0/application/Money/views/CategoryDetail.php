    <h2>
        <a href="/book/editBook/<?php echo $bookId; ?>/"><?php echo $bookName; ?></a>
    </h2>

    <div id="account-general-info" class="accounts-dropdown input-group">
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

    <div id="book-summary" class='well'>
        <div class="left-half">
            <div class="bold"><?php echo $parentAccount->account_name; ?> Amount: $<?php echo number_format($parentAccount->account_amount,2,'.',','); ?></div>
        </div>

        <div id="category_form" class="section">
            <form name="bookEditForm" id="bookEditForm" action="/funds/updateAccountFunds/" method="post" style="margin:10px 0 20px 0">
                <input type="hidden" name="id" value="<?php echo $bookId; ?>" />
                <input type="hidden" name="parent_account" value="<?php echo $parentAccount->account_id;?>" />
				<div class="input-group date" data-provide="datepicker">
					<input type="text" class="form-control">
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</div>
				</div>
				<div class="input-group">
					<input type="text" name="description" class="form-control" id='description' autocomplete="off" />
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-pencil"></span>
					</div>
				</div>
				<div class="input-group">
					<input type="text" class="required number money form-control" name="amount" id='amount' autocomplete="off" />
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-usd"></span>
					</div>
				</div>
				<div class="input-group">
					<select class="required form-control" name="operation">
						<option value="0">-- Select Operation --</option>
						<option value="addFromBucket">Credit</option>
						<option value="deduction" selected="selected">Debit</option>
						<option value="refund">Refund</option>
					</select>
				</div>
				<div class="input-group">
					<input id="refund" type="text" class="numeric" name="refundId" value="Refund Transaction ID" />
				</div>
                <div id="submit" class="input-group">
					<input type="submit" value="Submit" class="form-control" />
				</div>
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
		<?= isset($transactions) ? $transactions : null; ?>
	</div>
