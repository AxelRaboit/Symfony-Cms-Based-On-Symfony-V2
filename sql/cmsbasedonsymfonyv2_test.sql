-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 18 déc. 2023 à 14:48
-- Version du serveur : 8.0.35-0ubuntu0.22.04.1
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cmsbasedonsymfonyv2_test`
--

-- --------------------------------------------------------

--
-- Structure de la table `backend_message`
--

CREATE TABLE `backend_message` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_by_sender_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_by_receiver_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `backend_message`
--

INSERT INTO `backend_message` (`id`, `sender_id`, `receiver_id`, `subject`, `message`, `created_at`, `updated_at`, `deleted_at`, `deleted_by_sender_at`, `deleted_by_receiver_at`, `is_read`) VALUES
(36, 67, 59, NULL, 'Message envoyé par remi 1', '2023-12-04 18:57:58', '2023-12-04 19:22:09', NULL, NULL, NULL, 1),
(37, 67, 59, NULL, 'Test', '2023-12-04 19:24:11', '2023-12-05 05:23:03', NULL, NULL, NULL, 1),
(38, 67, 59, 'Mon sujet', 'Test', '2023-12-04 19:59:28', '2023-12-04 19:59:28', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `data_enum`
--

CREATE TABLE `data_enum` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dev_key` int NOT NULL,
  `is_system` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `data_enum`
--

INSERT INTO `data_enum` (`id`, `name`, `category`, `value`, `dev_key`, `is_system`) VALUES
(1, 'DATA_PAGE_BACKEND_LOGIN_DEV_KEY', 'page', NULL, 10000, 1),
(2, 'DATA_PAGE_BACKEND_REGISTER_DEV_KEY', 'page', NULL, 10001, 1),
(3, 'DATA_RECAPTCHA_SECRET_DEV_KEY', 'google', '6Lf7GDUpAAAAAHhmjyIgpBGDYfOMJkyZI3i9S3v0', 10002, 1),
(4, 'DATA_RECAPTCHA_PUBLIC_DEV_KEY', 'google', '6Lf7GDUpAAAAABu_UY8xBEFAhGRIb_CCk3IQk4_f', 10003, 1),
(5, 'DATA_FRONTEND_MENU_DEV_KEY', 'menu', 'frontend', 10004, 1),
(6, 'DATA_FRONTEND_FOOTER_DEV_KEY', 'footer', 'footer', 10005, 1),
(7, 'DATA_APPLICATION_MENU_DEV_KEY', 'menu', 'application', 10006, 1),
(8, 'DATA_ADMIN_MENU_DEV_KEY', 'menu', 'admin', 10007, 1),
(9, 'DATA_PAGE_ERROR_404_DEV_KEY', 'page', '46', 10008, 1),
(36, 'DATA_PAGE_HOMEPAGE_DEV_KEY', 'page', '74', 10009, 1),
(41, 'DATA_PAGE_CONTACT_DEV_KEY', 'page', '73', 10010, 1),
(42, 'DATA_TEMPLATE_EMAIL_CONTACT_DEV_KEY', 'email', 'email/template/default-contact.html.twig', 10011, 1);

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
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `name`, `height`, `width`, `size`, `category`, `alt`, `created_at`, `updated_at`, `deleted_at`, `title`, `description`) VALUES
(1, '6359.jpg', NULL, NULL, NULL, NULL, NULL, '2023-11-23 18:16:06', '2023-11-23 18:16:06', '2023-11-23 18:16:06', NULL, NULL),
(2, '6517.jpg', NULL, NULL, NULL, 'Categorie de l\'image', 'Text alternatif de l\'image', '2023-11-23 18:42:04', '2023-12-01 09:45:11', '2023-11-23 18:42:04', 'Nom de l\'image', 'Description de l\'image'),
(69, '656e5b630c929781470480.jpeg', NULL, NULL, 1213291, NULL, NULL, '2023-12-04 23:06:11', '2023-12-04 23:06:11', NULL, NULL, NULL);

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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu_item`
--

INSERT INTO `menu_item` (`id`, `page_id`, `parent_id`, `weight`, `name`) VALUES
(1, NULL, NULL, 0, 'Menu1'),
(2, 21, 1, 0, 'Menu2'),
(3, 22, NULL, 0, 'Menu3'),
(5, 23, NULL, 0, 'Test solo');

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
(1, 1),
(2, 1),
(3, 1),
(5, 1);

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
  `thumbnail_id` int DEFAULT NULL,
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
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `dev_key` int DEFAULT NULL,
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `canonical_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `banner_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible_for_backend_actions` tinyint(1) NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` int NOT NULL,
  `display_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` int NOT NULL,
  `is_seo_no_follow` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `page`
--

INSERT INTO `page` (`id`, `parent_id`, `page_type_id`, `banner_id`, `thumbnail_id`, `website_id`, `name`, `title`, `template`, `content_primary`, `content_secondary`, `content_tertiary`, `content_quaternary`, `description`, `dev_code_route_name`, `cta_title`, `cta_text`, `cta_url`, `slug`, `created_at`, `updated_at`, `deleted_at`, `dev_key`, `meta_description`, `canonical_url`, `published_at`, `banner_title`, `visible_for_backend_actions`, `meta_title`, `weight`, `display_type`, `state`, `is_seo_no_follow`) VALUES
(5, NULL, 1, NULL, 69, 1, 'S\'enregistrer', 'S\'enregistrer', NULL, NULL, NULL, NULL, NULL, NULL, 'app_backend_register', NULL, NULL, NULL, 'backend/inscription', '2023-11-24 09:09:12', '2023-12-17 10:58:15', NULL, 68, 'S\'enregistrer', NULL, NULL, NULL, 0, NULL, 0, 'detail', 2, 0),
(6, NULL, 1, NULL, NULL, 1, 'Connexion', 'Connexion', NULL, NULL, NULL, NULL, NULL, NULL, 'app_backend_login', NULL, NULL, NULL, 'backend/connexion', '2023-11-24 13:03:05', '2023-12-03 19:43:31', NULL, 48, 'Connexion', NULL, NULL, NULL, 0, NULL, 0, 'detail', 2, 0),
(7, NULL, 1, NULL, NULL, 1, 'Déconnexion', 'Déconnexion', NULL, NULL, NULL, NULL, NULL, NULL, 'app_backend_logout', NULL, NULL, NULL, 'backend/deconnexion', '2023-11-24 14:04:05', '2023-12-03 19:43:24', NULL, 47, 'Déconnexion', NULL, NULL, NULL, 0, NULL, 0, 'detail', 2, 0),
(16, NULL, 1, 2, NULL, 1, 'Accueil', 'Accueil', 'frontend/page/page-default.html.twig', 'Page d\'accueil', NULL, NULL, NULL, 'Description de la page d\'accueil', NULL, NULL, NULL, NULL, '/', '2023-11-30 18:36:30', '2023-12-18 12:33:50', NULL, 74, NULL, NULL, NULL, NULL, 0, NULL, 0, 'detail', 2, 0),
(18, NULL, 1, NULL, NULL, 1, 'Page 404', 'Page 404', 'exceptions/page-404-not-found.html.twig', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '404', '2023-12-01 12:25:28', '2023-12-03 19:43:09', NULL, 46, NULL, NULL, NULL, NULL, 0, NULL, 0, 'detail', 2, 0),
(21, NULL, 2, 1, NULL, 1, 'Test', 'Test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'testtt', '2023-12-01 14:26:09', '2023-12-03 22:46:57', NULL, 61, NULL, NULL, NULL, NULL, 1, NULL, 0, 'detail', 2, 0),
(22, NULL, 2, 2, NULL, 1, 'Test 2', 'Test 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test-2', '2023-12-03 22:48:11', '2023-12-17 16:17:22', NULL, 72, NULL, NULL, NULL, NULL, 0, NULL, 0, 'detail', 1, 0),
(23, NULL, 1, NULL, NULL, 1, 'Page de test', 'Page de test', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test', '2023-12-04 23:05:51', '2023-12-17 13:48:18', NULL, 71, NULL, NULL, NULL, NULL, 0, NULL, 0, 'detail', 2, 1),
(24, NULL, 1, NULL, NULL, 1, 'Contactez-nous', 'Contactez-nous', 'frontend/contact/page-contact.html.twig', NULL, NULL, NULL, NULL, NULL, 'app_contact', NULL, NULL, NULL, 'contactez-nous', '2023-12-17 17:16:23', '2023-12-17 17:16:23', NULL, 73, NULL, NULL, NULL, NULL, 0, NULL, 0, 'detail', 2, 0);

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
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `url_prefix` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `page_type`
--

INSERT INTO `page_type` (`id`, `name`, `dev_key`, `created_at`, `updated_at`, `deleted_at`, `template`, `url_prefix`) VALUES
(1, 'root', 1, '2023-11-23 08:45:38', '2023-11-23 08:45:38', '2023-11-23 08:45:38', NULL, '/'),
(2, 'testt', 6, '2023-11-24 08:49:04', '2023-12-17 14:34:11', '2023-11-24 08:49:04', 'frontend/page/page-default.html.twig', '/testt');

-- --------------------------------------------------------

--
-- Structure de la table `user_application`
--

CREATE TABLE `user_application` (
  `id` int NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `last_login_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_backend`
--

CREATE TABLE `user_backend` (
  `id` int NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `last_login_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_backend`
--

INSERT INTO `user_backend` (`id`, `email`, `roles`, `password`, `username`, `created_at`, `updated_at`, `deleted_at`, `last_login_at`) VALUES
(59, 'axel.raboit@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$gOaUpVosEstTVuWoHR6btOBZQcshcjlUsWYHxHR9SzoB/adCObIuC', 'AxelR', '2023-11-26 21:49:07', '2023-12-03 11:41:41', NULL, '2023-12-18 13:43:21'),
(60, 'axel1@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$24lpZT2A8hyFDO9ENrplV.RSH8mibWrG6.Ds4B1UP25jsayDJJ9tO', 'Axel1', '2023-11-26 21:51:19', '2023-11-26 21:51:19', NULL, NULL),
(67, 'remi@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$SWAXTkamTReaU3oN8GTuc.Ud8Gzwr5v0zgdXamA7hThbPdbUHx3Lq', 'Remi', '2023-11-26 21:52:56', '2023-11-27 20:30:38', NULL, '2023-12-04 19:59:06'),
(70, 'john@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$XU7N5VKaw7UenQ2jw7d8T.xVgccgFhg8RyR0ed/r3/iYSeNxzbnQW', 'JohnD', '2023-11-26 21:53:41', '2023-11-26 22:05:46', NULL, NULL),
(71, 'ax666@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$IzcCc.7nbxUehtXyg3d7G.0FkJwKGlZRIqpcCMTa6yBkzcklsNNka', 'AxelR7', '2023-11-27 19:31:18', '2023-11-27 19:31:18', NULL, NULL),
(72, 'axelzsz@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$CV51qw3fNzMZ1g.CFke5ReToEI89480iyhJnc0jinya4yUAsbRlhy', 'AxelR67', '2023-11-27 20:31:08', '2023-11-27 20:31:08', NULL, NULL),
(73, 'axelssss.raboit@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$u9niXq1/pCwhaifmNSDGueIGvJH9qMHhsfWUwRQR2YgmNIXdowK8i', 'AxelR', '2023-11-27 22:27:54', '2023-11-27 22:27:54', NULL, NULL),
(74, 'ax1123@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$BmoM8Xh88rTVGM0G0nggyu885fFUJbIpqvnkunvf4dqmsR6YGT6lm', 'AxelS', '2023-11-27 22:28:25', '2023-11-27 22:28:25', NULL, NULL),
(75, 'axsel.raboit@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$fipDTIuFPKlcwLXdJEYoUOmr/5cTbISkoBCJoDRI.MdR0.mE9yqLC', 'AxelR', '2023-11-27 22:28:49', '2023-11-27 22:28:49', NULL, NULL),
(77, 'kim@gmail.com', '[\"ROLE_BACKEND\"]', '$2y$13$NWyjZ2ZkzQsaLvDj/vOyp.JXBtZ/KEZ8UyPLu4hHrK9SHaK6d.Z4W', 'kim', '2023-12-04 23:03:15', '2023-12-04 23:03:15', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_backend_information`
--

CREATE TABLE `user_backend_information` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `picture_profile_size` double DEFAULT NULL,
  `picture_profile_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `deleted_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_backend_information`
--

INSERT INTO `user_backend_information` (`id`, `user_id`, `picture_profile_size`, `picture_profile_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 59, 78762, '656ba37e954f7568482713.jpg', '2023-12-02 18:18:56', '2023-12-03 11:41:41', NULL);

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
  `port` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `website`
--

INSERT INTO `website` (`id`, `name`, `domain`, `email`, `hostname`, `protocol`, `port`) VALUES
(1, 'myWebsite', 'localhost', 'axel.raboit@gmail.com', 'localhost', 'http://', 8000);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `backend_message`
--
ALTER TABLE `backend_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8EB53726F624B39D` (`sender_id`),
  ADD KEY `IDX_8EB53726CD53EDB6` (`receiver_id`);

--
-- Index pour la table `data_enum`
--
ALTER TABLE `data_enum`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dev_key_unique` (`dev_key`);

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
  ADD UNIQUE KEY `slug_unique` (`slug`),
  ADD KEY `IDX_140AB620727ACA70` (`parent_id`),
  ADD KEY `IDX_140AB6203F2C6706` (`page_type_id`),
  ADD KEY `IDX_140AB620684EC833` (`banner_id`),
  ADD KEY `IDX_140AB62018F45C82` (`website_id`),
  ADD KEY `IDX_140AB620FDFF2E92` (`thumbnail_id`);

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
  ADD UNIQUE KEY `UNIQ_D7E8D77BE7927C74` (`email`);

--
-- Index pour la table `user_backend_information`
--
ALTER TABLE `user_backend_information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_502B13CAA76ED395` (`user_id`);

--
-- Index pour la table `website`
--
ALTER TABLE `website`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `backend_message`
--
ALTER TABLE `backend_message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `data_enum`
--
ALTER TABLE `data_enum`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `ip_whitelist`
--
ALTER TABLE `ip_whitelist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `page`
--
ALTER TABLE `page`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `page_gallery`
--
ALTER TABLE `page_gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `page_type`
--
ALTER TABLE `page_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user_application`
--
ALTER TABLE `user_application`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_backend`
--
ALTER TABLE `user_backend`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT pour la table `user_backend_information`
--
ALTER TABLE `user_backend_information`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `website`
--
ALTER TABLE `website`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `backend_message`
--
ALTER TABLE `backend_message`
  ADD CONSTRAINT `FK_8EB53726CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user_backend` (`id`),
  ADD CONSTRAINT `FK_8EB53726F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user_backend` (`id`);

--
-- Contraintes pour la table `menu_item`
--
ALTER TABLE `menu_item`
  ADD CONSTRAINT `FK_D754D550727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `menu_item` (`id`),
  ADD CONSTRAINT `FK_D754D550C4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `FK_140AB620FDFF2E92` FOREIGN KEY (`thumbnail_id`) REFERENCES `image` (`id`);

--
-- Contraintes pour la table `page_gallery`
--
ALTER TABLE `page_gallery`
  ADD CONSTRAINT `FK_BD4B93AF3DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_BD4B93AFC4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`);

--
-- Contraintes pour la table `user_backend_information`
--
ALTER TABLE `user_backend_information`
  ADD CONSTRAINT `FK_502B13CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user_backend` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
