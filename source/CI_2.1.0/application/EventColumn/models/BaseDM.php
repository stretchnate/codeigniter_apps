<?php

/**
 * this is an abstract base class for Data Models
 *
 */
abstract class BaseDM extends N8_Model {

	protected $insert_id;

	abstract public function load($id);

	abstract public function save();

	abstract protected function update();

	abstract protected function insert();
}

?>
