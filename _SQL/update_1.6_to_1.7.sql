-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- G�n�r� le : Mar 15 Mars 2011 � 18:35
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de donn�es: `bsup`
--


update tparameters SET version="1.7";

INSERT INTO `bsup_demo`.`tstates` (`id`, `name`) VALUES (NULL, 'Non atrribué');

ALTER TABLE `tusers` ADD `chgpwd` INT NOT NULL ;

