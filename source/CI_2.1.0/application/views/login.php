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
    <title>form 1</title>
    <link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>ui-lightness/jquery-ui-1.8.2.custom.css" />
	<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-1.4.2.min.js"></script>
	<script type='text/javascript' src='<?php echo JS_PATH; ?>jquery-ui-1.8.21.custom.min.js'></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>login.js"></script>
</head>
<body>
	<div id="header">
		<div id="date"><?php echo date('l, F j'); ?></div>
	</div>

	<div id="container">
		<?php $date = date('l, F j'); ?>

		<h2>Please log in first</h2>
		<?php
		if(isset($error)) {
			echo "<p>$error</p>";
		}
		?>
		<form name="loginForm" action="/admin/login" method="post" style="float:left;">
		<p>
			<label for="username"><span class="error">*</span>Username: <br />
			<input type="text" class="required" name="username" value="" id="username" /></label>
		</p>
		<p>
			<label for="passw"><span class="error">*</span>Password: <br />
			<input type="password" class="required" name="password" value="" id="passw" /></label>
		</p>
		<p>
			<input type="submit" name="submLogin" value="Log In" />
			<a href="/admin/register/" id="register">Register</a>
		</p>
		</form>
        <div style="float:left;">
            <?= $adsense; ?>
        </div>
        <div class="clear">&nbsp;</div>
	</div>
	<div id="footer">
		<div id="copy">Copyright&copy; <?php $year = date('Y'); echo $year;?> Me.</div>
	</div>
    <?= $adsense; ?>
</body>
</html>