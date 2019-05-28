-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 21 mei 2019 om 14:17
-- Serverversie: 10.1.29-MariaDB
-- PHP-versie: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `excellenttaste`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `artikel`
--

CREATE TABLE `artikel` (
  `artikel_id` int(11) NOT NULL,
  `naam` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `prijs` double(5,2) NOT NULL,
  `subgerecht` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `artikel`
--

INSERT INTO `artikel` (`artikel_id`, `naam`, `prijs`, `subgerecht`) VALUES
(7, 'Koffie', 3.25, 1),
(8, 'Fanta', 1.50, 1),
(9, 'Appeltaart', 3.50, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bestelling`
--

CREATE TABLE `bestelling` (
  `bestelling_id` int(11) NOT NULL,
  `tafel` int(11) NOT NULL,
  `datum` date NOT NULL,
  `tijd` time NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `aantal` int(11) NOT NULL,
  `reserveringinfo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bestelling`
--

INSERT INTO `bestelling` (`bestelling_id`, `tafel`, `datum`, `tijd`, `artikel_id`, `aantal`, `reserveringinfo`) VALUES
(1, 3, '2019-05-21', '20:15:00', 8, 2, 2),
(2, 4, '2019-05-21', '09:00:00', 7, 2, 3),
(3, 4, '2019-05-21', '19:00:00', 9, 2, 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gerecht`
--

CREATE TABLE `gerecht` (
  `gerechtcode` int(11) NOT NULL,
  `gerecht` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klant`
--

CREATE TABLE `klant` (
  `klant_ID` int(11) NOT NULL,
  `naam` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `telefoon` varchar(15) CHARACTER SET utf8mb4 NOT NULL,
  `straat` varchar(75) CHARACTER SET utf8mb4 NOT NULL,
  `postcode` char(10) CHARACTER SET utf8mb4 NOT NULL,
  `plaats` varchar(75) CHARACTER SET utf8mb4 NOT NULL,
  `land` varchar(75) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `klant`
--

INSERT INTO `klant` (`klant_ID`, `naam`, `telefoon`, `straat`, `postcode`, `plaats`, `land`) VALUES
(1, 'Jansen', '0612345678', 'Vrijheidslaan 1a', '4532JA', 'Emst', 'Nederland'),
(2, 'Faoud', '0369876543', 'Stalinlaan 3b', '9819IK', 'Houwerzijl', 'Nederland'),
(3, 'Mevrouw Pietersen', '0366543987', ' Parijsstraat 5z', '7823FY', 'Vuren', 'Nederland'),
(19, 'Withaar', '0606060606', 'Leidschestraat 1a', '0009OK', 'Edam', 'Nederland');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reservering`
--

CREATE TABLE `reservering` (
  `reservering_id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `aantal` int(11) NOT NULL,
  `gebruikt` tinyint(1) NOT NULL,
  `allergie` text CHARACTER SET utf8mb4 NOT NULL,
  `opmerkingen` text CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `reservering`
--

INSERT INTO `reservering` (`reservering_id`, `klant_id`, `aantal`, `gebruikt`, `allergie`, `opmerkingen`) VALUES
(1, 3, 4, 0, '', ''),
(2, 1, 2, 1, '', ''),
(3, 2, 5, 0, '', ''),
(36, 19, 4, 0, 'Notenallergie', 'Graag bij het raam');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reserveringinfo`
--

CREATE TABLE `reserveringinfo` (
  `reservering_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `tijd` time NOT NULL,
  `tafel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `reserveringinfo`
--

INSERT INTO `reserveringinfo` (`reservering_id`, `datum`, `tijd`, `tafel`) VALUES
(1, '2016-04-20', '18:00:00', 1),
(2, '2016-04-20', '19:00:00', 3),
(3, '2016-04-20', '18:00:00', 4),
(36, '2019-05-26', '19:00:00', 10);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `subgerecht`
--

CREATE TABLE `subgerecht` (
  `gerechtid` int(11) NOT NULL,
  `subgerechtid` int(11) NOT NULL,
  `subgerecht` varchar(50) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `subgerecht`
--

INSERT INTO `subgerecht` (`gerechtid`, `subgerechtid`, `subgerecht`) VALUES
(1, 1, 'drankjes'),
(2, 2, 'eten');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`artikel_id`);

--
-- Indexen voor tabel `bestelling`
--
ALTER TABLE `bestelling`
  ADD PRIMARY KEY (`bestelling_id`);

--
-- Indexen voor tabel `klant`
--
ALTER TABLE `klant`
  ADD PRIMARY KEY (`klant_ID`);

--
-- Indexen voor tabel `reservering`
--
ALTER TABLE `reservering`
  ADD PRIMARY KEY (`reservering_id`);

--
-- Indexen voor tabel `reserveringinfo`
--
ALTER TABLE `reserveringinfo`
  ADD PRIMARY KEY (`reservering_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `artikel`
--
ALTER TABLE `artikel`
  MODIFY `artikel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `klant`
--
ALTER TABLE `klant`
  MODIFY `klant_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT voor een tabel `reserveringinfo`
--
ALTER TABLE `reserveringinfo`
  MODIFY `reservering_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
