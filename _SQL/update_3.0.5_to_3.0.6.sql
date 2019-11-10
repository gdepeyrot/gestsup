-- SQL Update for GestSup !!! If you are not in lastest version, all previous scripts must be passed before !!! ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

UPDATE tparameters SET version="3.0.6";

ALTER TABLE `trights` CHANGE `side_open_ticket` `side_open_ticket` INT(1) NOT NULL COMMENT 'Affiche le bouton Ouvrir un nouveau ticket';
ALTER TABLE `trights` CHANGE `ticket_template` `ticket_template` INT(1) NOT NULL COMMENT 'Affiche le bouton modèle de ticket';
ALTER TABLE `trights` CHANGE `ticket_user_actions` `ticket_user_actions` INT(1) NOT NULL COMMENT 'Affiche les boutons actions pour le demandeur';
ALTER TABLE `trights` CHANGE `ticket_cat_actions` `ticket_cat_actions` INT(1) NOT NULL COMMENT 'Affiche les boutons actions pour les catégories';
ALTER TABLE `trights` CHANGE `ticket_save_close` `ticket_save_close` INT(1) NOT NULL COMMENT 'Affiche le bouton de clôture dans le ticket';
ALTER TABLE `trights` CHANGE `ticket_send_mail` `ticket_send_mail` INT(1) NOT NULL COMMENT 'Affiche le bouton envoyer un mail dans le ticket';
ALTER TABLE `trights` CHANGE `ticket_cancel` `ticket_cancel` INT(1) NOT NULL COMMENT 'Affiche le bouton annuler dans le ticket';
ALTER TABLE `trights` CHANGE `ticket_new_send` `ticket_new_send` INT(1) NOT NULL COMMENT 'Affiche le bouton envoyer pour les nouveaux tickets';

UPDATE tincidents,tthreads SET tincidents.date_res=tthreads.date WHERE tthreads.ticket=tincidents.id AND tthreads.type=4 AND  tincidents.date_res='0000-00-00 00:00:00' AND  tincidents.state='3';
