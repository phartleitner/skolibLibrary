-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 02. Apr 2018 um 22:22
-- Server-Version: 10.1.21-MariaDB
-- PHP-Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `skolib_basis`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisations`
--

CREATE TABLE `organisations` (
  `bNr` int(11) NOT NULL,
  `kurz` varchar(20) CHARACTER SET utf8 NOT NULL,
  `Name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `Ort` varchar(100) CHARACTER SET utf8 NOT NULL,
  `mysql` varchar(20) CHARACTER SET utf8 NOT NULL,
  `pass` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `organisations`
--

INSERT INTO `organisations` (`bNr`, `kurz`, `Name`, `Ort`, `mysql`, `pass`) VALUES
(5, 'skolib_template', 'Template Schule', 'Musterstadt', 'root', '');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `organisations`
--
ALTER TABLE `organisations`
  ADD PRIMARY KEY (`bNr`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
