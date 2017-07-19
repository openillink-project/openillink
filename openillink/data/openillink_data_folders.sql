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
-- Structure de la table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `query` text COLLATE utf8mb4_unicode_ci,
  `user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `library` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `position` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `folders`
--

INSERT INTO `folders` (`id`, `title`, `description`, `query`, `user`, `library`, `active`, `position`) VALUES
(1, 'To invoice', 'folder with orders to invoice', '(library LIKE ''LIB1'' ) AND (stade LIKE ''7'' )', 'sadmin', NULL, 1, 1),
(4, 'Renewed', 'Orders to be renewed', '(library LIKE ''LIB3'' ) AND (stade LIKE ''11''  OR stade LIKE ''10''  OR stade LIKE ''7'' )', 'sadmin', NULL, 1, 3),
(5, 'Out XYZ', 'Out of the library XYZ', '(library LIKE ''LIB6'' ) AND (stade LIKE ''3''  OR stade LIKE ''1''  OR stade LIKE ''11'' )', 'sadmin', NULL, 1, 2),
(6, 'Test', 'Folder test inactif', '(library LIKE ''LIB2''  OR library LIKE ''LIB5'' ) AND (stade LIKE ''10''  OR stade LIKE ''1''  OR stade LIKE ''4'' )', 'sadmin', NULL, 0, 1),
(7, 'Test', 'Test de filtre pour PI', '(library LIKE ''CMU'' ) AND (stade LIKE ''1''  OR stade LIKE ''4'' )', 'iriarte', NULL, 1, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `user` (`user`),
  ADD KEY `library` (`library`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
