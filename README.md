CEP Saint-Maur
========================

Site Web du CEP Saint-Maur

[![Build Status](https://travis-ci.org/Fylhan/cep-saint-maur.svg?branch=master)](https://travis-ci.org/Fylhan/cep-saint-maur)

Travail en cours
--------------------------------
### Tâches en cours
* [████100%] Add Composer support
* [███▒ 95%] Go to PHP PSR-0
* [████100%] Create proper config files
* [████100%] Move illustrations and assets to assets, no templatable js
* [███▒ 80%] Enhance upload: reduce instead of crop, display properply, admin menu
* [███▒ 75%] Simplify installation
* [████100%] Fix cache and filters
* [████100%] Use PicoDB, sqlite, and automatically configure the db at installation
* [████100%] Add to travis
* [██▒▒ 50%] Add unit tests
* [█▒▒▒ 10%] Add Docker configuration for developper purpose
* [▒▒▒▒  0%] History of content pages
* [▒▒▒▒  0%] Several accoun with different access
* [▒▒▒▒  0%] Security check, add CSRF
* [█▒▒▒ 10%] Change assests generation
* [█▒▒▒ 10%] Add documentation
* [▒▒▒▒  0%] Check sitemap date update + admin content date update
* [▒▒▒▒  0%] Optimiser les images : les passer en JPG
* [▒▒▒▒  0%] Check envoi d'email
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

And finaly copy the file "config.php.dist" to "config.php", and then adapt it to your local configuration (database credentials, url ...).

That's it!

To verify if everything is installed correctly, open "<host>/check_setup.php" in your browser.

Documentation
--------------------------------

* [PHP Projet Architecture](doc/PHP.md)
* [Database Management](doc/Database.md)
* [Templating & HTML](doc/View.md)
* [CSS & Javascript](doc/CSS-Javascript.md)

Credits & Licence
--------------------------------
* Design		: Vincent Fauriaux
* Développement	: Olivier Maridat

Even if it is not widely disclosed, this software is open source under the LGPL3+ license.

