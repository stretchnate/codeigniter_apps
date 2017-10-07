    <h2>New Account Form</h2>
	<div class="formResult result">
		<span id="ajax-load"><img src='<?php echo IMG_PATH; ?>ajax-loader.gif' alt='loading...' /></span>
		<span id="result-message">&nbsp;</span>
	</div>
	<div id="new-book">
		<form name="newAccountForm" id="newAccountForm" action="/accountCTL/createNewAccount/" method="post">
			<div class="form-group">
				<input type="text" name="name" id="name" value='Account Name' class='required form-control' />
			</div>
			<div class="form-group">
				<select name="pay_schedule" class="required form-control">
					<option value="">- - How Often are you paid - -</option>
					<option value="1">Bi-Weekly</option>
					<option value="2">Weekly</option>
					<option value="3">Twice a Month</option>
					<option value="4">Monthly</option>
				</select>
			</div>
			<div class="form-group">
				<input type="submit" value="Add Account" class='form-control' />
			</div>
		</form>
	</div>