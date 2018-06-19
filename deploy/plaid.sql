-- DROP TABLE "plaid_response" ---------------------------------
DROP TABLE IF EXISTS `plaid_response` CASCADE;
-- -------------------------------------------------------------

-- CREATE TABLE "plaid_response" -------------------------------
CREATE TABLE `plaid_response` ( 
	`id` INT( 255 ) AUTO_INCREMENT NOT NULL,
	`request_id` VARCHAR( 255 ) NOT NULL,
	`product` ENUM( 'Auth', 'Identity', 'Income', 'Balance', 'TokenExchange' ) NOT NULL DEFAULT 'Auth',
	`data` TEXT NOT NULL,
	`added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `unique_id` UNIQUE( `id` ) )
ENGINE = INNODB;
-- -------------------------------------------------------------

-- DROP TABLE "plaid_transaction" ------------------------------
DROP TABLE IF EXISTS `plaid_transaction` CASCADE;
-- -------------------------------------------------------------

-- CREATE TABLE "plaid_transaction" ----------------------------
CREATE TABLE `plaid_transaction` ( 
	`id` INT( 255 ) AUTO_INCREMENT NOT NULL,
	`request_id` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`data` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `unique_id` UNIQUE( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------

-- DROP TABLE "plaid_connection" ------------------------------
DROP TABLE IF EXISTS `plaid_connection` CASCADE;
-- -------------------------------------------------------------

-- CREATE TABLE "plaid_connection" -----------------------------
CREATE TABLE `plaid_connection` ( 
	`item_id` VARCHAR( 64 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`plaid_account_id` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`account_id` INT( 11 ) NOT NULL,
	`access_token` VARCHAR( 64 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`transactions_ready` ENUM( 'INITIAL_UPDATE', 'HISTORICAL_UPDATE', 'NONE' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'NONE',
	`transactions_updated` DATETIME,
	`dt_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`active` TINYINT(1) NOT NULL DEFAULT 1,
	PRIMARY KEY ( `item_id`,`plaid_account_id`,`account_id` ),
	CONSTRAINT `unique_account_id` UNIQUE( `account_id` ),
	CONSTRAINT `unique_plaid_account_id` UNIQUE( `plaid_account_id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = INNODB;
-- -------------------------------------------------------------


-- DROP TABLE "api_vendor" ------------------------------
DROP TABLE IF EXISTS `api_vendor` CASCADE;
-- -------------------------------------------------------------

-- CREATE TABLE "api_vendor" -----------------------------------
CREATE TABLE `api_vendor` ( 
	`id` INT( 255 ) AUTO_INCREMENT NOT NULL,
	`name` VARCHAR( 128 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`username` VARCHAR( 64 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	`password` VARCHAR( 128 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	`credentials` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`disabled_date` DATETIME NULL,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------

-- CHANGE "LENGTH" OF "FIELD "bookName" ------------------------
ALTER TABLE `booksummary` MODIFY `bookName` VARCHAR( 128 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'New Book';
-- -------------------------------------------------------------

-- CHANGE "TYPE" OF "FIELD "date" ------------------------------
ALTER TABLE `new_funds` MODIFY `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
-- -------------------------------------------------------------

-- ADD ACCOUNT TYPE TO ACCOUNTS TABLE --------------------------
ALTER TABLE `accounts` ADD COLUMN `account_type` ENUM('checking','savings') DEFAULT 'checking' NOT NULL AFTER `account_name`;
-- --------------------------------------------------------------