    <h2>New Account Form</h2>
	<div class="formResult result">
		<span id="ajax-load"><img src='<?php echo IMG_PATH; ?>ajax-loader.gif' alt='loading...' /></span>
		<span id="result-message">&nbsp;</span>
	</div>
	<div id="new-book">
		<form name="newAccountForm" id="newAccountForm" action="/accountCTL/createNewAccount/" method="post">
			<table>
				<tr>
					<td>
						<span class="error">*</span>
						<label for="name">Account Name: </label>
					</td>
					<td><input type="text" name="name" id="name" /><br /></td>
					<td><div class="result ajaxResult"></div></td>
				</tr>
				<tr>
					<td>
						<span class="error">*</span>
						<label for="pay_schedule">How Often are you paid:</label>
					</td>
					<td>
						<select name="pay_schedule" class="required">
							<option value="">- - Please Select - -</option>
							<option value="1">Bi-Weekly</option>
							<option value="2">Weekly</option>
							<option value="3">Twice a Month</option>
							<option value="4">Monthly</option>
						</select>
					</td>
					<td><div id="amt-message" class="result">&nbsp;</div></td>
				</tr>
			</table>
			<input type="submit" value="Add Account" />
		</form>
	</div>