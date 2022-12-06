<html>
<head>
	<title>Error View</title>
</head>

<body>
	<h1>Error View</h1>
	<div id="error">
		<?php
			echo $message;
			echo '<!--'.
				'Book ID: '.$bookId.
				'-->';
		?>
	</div>
	
</body>
</html>

