-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 20. kvě 2025, 12:41
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `sharemoney`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `balance` decimal(12,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `balance`, `created_at`, `updated_at`) VALUES
(11, 'Muj ucet', 0.00, '2025-05-19 11:15:16', '2025-05-19 11:15:16'),
(12, 'Ucet cizi', 0.00, '2025-05-19 11:15:27', '2025-05-19 11:15:27');

-- --------------------------------------------------------

--
-- Struktura tabulky `account_memberships`
--

CREATE TABLE `account_memberships` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('admin','moderator','user') NOT NULL DEFAULT 'user',
  `joined_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `account_memberships`
--

INSERT INTO `account_memberships` (`id`, `account_id`, `user_id`, `role`, `joined_at`) VALUES
(7, 11, 103, 'admin', '2025-05-19 13:15:16'),
(8, 12, 103, 'user', '2025-05-19 13:15:27');

-- --------------------------------------------------------

--
-- Struktura tabulky `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipient_account_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `transaction_types`
--

CREATE TABLE `transaction_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `transaction_types`
--

INSERT INTO `transaction_types` (`id`, `name`) VALUES
(1, 'Platba'),
(2, 'Vklad');

-- --------------------------------------------------------

--
-- Struktura tabulky `members`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar_path` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `members`
--

INSERT INTO `users` (`id`, `name`, `surname`, `username`, `email`, `password`, `avatar_path`, `created_at`, `updated_at`) VALUES
(101, 'Filip', ' Neni', 'FiRak135', 'kokot@sss.czom', '$2y$12$QNYBiWP5lQDijNBolBHklOrD7CczYYbojDVhf6toMClFRblBOUkP6', '', '2025-05-04 17:08:33', '2025-05-06 20:17:40'),
(102, 'Filip', 'Rakus', 'FiRak381', 'rakusfilip@seznam.cz', '$2y$12$Zb6odbPbJxpAMjI15FZDl.y3loqUUAvvgS6.LpjNQGP54/4ni55Uy', '', '2025-05-04 17:14:15', '2025-05-04 17:14:15'),
(103, 'Jan', 'Sobota', 'todob334', 'test@test.com', '$2y$12$KRKjNo/.M5Mm2mmful7sVuoUhikXwtjygkhaOsWWJgqjce.lD0GUK', 'images/profilePhotos/682a00ff404b1.jpg', '2025-05-04 17:16:54', '2025-05-18 15:47:11');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `account_memberships`
--
ALTER TABLE `account_memberships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_account_user` (`account_id`,`user_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pro tabulku `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `idx_transaction_account` (`account_id`),
  ADD KEY `idx_transaction_user` (`user_id`),
  ADD KEY `idx_transaction_type` (`type_id`);

--
-- Indexy pro tabulku `transaction_types`
--
ALTER TABLE `transaction_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `members`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pro tabulku `account_memberships`
--
ALTER TABLE `account_memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `transaction_types`
--
ALTER TABLE `transaction_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pro tabulku `members`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `account_memberships`
--
ALTER TABLE `account_memberships`
  ADD CONSTRAINT `account_memberships_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_memberships_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `transaction_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
