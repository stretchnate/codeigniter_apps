<?php
class CliCTL {

	private $utils_path;

	function __construct() {
		echo "\n[" . date("Y-m-d h:i:s") . "]: calling CliCTL\n";
		$this->utils_path = APPPATH."/libraries/utils/";
//		parent::__construct();
	}

	public function mySQLBackup() {
		echo "CliCTL::mySQLBackup\n";
		require_once($this->utils_path."mysqlbackup.php");
		$mysql_backup = new MySQLBackup();
		$mysql_backup->backupMySQL();
	}

	public function purge() {
		require_once($this->utils_path."mysqlbackup.php");
		$mysql_backup = new MySQLBackup();
		$mysql_backup->purge();
	}
}

?>
