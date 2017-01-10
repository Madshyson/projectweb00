-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 19 Juin 2016 à 21:37
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `rush`
--
CREATE DATABASE IF NOT EXISTS `rush` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `rush`;

-- --------------------------------------------------------

--
-- Structure de la table `commands`
--

DROP TABLE IF EXISTS `commands`;
CREATE TABLE `commands` (
  `ID_CMD` varchar(50) NOT NULL,
  `ID_USR` int(11) NOT NULL,
  `CMD_Total` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `lists`
--

DROP TABLE IF EXISTS `lists`;
CREATE TABLE `lists` (
  `LST_ID` int(11) NOT NULL,
  `ID_CMD` varchar(50) NOT NULL,
  `LST_PRD` int(11) NOT NULL,
  `LST_Quantity` int(11) NOT NULL,
  `LST_Date` date NOT NULL,
  `LST_Prix` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `PRD_ID` int(11) NOT NULL,
  `PRD_Name` varchar(50) NOT NULL,
  `PRD_Type` varchar(50) NOT NULL,
  `PRD_Description` text NOT NULL,
  `PRD_Amount` int(11) NOT NULL DEFAULT '0',
  `PRD_Prix` float(10,2) NOT NULL,
  `PRD_Img` varchar(200) NOT NULL,
  `PRD_AddDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `products`
--

INSERT INTO `products` (`PRD_ID`, `PRD_Name`, `PRD_Type`, `PRD_Description`, `PRD_Amount`, `PRD_Prix`, `PRD_Img`, `PRD_AddDate`) VALUES
(1, 'Cataplute Lourde', '1', 'Grosse Catapulte bien lourde.', 11, 1500.00, '300px-Replica_catapult.jpg', '2016-06-19'),
(3, 'Arc', '8', 'Formidable arc en bois.', 7, 50.00, 'arc.png', '2016-06-19'),
(4, 'Baliste style', '2', 'Trop classe. ', 19, 600.00, 'baliste.jpg', '2016-06-19'),
(5, 'Banquet ', '5', 'Enorme banquet pour 1000 personnes ', 6, 3900.00, 'banquet.jpg', '2016-06-19'),
(6, 'Carreaux', '7', 'Pack de 10 carreaux.', 8, 60.00, 'carreaux.jpg', '2016-06-19'),
(7, 'Catapulte', '1', 'Petite catapulte', 8, 800.00, 'catapulte.jpg', '2016-06-19'),
(8, 'Chaudron', '5', 'Pour faire de bonnes soupes', 15, 40.00, 'chaudron.jpg', '2016-06-19'),
(9, 'Echelle', '7', 'Pour grimper les ramparts.', 16, 20.00, 'echelle.png', '2016-06-19'),
(10, 'Epee', '8', 'Belle epee en acier de forger de Blanche Rive.', 12, 60.00, 'epee.jpg', '2016-06-19'),
(11, 'Fleche', '7', 'Fleche par pack de 100.', 4, 691.00, 'fleche .jpg', '2016-06-19'),
(12, 'Gourde', '6', 'Pour chaque soldat. (pas cher)', 8, 5.00, 'gourde.jpg', '2016-06-19'),
(13, 'Grand Trebuchet', '3', 'FORMIDABLE TREBUCHET. PROMO.', 10, 8000.00, 'Grand-Trebuchet.jpg', '2016-06-19'),
(14, 'Ration', '5', 'Tres bon steak ! ', 11, 30.00, 'ration.jpg', '2016-06-19'),
(15, 'Petit trebuchet', '3', 'Petit trebuchet assez cozy ', 3, 7999.00, 'trebuchet.jpg', '2016-06-19'),
(16, 'Tente', '4', 'YOLO', 7, 1.00, 'tente.jpg', '2016-06-19');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `TYP_ID` int(11) NOT NULL,
  `TYP_Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `type`
--

INSERT INTO `type` (`TYP_ID`, `TYP_Name`) VALUES
(1, 'Catapulte'),
(2, 'Baliste'),
(3, 'Trebuchet'),
(4, 'Tente'),
(5, 'Aliments'),
(6, 'Boissons'),
(7, 'Munitions'),
(8, 'Armes Soldats');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `USR_ID` int(11) NOT NULL,
  `USR_Nom` varchar(50) NOT NULL,
  `USR_Prenom` varchar(50) NOT NULL,
  `USR_Password` varchar(128) NOT NULL,
  `USR_Mail` varchar(125) NOT NULL,
  `USR_Phone` varchar(20) NOT NULL,
  `USR_Voie` varchar(150) DEFAULT NULL,
  `USR_Ville` varchar(50) DEFAULT NULL,
  `USR_Cp` varchar(10) DEFAULT NULL,
  `USR_Cmd` int(11) NOT NULL DEFAULT '0',
  `USR_Confirmed` int(11) NOT NULL DEFAULT '0',
  `USR_Active` int(11) NOT NULL DEFAULT '1',
  `USR_Token` varchar(64) NOT NULL,
  `USR_AdminToken` varchar(64) NOT NULL DEFAULT '0',
  `USR_AddDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`USR_ID`, `USR_Nom`, `USR_Prenom`, `USR_Password`, `USR_Mail`, `USR_Phone`, `USR_Voie`, `USR_Ville`, `USR_Cp`, `USR_Cmd`, `USR_Confirmed`, `USR_Active`, `USR_Token`, `USR_AdminToken`, `USR_AddDate`) VALUES
(1, 'Admin', 'Admin', '6a4e012bd9583858a5a6fa15f58bd86a25af266d3a4344f1ec2018b778f29ba83be86eb45e6dc204e11276f4a99eff4e2144fbe15e756c2c88e999649aae7d94', 'admin', '003242866109', 'Test', 'test', '456', 26, 1, 1, '', 'test', '2016-06-18');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`LST_ID`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`PRD_ID`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`TYP_ID`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USR_ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `lists`
--
ALTER TABLE `lists`
  MODIFY `LST_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `PRD_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `TYP_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `USR_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
