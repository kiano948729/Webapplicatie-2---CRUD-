-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Gegenereerd op: 13 mei 2025 om 09:08
-- Serverversie: 5.7.44
-- PHP-versie: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reizen`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `accommodaties`
--

CREATE TABLE `accommodaties` (
  `accommodation_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('hostel','hotel','huis','camping','anders') NOT NULL,
  `location` varchar(100) NOT NULL,
  `description` text,
  `photo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `accommodaties`
--

INSERT INTO `accommodaties` (`accommodation_id`, `name`, `type`, `location`, `description`, `photo_url`) VALUES
(1, 'Beach House Zandvoort', 'huis', 'Zandvoort, Nederland', 'Prachtig strandhuis vlak aan zee met uitzicht.', NULL),
(2, 'Hotel Central Amsterdam', 'hotel', 'Amsterdam, Nederland', 'Luxe hotel midden in het centrum van Amsterdam.', NULL),
(3, 'Sunset Apartment', 'anders', 'Barcelona, Spanje', 'Modern appartement met uitzicht op de zee.', NULL),
(4, 'Mountain Retreat', 'huis', 'Zermatt, Zwitserland', 'Rustige berghut ideaal voor wintersport en ontspanning.', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin1', 'admin', 'admin1@example.com', '2025-05-08 08:45:54'),
(2, 'admin2', 'admin', 'admin2@example.com', '2025-05-08 08:45:54');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `boekingen`
--

CREATE TABLE `boekingen` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `booking_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text,
  `review_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registration_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `vakantie_deals`
--

CREATE TABLE `vakantie_deals` (
  `deal_id` int(11) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `backpacking_type` enum('natuur','stedelijk') NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `created_by_admin` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `vakantie_deals`
--

INSERT INTO `vakantie_deals` (`deal_id`, `destination`, `description`, `price`, `accommodation_id`, `backpacking_type`, `photo_url`, `created_by_admin`, `created_at`) VALUES
(5, 'Zandvoort, Nederland', 'Geniet van een verblijf in een prachtig strandhuis, perfect voor koppels en gezinnen.', 159.99, 1, 'natuur', 'images/zandvoort.jpg', 1, '2025-05-08 08:46:08'),
(6, 'Amsterdam, Nederland', 'Ervaar het bruisende stadsleven in een luxe hotel in het hart van Amsterdam.', 220.00, 2, 'stedelijk', 'images/amsterdam.jpg', 2, '2025-05-08 08:46:08'),
(7, 'Barcelona, Spanje', 'Moderne accommodatie dicht bij het strand met zonnig balkon.', 189.50, 3, 'stedelijk', 'images/barcelona.jpg', 1, '2025-05-08 08:46:08'),
(8, 'Zermatt, Zwitserland', 'Berghut met uitzicht op de Alpen, perfect voor natuurliefhebbers en wintersport.', 245.75, 4, 'natuur', 'images/zermatt.jpg', 2, '2025-05-08 08:46:08'),
(10, 'test', 'test', 45.00, 4, 'natuur', 'test', 1, '2025-05-08 09:37:08'),
(15, 'test', 'test', 45.00, 4, 'natuur', 'test', 1, '2025-05-08 09:37:08'),
(95, 'test', 'test', 45.00, 4, 'natuur', 'test', 1, '2025-05-08 09:37:40');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `accommodaties`
--
ALTER TABLE `accommodaties`
  ADD PRIMARY KEY (`accommodation_id`);

--
-- Indexen voor tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `boekingen`
--
ALTER TABLE `boekingen`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `deal_id` (`deal_id`);

--
-- Indexen voor tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `deal_id` (`deal_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `vakantie_deals`
--
ALTER TABLE `vakantie_deals`
  ADD PRIMARY KEY (`deal_id`),
  ADD KEY `accommodation_id` (`accommodation_id`),
  ADD KEY `created_by_admin` (`created_by_admin`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `accommodaties`
--
ALTER TABLE `accommodaties`
  MODIFY `accommodation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `boekingen`
--
ALTER TABLE `boekingen`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `vakantie_deals`
--
ALTER TABLE `vakantie_deals`
  MODIFY `deal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `boekingen`
--
ALTER TABLE `boekingen`
  ADD CONSTRAINT `boekingen_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `boekingen_ibfk_2` FOREIGN KEY (`deal_id`) REFERENCES `vakantie_deals` (`deal_id`);

--
-- Beperkingen voor tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`deal_id`) REFERENCES `vakantie_deals` (`deal_id`);

--
-- Beperkingen voor tabel `vakantie_deals`
--
ALTER TABLE `vakantie_deals`
  ADD CONSTRAINT `vakantie_deals_ibfk_1` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodaties` (`accommodation_id`),
  ADD CONSTRAINT `vakantie_deals_ibfk_2` FOREIGN KEY (`created_by_admin`) REFERENCES `admins` (`admin_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
