<?php

// Names
defined('SiteNom') or define('SiteNom', 'Le CEP Saint-Maur');
defined('SiteDesc') or define('SiteDesc', 'Communauté Évangélique Protestante');
defined('Encodage') or define('Encodage', 'UTF-8');
defined('DefaultLocale') or define('DefaultLocale', 'fr_FR');
defined('Author') or define('Author', 'Vincent et Olivier');
defined('MetaTitleDefault') or define('MetaTitleDefault', SiteNom . ' - ' . SiteDesc);
defined('MetaKwDefault') or define('MetaKwDefault', 'Dieu, église, protestant, évangélique, cep, saint-maur');
defined('MetaDescDefault') or define('MetaDescDefault', SiteDesc . ' de Saint-Maur');
defined('MetaTitle') or define('MetaTitle', SiteNom);
defined('MetaKw') or define('MetaKw', 'Dieu, église, cep, saint-maur');
defined('MetaDesc') or define('MetaDesc', SiteNom);

// Path constants
// Convention : on ne termine jamais terminé par un slash !
defined('SITE_PATH') or define('SITE_PATH', 'http://cepsaintmaur.fr/');
defined('POSITION_RELATIVE') or define('POSITION_RELATIVE', '');
defined('ASSETS_PATH') or define('ASSETS_PATH', POSITION_RELATIVE . 'assets');
    defined('CSS_PATH') or define('CSS_PATH', ASSETS_PATH . '/css');
    defined('IMG_PATH') or define('IMG_PATH', ASSETS_PATH . '/img');
    defined('JS_PATH') or define('JS_PATH', ASSETS_PATH . '/js');
defined('CACHE_PATH') or define('CACHE_PATH', POSITION_RELATIVE . 'cache');
    defined('EMAIL_PATH') or define('EMAIL_PATH', CACHE_PATH . '/email');
defined('DATA_PATH') or define('DATA_PATH', POSITION_RELATIVE . 'data');
    defined('UPLOAD_PATH') or define('UPLOAD_PATH', DATA_PATH . '/upload');
defined('SRC_PATH') or define('SRC_PATH', POSITION_RELATIVE . 'src');

defined('ParameterFilePath') or define('ParameterFilePath', CACHE_PATH . '/config-user.php');
defined('BanFilePath') or define('BanFilePath', CACHE_PATH . '/ban.json');

// Database
defined('DB_DRIVER') or define("DB_DRIVER", 'sqlite');
defined('DB_FILENAME') or define('DB_FILENAME', DATA_PATH . '/db.sqlite'); // Sqlite configuration
defined('DB_USERNAME') or define('DB_USERNAME', 'root');
defined('DB_PASSWORD') or define('DB_PASSWORD', 'root');
defined('DB_HOSTNAME') or define('DB_HOSTNAME', 'localhost');
defined('DB_NAME') or define('DB_NAME', 'cepsaintmaur');
defined('DB_PORT') or define('DB_PORT', null);
defined('DB_PREFIX') or define('DB_PREFIX', 'cep_');

// Others
defined('DEBUG') or define('DEBUG', true); // true, false
defined('SecurityKey') or define('SecurityKey', '$2a$12$SH2SVrFpLOsneL5lHZRmuuSffz7yisadguqJfDgh1yiGUhBB91pV.'); // bcrypt
defined('LastModification') or define('LastModification', mktime(0, 0, 0, 02, 04, 2016));
defined('LastModificationActualites') or define('LastModificationActualites', LastModification);
defined('HAAT') or define('HAAT', 'haat');
defined('DOHOT') or define('DOHOT', 'dohot');
defined('NbParPage') or define('NbParPage', '2');
defined('NbParPageAdmin') or define('NbParPageAdmin', '20');
defined('NbMaxLienPagination') or define('NbMaxLienPagination', '10');
defined('NbItemPerFeed') or define('NbItemPerFeed', '20');
defined('EmailAdmin') or define('EmailAdmin', 'olivier@maridat.com');
if (DEBUG) {
    defined('EmailFlambeaux') or define('EmailFlambeaux', EmailAdmin);
    defined('EmailGDJ') or define('EmailGDJ', EmailAdmin);
    defined('EmailContact') or define('EmailContact', EmailAdmin);
}
else {
    defined('EmailFlambeaux') or define('EmailFlambeaux', 'flambeaux@cepsaintmaur.fr');
    defined('EmailGDJ') or define('EmailGDJ', 'gdj@cepsaintmaur.fr');
    defined('EmailContact') or define('EmailContact', 'contact@cepsaintmaur.fr');
}
defined('CodeStats') or define('CodeStats', '');
defined('CodeWebmasterTools') or define('CodeWebmasterTools', '');
defined('Antibot') or define('Antibot', 12);
defined('DisplayHelp') or define('DisplayHelp', 1);
defined('SessionTimeoutKeepConnected') or define('SessionTimeoutKeepConnected', 60 * 60 * 24 * 365); // 1 year
defined('SessionTimeoutNormal') or define('SessionTimeoutNormal', 60 * 60 * 2); // 2 hours
defined('LoginTryNumber') or define('LoginTryNumber', 5); // 2 hours
defined('LoginBanishedTimeout') or define('LoginBanishedTimeout', 30 * 60); // 30 min
defined('ThumbWidth') or define('ThumbWidth', 200);
defined('ThumbHeight') or define('ThumbHeight', 200);
defined('ThumbAdminWidth') or define('ThumbAdminWidth', 128);
defined('ThumbAdminHeight') or define('ThumbAdminHeight', 128);
defined('CacheEnabled') or define('CacheEnabled', ! DEBUG);
defined('CompressionEnabled') or define('CompressionEnabled', false);

define('OK_REDIRECTION', 1);
define('OK_NONBLOQUANT', 2);
define('OK_BLOQUANT', 3);
define('ERREUR_BLOQUANT', 0);
define('ERREUR_NONBLOQUANT', - 1);
define('ERREUR_REDIRECTION', - 2);
define('ERREUR', 0);
define('OK', 1);
define('NEUTRE', 2);
