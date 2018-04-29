	<h2>Add Funds</h2>
	<div id="new-book">
	<?php if(isset($error)) echo $error; ?>
		<form name="newFundsForm" id="newFundsForm" action="" method="post">
			<input type="hidden" name="job" />
			<div class="form-group">
				<div class='input-group date'>
					<input type="date" class="form-control" name="date" autocomplete="off">
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</div>
				</div>
			</div>
			<div class='form-group'>
				<input type="text" placeholder="Source" class="form-control" name="source" id="source" value="" required>
			</div>
			<div class='form-group'>
				<input type="number" step="0.01" placeholder="Gross Amount" class="money form-control" name="gross" id="gross_amount" value="" required>
			</div>
			<div class='form-group'>
				<input type="number" step="0.01" placeholder="Net Amount" name="net" id="net_amount" class="money form-control" value="" required>
			</div>
			<div class='form-group'>
				<select name="account" class="form-control" required>
					<option value="">- - Select Account - -</option>
					<?php
					foreach($accounts as $account) {
					?>
					<option value="<?php echo $account->account_id;?>"><?php echo $account->account_name;?></option>
					<?php
					} ?>
				</select>
			</div>
			<div class='form-check'>
				<label class='form-check-label'>
					<input type="checkbox" name="manual" id="manual" class='form-check-input'> Manually distribute to Categories
				</label>
			</div>
			<input type="submit" value="Submit" class="btn btn-primary" />
		</form>
	</div>
