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
    <title>Smart Budget - Login</title>
	<meta charset='utf-8'>
	<!-- ensure proper mobile rendering and touch zooming with the following tag -->
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>ui-lightness/jquery-ui-1.8.2.custom.css" />
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    <!--<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-1.4.2.min.js"></script>-->
	<script type='text/javascript' src='<?php echo JS_PATH; ?>jquery-ui-1.8.21.custom.min.js'></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>login.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div id="header" class="border">
			<h1><a href="/">Smart Budget<span style="font-size:40%;"></span></a></h1>
			<div id="date" class="main_color"><?php echo date('l, F j'); ?></div>
		</div>

		<h2>Please log in</h2>
		<?php
		if(isset($error)) {
			echo "<p>$error</p>";
		}
		?>
		<form name="loginForm" action="/admin/login" method="post" style="float:left;">
			<div class="form-group">
				<label for="username"><span class="error">*</span>Username: <br />
				<input type="text" class="form-control required" name="username" value="" id="username" /></label>
			</div>
			<div class="form-group">
				<label for="passw"><span class="error">*</span>Password: <br />
				<input type="password" class="form-control required" name="password" value="" id="passw" /></label>
			</div>
			<div class="form-group">
				<input type="submit" class="form-control btn btn-lg" name="submLogin" value="Log In" />
				<a href="/admin/register/" id="register" class="btn btn-lg">Register</a>
			</div>
		</form>
		<div class="clear">&nbsp;</div>

		<div id="footer">
			<div class='links'>
				<?= $footer_nav->getUl(); ?>
			</div>
			<div id="copy">
				&copy;2010-<?php $year = date('Y'); echo $year;?> stretchnate.com
				<!--<span class="version">v3.2</span>-->
			</div>
		</div>
		<?= showAd(AdFactory::AD_AUTO); ?>
	</div>
</body>
</html>