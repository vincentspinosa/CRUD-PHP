-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 20 déc. 2022 à 09:48
-- Version du serveur :  5.7.34
-- Version de PHP : 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test_technique_evogue`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

CREATE TABLE `annonces` (
  `id` int(3) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `tarif` int(7) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `m2` int(5) NOT NULL,
  `description` text,
  `photo` varchar(255) DEFAULT NULL,
  `date_enregistrement` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `titre`, `tarif`, `ville`, `m2`, `description`, `photo`, `date_enregistrement`) VALUES
(28, 'Annonce B', 8000, 'Paris', 30, 'Ceci est la description de l&#039;annonce !', 'assets/img/istockphoto-688068466-612x612.jpeg', '2022-12-20 02:31:17'),
(29, 'Annonce C', 7700, 'Marseille', 44, 'Ceci est la description de l&#039;annonce !', 'assets/img/istockphoto-154015023-612x612.jpeg', '2022-12-20 02:31:59'),
(30, 'Annonce D', 21000, 'Toulouse', 55, 'Ceci est la description de l&#039;annonce !', 'assets/img/istockphoto-1009868694-612x612.jpeg', '2022-12-20 02:32:42'),
(34, 'Annonce J', 11900, 'Paris', 25, 'Ceci est la description de l&#039;annonce !', 'assets/img/istockphoto-891217220-612x612.jpeg', '2022-12-20 03:56:21'),
(35, 'Annonce L', 115000, 'Marseille', 212, 'Ceci est la description de l&#039;annonce !', 'assets/img/istockphoto-891217204-612x612.jpeg', '2022-12-20 04:49:57'),
(36, 'Annonce G', 84900, 'Paris', 190, 'Ceci est la &quot;description&quot; de l&#039;annonce !', 'assets/img/istockphoto-886405586-612x612.jpeg', '2022-12-20 06:04:29'),
(38, 'Annonce test', 100000, 'Toulouse', 210, 'Ceci est la &quot;description&quot; de l&#039;annonce !', 'assets/img/istockphoto-891217218-612x612.jpeg', '2022-12-20 07:36:07'),
(40, 'Annonce W', 19900, 'Roubaix', 122, 'Ceci est la nouvelle description !', 'assets/img/istockphoto-989520942-612x612.jpeg', '2022-12-20 08:32:28'),
(41, 'Annonce YY', 15000, 'Paris', 12, '&quot;Ceci&quot; EST une description !', 'assets/img/istockphoto-1149071433-612x612.jpeg', '2022-12-20 09:49:40');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annonces`
--
ALTER TABLE `annonces`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
