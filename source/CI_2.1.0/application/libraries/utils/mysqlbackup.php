<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class is used to create full mysql dumps of all databases listed in application/config/database.php
 *
 * @author dnate
 * @since 2012.08.25
 */
class MySQLBackup {

	private $db_parms   = array();
	private $dmp_path   = "/usr/backup/mysql/";
	private $mysql_path = "/usr/bin/mysqldump";

	function __construct() {
		echo "Hello, my name is MySQLBackup\n";
		$this->CI =& get_instance();

		// Is the config file in the environment folder?
		if ( ! defined('ENVIRONMENT') OR ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')) {
			if ( ! file_exists($file_path = APPPATH.'config/database.php')) {
				show_error('The configuration file database.php does not exist.');
			}
		}

		require_once($file_path);

		$this->db_parms = $db;

		if( stristr(PHP_OS, 'WIN') ) {
			$this->mysql_path = "D:\xampp\mysql\bin\mysqldump.exe";
			$this->dmp_path   = "D:\My Documents\budget_backups";
		}
	}

	/**
	 * The main method, creates the mysql dump
	 *
	 * @return void
	 */
	public function backupMySQL() {

		$this->createSubDirectories();

		if( is_array($this->db_parms) ) {
			foreach($this->db_parms as $db => $parms) {
				$system_output = array();
				$command_success;

				$dmp_file = $this->dmp_path . date("Y") . "/" . date("F") . "/" . date("Ymd") . "/" . time() . "_" . $parms['database'] . "_mysql_dump.sql";

				$command  = $this->mysql_path . " --opt";
				$command .= " --host="         . $parms['hostname'];
				$command .= " --user="         . $parms['username'];
				$command .= " --password="     . $parms['password'] . " ";
				$command .= $parms['database'] . " > "              . $dmp_file;

				exec($command, $system_output, $command_success);

				if( file_exists($dmp_file) && filesize($dmp_file) > 0 ) {
					echo "File ".$dmp_file." created successfully\n";
					log_message('debug', "mysqlbackup: File ".$dmp_file." created successfully");

					echo "Attempting to gzip ".$dmp_file."\n";
					log_message('debug', "mysqlbackup: Attempting to gzip ".$dmp_file);
					exec("gzip ".$dmp_file, $gzip_array = array(), $gzip_result);
				} else {
					echo $command_success."\n";
					log_message('debug', "mysqlbackup: ".$command_success);
					if(is_array($system_output)) {
						foreach($system_output as $output) {
							echo $output."\n";
							log_message('debug', "mysqlbackup: ".$output);
						}
					}
				}
			}
		} else {
			echo "No Database parameters exist :(\n";
		}
	}

	/**
	 * creates subdirectories to store the mysql dump. Final directory should be /usr/backup/mysql/[Year]/[Month]/[YYYYMMDD]/
	 *
	 * @return void
	 */
	private function createSubDirectories() {
		if( !file_exists($this->dmp_path . date("Y")) || !is_dir($this->dmp_path . date("Y")) ) {
			echo "creating directory " . $this->dmp_path . date("Y") . "\n";
			if( !mkdir($this->dmp_path . date("Y"), 0775) ) {
				echo "failed to create directory " . $this->dmp_path . date("Y") . "\n";
				die();
			}
		}

		if( !file_exists($this->dmp_path . date("Y") . "/" . date("F")) || !is_dir($this->dmp_path . date("Y") . "/" . date("F")) ) {
			echo "creating directory " . $this->dmp_path . date("Y") . "/" . date("F") . "\n";
			if( !mkdir($this->dmp_path . date("Y") . "/" . date("F"), 0775) ) {
				echo "failed to create directory " . $this->dmp_path . date("Y") . "/" . date("F") . "\n";
				die();
			}
		}

		if( !file_exists($this->dmp_path . date("Y") . "/" . date("F") . "/" . date("Ymd")) || !is_dir($this->dmp_path . date("Y") . "/" . date("F") . "/" . date("Ymd")) ) {
			echo "creating directory " . $this->dmp_path . date("Y") . "/" . date("F") . "/" . date("Ymd") . "\n";
			if( !mkdir($this->dmp_path . date("Y") . "/" . date("F") . "/" . date("Ymd"), 0775) ) {
				echo "failed to create directory " . $this->dmp_path . date("Y") . "/" . date("F") . "/" . date("Ymd") . "\n";
				die();
			}
		}
	}
}