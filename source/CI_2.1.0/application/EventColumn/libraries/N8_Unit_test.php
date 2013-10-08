<?php

/**
 * The CI unit_test class leaves something ot be desired as far as feedback when a test is failed.
 * so this wrapper class is going to help us get more for our money.
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

	public function assertEquals($expected, $actual, $test_name = null, $notes = null) {
		$this->run($actual, $expected, $test_name, $notes);
	}

	public function assertCount($expected, array $array, $test_name = null, $notes = null) {
		$count = count($array);
		$this->run($count, $expected, $test_name, $notes);
	}

	public function assertClassName($expected, $class, $test_name = null, $notes = null) {
		$class_name = get_class($class);
		$this->run($class_name, $expected, $test_name, $notes);
	}

	public function assertException($expected, $object, $method, $args = array(), $test_name = null, $notes = null) {
		try {
			$object->$method(implode(', ', $args));
			$this->run('No Exception Caught', $expected, $test_name, $notes);
		} catch (Exception $e) {
			$this->run(get_class($e), $expected, $test_name, $notes);
		}
	}

}

?>
