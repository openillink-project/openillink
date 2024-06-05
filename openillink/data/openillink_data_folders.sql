-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 06 Juin 2017 à 12:56
-- Version du serveur :  10.1.19-MariaDB
-- Version de PHP :  5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `openillink_unige`
--

-- --------------------------------------------------------

--
-- Contenu de la table `folders`
--

INSERT INTO `folders` (`id`, `title`, `description`, `query`, `user`, `library`, `active`, `position`) VALUES
(1, 'To invoice', 'folder with orders to invoice', '(bibliotheque  LIKE ''LIB1'' ) AND (stade LIKE ''7'' )', 'sadmin', NULL, 1, 1),
(4, 'Renewed', 'Orders to be renewed', '(bibliotheque  LIKE ''LIB3'' ) AND (stade LIKE ''11''  OR stade LIKE ''10''  OR stade LIKE ''7'' )', 'sadmin', NULL, 1, 3),
(5, 'Out XYZ', 'Out of the library XYZ', '(bibliotheque  LIKE ''LIB6'' ) AND (stade LIKE ''3''  OR stade LIKE ''1''  OR stade LIKE ''11'' )', 'sadmin', NULL, 1, 2),
(6, 'Test', 'Folder test inactif', '(bibliotheque  LIKE ''LIB2''  OR bibliotheque  LIKE ''LIB5'' ) AND (stade LIKE ''10''  OR stade LIKE ''1''  OR stade LIKE ''4'' )', 'sadmin', NULL, 0, 1),
(7, 'Test', 'Test de filtre pour PI', '(bibliotheque  LIKE ''CMU'' ) AND (stade LIKE ''1''  OR stade LIKE ''4'' )', 'iriarte', NULL, 1, 1);
