-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Jeu 02 Mai 2013 à 12:08
-- Version du serveur: 5.5.20
-- Version de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `cepsaintmaur`
--

-- --------------------------------------------------------

--
-- Structure de la table `cep_actualite`
--

CREATE TABLE IF NOT EXISTS `cep_actualite` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL COMMENT 'Titre du message',
  `contenu` text NOT NULL COMMENT 'Contenu du message',
  `date_debut` date NOT NULL COMMENT 'Date de la création du message, ne s''affichera aps avant cette date',
  `date_modif` date NOT NULL COMMENT 'Date de la dernière modification',
  `date_fin` date NOT NULL COMMENT 'Date de fin de cette actualite, ne s''affichera aps aprèscette date',
  `etat` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 = activé, 2=activé (invisible, 0 désactivé',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `cep_actualite`
--

INSERT INTO `cep_actualite` (`id`, `titre`, `contenu`, `date_debut`, `date_modif`, `date_fin`, `etat`) VALUES
(8, 'What is missing?', 'D''aprÃ¨s moi, voici quelques petites modifications qui pourraient encore manquer avant de mettre en ligne cette nouvelle version.<br>\r\n\r\n<ul>\r\n	<li><strike>ContrÃ´ler l''accÃ¨s de la partie administration (<em>filter</em>)</strike></li>\r\n	<li><strike>GÃ©rer l''affichage des news sur la page d''accueil</strike></li>\r\n	<li><strike>CrÃ©er un lien vers l''administration sur l''accueil du site</strike></li>\r\n	<li><strike>RÃ¨gler le bug d''affichage des flash messages</strike></li>\r\n	<li> Ajouter le systÃ¨me de modification des pages du site</li>\r\n	<li><span style="\\&quot;background-color:" transparent;\\"="">CrÃ©er un document de conception</span></li>\r\n	<li><strike>Page "contact"</strike>\r\n<ul>\r\n	<li><strike>Affichage plan (+ mobile)</strike></li>\r\n	<li><strike>Erreur du formulaire</strike></li>\r\n</ul></li>\r\n	<li>Page "nous connaÃ®tre" \r\n<ul>\r\n	<li>AmÃ©liorer le contenu de notre prÃ©sentation. <a data-cke-saved-href="\\&quot;http://eglise-protestante-baptiste-noisy.com/2.html\\&quot;" href="\\&quot;http://eglise-protestante-baptiste-noisy.com/2.html\\&quot;">Exemple un peu plus vivant</a>.</li>\r\n	<li>Affichage logo CNEF + CAEF</li>\r\n</ul></li>\r\n	<li>Administration \r\n<ul>\r\n	<li><strike>Plus d\\''informations</strike></li>\r\n	<li>AmÃ©liorer la sÃ©curitÃ© (partie administration + upload)<br></li>\r\n	<li><strike>Upload de fichiers et liste des uploads</strike><br>\r\n</li>\r\n	<li><strike>Ajouter / Supprimer des gens des listes de diffusion :ok mais ne marche pas</strike><br>\r\n</li>\r\n</ul></li>\r\n</ul> D''autres idÃ©es ? TrÃ¨s bien ! Cela vous donne l\\''occasion de tester l''ajout d''une news view <a data-cke-saved-href="\\&quot;./administration.html\\&quot;" href="\\&quot;http://cepsaintmaur.fr/test/administration.html\\&quot;">l''interface d''administration</a>.', '2012-06-26', '2013-04-03', '2013-12-30', 1),
(11, 'Just for fun', 'Et pour Ãªtre sÃ»r que tout s''affiche bien comme on veut !<br>\r\n [Lire la suite]<br>\r\n<br>\r\n A bientÃ´t !!', '2012-09-18', '2013-04-03', '2013-04-07', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
