<?php
//Set no caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

function showAd($ad_type) {
    $ad = AdFactory::getAdService('adsense');
    $ad->displayAd(AdFactory::AD_AUTO);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= COMPANY_NAME; ?> - Login</title>
	<meta charset='utf-8'>
	<!-- ensure proper mobile rendering and touch zooming with the following tag -->
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>style.css" />
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>login.js"></script>
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
				<a class="navbar-brand" href="/" title="<?= COMPANY_NAME; ?>"><img id="logo" class="navbar-brand" src="/images/logo.png" alt="<?= COMPANY_NAME; ?>" /></a>
			</div>
		</nav>

		<?php
		if(isset($error)) {
			echo "<p>$error</p>";
		}
		?>
		<form name="loginForm" action="/admin/login" method="post">
			<div class="form-group">
				<input type="text" class="form-control required" name="username" value="Username" id="username" />
			</div>
			<div class="form-group">
				<input type="password" class="form-control required" name="password" value="Password" id="password" />
			</div>
			<input type="submit" class="btn btn-primary" name="submLogin" value="Log In" />
			<a href="/admin/register/" id="register" class="btn btn-info">Register</a>
		</form>

		<div id="footer">
			<div class='links'>
				<?= $footer_nav->getUl(); ?>
			</div>
			<div id="copy">
				&copy;2010-<?php $year = date('Y'); echo $year;?> <?=COMPANY_NAME;?>
				<div class="quantum">Powered by <img src="/images/quantum_logo_transparent_bg.png" height="20px" /></div>
			</div>
		</div>
		<?= showAd(AdFactory::AD_AUTO); ?>
	</div>
</body>
</html>