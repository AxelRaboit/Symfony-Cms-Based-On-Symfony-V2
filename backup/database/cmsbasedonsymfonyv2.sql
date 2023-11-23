-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 23 nov. 2023 à 19:29
-- Version du serveur : 8.0.35-0ubuntu0.22.04.1
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cmsbasedonsymfonyv2`
--

-- --------------------------------------------------------

--
-- Structure de la table `country`
--

CREATE TABLE `country` (
  `id` int NOT NULL,
  `timezone_id` int NOT NULL,
  `alpha2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` int NOT NULL,
  `alpha3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en_gb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_fr_fr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `data_enum`
--

CREATE TABLE `data_enum` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dev_key` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `data_enum`
--

INSERT INTO `data_enum` (`id`, `name`, `category`, `value`, `dev_key`) VALUES
(1, 'DATA_PAGE_BACKEND_LOGIN_DEV_KEY', 'page', NULL, 10000),
(2, 'DATA_PAGE_BACKEND_REGISTER_DEV_KEY', 'page', NULL, 10001),
(3, 'DATA_RECAPTCHA_SECRET_DEV_KEY', 'google', NULL, 10002),
(4, 'DATA_RECAPTCHA_PUBLIC_DEV_KEY', 'google', NULL, 10003),
(5, 'DATA_FRONTEND_MENU_DEV_KEY', 'menu', 'frontend', 10004),
(6, 'DATA_FRONTEND_FOOTER_DEV_KEY', 'footer', 'footer', 10005),
(7, 'DATA_APPLICATION_MENU_DEV_KEY', 'menu', 'application', 10006),
(8, 'DATA_ADMIN_MENU_DEV_KEY', 'menu', 'admin', 10007),
(9, 'DATA_PAGE_ERROR_404_DEV_KEY', 'page', '20000', 10008);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` double DEFAULT NULL,
  `width` double DEFAULT NULL,
  `size` double DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `name`, `height`, `width`, `size`, `category`, `alt`, `created_at`, `updated_at`, `deleted_at`, `title`) VALUES
(1, '6359.jpg', NULL, NULL, NULL, NULL, NULL, '2023-11-23 18:16:06', '2023-11-23 18:16:06', '2023-11-23 18:16:06', NULL),
(2, '6517.jpg', NULL, NULL, NULL, NULL, NULL, '2023-11-23 18:42:04', '2023-11-23 18:42:04', '2023-11-23 18:42:04', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ip_whitelist`
--

CREATE TABLE `ip_whitelist` (
  `id` int NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`id`, `name`, `category`) VALUES
(1, 'frontend', 'frontend');

-- --------------------------------------------------------

--
-- Structure de la table `menu_item`
--

CREATE TABLE `menu_item` (
  `id` int NOT NULL,
  `page_id` int DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `weight` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu_item`
--

INSERT INTO `menu_item` (`id`, `page_id`, `parent_id`, `weight`, `name`) VALUES
(1, 3, NULL, 0, 'Test');

-- --------------------------------------------------------

--
-- Structure de la table `menu_item_menu`
--

CREATE TABLE `menu_item_menu` (
  `menu_item_id` int NOT NULL,
  `menu_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu_item_menu`
--

INSERT INTO `menu_item_menu` (`menu_item_id`, `menu_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `page`
--

CREATE TABLE `page` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `page_type_id` int NOT NULL,
  `banner_id` int DEFAULT NULL,
  `image_thumbnail_id` int DEFAULT NULL,
  `website_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_primary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content_secondary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content_tertiary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content_quaternary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dev_code_route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `dev_key` int DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `canonical_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `page`
--

INSERT INTO `page` (`id`, `parent_id`, `page_type_id`, `banner_id`, `image_thumbnail_id`, `website_id`, `name`, `title`, `template`, `content_primary`, `content_secondary`, `content_tertiary`, `content_quaternary`, `description`, `dev_code_route_name`, `cta_title`, `cta_text`, `cta_url`, `banner_title`, `slug`, `created_at`, `updated_at`, `deleted_at`, `dev_key`, `category`, `meta_description`, `canonical_url`) VALUES
(1, NULL, 1, NULL, NULL, 1, 'Page 404 Not Found', 'Page 404 Not Found', 'page/page-404-not-found.html.twig', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '404', '2023-11-23 08:44:18', '2023-11-23 08:44:18', '2023-11-23 08:44:18', 20000, NULL, 'Page 404 Not Found', NULL),
(2, NULL, 1, 1, NULL, 1, 'Accueil', 'Accueil', 'page/page-default.html.twig', 'Page d\'accueil', NULL, NULL, NULL, 'Description de la page d\'accueil', 'app_home', NULL, NULL, NULL, NULL, '/', '2023-11-23 11:36:00', '2023-11-23 11:36:00', '2023-11-23 11:36:00', 2, NULL, 'Accueil', NULL),
(3, NULL, 1, 2, NULL, 1, 'Test', 'Test', NULL, 'Contenu content primary', 'Contenu content secondary', 'Contenu content tertiary', 'Contenu content quaternary', 'Description test', 'app_test', NULL, NULL, NULL, NULL, 'test', '2023-11-23 14:03:48', '2023-11-23 14:03:48', '2023-11-23 14:03:48', 3, NULL, 'Test', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `page_gallery`
--

CREATE TABLE `page_gallery` (
  `id` int NOT NULL,
  `image_id` int NOT NULL,
  `page_id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_text` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_url` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `weight` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `page_type`
--

CREATE TABLE `page_type` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dev_key` int NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `page_type`
--

INSERT INTO `page_type` (`id`, `name`, `dev_key`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'root', 1, '2023-11-23 08:45:38', '2023-11-23 08:45:38', '2023-11-23 08:45:38');

-- --------------------------------------------------------

--
-- Structure de la table `timezone`
--

CREATE TABLE `timezone` (
  `id` int NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coordinates` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_zone` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `utc_offset` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `utc_dst_offset` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_application`
--

CREATE TABLE `user_application` (
  `id` int NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_backend`
--

CREATE TABLE `user_backend` (
  `id` int NOT NULL,
  `country_id` int NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `website`
--

CREATE TABLE `website` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hostname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `protocol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `website`
--

INSERT INTO `website` (`id`, `name`, `domain`, `email`, `hostname`, `protocol`, `port`) VALUES
(1, 'website', 'localhost', 'axel.raboit@gmail.com', 'localhost', 'http://', '8000');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5373C9663FE997DE` (`timezone_id`);

--
-- Index pour la table `data_enum`
--
ALTER TABLE `data_enum`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ip_whitelist`
--
ALTER TABLE `ip_whitelist`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D754D550C4663E4` (`page_id`),
  ADD KEY `IDX_D754D550727ACA70` (`parent_id`);

--
-- Index pour la table `menu_item_menu`
--
ALTER TABLE `menu_item_menu`
  ADD PRIMARY KEY (`menu_item_id`,`menu_id`),
  ADD KEY `IDX_AC75195C9AB44FE0` (`menu_item_id`),
  ADD KEY `IDX_AC75195CCCD7E912` (`menu_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_140AB620727ACA70` (`parent_id`),
  ADD KEY `IDX_140AB6203F2C6706` (`page_type_id`),
  ADD KEY `IDX_140AB620684EC833` (`banner_id`),
  ADD KEY `IDX_140AB620F73056FE` (`image_thumbnail_id`),
  ADD KEY `IDX_140AB62018F45C82` (`website_id`);

--
-- Index pour la table `page_gallery`
--
ALTER TABLE `page_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BD4B93AF3DA5256D` (`image_id`),
  ADD KEY `IDX_BD4B93AFC4663E4` (`page_id`);

--
-- Index pour la table `page_type`
--
ALTER TABLE `page_type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `timezone`
--
ALTER TABLE `timezone`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_application`
--
ALTER TABLE `user_application`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D401454E7927C74` (`email`);

--
-- Index pour la table `user_backend`
--
ALTER TABLE `user_backend`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D7E8D77BE7927C74` (`email`),
  ADD KEY `IDX_D7E8D77BF92F3E70` (`country_id`);

--
-- Index pour la table `website`
--
ALTER TABLE `website`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `country`
--
ALTER TABLE `country`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `data_enum`
--
ALTER TABLE `data_enum`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `ip_whitelist`
--
ALTER TABLE `ip_whitelist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `page`
--
ALTER TABLE `page`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `page_gallery`
--
ALTER TABLE `page_gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `page_type`
--
ALTER TABLE `page_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `timezone`
--
ALTER TABLE `timezone`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_application`
--
ALTER TABLE `user_application`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_backend`
--
ALTER TABLE `user_backend`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `website`
--
ALTER TABLE `website`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `country`
--
ALTER TABLE `country`
  ADD CONSTRAINT `FK_5373C9663FE997DE` FOREIGN KEY (`timezone_id`) REFERENCES `timezone` (`id`);

--
-- Contraintes pour la table `menu_item`
--
ALTER TABLE `menu_item`
  ADD CONSTRAINT `FK_D754D550727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `menu_item` (`id`),
  ADD CONSTRAINT `FK_D754D550C4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`);

--
-- Contraintes pour la table `menu_item_menu`
--
ALTER TABLE `menu_item_menu`
  ADD CONSTRAINT `FK_AC75195C9AB44FE0` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_AC75195CCCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `page`
--
ALTER TABLE `page`
  ADD CONSTRAINT `FK_140AB62018F45C82` FOREIGN KEY (`website_id`) REFERENCES `website` (`id`),
  ADD CONSTRAINT `FK_140AB6203F2C6706` FOREIGN KEY (`page_type_id`) REFERENCES `page_type` (`id`),
  ADD CONSTRAINT `FK_140AB620684EC833` FOREIGN KEY (`banner_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_140AB620727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `page` (`id`),
  ADD CONSTRAINT `FK_140AB620F73056FE` FOREIGN KEY (`image_thumbnail_id`) REFERENCES `image` (`id`);

--
-- Contraintes pour la table `page_gallery`
--
ALTER TABLE `page_gallery`
  ADD CONSTRAINT `FK_BD4B93AF3DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_BD4B93AFC4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`);

--
-- Contraintes pour la table `user_backend`
--
ALTER TABLE `user_backend`
  ADD CONSTRAINT `FK_D7E8D77BF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
