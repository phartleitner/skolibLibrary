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
-- Datenbank: `skolib_template`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_ausleihe`
--

CREATE TABLE `skolib_ausleihe` (
  `aNr` int(11) NOT NULL,
  `tNr` int(11) NOT NULL DEFAULT '0',
  `user` int(1) NOT NULL DEFAULT '0',
  `sNr` int(11) NOT NULL DEFAULT '0',
  `aus` int(11) NOT NULL DEFAULT '0',
  `frist` int(11) DEFAULT '0',
  `rueck` int(11) NOT NULL DEFAULT '0',
  `mahn` int(11) NOT NULL DEFAULT '0',
  `extend` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_barc_forms`
--

CREATE TABLE `skolib_barc_forms` (
  `bfNr` int(11) NOT NULL,
  `public` int(1) NOT NULL COMMENT 'Anzeige des Formats beim Labeldruck',
  `name` varchar(100) NOT NULL,
  `margin_left` int(11) NOT NULL,
  `top` int(11) NOT NULL,
  `fontsize` int(11) NOT NULL,
  `lineheight` int(11) NOT NULL,
  `cols` int(11) NOT NULL,
  `rows` int(11) NOT NULL,
  `colwidth` int(11) NOT NULL,
  `rowheight` int(11) NOT NULL,
  `picspace_v` int(11) NOT NULL COMMENT 'Position des Bildes nach oben zur Passung',
  `textspace_v` int(11) NOT NULL COMMENT 'Verschiebung des Texts vertikal (zur Passung an Bild)',
  `textspace_h` int(11) NOT NULL,
  `showcode` int(11) NOT NULL COMMENT 'Anzeige des Barcodes',
  `maxpages` int(11) NOT NULL,
  `picwidth` int(11) NOT NULL,
  `picheight` int(11) NOT NULL,
  `ratio` double NOT NULL,
  `signatur` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `skolib_barc_forms`
--

INSERT INTO `skolib_barc_forms` (`bfNr`, `public`, `name`, `margin_left`, `top`, `fontsize`, `lineheight`, `cols`, `rows`, `colwidth`, `rowheight`, `picspace_v`, `textspace_v`, `textspace_h`, `showcode`, `maxpages`, `picwidth`, `picheight`, `ratio`, `signatur`) VALUES
(1, 1, 'Herma 4201', 25, 775, 6, 8, 4, 16, 146, 51, 10, 35, 0, 1, 2, 300, 35, 0.45, 1),
(2, 1, '2 x 10 Etiketten', 30, 780, 10, 10, 2, 10, 300, 80, -30, 20, 42, 1, 7, 300, 50, 0.7, 0),
(3, 1, '3 x 10 Etiketten', 40, 780, 10, 10, 3, 10, 170, 80, -25, 25, 10, 1, 4, 280, 60, 0.6, 0),
(4, 1, '2 x 10 kleiner', 40, 780, 8, 10, 2, 10, 170, 80, -25, 25, 0, 1, 4, 280, 60, 0.6, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_customer`
--

CREATE TABLE `skolib_customer` (
  `SNr` int(3) NOT NULL,
  `SvNr` bigint(11) NOT NULL DEFAULT '0',
  `ASVID` varchar(100) NOT NULL,
  `SBarcode` varchar(20) NOT NULL,
  `SName` varchar(40) NOT NULL,
  `SRufname` varchar(40) NOT NULL,
  `KName` varchar(10) NOT NULL,
  `upd` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `skolib_customer`
--

INSERT INTO `skolib_customer` (`SNr`, `SvNr`, `ASVID`, `SBarcode`, `SName`, `SRufname`, `KName`, `upd`) VALUES
(1, 0, '8a3b0cce-5b37da03-015b-39630a09-0645', '1000000001', 'Mustermann', 'Michael', '05a', 1),
(2, 0, '8a3b0cce-5b37da03-015b-38e810a3-0270', '1000000002', 'Musterfrau', 'Kira', '09a', 1),
(3, 0, '8a3b0cce-5b3e17a5-015b-3e2471be-0034', '1000000003', 'Testmann', 'Theo', '12', 1),
(4, 0, '8a3b0cce-5b37da03-015b-3943ea4e-058c', '1000000004', 'Testfrau', 'Theresa', '07b', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_customer_data`
--

CREATE TABLE `skolib_customer_data` (
  `WNr` int(11) NOT NULL,
  `WB1Nr` int(11) NOT NULL DEFAULT '0',
  `WB2Nr` int(11) NOT NULL DEFAULT '0',
  `ordinal` int(11) NOT NULL DEFAULT '0',
  `WFeld` varchar(50) NOT NULL DEFAULT '',
  `WAusgabe` varchar(50) NOT NULL DEFAULT '',
  `public` int(1) NOT NULL DEFAULT '0',
  `lehrer` int(1) NOT NULL DEFAULT '0',
  `in_use` int(11) NOT NULL DEFAULT '0',
  `source_table` varchar(50) NOT NULL,
  `source_field` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `skolib_customer_data`
--

INSERT INTO `skolib_customer_data` (`WNr`, `WB1Nr`, `WB2Nr`, `ordinal`, `WFeld`, `WAusgabe`, `public`, `lehrer`, `in_use`, `source_table`, `source_field`) VALUES
(1, 1, 1, 1, 'SName', 'Name', 1, 1, 1, '', ''),
(3, 1, 1, 3, 'SRufname', 'Rufname', 1, 1, 1, '', ''),
(11, 1, 1, 6, 'SGeburtsdatum', 'Geburtsdatum', 1, 1, 1, '', ''),
(20, 1, 3, 7, 'status', 'Status', 0, 0, 1, '', ''),
(40, 1, 1, 7, 'SvNr', 'SchülerID', 0, 0, 1, '', ''),
(41, 0, 0, 0, 'KName', 'Klasse', 0, 0, 1, '%_sstatus', 'KNr');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_data_fields`
--

CREATE TABLE `skolib_data_fields` (
  `dfNr` int(11) NOT NULL,
  `df` varchar(20) DEFAULT NULL,
  `dfout` varchar(20) DEFAULT NULL,
  `dftext` varchar(30) DEFAULT NULL,
  `pflicht` int(11) NOT NULL DEFAULT '0',
  `length` int(11) NOT NULL DEFAULT '0',
  `cols` int(11) NOT NULL DEFAULT '0',
  `ordinal` int(11) NOT NULL DEFAULT '0',
  `dwNr` int(1) DEFAULT NULL COMMENT 'refers to table skolib_dwert',
  `input_type` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `skolib_data_fields`
--

INSERT INTO `skolib_data_fields` (`dfNr`, `df`, `dfout`, `dftext`, `pflicht`, `length`, `cols`, `ordinal`, `dwNr`, `input_type`) VALUES
(1, 'titel', 'Titel', '', 1, 60, 0, 1, 0, 'input'),
(2, 'autor', 'Autor/Herausgeber', '(Name, Vorname)', 1, 40, 0, 2, 0, 'input'),
(3, 'hkat', 'Kategorie', '', 1, 30, 0, 3, 1, 'select'),
(4, 'ukat1', 'Unterkategorie1', '', 0, 30, 0, 4, 2, 'select'),
(5, 'swort', 'Schlagwort', '', 0, 70, 4, 7, 0, 'textarea'),
(6, 'mtyp', 'Medium', '', 1, 30, 0, 6, 4, 'select'),
(7, 'zusatz', 'Sonstiges', '', 0, 30, 0, 8, 0, 'input'),
(8, 'ukat2', 'Unterkategorie2', '', 0, 30, 0, 5, 3, 'select');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_drop_down`
--

CREATE TABLE `skolib_drop_down` (
  `ddNr` int(11) NOT NULL,
  `dwNr` int(11) NOT NULL DEFAULT '0',
  `wert` varchar(70) DEFAULT NULL,
  `ordinal` int(11) NOT NULL DEFAULT '0',
  `sig_wert` varchar(5) DEFAULT NULL COMMENT 'Signaturwert',
  `standard` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `skolib_drop_down`
--

INSERT INTO `skolib_drop_down` (`ddNr`, `dwNr`, `wert`, `ordinal`, `sig_wert`, `standard`) VALUES
(1, 1, 'Abenteuer', 1, NULL, 0),
(2, 1, 'Detektivgeschichten', 2, NULL, 0),
(3, 1, 'Fantasy', 3, NULL, 0),
(4, 1, 'Freundschaft und  Familie', 4, NULL, 0),
(5, 1, 'Historische Romane', 5, NULL, 0),
(6, 1, 'Klassiker', 6, NULL, 0),
(7, 1, 'Krimi / Thriller', 7, NULL, 0),
(8, 1, 'Liebe', 8, NULL, 0),
(9, 1, 'Märchen und Sagen', 9, NULL, 0),
(10, 2, 'Ägyptisch', 1, NULL, 0),
(11, 2, 'Amerika', 2, NULL, 0),
(12, 2, 'Englisch', 3, NULL, 0),
(13, 2, 'Griechisch', 4, NULL, 0),
(14, 2, 'Kapkolonie', 5, NULL, 0),
(15, 2, 'Nachkriegsdeutschland', 6, NULL, 0),
(16, 2, 'Römisch', 7, NULL, 0),
(17, 2, 'Bronzezeit', 8, NULL, 0),
(18, 2, 'Das lange 19.Jh', 9, NULL, 0),
(19, 2, 'Frühe Neuzeit', 10, NULL, 0),
(20, 2, 'Frühmittelalter', 11, NULL, 0),
(21, 2, 'Hochmittelalter', 12, NULL, 0),
(22, 2, 'Nationalsozialismus', 13, NULL, 0),
(23, 2, 'Spätmittelalter', 14, NULL, 0),
(24, 2, 'Ältere', 15, NULL, 0),
(25, 2, 'Jüngere', 16, NULL, 0),
(26, 2, 'Jungs', 17, NULL, 0),
(27, 2, 'Mädchen', 18, NULL, 0),
(28, 2, 'Schifffahrt', 19, NULL, 0),
(29, 4, 'Buch', 1, NULL, 0),
(30, 4, 'CD/DVD', 2, NULL, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_dwert`
--

CREATE TABLE `skolib_dwert` (
  `dwNr` int(11) NOT NULL,
  `value` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `skolib_dwert`
--

INSERT INTO `skolib_dwert` (`dwNr`, `value`) VALUES
(1, 'hkat'),
(2, 'ukat1'),
(4, 'mtyp'),
(3, 'ukat2');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_library_defaults`
--

CREATE TABLE `skolib_library_defaults` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  `active` int(1) NOT NULL,
  `setup_category` varchar(50) NOT NULL,
  `setup_name` varchar(100) NOT NULL,
  `setup_field` varchar(50) NOT NULL,
  `setup_update` varchar(50) NOT NULL,
  `setup_comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `skolib_library_defaults`
--

INSERT INTO `skolib_library_defaults` (`id`, `category`, `type`, `value`, `active`, `setup_category`, `setup_name`, `setup_field`, `setup_update`, `setup_comment`) VALUES
(1, 'dashboard', 'borrowedItemsAmount', '', 1, 'Konfiguration Startseite', 'Anzeige entliehener Titel', 'borrowed', 'active', NULL),
(2, 'dashboard', 'inventoryAmount', '', 1, 'Konfiguration Startseite', 'Anzeige der Gesamttitelanzahl', 'inventoryamount', 'active', NULL),
(3, 'dashboard', 'favourites', '', 1, 'Konfiguration Startseite', 'Anzahl der beliebtesten Titel', 'favourites', 'active', NULL),
(4, 'dashboard', 'dueItems', '', 1, 'Konfiguration Startseite', 'Anzahl fälliger Titel', 'dueitems', 'active', NULL),
(5, 'dashboard', 'warnedItems', '', 1, 'Konfiguration Startseite', 'Anzahl angemahnter Titel', 'warneditems', 'active', NULL),
(6, 'libraryprefix', '', '99', 1, 'Basiseinstellungen', 'Präfix der Bücherbarcodes', 'libraryprefix', 'value', 'Ändern Sie diesen Wert nach dem ersten Setup nicht mehr'),
(7, 'serieslib', '', '0', 1, 'Basiseinstellungen', 'Lernmittelbibliothek', 'serieslib', 'value', '1 wenn Lernmittelbibliothek'),
(8, 'extension', '', '21', 1, 'Basiseinstellungen', 'Verlängerungsdauer', 'extension', 'value', 'in Tagen'),
(9, 'length', '', '30', 1, 'Basiseinstellungen', 'Ausleihdauer', 'length', 'value', 'in Tagen'),
(10, 'barclength', '', '10', 1, 'Basiseinstellungen', 'Länge der Barcodes', 'barclength', 'value', 'Ändern Sie diesen Wert nach dem ersten Setup nicht mehr'),
(11, 'customerprefix', '', '10', 1, 'Basiseinstellungen', 'Präfix der Kundenbarcodes', 'customerprefix', 'value', 'Ändern Sie diesen Wert nach dem ersten Setup nicht mehr'),
(12, 'signatureprint', '', '1', 1, 'Basiseinstellungen', 'Drucke Signaturetiketten', 'signatureprint', 'value', 'sollte nur aktiv sein wenn keine Lernmittelbibliothek'),
(13, 'signatureseparator', '', '/', 1, 'Basiseinstellungen', 'Trennzeichen für Signaturen', 'signatureseparator', 'value', 'nur relevant wenn keine Lernmittelbibliothek');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_login_token`
--

CREATE TABLE `skolib_login_token` (
  `userId` int(11) NOT NULL,
  `validity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Daten für Tabelle `skolib_login_token`
--

INSERT INTO `skolib_login_token` (`userId`, `validity`) VALUES
(1, '2018-04-02 20:38:02');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_navigation`
--

CREATE TABLE `skolib_navigation` (
  `mNr` int(11) NOT NULL,
  `mentry` varchar(50) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `mlink` varchar(40) DEFAULT NULL,
  `ordinal` int(11) NOT NULL,
  `collapsible` tinyint(1) NOT NULL,
  `nav_area` int(11) NOT NULL COMMENT 'referring to submenue header by index',
  `property` int(1) NOT NULL COMMENT 'benötigtes Recht, Bezug auf RWert',
  `popup` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `skolib_navigation`
--

INSERT INTO `skolib_navigation` (`mNr`, `mentry`, `type`, `icon`, `mlink`, `ordinal`, `collapsible`, `nav_area`, `property`, `popup`) VALUES
(1, 'Ausleihe', 'out', '', 'ausleihe.php', 1, 0, 0, 1, 0),
(2, 'Rueckgabe', 'return', '', 'return.php', 2, 0, 0, 1, 0),
(3, 'Barcodes', 'barc', 'fingerprint', 'barcodeselect.php', 3, 1, 101, 2, 0),
(4, 'Info', 'info', '', 'infoscan.php', 3, 0, 0, 1, 0),
(5, 'Buchbestand', '', 'library_books', 'admin.php', 1, 1, 101, 2, 0),
(9, 'Benutzer', 'users', 'local_library', 'uservwltg.php', 1, 0, 102, 3, 1),
(10, 'Datenabgleich', 'update', 'update', 'schueler_abgleich_select.php', 2, 0, 102, 3, 0),
(13, 'Suchen', 'search', '', 'suche.php', 5, 0, 0, 1, 0),
(119, 'einzelne Barcodes', 'singlebarc', 'print', NULL, 3, 0, 3, 2, 1),
(15, 'Inventar', 'stock', 'storage', 'inventar.php', 2, 0, 5, 2, 1),
(16, 'Doubletten-Check', '', 'error_outline', 'dblcheck.php', 4, 0, 101, 2, 0),
(17, 'entliehene Bücher', 'borrowed', 'call_made', 'entliehen.php', 5, 0, 101, 2, 0),
(101, 'Verwaltung', 'admin', '', NULL, 6, 0, 0, 2, 0),
(102, 'Einstellungen', 'settings', 'settings', NULL, 7, 1, 101, 3, 0),
(103, 'Bücher', 'bookbarc', 'print', NULL, 1, 0, 3, 2, 1),
(104, 'Klassen', 'customer', 'print', NULL, 2, 0, 3, 2, 1),
(111, 'Signaturen generieren', 'makesig', 'add', NULL, 6, 0, 120, 4, 1),
(110, 'Hinzufügen', 'addItem', 'add', NULL, 1, 0, 5, 3, 1),
(112, 'Bücherimport', 'bookimport', 'book', NULL, 3, 0, 115, 4, 1),
(113, 'Signatureinstellungen', 'managesig', 'fingerprint', NULL, 2, 0, 120, 4, 1),
(114, 'Customerimport', 'customerimport', 'person', NULL, 2, 0, 115, 4, 1),
(115, 'SuperAdminFunktonen', '', 'visibility', NULL, 4, 1, 102, 4, 0),
(116, 'Barcodeformulare', 'managebarcform', 'view_compact', NULL, 3, 0, 120, 4, 1),
(120, 'Setup', '', 'tune', NULL, 1, 1, 115, 4, 1),
(121, 'Basiseinstellungen', 'setup', 'tune', NULL, 1, 0, 120, 4, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_rights`
--

CREATE TABLE `skolib_rights` (
  `RNr` int(11) NOT NULL,
  `RWert` int(1) NOT NULL,
  `RName` varchar(15) NOT NULL DEFAULT '',
  `ausgabe` varchar(20) NOT NULL DEFAULT '',
  `s_admin` int(1) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `grant_right` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `skolib_rights`
--

INSERT INTO `skolib_rights` (`RNr`, `RWert`, `RName`, `ausgabe`, `s_admin`, `description`, `grant_right`) VALUES
(1, 4, 'superadmin', 'Superadmin', 1, '', 4),
(2, 3, 'admin', 'Bibliotheksverwalter', NULL, 'zusätzlich Verwaltung der Bibliothekare und Bibliotheksverwalter', 3),
(3, 2, 'bibliothekar', 'Bibliothekar', NULL, 'zusätzlich Anlegen von Benutzern und Helfern', 3),
(4, 1, 'helfer', 'Bibliothekshelfer', NULL, 'zusätzlich Bücherverwaltung', 2),
(5, 0, 'user', 'Benutzer', NULL, 'berechtigt zur Titelsuche', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_signature_rules`
--

CREATE TABLE `skolib_signature_rules` (
  `id` int(11) NOT NULL,
  `typ` varchar(100) NOT NULL,
  `field` varchar(50) NOT NULL,
  `query` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='signbature and library defaults' ROW_FORMAT=COMPACT;

--
-- Daten für Tabelle `skolib_signature_rules`
--

INSERT INTO `skolib_signature_rules` (`id`, `typ`, `field`, `query`) VALUES
(1, 'element', '3', 'SELECT skolib_drop_down.wert FROM skolib_drop_down,skolib_dwert WHERE skolib_dwert.dwNr = skolib_drop_down.dwNr AND skolib_dwert.value = \"hkat\" AND skolib_drop_down.ddNr in(SELECT hkat FROM skolib_titel WHERE tNr = %wert% ) '),
(2, 'element', '2', 'SELECT autor FROM skolib_titel WHERE tNr = %wert%'),
(3, 'element', '1', 'SELECT titel FROM skolib_titel WHERE TNr = %wert%'),
(6, 'element', '4', 'SELECT skolib_drop_down.wert FROM skolib_drop_down,skolib_dwert WHERE skolib_dwert.dwNr = skolib_drop_down.dwNr AND skolib_dwert.value = \"ukat1\" AND skolib_drop_down.ddNr in(SELECT hkat FROM skolib_titel WHERE tNr = %wert% ) '),
(7, 'library_redundant', '99', ''),
(8, 'element', '2', 'SELECT autor FROM skolib_titel WHERE tNr = %wert%'),
(11, 'librarytype_redundant', '0', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_signature_settings`
--

CREATE TABLE `skolib_signature_settings` (
  `id` int(11) NOT NULL,
  `ruleId` int(11) NOT NULL,
  `hkatId` int(11) NOT NULL COMMENT '0 wenn default',
  `length` int(11) NOT NULL,
  `ordinal` int(11) NOT NULL,
  `addNr` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Daten für Tabelle `skolib_signature_settings`
--

INSERT INTO `skolib_signature_settings` (`id`, `ruleId`, `hkatId`, `length`, `ordinal`, `addNr`) VALUES
(1, 1, 0, 5, 1, 0),
(2, 2, 0, 3, 2, 0),
(3, 1, 1, 99, 1, 1),
(4, 3, 1, 10, 2, 0),
(6, 6, 1, 5, 3, 0),
(7, 8, 1, 5, 4, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_titel`
--

CREATE TABLE `skolib_titel` (
  `tNr` int(11) NOT NULL,
  `titel` varchar(70) DEFAULT NULL,
  `autor` varchar(60) DEFAULT NULL,
  `hkat` int(11) NOT NULL DEFAULT '0',
  `ukat1` int(11) NOT NULL DEFAULT '0',
  `ukat2` int(11) NOT NULL DEFAULT '0',
  `mtyp` int(11) NOT NULL DEFAULT '0',
  `zusatz` varchar(30) DEFAULT NULL,
  `swort` varchar(255) DEFAULT NULL,
  `signatur` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `erfasst` varchar(20) NOT NULL,
  `print` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Daten für Tabelle `skolib_titel`
--

INSERT INTO `skolib_titel` (`tNr`, `titel`, `autor`, `hkat`, `ukat1`, `ukat2`, `mtyp`, `zusatz`, `swort`, `signatur`, `barcode`, `erfasst`, `print`) VALUES
(1, 'Hypatia', 'Zitelmann, Arnulf ', 5, 10, 0, 29, '', '', 'Histo/Zit', '9900000001', '20180217', '20180324 20:47:47'),
(2, 'Verschollen in der Pyramide', 'Naumann, Rosa ', 5, 10, 0, 29, '', '', 'Histo/Nau', '9900000002', '20180217', '20180324 20:47:47'),
(3, 'Ich fand Tut-ench-Amun', 'Carter, Howard ', 5, 10, 0, 29, '', '', 'Histo/Car', '9900000003', '20180217', '20180324 20:47:47'),
(4, 'Rette dich Pharao', 'Grund, Josef Carl', 5, 10, 0, 29, '', '', 'Histo/Gru', '9900000004', '20180217', '20180324 20:47:47'),
(5, 'die Welt der Pharaonen', 'Baumann, Hans ', 5, 10, 0, 29, '', '', 'Histo/Bau', '9900000005', '20180217', '20180324 20:47:47'),
(6, 'Helen Keller', 'Clevé, Evelyn ', 4, 24, 0, 29, '', '', 'Freun/Cle', '9900000006', '20180217', '20180324 20:47:47'),
(7, 'M.C. Higgins, der Grosse', 'Hamilton, Virginia ', 4, 24, 0, 29, '', '', 'Freun/Ham', '9900000007', '20180217', '20180324 20:47:47'),
(8, 'Entführung nach Hause', 'Procházková, Iva ', 4, 24, 0, 29, '', '', 'Freun/Pro', '9900000008', '20180217', '20180324 20:47:47'),
(9, 'Theos Reise', 'Clément, Catherine ', 4, 24, 0, 29, '', '', 'Freun/Clé', '9900000009', '20180217', '20180324 20:47:47'),
(10, 'Ein Anruf von Sebastian', 'Korschunow, Irina ', 4, 24, 0, 29, '', '', 'Freun/Kor', '9900000010', '20180217', '20180324 20:47:47'),
(11, 'Blaupause blueprint', 'Kerner, Charlotte ', 4, 24, 0, 29, '', '', 'Freun/Ker', '9900000011', '20180217', '20180324 20:47:47'),
(12, 'Blaupause blueprint', 'Kerner, Charlotte ', 4, 24, 0, 29, '', '', 'Freun/Ker', '9900000012', '20180217', '20180324 20:47:47'),
(13, 'Blaupause blueprint', 'Kerner, Charlotte ', 4, 24, 0, 29, '', '', 'Freun/Ker', '9900000013', '20180217', '20180324 20:47:47'),
(14, 'Meine Schwester Sara', 'Weiss, Ruth ', 4, 24, 0, 29, '', '', 'Freun/Wei', '9900000014', '20180217', '20180324 20:47:47'),
(15, 'Was du willst', 'Klass, David ', 4, 24, 0, 29, '', '', 'Freun/Kla', '9900000015', '20180217', '20180324 20:47:47'),
(16, 'Mit offenen Augen', 'Oates, Joyce Carol ', 4, 24, 0, 29, '', '', 'Freun/Oat', '9900000016', '20180217', '20180324 20:47:47'),
(17, 'Sexy', 'Oates, Joyce Carol ', 4, 24, 0, 29, '', '', 'Freun/Oat', '9900000017', '20180217', '20180324 20:47:47'),
(18, 'Zwei Wege in den Sommer', 'Paluch, Andrea / Habeck, Robert ', 4, 24, 0, 29, '', '', 'Freun/Pal', '9900000018', '20180217', '20180324 20:47:47'),
(19, 'Wohin du mich führst', 'Grossman, David ', 4, 24, 0, 29, '', '', 'Freun/Gro', '9900000019', '20180217', '20180324 20:47:47'),
(20, 'Im Schatten der Wächter', 'Gardner, Graham ', 4, 24, 0, 29, '', '', 'Freun/Gar', '9900000020', '20180217', '20180324 20:47:47'),
(21, 'Und was ist mit mir?', 'Feth, Monika ', 4, 24, 0, 29, '', '', 'Freun/Fet', '9900000021', '20180217', '20180324 20:47:47'),
(22, 'Der Graf von Monte Christo', 'Dumas, Alexandre ', 4, 24, 0, 29, '', '', 'Freun/Dum', '9900000022', '20180217', '20180324 20:47:47'),
(23, 'Luna', 'Peters, Julie Anne', 4, 24, 0, 29, '', '', 'Freun/Pet', '9900000023', '20180217', '20180324 20:47:47'),
(24, 'Eine wie Alaska', 'Green, John ', 4, 24, 0, 29, '', '', 'Freun/Gre', '9900000024', '20180217', '20180324 20:47:47'),
(25, 'Pawlows Kinder', 'Borowiak, Simon ', 4, 24, 0, 29, '', '', 'Freun/Bor', '9900000025', '20180217', '20180324 20:47:47'),
(26, 'Mauer aus Wut', 'Wahl, Mats ', 4, 24, 0, 29, '', '', 'Freun/Wah', '9900000026', '20180217', '20180324 20:47:47'),
(27, 'Du musst die Wahrheit sagen', 'Wahl, Mats ', 4, 24, 0, 29, '', '', 'Freun/Wah', '9900000027', '20180217', '20180324 20:47:47'),
(28, 'Alles nur ein Spiel?', 'Mariah, ', 4, 24, 0, 29, '', '', 'Freun/Mar', '9900000028', '20180217', '20180324 20:47:47'),
(29, 'Unter Freunden', 'Fuchs, Thomas ', 4, 24, 0, 29, '', '', 'Freun/Fuc', '9900000029', '20180217', '20180324 20:47:47'),
(30, 'Wir Kinder vom Bahnhof Zoo', 'F., Christiane ', 4, 24, 0, 29, '', '', 'Freun/F.,', '9900000030', '20180217', '20180324 20:47:47'),
(31, 'Behalt das Leben lieb', 'Haar, Jaap ter', 4, 24, 0, 29, '', '', 'Freun/Haa', '9900000031', '20180217', '20180324 20:47:47'),
(32, 'Am kürzeren Ende der Sonnenallee', 'Brussig, Thomas ', 4, 24, 0, 29, '', '', 'Freun/Bru', '9900000032', '20180217', '20180324 20:47:47'),
(33, 'Ich hab dir nie einen Rosengarten versprochen', 'Green, Hannah ', 4, 24, 0, 29, '', '', 'Freun/Gre', '9900000033', '20180217', '20180324 20:47:47'),
(34, 'Nicht ohne meine Tochter', 'Mahmoody, Betty ', 4, 24, 0, 29, '', '', 'Freun/Mah', '9900000034', '20180217', '20180324 20:47:47'),
(35, 'Die neuen Leiden des jungen W.', 'Plenzdorf, Ulrich ', 4, 24, 0, 29, '', '', 'Freun/Ple', '9900000035', '20180217', '20180324 20:47:47'),
(36, 'Die Tante Jolesch', 'Torberg, Friedrich ', 4, 24, 0, 29, '', '', 'Freun/Tor', '9900000036', '20180217', '20180324 20:47:47'),
(37, 'Die Erben der Tante Jolesch', 'Torberg, Friedrich ', 4, 24, 0, 29, '', '', 'Freun/Tor', '9900000037', '20180217', '20180324 20:47:47'),
(38, 'Der Abituriententag', 'Werfel, Franz ', 4, 24, 0, 29, '', '', 'Freun/Wer', '9900000038', '20180217', '20180324 20:47:47'),
(39, 'Der menschliche Makel', 'Roth, Philip ', 4, 24, 0, 29, '', '', 'Freun/Rot', '9900000039', '20180217', '20180324 20:47:47'),
(40, 'Kristina, vergiß nicht…', 'Fährmann, Willi ', 4, 24, 0, 29, '', '', 'Freun/Fäh', '9900000040', '20180217', '20180324 20:47:47'),
(41, 'Wüstenblume', 'Dirie, Waris ', 4, 24, 0, 29, '', '', 'Freun/Dir', '9900000041', '20180217', '20180324 20:47:47'),
(42, 'Nomadentochter', 'Dirie, Waris ', 4, 24, 0, 29, '', '', 'Freun/Dir', '9900000042', '20180217', '20180324 20:47:47'),
(43, 'Das Land, in dem man noe ankommt', 'Dhôtel, André ', 4, 24, 0, 29, '', '', 'Freun/Dhô', '9900000043', '20180217', '20180324 20:47:47'),
(44, 'Sansibar oder der letzte Grund', 'Andersch, Alfred ', 4, 24, 0, 29, '', '', 'Freun/And', '9900000044', '20180217', '20180324 20:47:47'),
(45, 'Der Weg in die Traumzeit', 'French, Jackie ', 4, 24, 0, 29, '', '', 'Freun/Fre', '9900000045', '20180217', '20180324 20:47:47'),
(46, 'Der lange Weg des Lukas B.', 'Fährmann, Willi ', 4, 24, 0, 29, '', '', 'Freun/Fäh', '9900000046', '20180217', '20180324 20:47:47'),
(47, 'Denken heisst zum Teufel beten', 'Kirchner, Wolfgang ', 4, 24, 0, 29, '', '', 'Freun/Kir', '9900000047', '20180217', '20180324 20:47:47'),
(48, 'Lucy', 'Kincaid, Jamaica ', 4, 24, 0, 29, '', '', 'Freun/Kin', '9900000048', '20180217', '20180324 20:47:47'),
(49, 'Der Tunnel', 'Kellermann, Bernhard ', 4, 24, 0, 29, '', '', 'Freun/Kel', '9900000049', '20180217', '20180324 20:47:47'),
(50, 'Die Memoiren des Peterhans von Binningen', 'Goetz, Curt ', 4, 24, 0, 29, '', '', 'Freun/Goe', '9900000050', '20180217', '20180324 20:47:47'),
(51, 'Die Macht des Feuers', 'Mankell, Henning ', 4, 24, 0, 29, '', '', 'Freun/Man', '9900000051', '20180217', '20180324 20:47:47'),
(52, 'Ein friedlicher Ort', 'Lingard, Joan ', 4, 24, 0, 29, '', '', 'Freun/Lin', '9900000052', '20180217', '20180324 20:47:47'),
(53, 'Oya Fremde Heimat Türkei', 'König/Straube/Taylan, ', 4, 24, 0, 29, '', '', 'Freun/Kön', '9900000053', '20180217', '20180324 20:47:47'),
(54, 'Madison und die Freiheit der Jugend', 'Klein, Norma ', 4, 24, 0, 29, '', '', 'Freun/Kle', '9900000054', '20180217', '20180324 20:47:47'),
(55, 'Das Große Kishon Buch', 'Müller, Langen ', 4, 24, 0, 29, '', '', 'Freun/Mül', '9900000055', '20180217', '20180324 20:47:47'),
(56, 'Sie haben mich zu einem Ausländer gemacht…', '0', 4, 24, 0, 29, '', '', 'Freun/0', '9900000056', '20180217', '20180324 20:47:47'),
(57, 'Simple', 'Murail, Marie-Aude ', 4, 24, 0, 29, '', '', 'Freun/Mur', '9900000057', '20180217', '20180324 20:47:47'),
(58, 'Simple', 'Murail, Marie-Aude ', 4, 24, 0, 29, '', '', 'Freun/Mur', '9900000058', '20180217', '20180324 20:47:47'),
(59, 'Verkauft', 'McCormick, Patricia ', 4, 24, 0, 29, '', '', 'Freun/McC', '9900000059', '20180217', '20180324 20:47:47'),
(60, 'Marcus Rosenbloom und die Liebe', 'Mazer, Harry ', 4, 24, 0, 29, '', '', 'Freun/Maz', '9900000060', '20180217', '20180324 20:47:47'),
(61, 'Meinst du, der Falke hat uns gesehen?', 'Mazer, Norma ', 4, 24, 0, 29, '', '', 'Freun/Maz', '9900000061', '20180217', '20180324 20:47:47'),
(62, 'Na, Schwesterech?', 'Mazer, Norma ', 4, 24, 0, 29, '', '', 'Freun/Maz', '9900000062', '20180217', '20180324 20:47:47'),
(63, 'Rosalenas Spiegel', 'Provoost, Anne ', 4, 24, 0, 29, '', '', 'Freun/Pro', '9900000063', '20180217', '20180324 20:47:47'),
(64, 'Die Zeit der geheimen Wünsche', 'Procházková, Iva ', 4, 24, 0, 29, '', '', 'Freun/Pro', '9900000064', '20180217', '20180324 20:47:47'),
(65, 'Leihst du mir deinen Blick', 'Zenatti, Valérie ', 4, 24, 0, 29, '', '', 'Freun/Zen', '9900000065', '20180217', '20180324 20:47:47'),
(66, 'Wir treffen uns wieder in meinem Paradies', 'Zachert, Isabell & Christel ', 4, 24, 0, 29, '', '', 'Freun/Zac', '9900000066', '20180217', '20180324 20:47:47'),
(67, 'Nur eine Liste', 'Vivian, Siobhan ', 4, 24, 0, 29, '', '', 'Freun/Viv', '9900000067', '20180217', '20180324 20:47:47'),
(68, 'Salto abwärts', 'Schliwka, Dieter ', 4, 24, 0, 29, '', '', 'Freun/Sch', '9900000068', '20180217', '20180324 20:47:47'),
(69, 'Aristoteles und Dnate entdecken die Geheimnisse des Universums', 'Sáenz, Benjamin Alire ', 4, 24, 0, 29, '', '', 'Freun/Sáe', '9900000069', '20180217', '20180324 20:47:47'),
(70, 'Onkel Toms Hütte', 'Beecher-Stowe, H. ', 5, 11, 0, 29, '', '', 'Histo/Bee', '9900000070', '20180217', '20180324 20:47:47'),
(71, 'Der Stern der Cherokee', 'Carter, Forest ', 5, 11, 0, 29, '', '', 'Histo/Car', '9900000071', '20180217', '20180324 20:47:47'),
(72, 'Der Stern der Cherokee', 'Carter, Forest ', 5, 11, 0, 29, '', '', 'Histo/Car', '9900000072', '20180217', '20180324 20:47:47'),
(73, 'Der fliegende Pfeil', 'Steuben, Fritz ', 5, 11, 0, 29, '', '', 'Histo/Ste', '9900000073', '20180217', '20180324 20:47:47'),
(74, 'Der rote Sturm', 'Steuben, Fritz ', 5, 11, 0, 29, '', '', 'Histo/Ste', '9900000074', '20180217', '20180324 20:47:47'),
(75, 'Hinter den Bergen die Freiheit', 'Sachse, Günter ', 5, 11, 0, 29, '', '', 'Histo/Sac', '9900000075', '20180217', '20180324 20:47:47'),
(76, 'Becky Brown - Versprich nach mir zu suchen', 'Schröder, Rainer M.', 5, 11, 0, 29, '', '', 'Histo/Sch', '9900000076', '20180217', '20180324 20:47:47'),
(77, 'Abby Lynn 1', 'Schröder, Rainer M.', 5, 11, 0, 29, '', '', 'Histo/Sch', '9900000077', '20180217', '20180324 20:47:47'),
(78, 'Abby Lynn 2', 'Schröder, Rainer M.', 5, 11, 0, 29, '', '', 'Histo/Sch', '9900000078', '20180217', '20180324 20:47:47'),
(79, 'Abby Lynn 3', 'Schröder, Rainer M.', 5, 11, 0, 29, '', '', 'Histo/Sch', '9900000079', '20180217', '20180324 20:47:47'),
(80, 'Abby Lynn 4', 'Schröder, Rainer M.', 5, 11, 0, 29, '', '', 'Histo/Sch', '9900000080', '20180217', '20180324 20:47:47'),
(81, 'Die Kinderkarawane', 'Rutgers, An ', 5, 11, 0, 29, '', '', 'Histo/Rut', '9900000081', '20180217', '20180324 20:47:47'),
(82, 'Der Raub des Chinabaumes', 'Hageni, Alfred ', 5, 11, 0, 29, '', '', 'Histo/Hag', '9900000082', '20180217', '20180324 20:47:47'),
(83, 'Sheriffs Räuber TexasRangers', 'Hetmann, Frederik ', 5, 11, 0, 29, '', '', 'Histo/Het', '9900000083', '20180217', '20180324 20:47:47'),
(84, 'Unsere kleine Farm', 'Wilder, Laura Ingalls', 5, 11, 0, 29, '', '', 'Histo/Wil', '9900000084', '20180217', '20180324 20:47:47'),
(85, 'Unsere kleine Farm 2', 'Wilder, Laura Ingalls', 5, 11, 0, 29, '', '', 'Histo/Wil', '9900000085', '20180217', '20180324 20:47:47'),
(86, 'Unsere kleine Farm 3', 'Wilder, Laura Ingalls', 5, 11, 0, 29, '', '', 'Histo/Wil', '9900000086', '20180217', '20180324 20:47:47'),
(87, 'Unsere kleine Farm 4', 'Wilder, Laura Ingalls', 5, 11, 0, 29, '', '', 'Histo/Wil', '9900000087', '20180217', '20180324 20:47:47'),
(88, 'Unsere kleine Farm 5', 'Wilder, Laura Ingalls', 5, 11, 0, 29, '', '', 'Histo/Wil', '9900000088', '20180217', '20180324 20:47:47'),
(89, 'Unsere kleine Farm 6', 'Wilder, Laura Ingalls', 5, 11, 0, 29, '', '', 'Histo/Wil', '9900000089', '20180217', '20180324 20:47:47'),
(90, 'Unsere kleine Farm 7', 'Wilder, Laura Ingalls', 5, 11, 0, 29, '', '', 'Histo/Wil', '9900000090', '20180217', '20180324 20:47:47'),
(91, 'das gläserne Messer', 'Tully, John ', 5, 11, 0, 29, '', '', 'Histo/Tul', '9900000091', '20180217', '20180324 20:47:47'),
(92, 'Roots', 'Haley, Alex ', 5, 11, 0, 29, '', '', 'Histo/Hal', '9900000092', '20180217', '20180324 20:47:47'),
(93, 'Roots', 'Haley, Alex ', 5, 11, 0, 29, '', '', 'Histo/Hal', '9900000093', '20180217', '20180324 20:47:47'),
(94, 'Booker Washington', 'Cabrières, Jean Francois', 5, 11, 0, 29, '', '', 'Histo/Cab', '9900000094', '20180217', '20180324 20:47:47'),
(95, 'Männer übers Meer verweht', 'Hetmann, Frederik ', 5, 17, 0, 29, '', '', 'Histo/Het', '9900000095', '20180217', '20180324 20:47:47'),
(96, 'Kleiner Weg', 'Zitelmann, Arnulf ', 5, 17, 0, 29, '', '', 'Histo/Zit', '9900000096', '20180217', '20180324 20:47:47'),
(97, 'Vom Rentierjäger zum Raubritter', 'Buchholz, Dahmen von – Vos, Tonny ', 5, 17, 0, 29, '', '', 'Histo/Buc', '9900000097', '20180217', '20180324 20:47:47'),
(98, 'Das Dorf am See', 'Grund, Josef Carl', 5, 17, 0, 29, '', '', 'Histo/Gru', '9900000098', '20180217', '20180324 20:47:47'),
(99, 'Die Höhle über dem Fluss', 'Grund, Josef Carl', 5, 17, 0, 29, '', '', 'Histo/Gru', '9900000099', '20180217', '20180324 20:47:47'),
(100, 'Die Stadt der Pferdegöttin', 'Grund, Josef Carl', 5, 17, 0, 29, '', '', 'Histo/Gru', '9900000100', '20180217', '20180324 20:47:47'),
(101, 'Die Höhlenkinder - Im heimlichen Grund', 'Sonnleitner, A. Th.', 5, 17, 0, 29, '', '', 'Histo/Son', '9900000101', '20180217', '20180324 20:47:47'),
(102, 'Die Höhle der weißen Wölfin', 'Beyerlein, Gabriele ', 5, 17, 0, 29, '', '', 'Histo/Bey', '9900000102', '20180217', '20180324 20:47:47'),
(103, 'Die Kette der Dragomira', 'Beyerlein, Gabriele ', 5, 17, 0, 29, '', '', 'Histo/Bey', '9900000103', '20180217', '20180324 20:47:47'),
(104, 'Das vertauschte Kind', 'Sutcliff, Roesemary ', 5, 17, 0, 29, '', '', 'Histo/Sut', '9900000104', '20180217', '20180324 20:47:47'),
(105, 'Die eiserne Lerche', 'Krausnick, Michail ', 5, 18, 0, 29, '', '', 'Histo/Kra', '9900000105', '20180217', '20180324 20:47:47'),
(106, 'Hungerweg- Das Schicksal der Schwabenkinder', 'Lang, Franz Othmar ', 5, 18, 0, 29, '', '', 'Histo/Lan', '9900000106', '20180217', '20180324 20:47:47'),
(107, 'Beeren-Sommer', 'Barth-Grözinger, Inge ', 5, 18, 0, 29, '', '', 'Histo/Bar', '9900000107', '20180217', '20180324 20:47:47'),
(108, 'Berlin, Bülowstraße 80a', 'Beyerlein, Gabriele ', 5, 18, 0, 29, '', '', 'Histo/Bey', '9900000108', '20180217', '20180324 20:47:47'),
(109, 'Die schwarzen Brüder', 'Tetzner, Lisa ', 5, 18, 0, 29, '', '', 'Histo/Tet', '9900000109', '20180217', '20180324 20:47:47'),
(110, 'Donnergrollen', 'Taylor, D. Mildred ', 5, 18, 0, 29, '', '', 'Histo/Tay', '9900000110', '20180217', '20180324 20:47:47'),
(111, 'Wir Kuckuckskinder', 'Pristawkin, Anatoli ', 5, 18, 0, 29, '', '', 'Histo/Pri', '9900000111', '20180217', '20180324 20:47:47'),
(112, 'Emmas Weg in die Freiheit', 'Jeier, Thomas ', 5, 18, 0, 29, '', '', 'Histo/Jei', '9900000112', '20180217', '20180324 20:47:47'),
(113, 'Zeit zu hassen, Zeit zu lieben', 'Fährmann, Willi ', 5, 18, 0, 29, '', '', 'Histo/Fäh', '9900000113', '20180217', '20180324 20:47:47'),
(114, 'Der Mann im Feuer', 'Fährmann, Willi ', 5, 18, 0, 29, '', '', 'Histo/Fäh', '9900000114', '20180217', '20180324 20:47:47'),
(115, 'Ich kann es nicht vergessen', 'Rostkowski, Margaret I.', 5, 18, 0, 29, '', '', 'Histo/Ros', '9900000115', '20180217', '20180324 20:47:47'),
(116, 'Tisha', 'Specht, Robert ', 5, 18, 0, 29, '', '', 'Histo/Spe', '9900000116', '20180217', '20180324 20:47:47'),
(117, 'Cuore', 'Amicis, Edmondo De', 5, 18, 0, 29, '', '', 'Histo/Ami', '9900000117', '20180217', '20180324 20:47:47'),
(118, 'Spuk in der Schule', 'Drinkwater, Carol ', 5, 18, 0, 29, '', '', 'Histo/Dri', '9900000118', '20180217', '20180324 20:47:47'),
(119, 'Gläserne Bienen', 'Jünger, Ernst ', 5, 18, 0, 29, '', '', 'Histo/Jün', '9900000119', '20180217', '20180324 20:47:47'),
(120, 'Völkerschlachtsdenkmal', 'Loest, Erich ', 5, 18, 0, 29, '', '', 'Histo/Loe', '9900000120', '20180217', '20180324 20:47:47'),
(121, 'Die schwarze Muse', 'Hirschberg, Dieter ', 5, 18, 0, 29, '', '', 'Histo/Hir', '9900000121', '20180217', '20180324 20:47:47'),
(122, 'Station Victoria', 'Cuneo, Anne ', 5, 18, 0, 29, '', '', 'Histo/Cun', '9900000122', '20180217', '20180324 20:47:47'),
(123, 'Sturz der Titanen', 'Follett, Ken ', 5, 18, 0, 29, '', '', 'Histo/Fol', '9900000123', '20180217', '20180324 20:47:47'),
(124, 'Der Fluch von Troja', 'Bellinda, ', 5, 18, 0, 29, '', '', 'Histo/Bel', '9900000124', '20180217', '20180324 20:47:47'),
(125, 'Merlin und Artus', 'Sutcliff, Rosemary ', 9, 12, 0, 29, '', '', 'Märch/Sut', '9900000125', '20180217', '20180324 20:47:47'),
(126, 'Merlin und Artus', 'Sutcliff, Rosemary ', 9, 12, 0, 29, '', '', 'Märch/Sut', '9900000126', '20180217', '20180324 20:47:47'),
(127, 'Lanzelot und Ginevra', 'Sutcliff, Rosemary ', 9, 12, 0, 29, '', '', 'Märch/Sut', '9900000127', '20180217', '20180324 20:47:47'),
(128, 'Der Rebell und der Herzog', 'Lux, Hanns Maria', 5, 19, 0, 29, '', '', 'Histo/Lux', '9900000128', '20180217', '20180324 20:47:47'),
(129, 'das Abenteuerliche Leben des Doktor Faust', 'Huby, Felix ', 5, 19, 0, 29, '', '', 'Histo/Hub', '9900000129', '20180217', NULL),
(130, 'Wolfgang Amadé', 'Tornius, Valerian ', 5, 19, 0, 29, '', '', 'Histo/Tor', '9900000130', '20180217', NULL),
(131, 'Wolfsrudel', 'Zwigtmann, Floortje ', 5, 19, 0, 29, '', '', 'Histo/Zwi', '9900000131', '20180217', NULL),
(132, 'Totenbraut', 'Blazon, Nina ', 5, 19, 0, 29, '', '', 'Histo/Bla', '9900000132', '20180217', NULL),
(133, 'das verschwundene Testament der Alice Shadwell', 'Schröder, Rainer M.', 5, 19, 0, 29, '', '', 'Histo/Sch', '9900000133', '20180217', NULL),
(134, 'Der abenteuerliche Simplicissimus', 'Grimmelshausen, Hans Jakob Christoffel von', 5, 19, 0, 29, '', '', 'Histo/Gri', '9900000134', '20180217', NULL),
(135, 'Der abenteuerliche Simplicissimus', 'Grimmelshausen, Hans Jakob Christoffel von', 5, 19, 0, 29, '', '', 'Histo/Gri', '9900000135', '20180217', NULL),
(136, 'Trenck', 'Frank, Bruno ', 5, 19, 0, 29, '', '', 'Histo/Fra', '9900000136', '20180217', NULL),
(137, 'Verrat!', 'Ott, Inge ', 5, 19, 0, 29, '', '', 'Histo/Ott', '9900000137', '20180217', NULL),
(138, 'Die letzte Frist', 'Rasputin, Valentin ', 5, 19, 0, 29, '', '', 'Histo/Ras', '9900000138', '20180217', NULL),
(139, 'Der junge Wiking', 'Buchholz, Dahmen von / Vos, T.', 5, 20, 0, 29, '', '', 'Histo/Buc', '9900000139', '20180217', NULL),
(140, 'Reiter aus der Sonne', 'Grund, Josef Carl', 5, 20, 0, 29, '', '', 'Histo/Gru', '9900000140', '20180217', NULL),
(141, 'Überfall der Wikinger', 'Andersen, Leif Esper', 5, 20, 0, 29, '', '', 'Histo/And', '9900000141', '20180217', NULL),
(142, 'Der goldene Kegel', 'Beyerlein, Gabriele ', 5, 20, 0, 29, '', '', 'Histo/Bey', '9900000142', '20180217', NULL),
(143, 'Der eiserne Heinrich', 'Ludwig, Christa ', 5, 20, 0, 29, '', '', 'Histo/Lud', '9900000143', '20180217', NULL),
(144, 'Mordred, Sohn des Artus', 'Springer, Nancy ', 5, 20, 0, 29, '', '', 'Histo/Spr', '9900000144', '20180217', NULL),
(145, 'Jenseits von Aran', 'Zitelmann, Arnulf ', 5, 20, 0, 29, '', '', 'Histo/Zit', '9900000145', '20180217', NULL),
(146, 'Vom Skamander zum Löwentor', 'Schliemann, Heinrich ', 5, 13, 0, 29, '', '', 'Histo/Sch', '9900000146', '20180217', NULL),
(147, 'Leonidas und seine Dreihundert', 'Renault, Mary ', 5, 13, 0, 29, '', '', 'Histo/Ren', '9900000147', '20180217', NULL),
(148, 'Entscheidung bei Salamis', 'Mondfeld, Wolfram zu', 5, 13, 0, 29, '', '', 'Histo/Mon', '9900000148', '20180217', NULL),
(149, 'Die Trojanerin', 'Allfrey, Katherine ', 5, 13, 0, 29, '', '', 'Histo/All', '9900000149', '20180217', NULL),
(150, 'Die Nacht der Zugvögel', 'Rechlin, Eva ', 5, 13, 0, 29, '', '', 'Histo/Rec', '9900000150', '20180217', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_user`
--

CREATE TABLE `skolib_user` (
  `id` int(3) NOT NULL,
  `Barcode` varchar(20) NOT NULL,
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Vorname` varchar(40) NOT NULL DEFAULT '',
  `login` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lastlogin` varchar(12) NOT NULL,
  `noshow` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `skolib_user`
--

INSERT INTO `skolib_user` (`id`, `Barcode`, `Name`, `Vorname`, `login`, `password`, `email`, `lastlogin`, `noshow`) VALUES
(1, '', 'admin', 'super', 'sadmin', '$2y$10$txY3oujvokc8AmtvFzZ28e1Xavy0MZvZvqLhbHoCG5Nt968.Eji0m', '', '', 1),
(0, '', 'Admin', 'The', 'admin', '$2y$10$C/lGYVUBG8bnL0pdOlzG8eLf2kT2k8.kuLMKHL4.UvNlicZBTAR4.', '', '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skolib_user_rights`
--

CREATE TABLE `skolib_user_rights` (
  `brNr` int(11) NOT NULL,
  `bibNr2` int(11) NOT NULL,
  `LNr` int(11) DEFAULT NULL,
  `user_right` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Zugriffsrechte auf Bibliotheken' ROW_FORMAT=FIXED;

--
-- Daten für Tabelle `skolib_user_rights`
--

INSERT INTO `skolib_user_rights` (`brNr`, `bibNr2`, `LNr`, `user_right`) VALUES
(1, 1, 1, 1),
(0, 0, 0, 4);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `skolib_ausleihe`
--
ALTER TABLE `skolib_ausleihe`
  ADD PRIMARY KEY (`aNr`);

--
-- Indizes für die Tabelle `skolib_barc_forms`
--
ALTER TABLE `skolib_barc_forms`
  ADD PRIMARY KEY (`bfNr`);

--
-- Indizes für die Tabelle `skolib_customer`
--
ALTER TABLE `skolib_customer`
  ADD PRIMARY KEY (`SNr`);

--
-- Indizes für die Tabelle `skolib_customer_data`
--
ALTER TABLE `skolib_customer_data`
  ADD PRIMARY KEY (`WNr`);

--
-- Indizes für die Tabelle `skolib_data_fields`
--
ALTER TABLE `skolib_data_fields`
  ADD PRIMARY KEY (`dfNr`);

--
-- Indizes für die Tabelle `skolib_drop_down`
--
ALTER TABLE `skolib_drop_down`
  ADD PRIMARY KEY (`ddNr`);

--
-- Indizes für die Tabelle `skolib_dwert`
--
ALTER TABLE `skolib_dwert`
  ADD PRIMARY KEY (`dwNr`);

--
-- Indizes für die Tabelle `skolib_library_defaults`
--
ALTER TABLE `skolib_library_defaults`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skolib_login_token`
--
ALTER TABLE `skolib_login_token`
  ADD PRIMARY KEY (`userId`);

--
-- Indizes für die Tabelle `skolib_navigation`
--
ALTER TABLE `skolib_navigation`
  ADD PRIMARY KEY (`mNr`);

--
-- Indizes für die Tabelle `skolib_rights`
--
ALTER TABLE `skolib_rights`
  ADD PRIMARY KEY (`RNr`);

--
-- Indizes für die Tabelle `skolib_signature_rules`
--
ALTER TABLE `skolib_signature_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skolib_signature_settings`
--
ALTER TABLE `skolib_signature_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skolib_titel`
--
ALTER TABLE `skolib_titel`
  ADD PRIMARY KEY (`tNr`);

--
-- Indizes für die Tabelle `skolib_user`
--
ALTER TABLE `skolib_user`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `skolib_user_rights`
--
ALTER TABLE `skolib_user_rights`
  ADD PRIMARY KEY (`brNr`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `skolib_ausleihe`
--
ALTER TABLE `skolib_ausleihe`
  MODIFY `aNr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;
--
-- AUTO_INCREMENT für Tabelle `skolib_barc_forms`
--
ALTER TABLE `skolib_barc_forms`
  MODIFY `bfNr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `skolib_customer`
--
ALTER TABLE `skolib_customer`
  MODIFY `SNr` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=696;
--
-- AUTO_INCREMENT für Tabelle `skolib_data_fields`
--
ALTER TABLE `skolib_data_fields`
  MODIFY `dfNr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT für Tabelle `skolib_drop_down`
--
ALTER TABLE `skolib_drop_down`
  MODIFY `ddNr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
