	<h1>Add Funds Form</h1>
	<div id="new-book">
	<?php if(isset($error)) echo $error; ?>
		<form name="newFundsForm" id="newFundsForm" action="" method="post">
			<input type="hidden" name="job" />
			<table>
				<tr>
					<td><label for="date">Date: </label></td>
					<td><input type="text" name="date" />yyyy-mm-dd</td>
				</tr>
				<tr>
					<td>
						<span class="error">*</span>
						<label for="source">Source: </label>
					</td>
					<td>
						<input type="text" class="required" name="source" />
					</td>
				</tr>
				<tr>
					<td>
						<span class="error">*</span>
						<label for="gross">Gross Amount: </label>
					</td>
					<td>
						$<input type="text" class="required number" name="gross" /> (before taxes)
					</td>
				</tr>
				<tr>
					<td><label for="net">Net Amount: </label></td>
					<td>$<input type="text" name="net" id="net" class="number" /> (after taxes)</td>
				</tr>
				<tr>
					<td>
						<span class="error">*</span>
						<label for="account">Into Account</label>
					</td>
					<td>
						<select name="account" class="required">
							<option value="">- - Select Account - -</option>
							<?
							foreach($accounts as $account) {
							?>
							<option value="<?php echo $account->account_id;?>"><?php echo $account->account_name;?></option>
							<?
							} ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">Manual <input type="checkbox" name="manual" id="manual" /></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value="Submit" />
					</td>
				</tr>
			</table>
		</form>
	</div>
