-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="3.0.7";

CREATE TABLE IF NOT EXISTS `tcompany` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `zip` int(6) NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

﻿-- find and create current companies;
INSERT INTO tcompany (name) 
SELECT distinct(company) FROM `tusers` WHERE tusers.company NOT LIKE '';

﻿-- update current user with companies ids ;
update tusers SET tusers.company=(SELECT tcompany.id FROM tcompany WHERE tcompany.name=tusers.company);

﻿-- modify company from tusers tables ;
ALTER TABLE  `tusers` CHANGE  `company`  `company` INT( 5 ) NOT NULL;

UPDATE trights SET ticket_save='2';

ALTER TABLE `trights` CHANGE `ticket_save_close` `ticket_save_close` INT(1) NOT NULL COMMENT 'Affiche le bouton enregistrer et fermer dans le ticket';
ALTER TABLE `trights` CHANGE `ticket_state` `ticket_state` INT(1) NOT NULL COMMENT 'Modification du champ etat dans le ticket';
ALTER TABLE `trights` CHANGE `ticket_time_hope` `ticket_time_hope` INT(1) NOT NULL COMMENT 'Modification du temps estimé passé par ticket';


ALTER TABLE  `trights` ADD  `ticket_new_type` INT( 1 ) NOT NULL COMMENT 'Modification du type pour les nouveaux tickets' AFTER  `ticket_cancel`;
ALTER TABLE  `trights` ADD  `ticket_new_type_disp` INT( 1 ) NOT NULL COMMENT 'Affiche le champ type pour les nouveaux tickets' AFTER  `ticket_new_type`;

UPDATE `trights` SET `ticket_new_type`='2' WHERE id='1' OR id='4' OR id='5';
UPDATE `trights` SET `ticket_new_type_disp`='2' WHERE id='1' OR id='4' OR id='5';

ALTER TABLE  `trights` ADD  `ticket_new_save` INT( 1 ) NOT NULL COMMENT 'Affiche le bouton sauvegarder sur les nouveaux tickets' AFTER  `ticket_new_send`;
UPDATE `trights` SET `ticket_new_save`='2' WHERE id='1' OR id='4' OR id='5';