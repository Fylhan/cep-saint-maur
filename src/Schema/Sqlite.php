<?php
namespace Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec('
        CREATE TABLE ' . DB_PREFIX . 'news (
            id INTEGER PRIMARY KEY,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            date_start INTEGER,
            date_update INTEGER,
            date_end INTEGER,
            state INTEGER DEFAULT 0
        )
    ');
    
    $pdo->exec('
        CREATE TABLE ' . DB_PREFIX . 'upload (
            id INTEGER PRIMARY KEY,
            title TEXT NULL,
            filename TEXT NOT NULL,
            thumb TEXT NULL,
            type INTEGER DEFAULT 0,
            date int
        )
    ');
    
    $pdo->exec('
        CREATE TABLE ' . DB_PREFIX . 'content (
            id INTEGER PRIMARY KEY,
            url TEXT NOT NULL,
            content TEXT NOT NULL,
            title TEXT NOT NULL,
            abstract TEXT NOT NULL,
            keywords TEXT NOT NULL,
            date_update INTEGER
        )
    ');
    
    $rq = $pdo->prepare('INSERT INTO ' . DB_PREFIX . 'content (url, title, content, abstract, keywords, date_update) VALUES (?, ?, ?, ?, ?, ?)');
    $rq->execute(array(
        'contact',
        'Nous contacter',
        '<figure>
		<img src="' . IMG_PATH . '/accueil.png" alt="Nous contacter" />
	</figure>
	<header>
		<h1>Nous contacter</h1>
	</header>
	<p>
		Vous avez une question&#160;? Vous désirez en savoir plus&#160;? 
		N\hésitez pas à nous rendre visite ou à nous contacter en utilisant le formulaire ci-dessous,
		ou bien l\'une des adresses emails suivantes si vous le préférez.
	</p>
	
	
	<h2>Nous trouver</h2>	
	<figure class="illustration map">
		<a href="http://www.openstreetmap.org/?lat=48.78998&lon=2.49355&zoom=17&layers=M" title="Plan OpenStreetMap : 137, rue Edgar Quinet à Saint-Maur">
            <img src="'.IMG_PATH.'/plan-cep.png" alt="Plan OpenStreetMap du 137, rue Edgar Quinet à Saint-Maur" class="plan" />
        </a>
		<figcaption>
			<a href="http://www.openstreetmap.org/?lat=48.78998&lon=2.49355&zoom=17&layers=M">137, rue Edgar Quinet<br />à Saint-Maur</a>
		</figcaption>
	</figure>
	<p class="adresse-desc">Notre adresse est la suivante&#160;:</p>
	<p class="adresse">
		<meta itemprop="url" content="http://cepsaintmaur.fr" />
		<span itemprop="name">Le <abbr title="Communauté Evangélique Protestante">CEP</abbr> Saint-Maur</span><br />
		<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<span itemprop="streetAddress">137, rue Edgar Quinet</span><br />
			<span itemprop="postalCode">94100</span> <span itemprop="addressLocality">Saint-Maur-des-Fossés<br />France</span><br />
		</span>
	</p>
	<p>
		Pour visualiser l\'emplacement de notre église sur une carte,
		vous pouvez consulter un plan détaillé interactif sur <a href="http://www.openstreetmap.org/?lat=48.78998&lon=2.49355&zoom=17&layers=M" title="OpenStreetMap : la carte coopérative libre">OpenStreetMap</a>
		(carte coopérative libre).
	</p>
	<a id="mapHandler" title="Charger le plan OpenStreetMap centré sur le CEP Saint-Maur">Afficher le plan interactif</a>
    <figure class="illustration" id="mapWrapper">
    	<div id="map"></div>
    </figure>',
        'Si vous avez une question à propos du CEP Saint-Maur ou que vous désirez en savoir plus : n\'hésitez pas à nous rendre visite ou à nous contacter par email !',
        'contact,plan,email',
        time()
    ));
    

    $rq->execute(array(
        'qui-sommes-nous',
        'Nous connaître',
        "<figure>
	<img src=\"" . IMG_PATH . "/qui-sommes-nous.png\" alt=\"Nous connaître\" />
</figure>
<header>
	<h1>Nous connaître</h1>
</header>
    
<h2>Qui sommes-nous ?</h2>
<p>
	Notre Église est située à Saint-Maur depuis 1980 ! Nous sommes de confession protestante évangélique, et membres des
	Communautés et Assemblées Evangéliques de France (<a href=\"http://www.caef.net/\">CAEF</a>)
	ainsi que du Conseil National des Evangéliques de France (<a href=\"http://lecnef.org/\">CNEF</a>).
</p>
<figure class=\"illustration\">
	<a href=\"http://www.caef.net/\" class=\"caef\"><img src=\"" . IMG_PATH . "/caef-grand.jpg\" alt=\"Logo CAEF\" /></a>
	<figcaption><a href=\"http://www.caef.net/\" title=\"Communautés et Assemblées Evangéliques de France\">CAEF</a></figcaption>
</figure>
<figure class=\"illustration\">
	<a href=\"http://lecnef.org/\" class=\"cnef\">CNEF</a>
	<figcaption><a href=\"http://lecnef.org/\" title=\"Conseil National des Evangéliques de France\">CNEF</a></figcaption>
</figure>
<p>
Nous vous laissons en découvrir plus sur ce que nous croyons à la lecture de notre confession de foi.
</p>
    
<h2>Confession de foi</h2>
<ol>
	<li>Nous croyons en un seul Dieu Créateur de toutes choses visibles et invisibles
		<ul>
			<li>Il est unique, souverain et tout-puissant.</li>
			<li>Dieu vivant, il existe de toute éternité et se manifeste en trois personnes de la même essence : Dieu, Fils, Saint-Esprit.</li>
			<li>Il se révèle aux hommes dans son amour, sa sainteté, sa justice et sa sagesse.</li>
		</ul>
	</li>
	<li>Nous croyons en Jésus-Christ, Fils unique du Père
		<ul>
			<li>Il est vrai Dieu et vrai homme.</li>
			<li>Conçu par le Saint-Esprit et né de la vierge Marie, son humanité est exempte de péché, bien qu’il fût tenté en toutes choses.</li>
			<li>Il est venu sur terre pour :
				<ul>
					<li>Faire connaître aux hommes le Père.</li>
					<li>Se charger de nos offenses et de nos péchés, en souffrant comme victime propitiatoire et en mourant sur la Croix.</li>
					<li>Réconcilier le Père avec ses créatures.</li>
				</ul>
			</li>
			<li>Ressuscité corporellement le troisième jour :
				<ul>
					<li>Il a triomphé du mal et de la mort.</li>
					<li>Il s'est assis à la droite de Dieu, a été déclaré Seigneur, tout jugement lui a été remis.</li>
					<li>Il est le seul médiateur entre Dieu et les hommes.</li>
				</ul>
			</li>
		</ul>
	</li>
	<li>Nous croyons en l'Esprit saint, troisième personne de la Trinité
		<ul>
			<li>Il est un avec le Père et le Fils</li>
			<li>C'est lui, qui, envoyé du Père et du Fils, convainc les hommes de péché en leur révélant Jésus-Christ comme Sauveur et Sauveur</li>
			<li>Agissant dans la création et dans l'histoire des hommes, il a été donné à l'Eglise à la Pentecôte :
				<ul>
					<li>Il est puissance pour le témoignage du croyant.</li>
					<li>Il inspire les croyants dans la prière.</li>
					<li>Il les conduit dans l'interprétation des Ecritures.</li>
					<li>Il distribue ses dons selon sa souveraineté.</li>
					<li>Il est la source de régénération et de sanctification du croyant. C'est lui qui le rend capable de persévérer pendant son séjour terrestre.</li>
				</ul>
			</li>
		</ul>
	<li>Nous croyons que la Bible, dans son intégralité (écrits canoniques de l'Ancien Testament et du Nouveau Testament) est la Parole de Dieu :
		<ul>
			<li>Inspirée par le Saint-Esprit, écrite par divers auteurs, elle est dans ses textes originaux complète et sans erreur.</li>
			<li>Autorité souveraine en matière de foi et de vie, elle est pour le croyant la règle de vérité.</li>
			<li>Aucune autre révélation n'a de pouvoir pour la modifier ou la compléter en quoi que ce soit.</li>
		</ul>
	</li>
	<li>Nous croyons que l'homme et la femme sont tous deux des créatures faites à l'image de Dieu, ayant été créées sans péché pour vivre en harmonie avec Lui.
		<ul>
			<li>C'est par l'incrédulité et la désobéissance du premier couple (Adam et Eve) qu'ils se sont séparés de Dieu et se sont privés de sa communion, entraînant toute l'humanité dans la corruption du péché et la plaçant sous la condamnation de Dieu et la domination de Satan.</li>
			<li>Les conséquences qui en découlent sont la mort et la perdition éternelle.</li>
		</ul>
	</li>
	<li>Nous croyons que le salut de l'homme, n'est dû ni à ses mérites ni à ses oeuvres.
		<ul>
			<li>Il est un don gratuit de Dieu.</li>
			<li>Ce salut, fondé sur le sacrifice accompli une fois pour toutes à la croix par Jésus-Christ, est accordé à tout homme qui se repent envers Dieu et croit en Jésus-Christ son Sauveur.</li>
			<li>Pardonné et justifié par pure grâce, l'homme est libéré de la domination du péché et de Satan.</li>
			<li>Baptisé de l'Esprit saint, il naît à une nouvelle vie en Jésus-Christ qui l'intègre dans l'Eglise. Ce salut et cette nouvelle vie sont éternels.</li>
		</ul>
	</li>
	<li>Nous croyons que Dieu rassemble, pour former l’Eglise, tous ceux qui sont rachetés en Jésus-Christ quelles que soient les races, cultures ou nations auxquelles ils appartiennent.
		<ul>
			<li>Cette Eglise fondée à la Pentecôte par la descente du Saint-Esprit constitue le corps de Jésus-Christ dont il est la tête, seul chef et source de vie. Son unité est l'oeuvre du Saint-Esprit et s'étend au delà des barrières dénominationnelles. Sa vocation est de glorifier Dieu, de proclamer et porter par la parole et l'action la bonne nouvelle du Royaume de Dieu jusqu'aux extrémités de la terre. Son expression concrète selon la Bible est celle des Églises locales, c'est-à-dire le lieu de rassemblement où les chrétiens :
				<ul>
					<li>témoignent de leur conversion au Seigneur par le baptême (par immersion) ;</li>
					<li>s'unissent et s'accordent pour être enseignés dans la Parole de Dieu, pour le partage de la cène et pour la prière ;</li>
					<li>servent pour l'édification de l'Eglise dans la soumission les uns aux autres en fonction des dons accordés à chacun par le Saint-Esprit.</li>
				</ul>
			</li>
		</ul>
	</li>
	<li>Nous croyons au retour de Jésus-Christ, espérance de l'Eglise.
		<ul>
			<li>Il viendra en personne et d'une manière reconnaissable pour chercher les siens.</li>
			<li>Les morts ressusciteront et tous les hommes seront jugés :
				<ul>
					<li>ceux qui auront cru pour vivre éternellement dans la présence de Dieu ;</li>
					<li>ceux qui n'auront pas cru pour le châtiment éternel.</li>
				</ul>
			</li>
			<li>Alors seront manifestés le règne et la gloire de Dieu.</li>
		</ul>
	</li>
</ol>",
        'Le CEP Saint-Maur est une communauté évangélique protestante. Quelle est notre histoire ? Qui sommes-nous ? En quoi croyons-nous ?',
        'jésus,saint-esprit,protestant,évangélique,caef,cnef,confession de foi',
        time()
    ));
    
    
    $rq->execute(array(
        'activites',
        'Nos activités',
        '<figure>
		<img src="' . IMG_PATH . '/activites.png" alt="Nos activités" />
	</figure>
    
	<header>
		<h1>Nos activités</h1>
	</header>
    
	<h2 id="culte">Culte</h2>
	<p>
		Chaque dimanche à 10h.<br />
		Avec un enseignement biblique pour les enfants de 10h45 à 11h45.
	</p>
    
	<h2 id="gdm">Rencontres autour de la Bible</h2>
	<p>
		Chaque mercredi à 20h30 <span>(sauf parfois en période de vacances scolaires)</span>.<br />
		Études bibliques par thèmes, ateliers découverte ou groupes de prière.
	</p>
    
	<h2 id="gdj">Groupe de jeunes</h2>
	<p>
		Chaque samedi à 19h <span>(sauf parfois en période de vacances scolaires)</span>.<br />
		Pour les lycéens et plus si affinités : soirées à thèmes, enseignements, repas, sorties, discussions, débats, concerts...
	</p>
    
	<h2 id="flambeaux">Flambeaux et Claires Flammes (Scoutisme)</h2>
	<img src="' . IMG_PATH . '/logo-flambeaux.png" alt="Logo Flambeaux" class="logoFlambeaux" width="100" height="100" />
	<p>
		Un dimanche toutes les trois semaines.<br />
		De 7 à 16 ans.
	</p>
	<ul class="urlFlambeaux">
		<li><a href="http://flambeaux.org">Site officiel des Flambeaux</a></li>
	</ul>
    
	<div class="clear"></div>
	<p>Et s\'il vous reste encore des questions&#160;: <a href="contact.html">n\'hésitez pas à nous contacter</a>&#160;!</p>',
        'Notre Eglise régulièrement diverses activités pour les plus jeunes comme pour les plus âgés.',
        'culte,atelier,flambeaux,scout,scoutisme,gdj,groupe de jeunes',
        time()
    ));
    
    $rq->execute(array(
        'politique-accessibilite',
        'Politique d\'accessibilité',
        "<header>
	<h1>Politique d'accessibilité</h1>
</header>
	
<h2>Taille d'affichage</h2>
<p>Les textes de contenu ont une taille de police relative, c'est à dire agrandissable selon les besoins.</p>

<p>Pour modifier la taille d'affichage du texte :</p>

<ul>
	<li>Avec divers navigateurs : <kbd>Ctrl</kbd> + <kbd>molette de la souris</kbd></li>
	<li>Internet Explorer : allez dans <em>Affichage</em> &gt;&gt; <em>Taille du texte</em> et choisissez.</li>
	<li>Mozilla Firefox, Safari, Chrome : faites <kbd>Ctrl</kbd> + <kbd>+</kbd> pour agrandir et <kbd>Ctrl</kbd> + <kbd>-</kbd> pour diminuer.</li>
	<li>Opera : appuyez sur les touches <kbd>+</kbd> ou <kbd>-</kbd> du pavé numérique. Ou bien allez dans <em>Affichage</em> &gt;&gt; <em>Zoom</em> et choisir.</li>
</ul>

<h2>Aides à la navigation</h2>
<h3>Liens d'évitement</h3>

<p>Menu placé dès le début du document, ces liens permettent dès le chargement de la page d'accéder directement à la partie recherchée : au contenu, au menu général, au moteur de recherche, etc. sans avoir à parcourir des informations non souhaitées.</p>

<p>Ces liens facilitent l'accès au site pour les handicapés et notamment les non voyants&nbsp;: ils leurs permettent de se placer directement à l'endroit souhaité.</p>

<h3>Navigation par tabulation</h3>

<p>Appuyez sur Tab et répétez jusqu'à sélectionner le lien désiré, validez par <kbd>Entrée</kbd></p>

<h3 id=\"accesskeys\">Raccourcis clavier (accesskeys)</h3>

<ul>
	<li><kbd>s</kbd> = Aller directement au contenu</li>
	<li><kbd>0</kbd> ou <kbd>6</kbd> = Politique d'accessibilité</li>
	<li><kbd>1</kbd> = Aller à l'Accueil du site</li>
	<li><kbd>2</kbd> = Page d'actualités<br>
</li>
	<li><kbd>3</kbd> ou <kbd>5</kbd> = Aller au Plan du site</li>
	<li><kbd>7</kbd> = Page de contact<br>
</li>
</ul>
<br />

<p>Mode d'emploi :</p>
<ul>
	<li><b>Mozilla Firefox, Safari, Netscape</b> (Windows) : Appuyez simultanément sur la touche <kbd>Shift</kbd> et <kbd>Alt</kbd>, ainsi que sur une des touches <kbd>accesskey</kbd> du clavier (pas le pavé numérique). Vous vous rendrez directement à l'endroit voulu.</li>
	<li><b>Internet Explorer</b> (Windows) : Appuyez simultanément sur la touche <kbd>Alt</kbd> et sur une des touches <kbd>accesskey</kbd> du clavier (pas le pavé numérique) et ensuite appuyez sur la touche <kbd>Entrée</kbd> pour vous rendre à l'endroit voulu.</li>
	<li>Opera (Windows, Mac et Linux) : <kbd>Esc</kbd> + <kbd>Shift</kbd> et <kbd>accesskey</kbd></li>
	<li>Safari, Internet Explorer (Mac OS X) : <kbd>Ctrl</kbd> et <kbd>accesskey</kbd></li>
	<li>Mozilla, Netscape (Mac OS X) : <kbd>Ctrl</kbd> et <kbd>accesskey</kbd></li>
	<li>Mozilla Firefox, Galeon (Linux) : <kbd>Alt</kbd> et <kbd>accesskey</kbd></li>
	<li>Konqueror (Linux) : <kbd>Ctrl</kbd>, puis <kbd>accesskey</kbd> (successivement)</li>
	<li>Les navigateurs plus anciens (Netscape 4, Camino, Galeon, Konqueror avant la version 3.3.0, Omniweb, Safari avant la version 1.2, Opera Windows/Linux avant la version 7) ne supportent pas les accesskeys.</li>
</ul>",
        'Politique d\'accessibilité du site du CEP Saint-Maur',
        'accesskey,accessibilité,about',
        time()
    ));
}
