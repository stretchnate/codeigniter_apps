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
    <title><?php echo $title ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" /><!-- support for border-radius in IE9 -->
    <link rel="shortcut icon" href="<?php echo IMG_PATH; ?>favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>redmond/jquery-ui-1.8.21.custom.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>jquery.dataTables_1.9.0.css" />
	<?php if(isset($scripts)) {
		foreach($scripts as $script)
			echo $script."\n\t";
	}?>
	<script type="text/javascript" src="/javascript/nav.js"></script>
</head>
<body>
	<div id="header" class="border">
		<div id="date">
            <?=" ".date('n.j.y'); ?>&nbsp;&nbsp;
            <?
            if($this->session->userdata('logged_user')) { ?>
            <a href='/admin/logout/'>logout</a>
            <?
            } ?>
        </div>
        <div id="console">
            <div id="user">
                Welcome
                <a href="/userCTL"><?=$this->session->userdata('logged_user')?></a>
            </div>
        </div>
		<h1><a href="/">Smart Budget<span style="font-size:40%;"></span></a></h1>
		<div id="nav">
			<div class="nav-background">
				<?php
					// echo $links;
					$nav = new NavigationUlLIB("main_nav");
					echo $nav->getUl();
				?>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<div id="container">
		<?php
		if( isset($sidebar_links) ) { ?>
		<div id="sidebar">
		<?php
			echo $sidebar_links;
		?>
		</div>
		<?php
		} ?>
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
