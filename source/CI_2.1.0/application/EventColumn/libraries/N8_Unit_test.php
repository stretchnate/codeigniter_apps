<?php

/**
 * The CI unit_test class leaves something ot be desired as far as feedback when a test is failed.
 * so this wrapper class is going to help us get more for our money. Not to mention make writing tests
 * feel a bit more like we are writing them in phpUnit.
 *
 * @author stretch
 */
class N8_Unit_test extends CI_Unit_test {

	const CR = "\n";

	/**
	 * provide a little more insight on failed tests so we can properly test or fix code.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @return	string
	 */
	function run($test, $expected = TRUE, $test_name = 'undefined', $notes = '') {
		parent::run($test, $expected, $test_name, $notes);

		//provide a little more insight on failed tests so we can properly test or fix code.
		$failed_message = "<span style='color:#ff3333;'>";
		$result = array_pop($this->results);

		if (in_array($expected, array('is_object', 'is_array'), TRUE)) {
			switch ($expected) {
				case 'is_object':
					$failed_message .= 'Failed to assert that ' . get_class($test) . ' is an object';
					break;

				case 'is_array':
					$failed_message .= 'Failed to assert that actual is an array';
					break;
			}
		} else {
			$failed_message .= "Failed to assert that '" . $test . "' (actual) is equal to '" . $expected . "' (expected)";
		}

		$failed_message .= "</span>";
		if (strtolower($result[0]['result']) == 'failed') {
			$result[0]['notes'] = !empty($result[0]['notes']) ? $failed_message . ' - ' . $result[0]['notes'] : $failed_message;
		}

		$this->results[] = $result;
	}

	public function parseResultsArray(array $results) {
		$failed_results = array();
		$assertions = count($results);
		$failed = 0;

		foreach ($results as $result) {
			if ($result['Result'] == 'Passed') {
				echo ".";
			} else {
				echo "F";
				$failed_results[] = $result;
			}
		}

		$failed = count($failed_results);
		echo self::CR;

		if (count($failed_results)) {
			echo "Failed Results" . self::CR;

			foreach ($failed_results as $failed_result) {
				$message = '';
				if (!empty($failed_result['Test Name'])) {
					$message = $failed_result['Test Name'] . self::CR;
				}
				$message .= $failed_result['File Name'] . self::CR;
				$message .= $failed_result['Line Number'] . self::CR;
				$message .= "expected " . $failed_result['Expected Datatype'] . " received " . $failed_result['Test Datatype'];

				echo $message;
			}
		}

		echo self::CR;
		echo $assertions . " assertions :: " . $failed . " failed";
		echo self::CR;
	}

	/**
	 * asserts whether the expected value is equal to the actual value
	 *
	 * @param mixed $expected
	 * @param mixed $actual
	 * @param string $test_name
	 * @param string $notes
	 */
	public function assertEquals($expected, $actual, $test_name = 'undefined', $notes = '') {
		$this->run($actual, $expected, $test_name, $notes);
	}

	/**
	 * Asserts the count of an array
	 *
	 * @param int $expected
	 * @param array $array
	 * @param string $test_name
	 * @param string $notes
	 */
	public function assertCount($expected, array $array, $test_name = 'undefined', $notes = '') {
		$count = count($array);
		$this->run($count, $expected, $test_name, $notes);
	}

	/**
	 * asserts whether a classname is as expected
	 *
	 * @param string $expected
	 * @param object $class
	 * @param string $test_name
	 * @param string $notes
	 */
	public function assertClassName($expected, $class, $test_name = 'undefined', $notes = '') {
		$class_name = get_class($class);
		$this->run($class_name, $expected, $test_name, $notes);
	}

	/**
	 * asserts whether an exception is the same as the exception that is expected. the trick to this one is passing
	 * arguments ($args) that will force the exception to be thrown.
	 *
	 * @param string $expected
	 * @param object $object
	 * @param string $method (method to call on $object)
	 * @param array $args (arguments to inject into $method)
	 * @param string $test_name
	 * @param string $notes
	 */
	public function assertException($expected, $object, $method, array $args = array(), $test_name = 'undefined', $notes = '') {
		try {
			$object->$method(implode(', ', $args));
			$this->run('No Exception Caught', $expected, $test_name, $notes);
		} catch (Exception $e) {
			$this->run(get_class($e), $expected, $test_name, $notes);
		}
	}

}

?>
