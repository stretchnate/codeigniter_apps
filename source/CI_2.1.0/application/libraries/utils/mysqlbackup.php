<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This class is used to create full mysql dumps of all databases listed in application/config/database.php
 * command
 *		/usr/bin/php /var/www/budget/public/index.php util/cliCTL/mySQLBackup
 *
 * @author dnate
 * @since 2012.08.25
 */
class MySQLBackup {

	const UBUNTU_ONE_LIFETIME   = 3;//months
	const UBUNTU_ONE            = '/home/stretch/Public/';
	const MYSQL_BACKUP_LIFETIME = 6;//months
	const MYSQL_BACKUP_PATH     = '/usr/backup/mysql/';
	const MYSQL_PATH            = '/usr/bin/mysqldump';

	private $db_parms   = array();

	function __construct() {
		$this->CI =& get_instance();

		// Is the config file in the environment folder?
		if ( ! defined('ENVIRONMENT') OR ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')) {
			if ( ! file_exists($file_path = APPPATH.'config/database.php')) {
				show_error('The configuration file database.php does not exist.');
			}
		}

		require_once($file_path);

		$this->db_parms = $db;
	}

	/**
	 * The main method, creates the mysql dump, stores it in MYSQL_BACKUP_PATH and copies it to UBUNTU_ONE
	 * for offsite backup
	 *
	 * @return void
	 */
	public function backupMySQL() {
		if( is_array($this->db_parms) ) {
			foreach($this->db_parms as $db => $parms) {
				$system_output = array();
				$command_success;

				$filename = date("Ymd") . "_" . time() . "_" . $parms['database'] . "_mysql_dump.sql";
				$dmp_file = self::MYSQL_BACKUP_PATH . $filename;

				$command  = self::MYSQL_PATH . " --opt";
				$command .= " --host="         . $parms['hostname'];
				$command .= " --user="         . $parms['username'];
				$command .= " --password="     . $parms['password'] . " ";
				$command .= $parms['database'] . " > "              . $dmp_file;

				exec($command, $system_output, $command_success);

				if( file_exists($dmp_file) && filesize($dmp_file) > 0 ) {
//					echo "File ".$dmp_file." created successfully\n";
					log_message('debug', "mysqlbackup: File ".$dmp_file." created successfully");

//					echo "Attempting to gzip ".$dmp_file."\n";
					log_message('debug', "mysqlbackup: Attempting to gzip ".$dmp_file);
					exec("gzip ".$dmp_file, $gzip_array = array(), $gzip_result);

					//copy the file to ubuntu one
					if(file_exists($dmp_file.".gz")) {
						if(copy($dmp_file.".gz", self::UBUNTU_ONE . $filename)) {
							log_message('debug', "mysqlbackup: copied ".$dmp_file.".gz to Ubuntu 1");
						} else {
							log_message('debug', "mysqlbackup: Warning - could not copy ".$dmp_file.".gz to Ubuntu 1");
						}
					} else {
						log_message('debug', "mysqlbackup: Warning - could not gzip ".$dmp_file);
					}
				} else {
//					echo $command_success."\n";
					log_message('debug', "mysqlbackup: ".$command_success);
					if(is_array($system_output)) {
						foreach($system_output as $output) {
//							echo $output."\n";
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
	 * purges old copies of mysql backups from both ubuntu One and the backup directory.
	 *
	 * @access public
	 * @return void
	 */
	public function purge() {
		log_message('debug', "mysqlbackup: starting purge");
		$this->purgeDirectory(self::UBUNTU_ONE, self::UBUNTU_ONE_LIFETIME);
		$this->purgeDirectory(self::MYSQL_BACKUP_PATH, self::MYSQL_BACKUP_LIFETIME);
	}

	/**
	 * purges a directory of any files that exceed $lifetime
	 *
	 * @param string $directory
	 * @param int $lifetime
	 * @access private
	 * @return void
	 */
	private function purgeDirectory($directory, $lifetime) {
		$iterator = new DirectoryIterator($directory);
		$today = new DateTime();
		while($iterator->valid()) {
			if($iterator->isFile() &&$iterator->isWritable()) {
				$m_time = new DateTime(date("Y-m-d H:i:s", $iterator->getMTime()));
				$time_diff = $today->diff($m_time);
				$file_age = $time_diff->format('%m');

				if($file_age > $lifetime) {
					log_message('debug', "mysqlbackup: purging".$iterator->getPathname());
					unlink($iterator->getPathname());
				}
			}

			$iterator->next();
		}
	}
}