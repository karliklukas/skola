-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 18. lis 2018, 13:33
-- Verze serveru: 5.7.14
-- Verze PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `stavebniny`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatele`
--

CREATE TABLE `uzivatele` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `heslo` varchar(500) COLLATE utf8_czech_ci NOT NULL,
  `opravneni` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `uzivatele`
--

INSERT INTO `uzivatele` (`id`, `jmeno`, `prijmeni`, `email`, `heslo`, `opravneni`) VALUES
(3, 'Monika', 'Kopřivová', 'monika@koprivova.cz', '$2y$10$oX7f4IiuLkGZ9VOFrwIcF.xb4dMSBpo2YzcUoBPWfDPNwBwTdtpzi', 1),
(7, 'Nela', 'Kopřivová', 'nela@koprivova.cz', '$2y$10$0J2MEeTUOu8OgpCtEavpcONJVrz74xoQjcPt2UjASD2B9T0eQLyw.', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `zbozi`
--

CREATE TABLE `zbozi` (
  `id` int(11) NOT NULL,
  `nazev` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `popis` varchar(150) COLLATE utf8_czech_ci NOT NULL,
  `cena` float NOT NULL,
  `mnozstvi` int(11) NOT NULL,
  `sleva` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `zbozi`
--

INSERT INTO `zbozi` (`id`, `nazev`, `popis`, `cena`, `mnozstvi`, `sleva`) VALUES
(1, 'Šroub', '2cm, černý, točivý', 2, 50, 10),
(2, 'Kladivo', '30cm rukojeť, 10 cm hlavice, černé, kovové', 49.9, 100, 0),
(3, 'Pila', 'dřevěná, velká', 55, 20, 0),
(4, 'Hřebík', '2cm, kovový', 2.5, 500, 0);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `zbozi`
--
ALTER TABLE `zbozi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pro tabulku `zbozi`
--
ALTER TABLE `zbozi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
