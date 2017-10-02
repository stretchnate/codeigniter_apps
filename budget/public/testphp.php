<!DOCTYPE html>
<html lang='en'>
	<head>
		<title>Bootstrap Example</title>
		<meta charset='utf-8'>
		<!-- ensure proper mobile rendering and touch zooming with the following tag -->
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>
		<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	</head>
	<body>
		<div class='container-fluid'>
			<h1>My First bootstrap page<small> use the &lt;small&gt; tag for secondary text</small></h1>
			<p>
				This is some text.<br>
				<mark>use the &lt;mark&gt; tag to highlight text</mark><br />
				use the &lt;abbr&gt; tag to hide a <abbr title='this is a hidden description'>description</abbr>.
			</p>
			<div>
				<h4>Description Lists</h4>
				<dl>
					<dt>Coffee</dt>
					<dd>- black hot drink</dd>
					<dt>Milk</dt>
					<dd>- white cold drink</dd>
				</dl>
			</div>
			<div>
				<h4>keyboard inputs</h4>
				Use <kbd>ctrl + p</kbd> to open the print dialog box.
			</div>
			<div>
				<h3>boostraps text colors</h3>
				<p class='text-muted'>.text-muted</p>
				<p class='text-primary'>.text-primary</p>
				<p class='text-success'>.text-success</p>
				<p class='text-info'>.text-info</p>
				<p class='text-warning'>.text-warning</p>
				<p class='text-danger'>.text-danger</p>
			</div>
			<div>
				<h3>boostraps background colors</h3>
				<p class='bg-primary'>.bg-primary</p>
				<p class='bg-success'>.bg-success</p>
				<p class='bg-info'>.bg-info</p>
				<p class='bg-warning'>.bg-warning</p>
				<p class='bg-danger'>.bg-danger</p>
			</div>
			<div>
				<h3>Image Awesomeness</h3>
				<img src='images/header_gradient.gif' class='img-rounded' alt='rounded corners' title='rounded corners' />
				<img src='images/header_gradient.gif' class='img-circle' alt='circle' title='circle' />
				<img src='images/header_gradient.gif' class='img-thumbnail' alt='thumbnail' title='thumbnail' />
			</div>
			<div>
				<h3>Well</h3>
				<div class='well'>This is a well</div>
				<div class='well well-sm'>they come in different sizes... this is small</div>
				<div class='well well-lg'>this is large</div>
			</div>
			<div>
				<h3>Alerts</h3>
				<div class='alert alert-success'>
					<strong>Success!</strong> this is a success alert.
					<a href='#' class='alert-link'>this is an alert link</a>
					close me by clicking the 'x' to the right.
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				</div>
				<div class='alert alert-info'>
					<strong>Info!</strong> this is an info alert.
					<a href='#' class='alert-link'>this is an alert link</a>
					close me by clicking the 'x' to the right.
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				</div>
				<div class='alert alert-warning'>
					<strong>Warning!</strong> this is a warning alert.
					<a href='#' class='alert-link'>this is an alert link</a>
					close me by clicking the 'x' to the right.
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				</div>
				<div class='alert alert-danger fade in'>
					<strong>Danger!</strong> this is a danger alert.
					<a href='#' class='alert-link'>this is an alert link</a>
					close me by clicking the 'x' to the right. I will fade out!
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				</div>
			</div>
		</div>
	</body>
</html>