<?php
//Set no caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html PUBLIC  "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Register</title>
    <link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>ui-lightness/jquery-ui-1.8.2.custom.css" />
	<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-1.4.2.min.js"></script>
	<script type='text/javascript' src='<?php echo JS_PATH; ?>jquery-ui-1.8.2.custom.min.js'></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>register.js"></script>
</head>
<body>
	<div id="header">
		<!-- <h1><a href=""><img src="/images/logo.png" alt="The Header" /></a></h1> -->
		<div id="date"><?php echo date('l, F j'); ?></div>
	</div>

	<div id="container">
		<?php $date = date('l, F jS H:ia'); ?>
		<h1>Register</h1>
		<?php
			//echo validation_errors();
			if(isset($error))
				echo "<div class='error'>".$error."</div>";
		?>
		<div>
			<form name="registerForm" id="registerForm" action="/admin/registerUser/" method="post">
				<div id="main" style="width:50%;">
					<p>
						<span class="error">*</span>Username:<br />
						<input type="text" id="username" class="required" name="username" size="32" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" /><span class="ajaxResult result">&nbsp;</span>
					</p>
					<p>
						<span class="error">*</span>Password:<br />
						<input type="password" id="password" class="required" name="password" size="32" value="" />
					</p>
					<p>
						<span class="error">*</span>Confirm Password:<br />
						<input type="password" id="confirmPassword" class="required" name="confirmPassword" size="32" value="" />
					</p>
					<p>
						<span class="error">*</span>Email:<br />
						<input type="text" id="email" class="required email" name="email" size="32" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" /><br />
						<input type="hidden" name="charitable" value="0" />
					</p>
					<!--p>
						<span class="error">*</span>Pay Schedule:<br />
						<select name="paySchedule">
							<option value="1">Bi-Weekly</option>
							<option value="2">Weekly</option>
							<option value="3">Twice a Month</option>
							<option value="4">Monthly</option>
						</select>
					</p-->
<!--					<p>	-->
<!--						Would you like a <a href="javascript:void(null)" class="tool-tip" title="can be used to save for charitable donations to churches, charity's etc.">-->
<!--									charitable contributions-->
<!--								</a>&nbsp;-->
<!--						account created?<br />-->
<!--						<input type="radio" name="charitable" value="0"<?php //if(!isset($_POST['charitable']) || $_POST['charitable'] == 0) echo " checked='checked'"; ?> />-->
<!--						&nbsp;-->
<!--						No-->
<!--						&nbsp;&nbsp;&nbsp;-->
<!--						<input type="radio" name="charitable" value="1"<?php //if(isset($_POST['charitable']) && $_POST['charitable'] == 1) echo " checked='checked'"; ?> />-->
<!--						&nbsp;-->
<!--						Yes-->
<!--					</p>-->
					<div>
						<label for="terms">Terms and Conditions:</label><br/>
						<textarea id="terms" cols="80" rows="20" readonly="readonly"><?php
							?>This is a budget management website, it will not make you rich, good looking or popular. It simply allows you to track your spending, much like a checkbook register (if you are old enough to know what that is). We will not share your information, we simply ask for an email so we can contact you if there is a problem. as of 4/6/2014 we haven't had to use anyone's email for any reason, granted there have only been two people using this site in that time frame so things could change.

<?php						?>You may use this site at your own risk. Just so you are aware what the risks are, the data for this site is backed up twice a week, in the event of a failure the data will be restored to the last good backup, which means you could lose some of your information, it will be up to you to re-insert said information. Also if the site gets a lot of traffic we could experience some heavy bandwidth usage which may result in slowness, if that happens we'll look into hosting the site on a better server/location. You may stop using the site at any time, we don't really care.

<?php						?>We do not collect bank account information for any reason, in fact the only information we collect that could identify you is your email address, of which could be fake as far as we know, it makes no difference to us. The reason for this is so we don't have to concern ourselves with protecting your data, if someone hacks our system all they will get is a bunch of hypothetical account/category names and some numbers. They might get an idea of how much money you make but they won't know where you keep it unless your account/category names identify the bank/account which you keep it in. So we advise you to be smart about naming accounts/categories once your registration is complete.

<?php						?>Now for the CYA stuff. money.stretchnate.co and it's affiliates will not be held responsible for any loss or damage to you, your financial future or your current financial situation that results in using money.stretchnate.co. As was previously stated, you may use this site at your own risk, we are not responsible for anything that happens as a result of you using money.stretchnate.co

<?php						?>By clicking "I agree" you are agreeing to these terms.
						</textarea>
						<span class="error">*</span>I agree:&nbsp;
						<input type="checkbox" id="agree_to_terms" class="required" name="agree_to_terms" />
					</div>
				</div>
				<!--<div id="charitableAcct">
					<p class="fat">
						Charitable Account Name:
						<input type="text" name="caName" value="<?php //if(isset($_POST['caName'])) echo $_POST['caName']; ?>" />
					</p>
					<p class="fat">
						How shall we calculate your contributions?
						<select name="calc" id="calc">
							<option value="1"<?php //if(isset($_POST['calc']) && $_POST['calc'] == 1) echo " selected='selected'"; ?>>Percentage %</option>
							<option value="2"<?php //if(isset($_POST['calc']) && $_POST['calc'] == 2) echo " selected='selected'"; ?>>Set Amount</option>
							<option value="3"<?php //if(!isset($_POST['calc']) || $_POST['calc'] == 3) echo " selected='selected'"; ?>>I'll do it manually</option>
						</select>
					</p>
					<p class="fat">
						How much per month do you want contributed?<br />
						<input type="text" name="multiplier" value="<?php if(isset($_POST['multiplier'])) echo $_POST['multiplier']; ?>" />
					</p>
					<p class="fat">
						What is the <a href="javascript:void(null)" class="tool-tip" title="i.e. in what order do you want your check deducted for this account?">Priority</a> of this account?
						<select name="priority" id="priority">
							<option value="1">1 (first)</option>
							<?php
//							for($i = 2;$i < 10;$i++) {
//								echo "<option value='{$i}'>{$i}</option>\n";
//							}
							?>
							<option value="10">10 (last)</option>
						</select>
					</p>
				</div>-->
				<p class="clear">&nbsp;</p>
				<p style="margin-top:10px;">
					<input type="submit" name="register" value="register" />
				</p>
			</form>
		</div>
		<a href="/admin/login/">Back to Login</a>
	</div>
	<div id="footer">
		<div id="copy">Copyright&copy; <?php $year = date('Y'); echo $year;?> Me.</div>
	</div>
</body>
</html>