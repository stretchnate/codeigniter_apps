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
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>style.css" />
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>register.js"></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>utilities.js"></script>
</head>
<body>
	<div class="container-fluid">
		<nav class="navbar navbar-default">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/" title="<?= COMPANY_NAME; ?>"><img id="logo" class="navbar-brand" src="/images/logo.jpg" alt="<?= COMPANY_NAME; ?>" /></a>
			</div>
		</nav>
		<h1>Register Here</h1>
		<?php
			//echo validation_errors();
			if(isset($error))
				echo "<div class='text-danger'>".$error."</div>";
		?>
		<div>
			<form name="registerForm" id="registerForm" action="/admin/registerUser/" method="post">
				<div class='form-group'>
					<input type="text" placeholder="Username" id="username" class="form-control" name="username" size="32" value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
					<span class="ajaxResult result">&nbsp;</span>
				</div>
				<div class='form-group'>
					<input type="password" placeholder="Password" id="password" class="form-control" name="password" size="32" value="" required>
				</div>
				<div class='form-group'>
					<input type="password" placeholder="Confirm Password" id="confirm_password" class="form-control" name="confirm_password" size="32" value="" required>
				</div>
				<div class='form-group'>
					<input type="text" placeholder="Email" id="email" class="email form-control" name="email" size="32" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
					<input type="hidden" name="charitable" value="0" />
				</div>

				<div class='form-group'>
					<div class='form-check'>
						<label class='form-check-label'>
							<input type="checkbox" id="agree_to_terms" class="form-check-input" name="agree_to_terms" required>
							I agree to the Terms and Conditions
						</label>
					</div>
					<div class="pre-scrollable well">
					<p>Please read these terms of service ("terms", "terms of service") carefully before using whyibudget.quantumfunds.net website (the "service") operated by <?= COMPANY_NAME ?> ("us", 'we", "our").</p>

					<strong>Conditions of Use</strong>
					<p>
					We will provide their services to you, which are subject to the conditions stated below in this document. Every time you visit this website, use its services or make a purchase, you accept the following conditions. This is why we urge you to read them carefully.
					</p>
					<strong>Privacy Policy</strong>
					<p>
					Before you continue using our website we advise you to read our privacy policy [link to privacy policy] regarding our user data collection. It will help you better understand our practices.
					</p>
					<strong>Copyright</strong>
					<p>
					Content published on this website (digital downloads, images, texts, graphics, logos) is the property of <?= COMPANY_NAME ?> and/or its content creators and protected by international copyright laws. The entire compilation of the content found on this website is the exclusive property of <?= COMPANY_NAME ?>, with copyright authorship for this compilation by <?= COMPANY_NAME ?>.
					</p>
					<strong>Communications</strong>
					<p>
					The entire communication with us is electronic. Every time you send us an email or visit our website, you are going to be communicating with us. You hereby consent to receive communications from us. If you subscribe to the news on our website, you are going to receive regular emails from us. We will continue to communicate with you by posting news and notices on our website and by sending you emails. You also agree that all notices, disclosures, agreements and other communications we provide to you electronically meet the legal requirements that such communications be in writing.
					</p>
					<strong>Applicable Law</strong>
					<p>
					By visiting this website, you agree that the laws of the state of Utah, without regard to principles of conflict laws, will govern these terms of service, or any dispute of any sort that might come between <?= COMPANY_NAME ?> and you, or its business partners and associates.
					</p>
					<strong>Disputes</strong>
					<p>
					Any dispute related in any way to your visit to this website or to products you purchase from us shall be arbitrated by state or federal court [your location] and you consent to exclusive jurisdiction and venue of such courts.
					</p>
					<strong>Comments, Reviews, and Emails</strong>
					<p>
					Visitors may post content as long as it is not obscene, illegal, defamatory, threatening, infringing of intellectual property rights, invasive of privacy or injurious in any other way to third parties. Content has to be free of software viruses, political campaign, and commercial solicitation.
					</p>
					<p>
					We reserve all rights (but not the obligation) to remove and/or edit such content. When you post your content, you grant <?= COMPANY_NAME ?> non-exclusive, royalty-free and irrevocable right to use, reproduce, publish, modify such content throughout the world in any media.
					</p>
					<strong>License and Site Access</strong>
					<p>
					We grant you a limited license to access and make personal use of this website. You are not allowed to download or modify it. This may be done only with written consent from us.
					</p>
					<strong>User Account</strong>
					<p>
					If you are an owner of an account on this website, you are solely responsible for maintaining the confidentiality of your private user details (username and password). You are responsible for all activities that occur under your account or password.
					</p>
					<p>
					We reserve all rights to terminate accounts, edit or remove content and cancel orders in their sole discretion.
					</p>
					</div>
				</div>
				<input type="submit" name="register" value="Submit" class='btn btn-primary' />
				<a href="/admin/login/" class='btn btn-info'>Back to Login</a>
			</form>
		</div>
	</div>
	<div id="footer">
		<div id="copy">
			&copy; <?php $year = date('Y'); echo $year;?> <?=strtolower(COMPANY_NAME);?>.com
			<div class="quantum">Powered by <img src="/images/quantum_logo_transparent_bg.png" height="20px" /></div>
		</div>
	</div>
</body>
</html>