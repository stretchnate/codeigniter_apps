<?php
//Set no caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quantum - User Registration</title>
	<meta charset='utf-8'>
	<!-- ensure proper mobile rendering and touch zooming with the following tag -->
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>main.css" />
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>register.js"></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>utilities.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div id="header">
			<h1>
				<a href="/"><img id="logo" class="navbar-brand" src="/images/logo.png" alt="<?= COMPANY_NAME; ?>" /></a>
			</h1>
		</div>
		<h2>Register Here</h2>
		<?php
			//echo validation_errors();
			if(isset($error))
				echo "<div class='text-danger'>".$error."</div>";
		?>
		<div>
			<form name="registerForm" id="registerForm" action="/admin/registerUser/" method="post">
				<div class='form-group'>
					<input type="text" id="username" class="form-control" name="username" size="32" value="<?= isset($_POST['username']) ? $_POST['username'] : 'Username'; ?>" required>
					<span class="ajaxResult result">&nbsp;</span>
				</div>
				<div class='form-group'>
					<input type="password" id="password" class="form-control" name="password" size="32" value="Password" required>
				</div>
				<div class='form-group'>
					<input type="password" id="confirm_password" class="form-control" name="confirm_password" size="32" value="Confirm Password" required>
				</div>
				<div class='form-group'>
					<input type="text" id="email" class="email form-control" name="email" size="32" value="<?= isset($_POST['email']) ? $_POST['email'] : 'Email'; ?>" required>
					<input type="hidden" name="charitable" value="0" />
				</div>

				<div class='form-group'>
					<div class='form-check'>
						<label class='form-check-label'>
							<input type="checkbox" id="agree_to_terms" class="form-check-input" name="agree_to_terms" required>
							Terms and Conditions
						</label>
					</div>
					<textarea id="terms" cols="80" rows="20" readonly class='form-control'><?php
						?>This is a budget management website, it will not make you rich, good looking or popular. It simply allows you to track your spending, much like a checkbook register (if you are old enough to know what that is). We will not share your information, we simply ask for an email so we can contact you if there is a problem. as of 4/6/2014 we haven't had to use anyone's email for any reason, granted there have only been two people using this site in that time frame so things could change.

<?php						?>You may use this site at your own risk. Just so you are aware what the risks are, the data for this site is backed up twice a week, in the event of a failure the data will be restored to the last good backup, which means you could lose some of your information, it will be up to you to re-insert said information. Also if the site gets a lot of traffic we could experience some heavy bandwidth usage which may result in slowness, if that happens we'll look into hosting the site on a better server/location. You may stop using the site at any time, we don't really care.

<?php						?>We do not collect bank account information for any reason, in fact the only information we collect that could identify you is your email address, of which could be fake as far as we know, it makes no difference to us. The reason for this is so we don't have to concern ourselves with protecting your data, if someone hacks our system all they will get is a bunch of hypothetical account/category names and some numbers. They might get an idea of how much money you make but they won't know where you keep it unless your account/category names identify the bank/account which you keep it in. So we advise you to be smart about naming accounts/categories once your registration is complete.

<?php						?>Now for the CYA stuff. <?= COMPANY_NAME; ?> and it's affiliates will not be held responsible for any loss or damage to you, your financial future or your current financial situation that results in using <?= COMPANY_NAME; ?>. As was previously stated, you may use this site at your own risk, we are not responsible for anything that happens as a result of you using <?= COMPANY_NAME; ?>

<?php						?>By clicking the checkbox you are agreeing to these terms.
					</textarea>
				</div>
				<input type="submit" name="register" value="Submit" class='btn btn-primary' />
				<a href="/admin/login/" class='btn btn-info'>Back to Login</a>
			</form>
		</div>
	</div>
	<div id="footer">
		<div id="copy">
			&copy;2010-<?php $year = date('Y'); echo $year;?> <?=COMPANY_NAME;?>
			<div class="quantum">Powered by <img src="/images/quantum_logo_transparent_bg.png" height="20px" /></div>
		</div>
	</div>
</body>
</html>