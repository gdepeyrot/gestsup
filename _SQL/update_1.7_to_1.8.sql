-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 15 Mars 2011 à 18:35
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `bsup`
--


update tparameters SET version="1.8";
ALTER TABLE `tincidents` ADD `disable` INT( 1 ) NOT NULL ;

