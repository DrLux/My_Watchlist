-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Creato il: Lug 14, 2017 alle 15:49
-- Versione del server: 10.1.19-MariaDB
-- Versione PHP: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tvsd`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `puntata`
--

CREATE TABLE `puntata` (
  `punt_id_serie` int(10) UNSIGNED NOT NULL,
  `stagione` int(2) NOT NULL,
  `num_puntata` int(2) NOT NULL,
  `punt_lingua` varchar(3) NOT NULL,
  `data` date NOT NULL,
  `trama` text,
  `id_img_puntata` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `puntata`
--

INSERT INTO `puntata` (`punt_id_serie`, `stagione`, `num_puntata`, `punt_lingua`, `data`, `trama`, `id_img_puntata`) VALUES
(247808, 7, 1, 'eng', '2017-07-12', 'Mike returns to Pearson Specter Litt as Harvey takes the reins. Louis manages the new class of associates, while Donna and Rachel find their places in the new world order.', 6042593),
(247808, 7, 2, 'eng', '2017-07-19', 'Harvey butts heads with his partners over a bold move. Mike pursues a pro bono case with the legal clinic. Donna''s actions raise tough questions at the firm.', 6042596),
(247808, 7, 3, 'eng', '2017-07-26', NULL, 6042597),
(247808, 7, 4, 'eng', '2017-08-02', NULL, 6042598),
(247808, 7, 5, 'eng', '2017-08-09', NULL, 6042599),
(247808, 7, 6, 'eng', '2017-08-16', NULL, 6042600),
(247808, 7, 7, 'eng', '2017-08-23', NULL, 6042601),
(247808, 7, 8, 'eng', '2017-08-30', NULL, 6042602),
(290853, 3, 1, 'eng', '2017-06-04', 'In the third season opener, the Clark family find themselves in a dire predicament and must work together to discover a path to safety.', 6006914),
(290853, 3, 2, 'eng', '2017-06-04', 'Following a harrowing journey, the Clark family arrive at their new home; and Strand faces resistance as he attempts to hold power over his domain.', 6032007),
(290853, 3, 3, 'eng', '2017-06-11', 'Still finding their place, Alicia and Nick fall in with new crowds while Madison discovers Otto''s past mimics that of her own.', 6032578),
(290853, 3, 4, 'eng', '2017-06-18', 'A mysterious character searches for purpose and soon becomes tied to the struggle over a key resource in the apocalypse.', 6076425),
(290853, 3, 5, 'eng', '2017-06-25', 'An oncoming threat disrupts peace; Madison and Troy search for answers; Alicia must reconcile with her past.', 6076426),
(290853, 3, 6, 'eng', '2017-07-02', 'Loyalty wavers at the Ranch; news of incoming danger spreads in the community; Madison struggles to keep everyone together; Nick grapples with a hard truth.', 6076427),
(290853, 3, 7, 'eng', '2017-07-09', 'In part one of the midseason finale, a new arrival sows a divide within the ranch, while Alicia forms a new relationship in hopes of maintaining peace.', 6076428),
(290853, 3, 8, 'eng', '2017-07-09', 'In the conclusion of the midseason finale, Madison must negotiate the terms of an agreement in the midst of ranch-wide turmoil; and Nick and Alicia challenge their mother''s motives.', 6132954),
(290853, 3, 9, 'eng', '2017-09-10', NULL, 6184791),
(290853, 3, 10, 'eng', '2017-09-10', NULL, 6186082);

-- --------------------------------------------------------

--
-- Struttura della tabella `serie`
--

CREATE TABLE `serie` (
  `id_serie` int(10) UNSIGNED NOT NULL,
  `serie_lingua` varchar(3) NOT NULL,
  `banner_img` varchar(60) NOT NULL,
  `ultima_mod` date NOT NULL,
  `status` varchar(10) NOT NULL,
  `nome_serie` varchar(50) NOT NULL,
  `stagioni` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `serie`
--

INSERT INTO `serie` (`id_serie`, `serie_lingua`, `banner_img`, `ultima_mod`, `status`, `nome_serie`, `stagioni`) VALUES
(247808, 'eng', 'graphical/247808-g17.jpg', '2017-07-14', 'Continuing', 'Suits', 7),
(290853, 'eng', 'graphical/290853-g5.jpg', '2017-07-14', 'Continuing', 'Fear the Walking Dead', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id_utente` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `img_path` varchar(50) NOT NULL DEFAULT 'img/user_icon.png',
  `compleanno` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id_utente`, `email`, `username`, `password`, `img_path`, `compleanno`) VALUES
(52, 'admin@tweb.it', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'img/user_icon.png', '0000-00-00');

-- --------------------------------------------------------

--
-- Struttura della tabella `watchlist`
--

CREATE TABLE `watchlist` (
  `wl_id_utente` bigint(10) UNSIGNED NOT NULL,
  `wl_id_serie` int(10) UNSIGNED NOT NULL,
  `wl_lingua` varchar(3) NOT NULL,
  `data_aggiunta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `watchlist`
--

INSERT INTO `watchlist` (`wl_id_utente`, `wl_id_serie`, `wl_lingua`, `data_aggiunta`) VALUES
(52, 247808, 'eng', '2017-07-14'),
(52, 290853, 'eng', '2017-07-14');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `puntata`
--
ALTER TABLE `puntata`
  ADD PRIMARY KEY (`punt_id_serie`,`stagione`,`num_puntata`,`punt_lingua`),
  ADD KEY `puntata_serie` (`punt_id_serie`,`punt_lingua`);

--
-- Indici per le tabelle `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`id_serie`,`serie_lingua`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id_utente`),
  ADD UNIQUE KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`wl_id_utente`,`wl_id_serie`,`wl_lingua`),
  ADD KEY `watchlist_serie` (`wl_id_serie`,`wl_lingua`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id_utente` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT per la tabella `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `wl_id_utente` bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `puntata`
--
ALTER TABLE `puntata`
  ADD CONSTRAINT `puntata_serie` FOREIGN KEY (`punt_id_serie`,`punt_lingua`) REFERENCES `serie` (`id_serie`, `serie_lingua`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_serie` FOREIGN KEY (`wl_id_serie`,`wl_lingua`) REFERENCES `serie` (`id_serie`, `serie_lingua`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `watchlist_utente` FOREIGN KEY (`wl_id_utente`) REFERENCES `utente` (`id_utente`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
