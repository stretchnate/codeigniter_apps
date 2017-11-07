    <h2>New Account Form</h2>
	<div class="formResult result">
		<span id="ajax-load"><img src='<?php echo IMG_PATH; ?>ajax-loader.gif' alt='loading...' /></span>
		<span id="result-message">&nbsp;</span>
	</div>
	<div id="new-book">
		<form name="newAccountForm" id="newAccountForm" action="/accountCTL/createNewAccount/" method="post">
			<div class="form-group">
				<input type="text" name="name" id="name" value='Name' class='form-control' required>
			</div>
			<div class="form-group">
				<select name="pay_schedule" class="form-control" required>
					<option value="">- - How Often are you paid - -</option>
					<option value="1">Every two weeks</option>
					<option value="2">Weekly</option>
					<option value="3">Twice a Month</option>
					<option value="4">Monthly</option>
				</select>
			</div>
			<button id='add_account' class='btn btn-primary'>Add Account</button>
		</form>
	</div>