CSS & Javascript
=================

Pour le CSS, aucun framework ou pre-compiler n'est utilisé. Ce qui n'est pas plus mal vu la petite taille des scripts aujourd'hui, on ne va pas charger un gros truc générique pour si peu.

Pour le Javascript, on utilise jQuery et plusieurs bibliothèques : 
* Pour la carte OpenStreetMap : OpenLayers
* Pour les vieux navigateurs : HTML5
* Pour le WYSIWYG de l'administration:
 * Une version ancienne mais livre de RedactorJS
 * jQuery UI DataTime Picker
* Pour le debug :
 * HighlightJS
C'est franchement overkill vu nos besoins. Heureusement, les grosses bibliothèques ne concernent que la partie administration. On pourrait au moins se passer de jQuery pour la partie publique.

Tout cela nous donne un petit paquet de fichiers CSS et Javascript. On utilise donc l'outil Grunt pour minifier ces fichiers et les concaténer en un seul pour réduire les requêtes HTTP : un fichier app.css, un fichier app.js. Grunt est aussi un peu overkill mais j'avais envi de tester ;-)

Par conséquent : **Il faut compiler CSS & Javascript après toute modification pour voir le résultat.**

Installation de Grunt
------------

Il faut au préalable installer Grunt CLI, ce qui nécessite d'installer npn (Node.js package manager) : voir [Grunt Getting started](http://gruntjs.com/getting-started). Grunt CLI va utiliser Grunt pour exécuter le script Gruntfile.js. Si vous n'avez pas Grunt sur votre machine, une version sera installée lors de la commande "npm install".

    npm update -g npm
    npm install -g grunt-cli
    npm install --save-dev

Compiler CSS & Javascript
------------

Il "suffit" d'exécuter la commande `grunt` pour vérifier la syntaxe, concaténer et minifier les scripts CSS et Javascript :

    $ grunt
    Running "concat:dist" (concat) task
    
    Running "uglify:build" (uglify) task
    
    Done, without errors.

Si tout cela vous fatigue au plus haut point, vous pouvez toujours utiliser un vieux script PHP qui fait ça moins bien :

    php scripts/minifyCssJs.php

