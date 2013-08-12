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
				echo "<p>{$error}</p>";
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