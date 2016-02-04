CREATE TABLE `cep_actualite` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `titre` varchar(255) NOT NULL COMMENT 'Titre du message',
  `contenu` text NOT NULL COMMENT 'Contenu du message',
  `date_debut` date NOT NULL COMMENT 'Date de la création du message, ne s''affichera aps avant cette date',
  `date_fin` date NOT NULL COMMENT 'Date de fin de cette actualite, ne s''affichera aps aprèscette date',
  `etat` int(1) unsigned NOT NULL default '1' COMMENT '1 = activé, 0 désactivé',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
