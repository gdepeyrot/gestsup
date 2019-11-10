-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- update GestSup version number
UPDATE tparameters SET version="3.1.2";

-- default asset state values
ALTER TABLE `tassets_state` ADD `display` VARCHAR(50) NOT NULL ;
ALTER TABLE `tassets_state` CHANGE `display` `display` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets_state` ADD `description` VARCHAR(50) NOT NULL AFTER `name`;
INSERT INTO `tassets_state` (`id`, `order`, `name`, `description`, `disable`, `display`) VALUES ('1', '1', 'Stock', 'Equipement en stock', '0', 'label label-sm label-info arrowed-in');
INSERT INTO `tassets_state` (`id`, `order`, `name`, `description`, `disable`, `display`) VALUES ('2', '2', 'Installé', 'Equipement installé en production', '0','label label-sm label-success arrowed arrowed-right arrowed-left');
INSERT INTO `tassets_state` (`id`, `order`, `name`, `description`, `disable`, `display`) VALUES ('3', '3', 'Standbye', 'Equipement de coté', '0','label label-sm label-warning arrowed-in arrowed-right arrowed-in arrowed-left');
INSERT INTO `tassets_state` (`id`, `order`, `name`, `description`, `disable`, `display`) VALUES ('4', '4', 'Recyclé', 'Equipement recyclé, jeté', '0', 'label label-sm label-inverse arrowed arrowed-right arrowed-left');


-- default asset type
INSERT INTO `tassets_type` (`id`, `name`) VALUES (NULL, 'PC');

-- default manufacturer
INSERT INTO `tassets_manufacturer` (`id`, `name`) VALUES (NULL, 'Dell');

-- default asset model
INSERT INTO `tassets_model` (`id`, `type`, `manufacturer`, `image`, `name`) VALUES (NULL, '1', '1', '3020.jpg', 'Optiplex 3020');

-- update asset core
ALTER TABLE `tassets` ADD `manufacturer` INT(5) NOT NULL AFTER `type`;
ALTER TABLE `tassets_model` ADD `ip` INT(1) NOT NULL ;
ALTER TABLE `tassets_network` ADD `disable` INT(1) NOT NULL ;
ALTER TABLE `tassets` ADD `ip2` VARCHAR(20) NOT NULL AFTER `ip`;
ALTER TABLE `tassets_model` ADD `ip2` INT(1) NOT NULL AFTER `ip`;
ALTER TABLE `tassets_model` CHANGE `ip2` `wifi` INT(1) NOT NULL;
ALTER TABLE `tassets_model` ADD `warranty` INT(2) NOT NULL AFTER `wifi`;

-- update 3.1.2.2
ALTER TABLE  `tassets_model` CHANGE  `name`  `name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `tassets_network` CHANGE `name` `name` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;