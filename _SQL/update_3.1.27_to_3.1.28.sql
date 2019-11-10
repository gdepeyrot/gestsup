-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET default_storage_engine=INNODB;

-- update GestSup version number
UPDATE tparameters SET version="3.1.28";

-- add last ping information on asset iface
ALTER TABLE `tassets_iface` ADD `date_ping_ok` DATETIME NOT NULL AFTER `mac`;
ALTER TABLE `tassets_iface` ADD `date_ping_ko` DATETIME NOT NULL AFTER `date_ping_ok`;

-- add order column to list table
ALTER TABLE `tcategory` ADD `number` INT(2) NOT NULL AFTER `id`;