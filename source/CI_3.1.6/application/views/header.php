<?php
//Set no caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $title ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" /><!-- support for border-radius in IE9 -->
	<meta charset='utf-8'>
	<!-- ensure proper mobile rendering and touch zooming with the following tag -->
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css">
	<link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>style.css" />
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	<?php
	if(isset($scripts) && is_array($scripts)) {
		foreach($scripts as $script)
			echo $script."\n\t";
	}
	?>
	<script type="text/javascript" src="/javascript/nav.js"></script>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container">
			<div id="header">
				<h1>
					<a href="/"><img id="logo" class="navbar-brand" src="/images/logo.png" alt="<?= COMPANY_NAME; ?>" /></a>
				</h1>
			</div>
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#" title="Quantum">Q</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<?php
					$nav = new NavigationUlLIB("main_nav", "nav navbar-nav");
					echo $nav->getUl();
				?>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<?=$logged_user?> <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="/userCTL">Profile</a></li>
							<li><a href="/admin/logout">logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<div id="content">
			<div class="error">
				<?
				if(isset($errors) && is_array($errors) && count($errors) > 0) {
					foreach($errors as $error) {
						echo urldecode($error)."<br />";
					}
				}
				?>
			</div>
