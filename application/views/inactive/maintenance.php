<!DOCTYPE html PUBLIC  "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>form 1</title>
    <link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>ui-lightness/jquery-ui-1.8.2.custom.css" />
	<script type="text/javascript" src="<?php echo JS_PATH; ?>jquery-1.4.2.min.js"></script>
	<script type='text/javascript' src='<?php echo JS_PATH; ?>jquery-ui-1.8.2.custom.min.js'></script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>login.js"></script>
</head>
<body>
	<div id="header">
		<!-- <h1><a href="<?php echo $hostpath; ?>"><img src="<?php echo IMG_PATH; ?>logo.png" alt="The Header" /></a></h1> -->
		<div id="date"><?php echo date('l, F j'); ?></div>
	</div>
	
	<div id="container">
		<?php $date = date('l, F j'); ?>
		
		<h2>Temporarily Down</h2>
		<?php
		if(isset($error)) {
			echo "<p>$error</p>";
		}
		?>
		This site is temporarily down for maintenance. Sorry for the inconvenience.
	</div>
	<div id="footer">
		<div id="copy">Copyright&copy; <?php $year = date('Y'); echo $year;?> Me.</div>
	</div>
</body>
</html>