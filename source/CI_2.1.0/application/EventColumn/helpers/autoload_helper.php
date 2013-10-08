<?

function __autoload($classname) {
	if (!class_exists($classname)) {
		if ($classname == 'CI_Model') {
			require_once("/var/www/source/CI_2.1.0/system/core/Model.php");
		} else if ($classname == 'N8_Model') {
			require_once("/var/www/source/CI_2.1.0/application/core/N8_Model.php");
		} else {
			//@todo consider moving libraries to application/libraries
			$dirs = array(
			    "/var/www/source/CI_2.1.0/application/EventColumn/models/",
			    "/var/www/source/CI_2.1.0/application/EventColumn/libraries/",
			);

			$result = false;

			if (strpos($classname, "_") !== false) {
				$result = underscoreLoadMethod($classname, $dirs);
			}

			if ($result === false) {
				$files = array($classname, lcfirst($classname));

				foreach ($dirs as $directory) {
					iterate($directory, $files);
				}
			}
		}
	}
}

/**
 * loads the file based on the classname broken apart to create subdirectories
 *
 * @param string $classname
 * @param array  $directories
 * @since 07.01.2013
 */
function underscoreLoadMethod($classname, $directories) {
	$result = false;
	$pieces = explode("_", $classname);
	$i = 1;

	foreach ($pieces as $piece) {
		if ($i == count($pieces)) {
			$piece .= ".php";
		}

		for ($j = 0; $j < count($directories); $j++) {
			$directories[$j] .= $piece;
			$directories[$j] .= (strpos($piece, ".php") === false) ? "/" : "";
		}
		$i++;
	}

	foreach ($directories as $filepath) {
		if (file_exists($filepath)) {
			require_once($filepath);
			$result = true;
		}
	}

	return $result;
}

/**
 * iterates through the directory looking for the file requested
 *
 * @param string $directory
 * @param array  $files
 */
function iterate($directory, array $files) {
	$ignore = array(".", "..");
	$result = false;
	$directory_iterator = new DirectoryIterator($directory);

	while ($directory_iterator->valid()) {
		if ($directory_iterator->isDir() === true && !in_array($directory_iterator->getFilename(), $ignore)) {
			$result = iterate($directory_iterator->getPathname(), $files);
		} elseif ($directory_iterator->isFile()) {
			if (in_array($directory_iterator->getBasename('.php'), $files)) {
				require_once($directory_iterator->getPathname());
				$result = true;
			}
		}

		if ($result === true) {
			break;
		}
		$directory_iterator->next();
	}

	return $result;
}

?>
