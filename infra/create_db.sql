-- Creating new Database for the dashboard
CREATE DATABASE IF NOT EXISTS `app1`;

USE `app1`;

-- Creating the tables in the database

-- AZ Table
CREATE TABLE IF NOT EXISTS `az_topology` ( 
	`id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR(15) NOT NULL , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- Tests table
CREATE TABLE IF NOT EXISTS `tests` ( 
	`id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR(64) NOT NULL , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- Platforms table
CREATE TABLE IF NOT EXISTS `platform` (
	`id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR(30) NOT NULL ,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- ODF versions table
CREATE TABLE IF NOT EXISTS `versions` ( 
	`id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR(15) NOT NULL , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- In version builds table
CREATE TABLE IF NOT EXISTS `builds` (
  	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  	`version` SMALLINT(5) UNSIGNED NOT NULL ,
  	`name` VARCHAR(10) NOT NULL ,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- Link between Versions and builds with foreign key
ALTER TABLE `builds` ADD INDEX(`version`);
ALTER TABLE `builds` ADD CONSTRAINT `version` FOREIGN KEY (`version`) REFERENCES `versions`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- Tests results table
CREATE TABLE IF NOT EXISTS `results` ( 
	`id` INT NOT NULL AUTO_INCREMENT ,
	`sample` SMALLINT NOT NULL DEFAULT '0' ,
	`version` SMALLINT(5) UNSIGNED NOT NULL ,
	`build` INT(10) UNSIGNED NOT NULL ,
	`platform` TINYINT(3) UNSIGNED NOT NULL ,
	`az_topology` TINYINT(3) UNSIGNED NOT NULL ,
	`test_name` TINYINT(3) UNSIGNED NOT NULL ,
	`es_link` TEXT NOT NULL ,
	`log_file` TEXT NOT NULL ,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- Adding indiacs for the results bable
ALTER TABLE `results` ADD INDEX(`version`);
ALTER TABLE `results` ADD INDEX(`platform`);
ALTER TABLE `results` ADD INDEX(`az_topology`);
ALTER TABLE `results` ADD INDEX(`test_name`);
ALTER TABLE `results` ADD INDEX(`build`);

-- Link between the results table to the other tables
ALTER TABLE `results` ADD FOREIGN KEY (`version`) REFERENCES `versions`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT; 
ALTER TABLE `results` ADD FOREIGN KEY (`platform`) REFERENCES `platform`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT; 
ALTER TABLE `results` ADD FOREIGN KEY (`az_topology`) REFERENCES `az-topology`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT; 
ALTER TABLE `results` ADD FOREIGN KEY (`test_name`) REFERENCES `tests`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT; 
ALTER TABLE `results` ADD FOREIGN KEY (`build`) REFERENCES `builds`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
