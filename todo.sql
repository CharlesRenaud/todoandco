-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 28 déc. 2025 à 17:09
-- Version du serveur :  10.4.16-MariaDB
-- Version de PHP : 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `todo`
--

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL,
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id`, `created_at`, `title`, `content`, `is_done`, `author_id`) VALUES
(2, '2025-12-02 17:27:05', 'test', 'test', 0, 3),
(3, '2025-12-18 18:00:18', 'New tache', 'test', 0, 4),
(4, '2025-12-18 18:05:25', 'tester', 'tester', 0, 4),
(5, '2025-12-18 18:06:05', 'Création d\'une tache', 'création d\'une tache', 1, 4),
(7, '2025-12-28 17:03:46', 'tache', 'de test', 0, 9);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '["ROLE_USER"]' CHECK (json_valid(`roles`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `roles`) VALUES
(1, 'Charles', '$2y$13$qTzrCl0GgFMveVdzererU.PSXKck0CNkCVrO9ySd74kfjMBLzboZW', 'charles@gmail.com', '[\"ROLE_USER\"]'),
(3, 'anonyme', '$2y$13$7eoahM5PdCkHdoPCDt.ce.jgr4tgjgSNUGKFAym4rX82rGIOzFFfG', 'anonyme@example.com', '[\"ROLE_USER\"]'),
(4, 'Charlitos', '$2y$13$IvAHMvmCi4IeVV6n89fTEuIjFRkm1Fzw74zchtD2RhXMr9lthdqCm', 'charlitos@gmail.com', '[\"ROLE_ADMIN\"]'),
(5, 'test', '$2y$13$2Qz4VecLMdX7p3nkxQdwCulbtHhufm8CXxUY2XM4721YzZ5fyfDM2', 'test@gmail.com', '[\"ROLE_USER\"]'),
(7, 'Testerz', '$2y$13$ezUxdi3I.dpHZcwr.u9q6..T.SI4SIlaQp1W8UhrBFqaifS9jyu9e', 'tester@gmail.com', '[\"ROLE_ADMIN\"]'),
(8, 'admin', '$2y$13$qA8.r/6p.igTA3WFnbBB6u1LrL4TJR9vl8jFfwTab8Kb14gCq/.AW', 'admin@gmail.com', '[\"ROLE_ADMIN\"]'),
(9, 'frank', '$2y$13$Gxqkn3clPaCKotMC1/hJNu8s4KAgbJgDpn24ZepgvoMY.IjASSq7S', 'frank@gmail.com', '[\"ROLE_USER\"]');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_TASK_AUTHOR` (`author_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_TASK_AUTHOR` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
