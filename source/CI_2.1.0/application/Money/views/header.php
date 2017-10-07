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
	<link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>main.css" />
	<!--<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>redmond/jquery-ui-1.8.21.custom.css" />-->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>jquery.dataTables_1.9.0.css" />-->
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
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
			<h1><a href="/"><?= COMPANY_NAME; ?></a></h1>
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
