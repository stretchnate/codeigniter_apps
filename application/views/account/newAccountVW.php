    <h2>Add a new Account</h2>
	<div class="formResult result">
		<span id="ajax-load"><img src='<?php echo IMG_PATH; ?>ajax-loader.gif' alt='loading...' /></span>
		<span id="result-message">&nbsp;</span>
	</div>
	<div id="new-book">
		<form name="newAccountForm" id="newAccountForm" action="/accountCTL/saveAccount/" method="post">
			<div class="form-group">
				<input type="hidden" name="account_id" value='<?= isset($account_dm) ? $account_dm->getAccountId() : null; ?>'>
				<input type="text" name="name" id="name" placeholder="Name" value='<?= isset($account_dm) ? $account_dm->getAccountName() : ""; ?>' class='form-control' required>
			</div>
            <div class="form-group">
                <select name="account_type" class="form-control" required>
                    <option value="checking">Checking</option>
                    <option value="savings">Savings</option>
                </select>
            </div>
			<div class="form-group">
			<?php
				$options = array(
					1 => 'Every two weeks',
					2 => 'Weekly',
					3 => 'Twice a Month',
					4 => 'Monthly'
				);
			?>
				<select name="pay_schedule" class="form-control" required>
					<option value="">-- How often are you paid --</option>
				<?php
				foreach($options as $value => $text) {
					$selected = null;
					if(isset($account_dm) && $account_dm->getPayScheduleCode() == $value) {
						$selected = ' selected';
					}
					echo "<option value='$value'$selected>$text</option>";
				}
				?>
				</select>
			</div>
			<button id='add_account' class='btn btn-primary'>Save</button>
		</form>
	</div>