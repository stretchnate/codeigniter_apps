	<h2>Add Funds Form</h2>
	<div id="new-book">
	<?php if(isset($error)) echo $error; ?>
		<form name="newFundsForm" id="newFundsForm" action="" method="post">
			<input type="hidden" name="job" />
			<div class="form-group">
				<div class='input-group date' data-provide="datepicker">
					<input type="text" class="form-control" name="date">
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</div>
				</div>
			</div>
			<div class='form-group'>
				<input type="text" class="required form-control" name="source" id="source" value="Source" />
			</div>
			<div class='form-group'>
				<input type="text" class="required number money form-control" name="gross" id="gross_amount" value="Gross Amount" />
			</div>
			<div class='form-group'>
				<input type="text" name="net" id="net_amount" class="number money form-control" value="Net Amount" />
			</div>
			<div class='form-group'>
				<select name="account" class="required form-control">
					<option value="">- - Select Account - -</option>
					<?
					foreach($accounts as $account) {
					?>
					<option value="<?php echo $account->account_id;?>"><?php echo $account->account_name;?></option>
					<?
					} ?>
				</select>
			</div>
			<div class='form-check'>
				<label class='form-check-label'>
					<input type="checkbox" name="manual" id="manual" class='form-check-input' /> Manually distribute to Categories
				</label>
			</div>
			<input type="submit" value="Submit" class="btn btn-primary" />
		</form>
	</div>
