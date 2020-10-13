-- phpMyAdmin SQL Dump
-- version 4.1.11
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 12 Juillet 2016 à 15:41
-- Version du serveur :  5.1.73-log
-- Version de PHP :  5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `openillink`
--

-- --------------------------------------------------------

--
-- Structure de la table `libraries`
--

DROP TABLE IF EXISTS `libraries`;
CREATE TABLE IF NOT EXISTS `libraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `default` tinyint(1) DEFAULT NULL,
  `name1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name3` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name4` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name5` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `has_shared_ordres` tinyint(1) DEFAULT NULL,
  `signature` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `search_issn` tinyint(1) NOT NULL,
  `search_isbn` tinyint(1) NOT NULL,
  `search_ptitle` tinyint(1) NOT NULL,
  `search_btitle` tinyint(1) NOT NULL,
  `search_atitle` tinyint(1) NOT NULL,
  `order_ext` tinyint(1) NOT NULL,
  `order_form` tinyint(1) NOT NULL,
  `openurl` tinyint(1) NOT NULL,
  `library` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `ordonnancement` int(3) DEFAULT NULL,
  `url_encoded` tinyint(1) DEFAULT NULL,
  `skip_words` tinyint(1) NOT NULL,
  `skip_txt_after_mark` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `lib_code` (`library`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `localizations`
--

DROP TABLE IF EXISTS `localizations`;
CREATE TABLE IF NOT EXISTS `localizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `library` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name3` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name4` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name5` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `illinkid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `stade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `localisation` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `envoye` date DEFAULT NULL,
  `facture` date DEFAULT NULL,
  `renouveler` date DEFAULT NULL,
  `prix` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prepaye` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `arrivee` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `prenom` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cgra` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cgrb` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_postal` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `localite` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type_doc` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `urgent` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `envoi_par` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titre_periodique` text COLLATE utf8_unicode_ci NOT NULL,
  `annee` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `volume` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supplement` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pages` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titre_article` text COLLATE utf8_unicode_ci,
  `auteurs` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `edition` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isbn` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issn` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `eissn` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doi` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remarques` text COLLATE utf8_unicode_ci,
  `remarquespub` text COLLATE utf8_unicode_ci,
  `historique` text COLLATE utf8_unicode_ci,
  `saisie_par` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bibliotheque` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refinterbib` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `PMID` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_consent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `anonymized` TINYINT NOT NULL DEFAULT '0' AFTER `user_consent`,
  PRIMARY KEY (`illinkid`),
  KEY `annee` (`annee`),
  KEY `bibliotheque` (`bibliotheque`),
  KEY `cgra` (`cgra`),
  KEY `date` (`date`),
  KEY `isbn` (`isbn`),
  KEY `issn` (`issn`,`eissn`),
  KEY `localisation` (`localisation`),
  KEY `mail` (`mail`),
  KEY `nom` (`nom`,`prenom`),
  KEY `pages` (`pages`),
  KEY `ref` (`ref`),
  KEY `renouveler` (`renouveler`),
  KEY `service` (`service`),
  KEY `sid` (`sid`,`pid`),
  KEY `stade` (`stade`),
  KEY `volume` (`volume`),
  KEY `ui` (`uid`,`doi`,`PMID`),
  KEY `titre_periodique` (`titre_periodique`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=160000 ;

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` smallint(6) NOT NULL,
  `title1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `help1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `help2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title3` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `help3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title4` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `help4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title5` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `help5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `in` tinyint(1) DEFAULT NULL,
  `out` tinyint(1) DEFAULT NULL,
  `trash` tinyint(1) DEFAULT NULL,
  `color` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `special` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `anonymize` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `special` (`special`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name3` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name4` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name5` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `department` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `faculty` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `library` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `internalip1display` tinyint(1) NOT NULL,
  `internalip2display` tinyint(1) NOT NULL,
  `externalipdisplay` tinyint(1) NOT NULL,
  `validation` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `department` (`department`),
  KEY `name1` (`name1`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `library` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created_ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE `folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `query` text COLLATE utf8_unicode_ci,
  `user` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `library` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `position` int(5) DEFAULT NULL,
  `order_count` int(11) DEFAULT NULL,
  `count_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `user` (`user`),
  KEY `library` (`library`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `resolver_cache`
--

DROP TABLE IF EXISTS `resolver_cache`;
CREATE TABLE `resolver_cache` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `params` varchar(512) NOT NULL,
  `cache` text NOT NULL,
  INDEX `params` (`params`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `resolver_cache`
--

DROP TABLE IF EXISTS `resolver_log`;
CREATE TABLE `resolver_log` (
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `package` varchar(512) NOT NULL,
  `params` varchar(512) NOT NULL,
  `referer` varchar(255) NOT NULL,
  `auth_level` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
