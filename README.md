CEP Saint-Maur
========================

Site Web du CEP Saint-Maur

[![Build Status](https://travis-ci.org/Fylhan/cep-saint-maur.svg?branch=master)](https://travis-ci.org/Fylhan/cep-saint-maur)

Travail en cours
--------------------------------
### Tâches en cours
* [████100%] Add Composer support
* [███▒ 95%] Go to PHP PSR-0
* [▒▒▒▒  0%] Optimiser les images : les passer en JPG
* [████100%] Create proper config files
* [███▒ 75%] Move illustrations and assets to assets, no templatable js
* [███▒ 75%] Fix upload (reduce instead of crop)
* [▒▒▒▒  0%] Check envoi d'email
* [█▒▒▒ 25%] Simplify installation
* [▒▒▒▒  0%] Fix cache and filters
* [▒▒▒▒  0%] Security check
* [█▒▒▒ 10%] Add to travis
* [██▒▒ 50%] Use PicoDB, sqlite, and automatically configure the db at installation
* [▒▒▒▒  0%] [Accueil] Mettre à jour les images, en particulier pour le bloc "Actualités"
* [▒▒▒▒  0%] [Nous connaître] Améliorer le contenu de la présentation, tout en restant court

Installation
--------------------------------

Install Composer on your machine if you don't already have it

    curl -s http://getcomposer.org/installer | php

Then use Composer to download and install all external libraries used by this software

    php composer.phar install

Now add proper user rights to folders "cache" and "data"

    chmod -R 755 cache data
    chown -R www-data:www-data cache data

And finaly copy the file "config.php.dist" to "config.php", and then adapt it to your local configuration (database credentiels, url ...).

That's it!

Credits & Licence
--------------------------------
* Design		: Vincent Fauriaux
* Développement	: Olivier Maridat

Even if it is not widely disclosed, this software is open source under the LGPL3+ license.

